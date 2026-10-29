<?php

namespace Tests\Feature;

use App\Models\LoanApplication;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_fetch_all_products(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total' => 24,
            ]);
    }

    public function test_can_fetch_all_12_branches(): void
    {
        $response = $this->getJson('/api/branches');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total' => 12,
            ]);
    }

    public function test_can_fetch_articles(): void
    {
        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total' => 3,
            ]);
    }

    public function test_can_fetch_publication_reports(): void
    {
        $response = $this->getJson('/api/reports');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total' => 6,
            ]);
    }

    public function test_can_fetch_settings(): void
    {
        $response = $this->getJson('/api/settings');

        $response->assertStatus(200)
            ->assertJsonPath('data.company_name', 'Perumda BPR Majalengka');
    }

    public function test_submitting_loan_application_persists_to_database(): void
    {
        $payload = [
            'nik' => '3210123456789012',
            'nama' => 'Budi Santoso',
            'alamat' => 'Jl. Pemuda No. 10 Majalengka',
            'hp' => '081299887766',
            'afiliasi' => 'KAS-KADIPATEN',
            'nominal' => '25000000',
            'tenor' => '24',
            'tujuan' => 'Modal Usaha Toko Kelontong',
            'sumber_pendapatan' => 'Usaha Dagang',
            'pendapatan_tetap' => '7000000',
            'pendapatan_lain' => '1000000',
            'deskripsi_lain' => 'Warung kopi sore',
            'agunan' => 'BPKB Motor Honda 2022',
        ];

        $response = $this->postJson('/api/pengajuan-kredit', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $refCode = $response->json('data.reference_code');
        $this->assertNotEmpty($refCode);

        $this->assertDatabaseHas('loan_applications', [
            'reference_code' => $refCode,
            'nik' => '3210123456789012',
            'nama' => 'Budi Santoso',
        ]);
    }

    public function test_submitting_loan_application_rejects_invalid_nik(): void
    {
        $payload = [
            'nik' => '12345', // invalid, not 16 digits
            'nama' => 'Budi Santoso',
            'alamat' => 'Jl. Pemuda No. 10 Majalengka',
            'hp' => '081299887766',
            'nominal' => '25000000',
            'tenor' => '24',
            'tujuan' => 'Modal Usaha',
            'pendapatan_tetap' => '7000000',
            'agunan' => 'BPKB',
        ];

        $response = $this->postJson('/api/pengajuan-kredit', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_submitting_loan_application_rejects_missing_required_fields(): void
    {
        $response = $this->postJson('/api/pengajuan-kredit', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik', 'nama', 'alamat', 'hp', 'nominal', 'tenor', 'tujuan', 'agunan']);
    }

    public function test_products_api_can_filter_by_category(): void
    {
        $response = $this->getJson('/api/products?category=tabungan');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        foreach ($data as $prod) {
            $this->assertEquals('tabungan', $prod['category']);
        }
    }

    public function test_product_detail_api(): void
    {
        $product = Product::first();
        $response = $this->getJson("/api/products/{$product->slug}");

        $response->assertStatus(200)
            ->assertJsonPath('data.name', $product->name);

        $notFound = $this->getJson('/api/products/slug-tidak-ada');
        $notFound->assertStatus(404);
    }

    public function test_global_search_api(): void
    {
        $response = $this->getJson('/api/search?q=tahara');
        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertArrayHasKey('products', $data);
        $this->assertArrayHasKey('branches', $data);
        $this->assertArrayHasKey('articles', $data);
    }
}
