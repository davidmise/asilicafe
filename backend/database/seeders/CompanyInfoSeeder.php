<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyInfo;

class CompanyInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyInfo = [
            // Basic Company Info
            [
                'key' => 'company_name',
                'value' => 'Asili Cafe',
                'type' => 'text',
                'description' => 'Company name'
            ],
            [
                'key' => 'company_tagline',
                'value' => 'Serving Excellence Since 2010',
                'type' => 'text',
                'description' => 'Company tagline'
            ],
            [
                'key' => 'company_description',
                'value' => 'Asili Cafe has been serving premium coffee and exceptional dining experiences for over 14 years. We pride ourselves on quality, service, and creating memorable moments for our customers.',
                'type' => 'text',
                'description' => 'Company description'
            ],

            // Contact Information
            [
                'key' => 'contact_address',
                'value' => '123 Coffee Street, Downtown, Your City',
                'type' => 'address',
                'description' => 'Business address'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (555) 123-4567',
                'type' => 'phone',
                'description' => 'Primary phone number'
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@asilicafe.com',
                'type' => 'email',
                'description' => 'Primary email address'
            ],

            // Business Hours
            [
                'key' => 'hours_weekday',
                'value' => 'Monday - Friday: 7:00 AM - 9:00 PM',
                'type' => 'text',
                'description' => 'Weekday operating hours'
            ],
            [
                'key' => 'hours_weekend',
                'value' => 'Saturday - Sunday: 8:00 AM - 10:00 PM',
                'type' => 'text',
                'description' => 'Weekend operating hours'
            ],

            // Social Media
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/asilicafe',
                'type' => 'url',
                'description' => 'Facebook page URL'
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/asilicafe',
                'type' => 'url',
                'description' => 'Twitter profile URL'
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/asilicafe',
                'type' => 'url',
                'description' => 'Instagram profile URL'
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/asilicafe',
                'type' => 'url',
                'description' => 'LinkedIn page URL'
            ],

            // About Us Content
            [
                'key' => 'about_story_title',
                'value' => 'Our Story',
                'type' => 'text',
                'description' => 'About section story title'
            ],
            [
                'key' => 'about_story_content',
                'value' => 'Founded in 2010, Asili Cafe began as a small neighborhood coffee shop with a big dream: to serve the perfect cup of coffee. Over 14 years, we have grown while maintaining our commitment to quality, community, and the art of coffee making.',
                'type' => 'text',
                'description' => 'About section story content'
            ],
            [
                'key' => 'about_vision_title',
                'value' => 'Our Vision',
                'type' => 'text',
                'description' => 'About section vision title'
            ],
            [
                'key' => 'about_vision_content',
                'value' => 'To be the premier destination for coffee lovers, creating exceptional experiences through superior quality, sustainable practices, and genuine hospitality that brings people together.',
                'type' => 'text',
                'description' => 'About section vision content'
            ],

            // Newsletter
            [
                'key' => 'newsletter_title',
                'value' => 'Stay Connected',
                'type' => 'text',
                'description' => 'Newsletter section title'
            ],
            [
                'key' => 'newsletter_description',
                'value' => 'Subscribe to our newsletter for the latest updates on new menu items, special offers, and events at Asili Cafe.',
                'type' => 'text',
                'description' => 'Newsletter section description'
            ]
        ];

        foreach ($companyInfo as $info) {
            CompanyInfo::create($info);
        }
    }
}
