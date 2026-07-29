<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_endpoint(): void
    {
        $response = $this->getJson('/api/public/profile');
        $response->assertStatus(200)
            ->assertJsonStructure(['organization_name', 'full_name', 'description', 'vision', 'mission', 'contact']);
    }

    public function test_public_inventory_and_articles_endpoints(): void
    {
        Item::create([
            'item_code' => 'TEST-001',
            'name' => 'Kamera DSLR',
            'category' => 'Elektronik',
            'status' => 'Tersedia',
        ]);

        Article::create([
            'title' => 'Judul Berita',
            'slug' => 'judul-berita',
            'content' => 'Konten artikel berita...',
            'status' => 'Published',
        ]);

        $this->getJson('/api/public/inventory')->assertStatus(200);
        $this->getJson('/api/public/inventory/TEST-001')->assertStatus(200)->assertJsonPath('data.name', 'Kamera DSLR');

        $this->getJson('/api/public/articles')->assertStatus(200);
        $this->getJson('/api/public/articles/judul-berita')->assertStatus(200)->assertJsonPath('data.title', 'Judul Berita');
    }

    public function test_admin_authentication_and_protected_loan_creation(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200)->assertJsonStructure(['access_token']);
        $token = $loginResponse->json('access_token');

        $item = Item::create([
            'item_code' => 'TEST-002',
            'name' => 'Proyektor Test',
            'category' => 'Elektronik',
            'status' => 'Tersedia',
        ]);

        // Unauthenticated loan request fails
        $this->postJson('/api/admin/loans', [
            'item_id' => $item->id,
            'borrower_name' => 'Ahmad',
            'borrower_phone' => '08123456789',
            'loan_date' => now()->toDateString(),
        ])->assertStatus(401);

        // Authenticated loan request succeeds and updates item status to Dipinjam
        $loanResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/loans', [
                'item_id' => $item->id,
                'borrower_name' => 'Ahmad',
                'borrower_phone' => '08123456789',
                'loan_date' => now()->toDateString(),
            ]);

        $loanResponse->assertStatus(201);
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'Dipinjam',
        ]);
        $this->assertDatabaseHas('loan_records', [
            'item_id' => $item->id,
            'borrower_name' => 'Ahmad',
        ]);
        $this->assertDatabaseHas('inventory_logs', [
            'item_id' => $item->id,
            'action' => 'LOANED',
        ]);
    }
}
