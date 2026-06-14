<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InvoiceIsolationTest extends TestCase
{
    use RefreshDatabase;

    private User $companyUser1;
    private Company $company1;

    private User $companyUser2;
    private Company $company2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Company 1 and its User
        $this->company1 = Company::create([
            'name' => 'Company A',
            'commercial_register' => 'CR1111',
            'phone' => '12345678',
            'address' => 'Address 1',
        ]);
        $this->companyUser1 = User::create([
            'name' => 'User A',
            'email' => 'user1@company.com',
            'password' => bcrypt('password'),
            'role' => 'company',
            'userable_id' => $this->company1->id,
            'userable_type' => Company::class,
        ]);

        // Create Company 2 and its User
        $this->company2 = Company::create([
            'name' => 'Company B',
            'commercial_register' => 'CR2222',
            'phone' => '87654321',
            'address' => 'Address 2',
        ]);
        $this->companyUser2 = User::create([
            'name' => 'User B',
            'email' => 'user2@company.com',
            'password' => bcrypt('password'),
            'role' => 'company',
            'userable_id' => $this->company2->id,
            'userable_type' => Company::class,
        ]);
    }

    /** @test */
    public function a_company_can_only_list_its_own_invoices()
    {
        // Create an invoice for Company 1
        Invoice::create([
            'company_id' => $this->company1->id,
            'invoice_number' => 'INV-001',
            'type' => 'credit',
            'status' => 'unpaid',
            'total_amount' => 100,
        ]);

        // Create an invoice for Company 2
        Invoice::create([
            'company_id' => $this->company2->id,
            'invoice_number' => 'INV-002',
            'type' => 'credit',
            'status' => 'unpaid',
            'total_amount' => 200,
        ]);

        // Authenticate as Company 1
        Sanctum::actingAs($this->companyUser1);

        $response = $this->getJson('/api/v1/invoices');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.invoice_number', 'INV-001');
        $response->assertJsonPath('data.0.company_id', $this->company1->id);
    }

    /** @test */
    public function creating_an_invoice_automatically_associates_it_with_the_authenticated_users_company()
    {
        Sanctum::actingAs($this->companyUser1);

        $response = $this->postJson('/api/v1/invoices', [
            'type' => 'cash',
            'currency' => 'USD',
            'items' => [
                [
                    'product_name' => 'Paracetamol',
                    'quantity' => 10,
                    'unit_price' => 5.0,
                    'discount' => 0,
                    'tax_percent' => 0,
                ]
            ]
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.company_id', $this->company1->id);

        $this->assertDatabaseHas('invoices', [
            'company_id' => $this->company1->id,
            'type' => 'cash',
        ]);
    }

    /** @test */
    public function a_company_cannot_view_another_companys_invoice()
    {
        $invoiceOfCompany2 = Invoice::create([
            'company_id' => $this->company2->id,
            'invoice_number' => 'INV-002',
            'type' => 'credit',
            'status' => 'unpaid',
            'total_amount' => 200,
        ]);

        Sanctum::actingAs($this->companyUser1);

        $response = $this->getJson("/api/v1/invoices/{$invoiceOfCompany2->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function a_company_cannot_delete_another_companys_invoice()
    {
        $invoiceOfCompany2 = Invoice::create([
            'company_id' => $this->company2->id,
            'invoice_number' => 'INV-002',
            'type' => 'credit',
            'status' => 'unpaid',
            'total_amount' => 200,
        ]);

        Sanctum::actingAs($this->companyUser1);

        $response = $this->deleteJson("/api/v1/invoices/{$invoiceOfCompany2->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function a_company_cannot_add_payments_to_another_companys_invoice()
    {
        $invoiceOfCompany2 = Invoice::create([
            'company_id' => $this->company2->id,
            'invoice_number' => 'INV-002',
            'type' => 'credit',
            'status' => 'unpaid',
            'total_amount' => 200,
        ]);

        Sanctum::actingAs($this->companyUser1);

        $response = $this->postJson("/api/v1/invoices/{$invoiceOfCompany2->id}/payments", [
            'amount' => 50,
            'method' => 'cash',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function a_company_cannot_list_payments_of_another_companys_invoice()
    {
        $invoiceOfCompany2 = Invoice::create([
            'company_id' => $this->company2->id,
            'invoice_number' => 'INV-002',
            'type' => 'credit',
            'status' => 'unpaid',
            'total_amount' => 200,
        ]);

        Sanctum::actingAs($this->companyUser1);

        $response = $this->getJson("/api/v1/invoices/{$invoiceOfCompany2->id}/payments");

        $response->assertStatus(403);
    }

    /** @test */
    public function a_company_cannot_create_a_return_invoice_for_another_companys_invoice()
    {
        $invoiceOfCompany2 = Invoice::create([
            'company_id' => $this->company2->id,
            'invoice_number' => 'INV-002',
            'type' => 'credit',
            'status' => 'unpaid',
            'total_amount' => 200,
        ]);

        Sanctum::actingAs($this->companyUser1);

        $response = $this->postJson("/api/v1/invoices/return", [
            'original_invoice_id' => $invoiceOfCompany2->id,
            'invoice_number' => 'RET-001',
            'total_amount' => 50,
        ]);

        $response->assertStatus(404); // returns ModelNotFoundException (404) because original invoice query is scoped to company
    }
}
