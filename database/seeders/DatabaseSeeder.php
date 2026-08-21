<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $products = [
            ['name' => 'Wireless Headphones', 'category' => 'Electronics', 'price_cents' => 7999, 'stock' => 25, 'description' => 'Over-ear Bluetooth headphones with noise cancellation and 30-hour battery life.'],
            ['name' => 'Mechanical Keyboard', 'category' => 'Electronics', 'price_cents' => 12999, 'stock' => 15, 'description' => 'Hot-swappable RGB mechanical keyboard with brown switches.'],
            ['name' => 'Running Shoes', 'category' => 'Sports', 'price_cents' => 8950, 'stock' => 40, 'description' => 'Lightweight cushioned running shoes for daily training.'],
            ['name' => 'Yoga Mat', 'category' => 'Sports', 'price_cents' => 2999, 'stock' => 60, 'description' => 'Non-slip 6mm yoga mat with carrying strap.'],
            ['name' => 'Coffee Maker', 'category' => 'Home', 'price_cents' => 4999, 'stock' => 20, 'description' => '12-cup programmable drip coffee maker with auto shut-off.'],
            ['name' => 'Desk Lamp', 'category' => 'Home', 'price_cents' => 3499, 'stock' => 35, 'description' => 'LED desk lamp with wireless charging pad and 3 light modes.'],
            ['name' => 'Backpack', 'category' => 'Accessories', 'price_cents' => 5999, 'stock' => 30, 'description' => 'Water-resistant 25L backpack with laptop compartment.'],
            ['name' => 'Smart Watch', 'category' => 'Electronics', 'price_cents' => 19999, 'stock' => 10, 'description' => 'Fitness tracking smart watch with heart-rate monitor and GPS.'],
        ];

        foreach ($products as $product) {
            Product::create($product + [
                'slug' => Product::makeUniqueSlug($product['name']),
                'is_published' => true,
            ]);
        }
    }
}
