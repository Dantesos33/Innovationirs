<?php
namespace Database\Seeders;

use App\Models\Category;
use Database\Seeders\Traits\DownloadsImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    use DownloadsImages;

    // Real categories from amsparts.com with CDN image URLs
    private array $categories = [
        [
            'name'        => 'Hydraulic Pumps',
            'sort_order'  => 1,
            'is_featured' => true,
            'description' => 'New aftermarket, used, and rebuilt hydraulic pumps for heavy construction equipment. We stock main pumps, gear pumps, piston pumps, and vane pumps from Bonfiglioli, Kawasaki, Nachi, Nabtesco, KYB, and more. Save 20–70% versus OEM dealer pricing.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/hydraulic-pumps.png',
        ],
        [
            'name'        => 'Final Drives',
            'sort_order'  => 2,
            'is_featured' => true,
            'description' => 'New aftermarket, used, and rebuilt final drives and travel motors for excavators, track loaders, and crawler equipment. Final drive manufacturers include Bonfiglioli, Hy-Dash, Kayaba, KYB, MAG, Kawasaki, Nachi, and Nabtesco. Warranties from 30 days to 1 year.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/final-drives.png',
        ],
        [
            'name'        => 'Undercarriages',
            'sort_order'  => 3,
            'is_featured' => true,
            'description' => 'Complete undercarriage components including track chains, track shoes, bottom rollers, carrier rollers, front idlers, sprockets, and recoil springs for excavators and bulldozers. New and rebuilt options available.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/undercarriages.png',
        ],
        [
            'name'        => 'Engines & Engine Parts',
            'sort_order'  => 4,
            'is_featured' => true,
            'description' => 'Complete engines and individual engine components including cylinder heads, pistons, rings, gaskets, crankshafts, camshafts, connecting rods, and engine rebuild kits for heavy equipment. New, remanufactured, and used options.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/engines-engine-parts.png',
        ],
        [
            'name'        => 'Hydraulic Cylinders',
            'sort_order'  => 5,
            'is_featured' => true,
            'description' => 'Boom, arm, and bucket cylinders for excavators plus lift, tilt, and steering cylinders for wheel loaders and other equipment. New, rebuilt, and used cylinder assemblies and seal kits available.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/hydraulic-cylinders.png',
        ],
        [
            'name'        => 'Attachments',
            'sort_order'  => 6,
            'is_featured' => true,
            'description' => 'Construction equipment attachments including buckets, blades, thumbs, grapples, augers, breakers, compactors, and quick coupler systems. New and used options for excavators, wheel loaders, and skid steers.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/attachments.png',
        ],
        [
            'name'        => 'Radiators',
            'sort_order'  => 7,
            'is_featured' => true,
            'description' => 'New aftermarket and rebuilt radiators, oil coolers, and intercoolers for heavy construction equipment. Aluminum-core and copper-brass options available. All units pressure tested before shipping.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/radiators.png',
        ],
        [
            'name'        => 'Transmissions',
            'sort_order'  => 8,
            'is_featured' => true,
            'description' => 'New aftermarket, used, and rebuilt transmissions for wheel loaders, motor graders, backhoes, and other heavy equipment. Torque converters, transmission filters, and rebuild kits also available.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/transmissions.png',
        ],
        [
            'name'        => 'Axles & Drivetrains',
            'sort_order'  => 9,
            'is_featured' => false,
            'description' => 'Complete axle assemblies, differentials, drive shafts, axle seals, and related drivetrain components for wheel loaders, motor graders, backhoes, and articulated haulers.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/axles-drivetrains.png',
        ],
        [
            'name'        => 'Cabs',
            'sort_order'  => 10,
            'is_featured' => false,
            'description' => 'Operator cab components including ROPS/FOPS cabs, cab glass, door seals, seats, seat belts, cab hardware, wiper motors, mirrors, gauges, and HVAC components for construction equipment.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/cabs.png',
        ],
        [
            'name'        => 'Seal Kits',
            'sort_order'  => 11,
            'is_featured' => false,
            'description' => 'Hydraulic cylinder seal kits, pump seal kits, motor seal kits, and O-ring kits for heavy construction equipment. Polyurethane, NBR, and Viton seal options available for all major makes.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/seal-kits.png',
        ],
        [
            'name'        => 'Swing Machinery',
            'sort_order'  => 12,
            'is_featured' => false,
            'description' => 'Swing motors, swing reduction gear boxes, swing bearing rings, and swing motor seal kits for hydraulic excavators. New, rebuilt, and used swing machinery assemblies available.',
            'image_url'   => 'https://assets.amsparts.com/assets/categories/swing-machinery.png',
        ],
        // Additional categories matching site navigation
        [
            'name'        => 'Filters & Maintenance',
            'sort_order'  => 13,
            'is_featured' => false,
            'description' => 'Engine oil filters, fuel filters, hydraulic filters, air filters, and complete service filter kits for heavy equipment maintenance. OEM-spec replacements for all major makes and models.',
            'image_url'   => null,
        ],
        [
            'name'        => 'Electrical Components',
            'sort_order'  => 14,
            'is_featured' => false,
            'description' => 'Alternators, starter motors, sensors, wiring harnesses, ECUs, relays, switches, and batteries for construction machinery. New and remanufactured electrical parts.',
            'image_url'   => null,
        ],
        [
            'name'        => 'Fuel System',
            'sort_order'  => 15,
            'is_featured' => false,
            'description' => 'Fuel injection pumps, fuel injectors, fuel lift pumps, fuel tanks, fuel filters, and fuel system seal kits for heavy equipment engines.',
            'image_url'   => null,
        ],
        [
            'name'        => 'Ground Engaging Tools',
            'sort_order'  => 16,
            'is_featured' => false,
            'description' => 'Bucket teeth, tooth adapters, cutting edges, wear plates, ripper shanks, and related ground-engaging tooling for excavators and bulldozers.',
            'image_url'   => null,
        ],
        [
            'name'        => 'Steering Components',
            'sort_order'  => 17,
            'is_featured' => false,
            'description' => 'Steering cylinders, steering pumps, steering control units, tie rods, and related steering hardware for wheel loaders, motor graders, and backhoes.',
            'image_url'   => null,
        ],
        [
            'name'        => 'Brakes & Brake Parts',
            'sort_order'  => 18,
            'is_featured' => false,
            'description' => 'Brake assemblies, brake discs, brake drums, brake actuators, parking brake components, and brake seals for heavy construction equipment.',
            'image_url'   => null,
        ],
    ];

    public function run(): void
    {
        $this->command->info('Seeding Categories...');

        foreach ($this->categories as $catData) {
            $imageUrl = $catData['image_url'];
            unset($catData['image_url']);

            $this->command->line("  → {$catData['name']}");

            $imageMediaId = null;
            if ($imageUrl) {
                $imageMediaId = $this->downloadImage(
                    $imageUrl,
                    'categories',
                    $catData['name'] . ' Category Image'
                );
            }

            Category::updateOrCreate(
                ['slug' => Str::slug($catData['name'])],
                array_merge($catData, [
                    'slug'           => Str::slug($catData['name']),
                    'is_active'      => true,
                    'image_media_id' => $imageMediaId,
                    'parts_count'    => 0,
                ])
            );
        }

        $this->command->info('  ✓ Categories seeded: ' . count($this->categories));
    }
}
