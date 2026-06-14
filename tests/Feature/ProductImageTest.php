<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    private User $companyUser;
    private Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Company and its User
        $this->company = Company::create([
            'name' => 'Test Company',
            'commercial_register' => 'CR1234',
            'phone' => '12345678',
            'address' => 'Test Address',
        ]);
        $this->companyUser = User::create([
            'name' => 'Company User',
            'email' => 'user@company.com',
            'password' => bcrypt('password'),
            'role' => 'company',
            'userable_id' => $this->company->id,
            'userable_type' => Company::class,
        ]);
    }

    /** @test */
    public function a_company_can_upload_a_product_image_when_creating_a_product()
    {
        Storage::fake('public');

        Sanctum::actingAs($this->companyUser);

        $file = UploadedFile::fake()->create('panadol.png', 100, 'image/png');

        $response = $this->postJson('/api/v1/company/products', [
            'name' => 'Panadol Extra',
            'scientific_name' => 'Paracetamol',
            'description' => 'Pain relief',
            'strength' => '500mg',
            'dosage_form' => 'Tablet',
            'production_date' => '2026-06-01',
            'expiry_date' => '2028-06-01',
            'batch_number' => 'B123',
            'price' => 10.5,
            'stock_quantity' => 100,
            'status' => true,
            'image' => $file,
        ]);

        $response->assertStatus(201);

        // Assert the image was stored
        $product = Product::first();
        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);

        // Assert response contains image_url
        $expectedUrl = asset('storage/' . $product->image_path);
        $response->assertJsonPath('data.image_url', $expectedUrl);
    }

    /** @test */
    public function product_responses_include_full_image_url()
    {
        // Create product with an image path
        $product = Product::create([
            'company_id' => $this->company->id,
            'name' => 'Panadol Extra',
            'scientific_name' => 'Paracetamol',
            'production_date' => '2026-06-01',
            'expiry_date' => '2028-06-01',
            'price' => 10.5,
            'stock_quantity' => 100,
            'status' => true,
            'image_path' => 'products/fake_image.png',
        ]);

        Sanctum::actingAs($this->companyUser);

        // Test GET /api/v1/company/products (Index)
        $indexResponse = $this->getJson('/api/v1/company/products');
        $indexResponse->assertStatus(200);
        $indexResponse->assertJsonPath('data.0.image_url', asset('storage/products/fake_image.png'));

        // Test GET /api/v1/company/products/{product} (Show)
        $showResponse = $this->getJson("/api/v1/company/products/{$product->id}");
        $showResponse->assertStatus(200);
        $showResponse->assertJsonPath('data.image_url', asset('storage/products/fake_image.png'));

        // Create a Pharmacy user to browse company products
        $pharmacy = \App\Models\Pharmacy::create([
            'name' => 'Test Pharmacy',
            'license_number' => 'LIC123',
            'phone' => '87654321',
            'address' => 'Pharmacy Address',
        ]);
        $pharmacyUser = User::create([
            'name' => 'Pharmacy User',
            'email' => 'user@pharmacy.com',
            'password' => bcrypt('password'),
            'role' => 'pharmacy',
            'userable_id' => $pharmacy->id,
            'userable_type' => \App\Models\Pharmacy::class,
        ]);

        Sanctum::actingAs($pharmacyUser);

        // Test GET /api/v1/pharmacy/companies/{company}/products
        $pharmacyResponse = $this->getJson("/api/v1/pharmacy/companies/{$this->company->id}/products");
        $pharmacyResponse->assertStatus(200);
        $pharmacyResponse->assertJsonPath('data.0.image_url', asset('storage/products/fake_image.png'));
    }
}
