<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 1. Core config & auth
            AdminSeeder::class,
            SiteSettingsSeeder::class,

            // 2. Equipment taxonomy — order matters: makes before models
            EquipmentTypesSeeder::class,   // equipment types first (no FK deps)
            MakesSeeder::class,            // makes (downloads logos from amsparts.com CDN)
            EquipmentModelsSeeder::class,  // models depend on makes
            CategoriesSeeder::class,       // categories (downloads images from amsparts.com CDN)

            // 3. Parts catalogue
            // Downloads product images from files.amsparts.com CDN
            // Attaches categories (many-to-many via part_categories with is_primary pivot)
            // Creates PartImage records and sets primary_image_id
            PartsSeeder::class,

            // 4. Blog
            BlogSeeder::class,

            // 5. Customer-facing data
            TestimonialsSeeder::class,
            FaqsSeeder::class,
            // CareerPostingsSeeder::class,

            // 6. CRM / leads
            // QuoteRequestsSeeder::class,
            ContactMessagesSeeder::class,

            // 7. Newsletter
            NewsletterSeeder::class,
        ]);
    }
}
