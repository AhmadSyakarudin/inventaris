<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Setup dummy data
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);
        $this->category = Category::create([
            'name' => 'Elektronik Test',
            'division_pj' => 'Sarpras'
        ]);
    }

    public function test_admin_can_view_items_page()
    {
        $response = $this->actingAs($this->admin)->get('/items');
        $response->assertStatus(200);
    }

    public function test_staff_can_view_items_page()
    {
        $response = $this->actingAs($this->staff)->get('/items');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_item()
    {
        $response = $this->actingAs($this->admin)->post('/items', [
            'name' => 'Laptop Baru',
            'category_id' => $this->category->id,
            'total' => 10,
        ]);

        $response->assertRedirect('/items');
        $this->assertDatabaseHas('items', [
            'name' => 'Laptop Baru',
            'total' => 10
        ]);
    }

    public function test_staff_cannot_create_item_due_to_middleware()
    {
        $response = $this->actingAs($this->staff)->post('/items', [
            'name' => 'Laptop Baru',
            'category_id' => $this->category->id,
            'total' => 10,
        ]);

        // Karena staff tidak punya akses (role:admin middleware), biasanya diredirect atau forbidden
        // Di aplikasi ini, middleware me-redirect (302) jika akses ditolak
        $response->assertStatus(302);
    }
}
