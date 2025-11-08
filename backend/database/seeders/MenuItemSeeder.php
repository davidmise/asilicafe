<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuItems = [
            // Hot Coffee
            [
                'name' => 'Classic Espresso',
                'description' => 'Rich and bold espresso shot made from premium Ethiopian beans',
                'price' => 3.50,
                'image' => 'img/menu-1.jpg',
                'category' => 'hot',
                'is_featured' => true,
                'is_available' => true
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Perfect balance of espresso, steamed milk, and milk foam',
                'price' => 4.25,
                'image' => 'img/menu-2.jpg',
                'category' => 'hot',
                'is_featured' => false,
                'is_available' => true
            ],
            [
                'name' => 'Caramel Macchiato',
                'description' => 'Espresso with vanilla syrup, steamed milk, and caramel drizzle',
                'price' => 5.75,
                'image' => 'img/menu-3.jpg',
                'category' => 'hot',
                'is_featured' => true,
                'is_available' => true
            ],
            [
                'name' => 'Mocha Latte',
                'description' => 'Rich chocolate and espresso blend with steamed milk',
                'price' => 5.25,
                'image' => 'img/menu-4.jpg',
                'category' => 'hot',
                'is_featured' => false,
                'is_available' => true
            ],

            // Cold Coffee
            [
                'name' => 'Iced Americano',
                'description' => 'Chilled espresso with cold water served over ice',
                'price' => 3.75,
                'image' => 'img/menu-5.jpg',
                'category' => 'cold',
                'is_featured' => false,
                'is_available' => true
            ],
            [
                'name' => 'Cold Brew',
                'description' => 'Smooth and refreshing coffee brewed cold for 24 hours',
                'price' => 4.50,
                'image' => 'img/menu-6.jpg',
                'category' => 'cold',
                'is_featured' => true,
                'is_available' => true
            ],
            [
                'name' => 'Iced Caramel Frappé',
                'description' => 'Blended coffee with caramel, ice, and whipped cream',
                'price' => 6.25,
                'image' => 'img/menu-7.jpg',
                'category' => 'cold',
                'is_featured' => true,
                'is_available' => true
            ],
            [
                'name' => 'Vanilla Iced Latte',
                'description' => 'Espresso with vanilla syrup and cold milk over ice',
                'price' => 4.75,
                'image' => 'img/menu-8.jpg',
                'category' => 'cold',
                'is_featured' => false,
                'is_available' => true
            ],

            // Food Items
            [
                'name' => 'Croissant Sandwich',
                'description' => 'Buttery croissant with ham, cheese, and fresh vegetables',
                'price' => 7.50,
                'image' => 'img/food-1.jpg',
                'category' => 'food',
                'is_featured' => false,
                'is_available' => true
            ],
            [
                'name' => 'Avocado Toast',
                'description' => 'Fresh avocado on artisan bread with tomatoes and herbs',
                'price' => 8.75,
                'image' => 'img/food-2.jpg',
                'category' => 'food',
                'is_featured' => true,
                'is_available' => true
            ],

            // Desserts
            [
                'name' => 'Chocolate Brownie',
                'description' => 'Rich, fudgy brownie served warm with vanilla ice cream',
                'price' => 5.95,
                'image' => 'img/dessert-1.jpg',
                'category' => 'dessert',
                'is_featured' => false,
                'is_available' => true
            ],
            [
                'name' => 'Tiramisu',
                'description' => 'Classic Italian dessert with coffee-soaked ladyfingers',
                'price' => 6.50,
                'image' => 'img/dessert-2.jpg',
                'category' => 'dessert',
                'is_featured' => true,
                'is_available' => true
            ]
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }
    }
}
