<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Fastest Door Delivery',
                'description' => 'Get your favorite Asili Cafe beverages and treats delivered to your door in under 30 minutes. We pride ourselves on quick, reliable service that brings our quality directly to you.',
                'icon' => 'fa fa-truck',
                'image' => 'img/service-1.jpg',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'title' => 'Fresh Coffee Beans',
                'description' => 'We source our premium coffee beans directly from sustainable farms around the world. Each batch is roasted fresh daily to ensure the perfect flavor profile in every cup.',
                'icon' => 'fa fa-coffee',
                'image' => 'img/service-2.jpg',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'title' => 'Best Quality Coffee',
                'description' => 'Our master baristas craft each drink with precision and care. From bean selection to brewing technique, we maintain the highest standards for exceptional coffee experiences.',
                'icon' => 'fa fa-award',
                'image' => 'img/service-3.jpg',
                'sort_order' => 3,
                'is_active' => true
            ],
            [
                'title' => 'Online Table Booking',
                'description' => 'Reserve your perfect spot at Asili Cafe with our convenient online booking system. Whether for a business meeting or casual catch-up, we have the perfect ambiance waiting for you.',
                'icon' => 'fa fa-table',
                'image' => 'img/service-4.jpg',
                'sort_order' => 4,
                'is_active' => true
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
