<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_product()
    {
        // Simulate logged in by setting session key the middleware checks
        $response = $this->withSession(['logged_in' => true])->post(route('products.store'), [
            'store_name' => 'Toko Test',
            'name' => 'Produk Test',
            'price' => 5000,
            'description' => 'Deskripsi test'
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'store_name' => 'Toko Test',
            'nama' => 'Produk Test',
            'price' => 5000,
        ]);
    }

    /** @test */
    public function price_with_comma_or_thousand_separator_is_parsed()
    {
        $this->withSession(['logged_in' => true])->post(route('products.store'), [
            'store_name' => 'Toko Test 2',
            'name' => 'Produk Dengan Koma',
            'price' => '120.000,00',
            'description' => 'Deskripsi'
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'store_name' => 'Toko Test 2',
            'nama' => 'Produk Dengan Koma',
            'price' => '120000.00',
        ]);

        // juga pastikan format koma tanpa titik ribuan
        $this->withSession(['logged_in' => true])->post(route('products.store'), [
            'store_name' => 'Toko Test 3',
            'name' => 'Produk Dengan Koma 2',
            'price' => '120000,50',
            'description' => 'Deskripsi 2'
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'store_name' => 'Toko Test 3',
            'nama' => 'Produk Dengan Koma 2',
            'price' => '120000.50',
        ]);
    }
}
