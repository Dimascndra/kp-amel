<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Branch;
use App\Models\Product;
use App\Models\PublicationReport;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeLandingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_renders_successfully_with_seeded_dynamic_data(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Perumda BPR Majalengka');
        $response->assertSee('KAPESTA');
        $response->assertSee('TAHARA');
        $response->assertSee('Kantor Kas Kadipaten');
        $response->assertSee('Dana Darurat');
        $response->assertSee('Laporan Publikasi 30 September 2024');
        $response->assertSee('Laporan Penerapan GCG 2023');
        $response->assertSee(route('admin.login'));
    }

    public function test_home_page_reflects_updated_settings_dynamically(): void
    {
        Setting::set('company_name', 'Bank BPR Majalengka Juara');
        Setting::set('phone_central', '(0233) 999888');

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Bank BPR Majalengka Juara');
        $response->assertSee('(0233) 999888');
    }

    public function test_home_page_reflects_new_article_dynamically(): void
    {
        Article::create([
            'title' => 'Tips Mengelola Keuangan Desa Bersama Bank Majalengka',
            'slug' => 'tips-mengelola-keuangan-desa',
            'category' => 'Edukasi Keuangan',
            'excerpt' => 'Panduan pengelolaan alokasi dana desa secara akuntabel.',
            'content' => '<p>Konten artikel lengkap tentang keuangan desa.</p>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Tips Mengelola Keuangan Desa Bersama Bank Majalengka');
    }

    public function test_landing_page_renders_dynamic_bpr_profile_and_counts(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Profil BPR modal content
        $response->assertSee('Profil Perumda BPR Majalengka');
        $response->assertSee('Menjadi Bank Perekonomian Rakyat yang terpercaya');
        $response->assertSee('Tonggak Sejarah Merger 8 BPR');
        $response->assertSee('Visi Perusahaan');
        $response->assertSee('Misi Perusahaan');

        // Dynamic category count buttons
        $tabunganCount = Product::where('category', 'tabungan')->active()->count();
        $depositoCount = Product::where('category', 'deposito')->active()->count();
        $kreditCount = Product::where('category', 'kredit')->active()->count();

        $response->assertSee("Kenali Tabungan ({$tabunganCount} Produk)");
        $response->assertSee("Kenali Deposito ({$depositoCount} Pilihan)");
        $response->assertSee("Kenali Kredit ({$kreditCount} Kategori)");

        // Download link for publication reports
        $response->assertSee('Unduh PDF');
        $response->assertSee('download');
    }
}
