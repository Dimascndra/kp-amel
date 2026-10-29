<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Branch;
use App\Models\LoanApplication;
use App\Models\Product;
use App\Models\PublicationReport;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
}
