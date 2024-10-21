<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure we have at least one user
        $user = User::factory()->create();

        $categories = ['Electronics', 'Furniture', 'Clothing', 'Books', 'Sports'];
        $subcategories = [
            'Electronics' => ['Smartphones', 'Laptops', 'Cameras', 'Audio'],
            'Furniture' => ['Sofas', 'Tables', 'Chairs', 'Beds'],
            'Clothing' => ['Shirts', 'Pants', 'Dresses', 'Shoes'],
            'Books' => ['Fiction', 'Non-fiction', 'Textbooks', 'Comics'],
            'Sports' => ['Equipment', 'Clothing', 'Accessories', 'Shoes']
        ];
        $conditions = ['New', 'Like New', 'Good', 'Fair', 'Poor'];
        $locations = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix'];

        for ($i = 0; $i < 50; $i++) {
            $category = $categories[array_rand($categories)];
            $subcategory = $subcategories[$category][array_rand($subcategories[$category])];

            Product::create([
                'user_id' => $user->id,
                'name' => "Product " . ($i + 1),
                'description' => "This is a description for product " . ($i + 1),
                'price' => rand(10, 1000) + (rand(0, 99) / 100),
                'category' => $category,
                'subcategory' => $subcategory,
                'condition' => $conditions[array_rand($conditions)],
                'location' => $locations[array_rand($locations)],
                'photos' => json_encode([
                    "https://picsum.photos/200/300?random=" . ($i + 1),
                    "https://picsum.photos/200/300?random=" . ($i + 2)
                ]),
                'clicks' => rand(0, 100),
            ]);
        }
    }
}