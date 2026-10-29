<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Branch;
use App\Models\LoanApplication;
use App\Models\Product;
use App\Models\PublicationReport;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('email', 'admin@bankmajalengka.co.id')->first();
    }

    public function test_admin_can_view_product_list_and_create_product(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/products');
        $response->assertStatus(200);
        $response->assertSee('Tabungan Hari Raya (TAHARA)');

        $payload = [
            'name' => 'Tabungan Qurban Baru',
            'code' => 'TAB-99',
            'category' => 'tabungan',
            'badge' => 'Produk Baru',
            'slogan' => 'Slogan tabungan qurban baru',
            'short_desc' => 'Deskripsi tabungan baru',
            'cta_text' => 'Buka Rekening',
            'cta_type' => 'whatsapp',
            'ketentuan' => "Syarat 1\nSyarat 2",
            'syarat' => "KTP Asli\nSetoran Awal",
            'is_active' => '1',
            'sort_order' => 50,
        ];

        $createResponse = $this->actingAs($this->admin)->post('/admin/products', $payload);
        $createResponse->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'code' => 'TAB-99',
            'name' => 'Tabungan Qurban Baru',
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::first();

        $updateResponse = $this->actingAs($this->admin)->put("/admin/products/{$product->id}", [
            'name' => 'Nama Produk Terupdate',
            'code' => $product->code,
            'category' => $product->category,
            'cta_text' => 'Hubungi Kami Segera',
            'cta_type' => 'whatsapp',
            'is_active' => '1',
            'sort_order' => 1,
        ]);

        $updateResponse->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Nama Produk Terupdate',
        ]);
    }

    public function test_admin_can_toggle_product_status(): void
    {
        $product = Product::first();
        $initialStatus = $product->is_active;

        $response = $this->actingAs($this->admin)->patch("/admin/products/{$product->id}/toggle");
        $response->assertStatus(302);

        $this->assertEquals(!$initialStatus, $product->fresh()->is_active);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::create([
            'slug' => 'produk-hapus-test',
            'code' => 'DEL-01',
            'name' => 'Produk Hapus Test',
            'category' => 'kredit',
            'cta_text' => 'Ajukan',
            'cta_type' => 'form_pengajuan',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/products/{$product->id}");
        $response->assertRedirect('/admin/products');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_crud_branches(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/branches');
        $response->assertStatus(200);
        $response->assertSee('Kantor Kas Kadipaten');

        // Create branch
        $createResponse = $this->actingAs($this->admin)->post('/admin/branches', [
            'name' => 'Kantor Kas Bantarujeg',
            'type' => 'Kas',
            'address' => 'Jl. Raya Bantarujeg No. 12, Majalengka',
            'phone' => '(0233) 890123',
            'whatsapp' => '0811-1111-6526',
            'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect('/admin/branches');
        $this->assertDatabaseHas('branches', ['name' => 'Kantor Kas Bantarujeg']);

        $branch = Branch::where('name', 'Kantor Kas Bantarujeg')->first();

        // Update branch
        $updateResponse = $this->actingAs($this->admin)->put("/admin/branches/{$branch->id}", [
            'name' => 'Kantor Kas Bantarujeg Baru',
            'type' => 'Kas',
            'address' => 'Jl. Raya Bantarujeg Baru No. 15',
            'hours' => 'Senin - Jumat: 08:00 - 15:30 WIB',
            'is_active' => '1',
        ]);
        $updateResponse->assertRedirect('/admin/branches');
        $this->assertDatabaseHas('branches', ['name' => 'Kantor Kas Bantarujeg Baru']);

        // Delete branch
        $delResponse = $this->actingAs($this->admin)->delete("/admin/branches/{$branch->id}");
        $delResponse->assertRedirect('/admin/branches');
        $this->assertDatabaseMissing('branches', ['id' => $branch->id]);
    }

    public function test_admin_can_crud_articles(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/articles');
        $response->assertStatus(200);

        // Create article
        $createResponse = $this->actingAs($this->admin)->post('/admin/articles', [
            'title' => 'Tips Menabung Cerdas BPR',
            'tag' => 'Edukasi',
            'tag_color' => 'blue',
            'excerpt' => 'Ringkasan tips menabung cerdas.',
            'content' => '<p>Isi artikel tips menabung cerdas secara disiplin.</p>',
            'published_at' => date('Y-m-d'),
            'is_published' => '1',
        ]);

        $createResponse->assertRedirect('/admin/articles');
        $this->assertDatabaseHas('articles', ['title' => 'Tips Menabung Cerdas BPR']);
    }

    public function test_admin_can_crud_reports(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/reports');
        $response->assertStatus(200);

        $createResponse = $this->actingAs($this->admin)->post('/admin/reports', [
            'title' => 'Laporan Publikasi Triwulan IV 2024',
            'category' => 'TRIWULAN IV - 2024',
            'category_color' => 'blue',
            'description' => 'Posisi neraca keuangan triwulan IV 2024.',
            'file_size' => '2.0 MB',
            'period' => 'Triwulan IV 2024',
            'is_active' => '1',
        ]);

        $createResponse->assertRedirect('/admin/reports');
        $this->assertDatabaseHas('publication_reports', ['title' => 'Laporan Publikasi Triwulan IV 2024']);
    }

    public function test_admin_can_manage_loan_applications(): void
    {
        $loan = LoanApplication::first();

        $response = $this->actingAs($this->admin)->get("/admin/loans/{$loan->id}");
        $response->assertStatus(200);
        $response->assertSee($loan->reference_code);
        $response->assertSee($loan->nama);

        // Update status
        $statusResponse = $this->actingAs($this->admin)->put("/admin/loans/{$loan->id}/status", [
            'status' => 'approved',
            'notes' => 'Telah disetujui oleh Kepala Cabang.',
        ]);

        $statusResponse->assertStatus(302);
        $this->assertEquals('approved', $loan->fresh()->status);
        $this->assertEquals('Telah disetujui oleh Kepala Cabang.', $loan->fresh()->notes);
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/settings');
        $response->assertStatus(200);

        $updateResponse = $this->actingAs($this->admin)->put('/admin/settings', [
            'hero_headline' => 'Masa depan cerah bersama Bank Majalengka.',
            'phone_central' => '(0233) 999888',
            'company_tagline' => 'Mitra Finansial Terpercaya Anda',
            'vision' => 'Menjadi BPR Terkemuka di Wilayah Ciayumajakuning.',
            'mission' => "1. Meningkatkan layanan UMKM\n2. Menjaga tata kelola sehat",
            'sejarah_singkat' => 'BPR Majalengka didirikan untuk melayani masyarakat Majalengka.',
        ]);

        $updateResponse->assertStatus(302);
        $this->assertEquals('Masa depan cerah bersama Bank Majalengka.', Setting::get('hero_headline'));
        $this->assertEquals('(0233) 999888', Setting::get('phone_central'));
        $this->assertEquals('Mitra Finansial Terpercaya Anda', Setting::get('company_tagline'));
        $this->assertEquals('Menjadi BPR Terkemuka di Wilayah Ciayumajakuning.', Setting::get('vision'));
        $this->assertStringContainsString('layanan UMKM', Setting::get('mission'));
        $this->assertStringContainsString('BPR Majalengka didirikan', Setting::get('sejarah_singkat'));
    }

    public function test_admin_can_upload_report_pdf(): void
    {
        $file = UploadedFile::fake()->create('laporan_test.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post('/admin/reports', [
            'title' => 'Laporan Test Upload PDF',
            'category' => 'Tahunan 2024',
            'category_color' => 'blue',
            'period' => '2024',
            'file' => $file,
        ]);

        $response->assertRedirect('/admin/reports');
        $report = PublicationReport::where('title', 'Laporan Test Upload PDF')->first();
        $this->assertNotNull($report);
        $this->assertNotNull($report->file_path);
        $this->assertFileExists(public_path($report->file_path));

        if (file_exists(public_path($report->file_path))) {
            unlink(public_path($report->file_path));
        }
    }

    public function test_admin_can_upload_product_images(): void
    {
        $img = UploadedFile::fake()->image('produk_baru.png', 100, 100);
        $hero = UploadedFile::fake()->image('hero_baru.jpg', 400, 200);

        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'name' => 'Tabungan Upload Test',
            'code' => 'TAB-UPLOAD',
            'category' => 'tabungan',
            'cta_text' => 'Buka Rekening',
            'cta_type' => 'whatsapp',
            'image_file' => $img,
            'hero_image_file' => $hero,
        ]);

        $response->assertRedirect('/admin/products');
        $product = Product::where('code', 'TAB-UPLOAD')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        $this->assertFileExists(public_path($product->image));
        $this->assertNotNull($product->hero_image);
        $this->assertFileExists(public_path($product->hero_image));

        if (file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        if (file_exists(public_path($product->hero_image))) {
            unlink(public_path($product->hero_image));
        }
    }

    public function test_admin_can_upload_article_image(): void
    {
        $img = UploadedFile::fake()->image('article_thumb.jpg', 300, 200);

        $response = $this->actingAs($this->admin)->post('/admin/articles', [
            'title' => 'Artikel Upload Test',
            'tag' => 'Finansial',
            'tag_color' => 'blue',
            'published_at' => date('Y-m-d'),
            'excerpt' => 'Ringkasan artikel upload',
            'content' => '<p>Konten artikel upload</p>',
            'image_file' => $img,
        ]);

        $response->assertRedirect('/admin/articles');
        $article = Article::where('title', 'Artikel Upload Test')->first();
        $this->assertNotNull($article);
        $this->assertNotNull($article->image);
        $this->assertFileExists(public_path($article->image));

        if (file_exists(public_path($article->image))) {
            unlink(public_path($article->image));
        }
    }
}
