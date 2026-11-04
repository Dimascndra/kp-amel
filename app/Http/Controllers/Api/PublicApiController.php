<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Branch;
use App\Models\Complaint;
use App\Models\LoanApplication;
use App\Models\Product;
use App\Models\PublicationReport;
use App\Models\Setting;
use App\Services\AntiFraudService;
use App\Mail\ComplaintSubmittedMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PublicApiController extends Controller
{
    public function products(Request $request): JsonResponse
    {
        $query = Product::active();

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('short_desc', 'like', "%{$q}%")
                  ->orWhere('code', 'like', "%{$q}%");
            });
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'total' => $products->count(),
            'data' => $products,
        ]);
    }

    public function productDetail(string $slug): JsonResponse
    {
        $product = Product::active()
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('id', is_numeric($slug) ? $slug : 0);
            })
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    public function branches(Request $request): JsonResponse
    {
        $query = Branch::active();

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('address', 'like', "%{$q}%");
            });
        }

        $branches = $query->get();

        return response()->json([
            'success' => true,
            'total' => $branches->count(),
            'data' => $branches,
        ]);
    }

    public function articles(Request $request): JsonResponse
    {
        $query = Article::published();

        if ($request->filled('tag')) {
            $query->where('tag', $request->query('tag'));
        }

        $articles = $query->get();

        return response()->json([
            'success' => true,
            'total' => $articles->count(),
            'data' => $articles,
        ]);
    }

    public function articleDetail(string $slug): JsonResponse
    {
        $article = Article::published()
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('id', is_numeric($slug) ? $slug : 0);
            })
            ->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan',
            ], 404);
        }

        $article->increment('views_count');

        return response()->json([
            'success' => true,
            'data' => $article,
        ]);
    }

    public function reports(Request $request): JsonResponse
    {
        $query = PublicationReport::active();

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        $reports = $query->get();

        return response()->json([
            'success' => true,
            'total' => $reports->count(),
            'data' => $reports,
        ]);
    }

    public function settings(): JsonResponse
    {
        $settings = Setting::getAllKeyValues();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $q = trim($request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json([
                'success' => true,
                'data' => [
                    'products' => [],
                    'branches' => [],
                    'articles' => [],
                ],
            ]);
        }

        $products = Product::active()
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('slogan', 'like', "%{$q}%")
                  ->orWhere('short_desc', 'like', "%{$q}%")
                  ->orWhere('code', 'like', "%{$q}%");
            })
            ->take(6)
            ->get();

        $branches = Branch::active()
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('address', 'like', "%{$q}%");
            })
            ->take(4)
            ->get();

        $articles = Article::published()
            ->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('tag', 'like', "%{$q}%")
                  ->orWhere('excerpt', 'like', "%{$q}%");
            })
            ->take(3)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'products' => $products,
                'branches' => $branches,
                'articles' => $articles,
            ],
        ]);
    }

    public function submitLoan(Request $request): JsonResponse
    {
        // Sanitize input
        $nominalClean = preg_replace('/[^0-9]/', '', (string)$request->input('nominal', 0));
        $tenorClean = (int) preg_replace('/[^0-9]/', '', (string)$request->input('tenor', 0));
        $pendapatanClean = preg_replace('/[^0-9]/', '', (string)$request->input('pendapatanTetap', $request->input('pendapatan_tetap', 0)));

        $request->merge([
            'nominal' => $nominalClean,
            'tenor' => $tenorClean,
            'pendapatan_tetap' => $pendapatanClean,
        ]);

        $validator = Validator::make($request->all(), [
            'nik' => ['required', 'digits:16'],
            'nama' => ['required', 'string', 'max:150'],
            'alamat' => ['required', 'string'],
            'hp' => ['required', 'string', 'max:25'],
            'nominal' => ['required', 'numeric', 'min:500000'],
            'tenor' => ['required', 'integer', 'min:1', 'max:120'],
            'tujuan' => ['required', 'string'],
            'sumber' => ['nullable', 'string'],
            'sumber_pendapatan' => ['nullable', 'string'],
            'pendapatan_tetap' => ['required', 'numeric'],
            'pendapatanLain' => ['nullable', 'string'],
            'pendapatan_lain' => ['nullable', 'string'],
            'deskripsiLain' => ['nullable', 'string'],
            'deskripsi_lain' => ['nullable', 'string'],
            'agunan' => ['required', 'string'],
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits' => 'Nomor Induk Kependudukan (NIK) harus tepat 16 digit.',
            'nama.required' => 'Nama lengkap debitur wajib diisi.',
            'alamat.required' => 'Alamat lengkap KTP wajib diisi.',
            'hp.required' => 'Nomor kontak / WhatsApp wajib diisi.',
            'nominal.min' => 'Pengajuan pinjaman minimal Rp 500.000,-.',
            'agunan.required' => 'Keterangan agunan/jaminan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $sumber = $request->input('sumber_pendapatan') ?: $request->input('sumber', 'Usaha / Pekerjaan');
        $pendapatanLain = $request->input('pendapatan_lain') ?: $request->input('pendapatanLain', '-');
        $deskripsiLain = $request->input('deskripsi_lain') ?: $request->input('deskripsiLain', '-');
        $afiliasi = $request->input('afiliasi', '-');

        $referenceCode = LoanApplication::generateReferenceCode();

        $loan = LoanApplication::create([
            'reference_code' => $referenceCode,
            'nik' => $request->input('nik'),
            'nama' => $request->input('nama'),
            'alamat' => $request->input('alamat'),
            'hp' => $request->input('hp'),
            'afiliasi' => $afiliasi,
            'nominal' => $nominalClean,
            'tenor' => $tenorClean,
            'tujuan' => $request->input('tujuan'),
            'sumber_pendapatan' => $sumber,
            'pendapatan_tetap' => $pendapatanClean,
            'pendapatan_lain' => $pendapatanLain,
            'deskripsi_lain' => $deskripsiLain,
            'agunan' => $request->input('agunan'),
            'status' => 'pending',
            'notes' => 'Pengajuan online via Website Portal BPR Majalengka.',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan pinjaman berhasil dikirim dan tersimpan di sistem.',
            'data' => [
                'reference_code' => $loan->reference_code,
                'nama' => $loan->nama,
                'nominal' => (float)$loan->nominal,
                'tenor' => $loan->tenor,
                'tanggal' => $loan->created_at->format('d/m/Y H:i'),
                'status' => $loan->status,
            ],
        ], 201);
    }

    /**
     * Generate dynamic anti-fraud CAPTCHA challenge.
     */
    public function captcha(AntiFraudService $antiFraud): JsonResponse
    {
        $captcha = $antiFraud->generateCaptcha();
        $formLoadToken = $antiFraud->generateFormLoadToken();

        return response()->json([
            'success' => true,
            'data' => [
                'question' => $captcha['question'],
                'captcha_token' => $captcha['token'],
                'form_load_token' => $formLoadToken,
            ],
        ]);
    }

    /**
     * Submit Customer Complaint with complete Anti-Fraud verification and email dispatch.
     */
    public function submitComplaint(Request $request, AntiFraudService $antiFraud): JsonResponse
    {
        // 1. Rate limiting per IP (Anti-DDoS / Anti-Flood)
        $ip = $request->ip();
        $rateKey = "complaint-submit:{$ip}";
        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak pengiriman pengaduan dari perangkat Anda. Demi keamanan anti-fraud, silakan tunggu {$seconds} detik lagi.",
            ], 429);
        }
        RateLimiter::hit($rateKey, 900); // 15 minutes window

        // 2. Anti-Fraud Honeypot verification (Bot Trap)
        if ($antiFraud->isHoneypotTriggered($request->input('website_hp')) || $antiFraud->isHoneypotTriggered($request->input('complaint_fax'))) {
            Log::warning("Anti-Fraud Honeypot triggered from IP {$ip}");
            return response()->json([
                'success' => false,
                'message' => 'Aktivitas otomatis (bot) terdeteksi oleh sistem keamanan.',
            ], 403);
        }

        // 3. Dynamic CAPTCHA verification
        $captchaToken = $request->input('captcha_token');
        $captchaAnswer = $request->input('captcha_answer');
        if (!$antiFraud->verifyCaptcha($captchaToken, $captchaAnswer)) {
            return response()->json([
                'success' => false,
                'message' => 'Verifikasi CAPTCHA keamanan salah atau telah kedaluwarsa. Silakan ulangi perhitungan.',
            ], 422);
        }

        // 4. Input validation
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc,filter', 'max:150'],
            'telepon' => ['required', 'string', 'max:30'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
            'kategori' => ['required', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:200'],
            'isi_pengaduan' => ['required', 'string', 'min:10', 'max:5000'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
        ], [
            'nama.required' => 'Nama lengkap pelapor wajib diisi.',
            'email.required' => 'Alamat email wajib diisi untuk pengiriman kode unik pengaduan.',
            'email.email' => 'Format alamat email tidak valid.',
            'telepon.required' => 'Nomor kontak / WhatsApp wajib diisi.',
            'kategori.required' => 'Kategori pengaduan wajib dipilih.',
            'judul.required' => 'Judul atau perihal pengaduan wajib diisi.',
            'isi_pengaduan.required' => 'Uraian pengaduan wajib diisi.',
            'isi_pengaduan.min' => 'Uraian pengaduan minimal 10 karakter.',
            'lampiran.mimes' => 'Format bukti lampiran harus berupa JPG, PNG, atau PDF.',
            'lampiran.max' => 'Ukuran bukti lampiran maksimal 3 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        // 5. Anti-Fraud risk analysis & sanitization
        $riskAnalysis = $antiFraud->analyzeRisk($request->only(['nama', 'email', 'judul', 'isi_pengaduan']));

        // Check if fast bot submission
        if ($antiFraud->isSubmissionTooFast($request->input('form_load_token'), 2)) {
            $riskAnalysis['risk_level'] = 'medium';
            $riskAnalysis['notes'] .= '; Fast submission detected (<2s)';
        }

        // 6. Handle file upload (optional proof)
        $lampiranPath = null;
        if ($request->hasFile('lampiran') && $request->file('lampiran')->isValid()) {
            $file = $request->file('lampiran');
            $filename = 'ADU_' . date('Ymd_His') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $lampiranPath = $file->storeAs('complaints', $filename, 'public');
        }

        // 7. Generate Ticket Unique Code & Save
        $ticketCode = Complaint::generateTicketCode();

        $complaint = Complaint::create([
            'ticket_code' => $ticketCode,
            'nama' => strip_tags(trim($request->input('nama'))),
            'email' => strtolower(trim($request->input('email'))),
            'telepon' => strip_tags(trim($request->input('telepon'))),
            'nomor_rekening' => $request->filled('nomor_rekening') ? strip_tags(trim($request->input('nomor_rekening'))) : null,
            'kategori' => $request->input('kategori'),
            'judul' => strip_tags(trim($request->input('judul'))),
            'isi_pengaduan' => strip_tags(trim($request->input('isi_pengaduan'))),
            'lampiran' => $lampiranPath,
            'status' => 'diproses',
            'ip_address' => $ip,
            'user_agent' => substr((string)$request->userAgent(), 0, 500),
            'fraud_risk_level' => $riskAnalysis['risk_level'],
            'fraud_notes' => $riskAnalysis['notes'],
        ]);

        // 8. Send Email notification with unique code and status explanation
        $emailSent = false;
        try {
            Mail::to($complaint->email)->send(new ComplaintSubmittedMail($complaint));
            $emailSent = true;
        } catch (\Throwable $e) {
            Log::error("Failed to send complaint confirmation email to {$complaint->email}: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dikirim! Kode unik pengaduan telah diterbitkan dan dikirimkan ke email Anda.',
            'data' => [
                'ticket_code' => $complaint->ticket_code,
                'nama' => $complaint->nama,
                'email' => $complaint->email,
                'kategori' => $complaint->kategori,
                'judul' => $complaint->judul,
                'status' => $complaint->status,
                'status_label' => $complaint->status_label,
                'tanggal' => $complaint->created_at->format('d/m/Y H:i'),
                'email_notified' => $emailSent,
            ],
        ], 201);
    }

    /**
     * Check Complaint status by Unique Ticket Code.
     */
    public function checkComplaintStatus(Request $request): JsonResponse
    {
        $code = trim((string) $request->input('ticket_code', $request->query('code', '')));

        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masukkan Kode Unik Pengaduan Anda.',
            ], 422);
        }

        $complaint = Complaint::where('ticket_code', $code)->first();

        if (!$complaint) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaduan dengan kode unik tersebut tidak ditemukan. Mohon periksa kembali kode tiket Anda.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'ticket_code' => $complaint->ticket_code,
                'nama' => $complaint->nama,
                'kategori' => $complaint->kategori,
                'judul' => $complaint->judul,
                'status' => $complaint->status,
                'status_label' => $complaint->status_label,
                'status_classes' => $complaint->status_badge_classes,
                'catatan_petugas' => $complaint->catatan_petugas ?: 'Laporan pengaduan Anda saat ini telah diterima oleh Bagian Layanan Nasabah & Kepatuhan Bank Majalengka dan sedang dalam tahap penelaahan dokumen.',
                'tanggal_lapor' => $complaint->created_at->format('d/m/Y H:i'),
                'tanggal_ditindaklanjuti' => $complaint->tanggal_ditindaklanjuti ? $complaint->tanggal_ditindaklanjuti->format('d/m/Y H:i') : null,
                'tanggal_selesai' => $complaint->tanggal_selesai ? $complaint->tanggal_selesai->format('d/m/Y H:i') : null,
            ],
        ]);
    }
}
