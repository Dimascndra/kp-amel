<?php

namespace Tests\Feature;

use App\Mail\ComplaintStatusUpdatedMail;
use App\Mail\ComplaintSubmittedMail;
use App\Models\Complaint;
use App\Models\User;
use App\Services\AntiFraudService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ComplaintTest extends TestCase
{
    use RefreshDatabase;

    protected AntiFraudService $antiFraud;

    protected function setUp(): void
    {
        parent::setUp();
        $this->antiFraud = new AntiFraudService();
    }

    public function test_can_generate_anti_fraud_captcha(): void
    {
        $response = $this->getJson('/captcha/generate');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'question',
                    'captcha_token',
                    'form_load_token',
                ],
            ]);
    }

    public function test_complaint_submission_fails_if_captcha_invalid(): void
    {
        $response = $this->postJson('/pengaduan', [
            'nama' => 'Nasabah Majalengka',
            'email' => 'nasabah@example.com',
            'telepon' => '081234567890',
            'kategori' => 'pelayanan',
            'judul' => 'Kendala Layanan Teller',
            'isi_pengaduan' => 'Uraian pengaduan lengkap nasabah mengenai transaksi perbankan.',
            'captcha_token' => 'invalid-token',
            'captcha_answer' => '999',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_complaint_submission_fails_if_anti_fraud_honeypot_triggered(): void
    {
        $captcha = $this->antiFraud->generateCaptcha();
        $token = $captcha['token'];
        $decrypted = json_decode(Crypt::decryptString($token), true);
        $answer = $decrypted['ans'];

        $response = $this->postJson('/pengaduan', [
            'nama' => 'Spam Bot',
            'email' => 'bot@spammer.com',
            'telepon' => '081234567890',
            'kategori' => 'pelayanan',
            'judul' => 'Spam Link',
            'isi_pengaduan' => 'This is automated bot spam submission.',
            'captcha_token' => $token,
            'captcha_answer' => $answer,
            'website_hp' => 'http://spam-link.com', // Honeypot filled!
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_successful_complaint_submission_generates_unique_code_and_dispatches_email(): void
    {
        Mail::fake();

        $captcha = $this->antiFraud->generateCaptcha();
        $token = $captcha['token'];
        $decrypted = json_decode(Crypt::decryptString($token), true);
        $answer = $decrypted['ans'];

        $response = $this->postJson('/pengaduan', [
            'nama' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@example.com',
            'telepon' => '081298765432',
            'nomor_rekening' => '1029384756',
            'kategori' => 'transaksi',
            'judul' => 'Keterlambatan Konfirmasi Setoran Tunai',
            'isi_pengaduan' => 'Setoran tunai pada kantor kas Kadipaten belum terupdate pada buku tabungan saya.',
            'captcha_token' => $token,
            'captcha_answer' => $answer,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'ticket_code',
                    'nama',
                    'email',
                    'kategori',
                    'judul',
                    'status',
                    'status_label',
                    'tanggal',
                ],
            ]);

        $ticketCode = $response->json('data.ticket_code');
        $this->assertStringStartsWith('BM-ADU-', $ticketCode);

        // Verify Complaint created in Database
        $this->assertDatabaseHas('complaints', [
            'ticket_code' => $ticketCode,
            'nama' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@example.com',
            'status' => 'diproses',
        ]);

        // Verify Email dispatched
        Mail::assertSent(ComplaintSubmittedMail::class, function ($mail) use ($ticketCode) {
            return $mail->complaint->ticket_code === $ticketCode
                && $mail->complaint->status === 'diproses'
                && $mail->hasTo('ahmad.fauzi@example.com');
        });
    }

    public function test_can_check_complaint_status_with_valid_unique_code(): void
    {
        $complaint = Complaint::create([
            'ticket_code' => 'BM-ADU-20261104-9876',
            'nama' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'telepon' => '081234567890',
            'kategori' => 'kredit',
            'judul' => 'Pertanyaan Syarat Agunan',
            'isi_pengaduan' => 'Ingin menanyakan kejelasan dokumen agunan tanah.',
            'status' => 'ditindaklanjuti',
            'catatan_petugas' => 'Petugas analis kredit sedang memeriksa kelengkapan sertifikat di kantor kas.',
        ]);

        $response = $this->getJson("/pengaduan/status?code={$complaint->ticket_code}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'ticket_code' => 'BM-ADU-20261104-9876',
                    'nama' => 'Siti Nurhaliza',
                    'status' => 'ditindaklanjuti',
                    'status_label' => 'Ditindaklanjuti',
                    'catatan_petugas' => 'Petugas analis kredit sedang memeriksa kelengkapan sertifikat di kantor kas.',
                ],
            ]);
    }

    public function test_admin_single_administrator_restriction(): void
    {
        $singleAdmin = User::create([
            'name' => 'Administrator Utama Bank Majalengka',
            'email' => 'admin@bankmajalengka.co.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $unauthorizedUser = User::create([
            'name' => 'Nasabah Biasa',
            'email' => 'nasabah.umum@example.com',
            'password' => Hash::make('password'),
            'role' => 'nasabah',
        ]);

        // Unauthorized user cannot access complaints module
        $responseUnauthorized = $this->actingAs($unauthorizedUser)->get('/admin/complaints');
        $responseUnauthorized->assertStatus(403);

        // Authorized single administrator can access complaints module
        $responseAuthorized = $this->actingAs($singleAdmin)->get('/admin/complaints');
        $responseAuthorized->assertStatus(200);
    }

    public function test_admin_can_update_complaint_status_and_notify_user_via_email(): void
    {
        Mail::fake();

        $admin = User::create([
            'name' => 'Administrator Utama Bank Majalengka',
            'email' => 'admin@bankmajalengka.co.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $complaint = Complaint::create([
            'ticket_code' => 'BM-ADU-20261104-1122',
            'nama' => 'Budi Hendrawan',
            'email' => 'budi@example.com',
            'telepon' => '081234567890',
            'kategori' => 'pelayanan',
            'judul' => 'Komplain Pelayanan',
            'isi_pengaduan' => 'Uraian pengaduan komplain pelayanan.',
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($admin)->put("/admin/complaints/{$complaint->id}/status", [
            'status' => 'selesai',
            'catatan_petugas' => 'Keluhan telah diselesaikan secara damai dan dana telah dikreditkan kembali ke rekening.',
            'send_email' => 1,
        ]);

        $response->assertRedirect(route('admin.complaints.show', $complaint));

        $complaint->refresh();
        $this->assertEquals('selesai', $complaint->status);
        $this->assertNotNull($complaint->tanggal_selesai);

        Mail::assertSent(ComplaintStatusUpdatedMail::class, function ($mail) {
            return $mail->complaint->status === 'selesai'
                && $mail->hasTo('budi@example.com');
        });
    }
}
