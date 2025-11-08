<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Sarah Johnson',
                'profession' => 'Business Executive',
                'message' => 'Asili Cafe has become my go-to spot for morning meetings. The coffee is exceptional and the atmosphere is perfect for productive conversations.',
                'image' => 'img/testimonial-1.jpg',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Michael Chen',
                'profession' => 'Software Developer',
                'message' => 'The consistency of quality at Asili Cafe is remarkable. Whether I order online or visit in person, every cup is crafted to perfection.',
                'image' => 'img/testimonial-2.jpg',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'Emma Rodriguez',
                'profession' => 'Freelance Designer',
                'message' => 'I spend hours working here, and the staff always makes me feel welcome. The wifi is fast, the coffee is amazing, and the environment is inspiring.',
                'image' => 'img/testimonial-3.jpg',
                'rating' => 5,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'name' => 'David Thompson',
                'profession' => 'University Professor',
                'message' => 'Asili Cafe understands coffee culture. From the careful bean selection to the skilled preparation, every detail shows their passion for excellence.',
                'image' => 'img/testimonial-4.jpg',
                'rating' => 5,
                'is_featured' => false,
                'is_active' => true
            ]
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
