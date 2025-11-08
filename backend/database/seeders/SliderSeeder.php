<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Slider;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'COFFEE',
                'subtitle' => 'We Have Been Serving',
                'description' => '* SINCE 2010 *',
                'image' => 'img/carousel-1.jpg',
                'button_text' => 'Explore Menu',
                'button_url' => '/menu',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'EXCELLENCE',
                'subtitle' => 'Brewing Quality &',
                'description' => '* EVERY DAY *',
                'image' => 'img/carousel-2.jpg',
                'button_text' => 'Learn More',
                'button_url' => '/about',
                'sort_order' => 2,
                'is_active' => true
            ]
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
