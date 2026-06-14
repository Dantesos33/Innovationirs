<?php
namespace Database\Seeders;

use App\Models\EquipmentType;
use Database\Seeders\Traits\DownloadsImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EquipmentTypesSeeder extends Seeder
{
    use DownloadsImages;

    // Real equipment types from amsparts.com navigation
    private array $types = [
        [
            'name'        => 'Excavator',
            'sort_order'  => 1,
            'description' => 'Hydraulic excavators, crawler excavators, and trackhoes for digging, trenching, demolition, and material handling. Parts available for mini, midi, and full-size excavator models from all major manufacturers.',
            'image_url'   => 'https://assets.amsparts.com/assets/img/excavator-parts.jpg',
        ],
        [
            'name'        => 'Backhoe',
            'sort_order'  => 2,
            'description' => 'Backhoe loaders and tractor-loader-backhoes (TLB) for utility work, trenching, and general construction. Parts available for Case, John Deere, Caterpillar, and JCB backhoe models.',
            'image_url'   => 'https://assets.amsparts.com/assets/img/backhoe-parts.jpg',
        ],
        [
            'name'        => 'Bulldozer',
            'sort_order'  => 3,
            'description' => 'Track-type tractors and crawler dozers with front blades for pushing soil, rock, and debris. Parts available for Caterpillar D-series, Komatsu D-series, John Deere 700-series, and other crawler tractors.',
            'image_url'   => 'https://assets.amsparts.com/assets/img/bulldozer-parts.jpg',
        ],
        [
            'name'        => 'Track Loader',
            'sort_order'  => 4,
            'description' => 'Compact track loaders (CTL) and crawler loaders for work in soft or rough terrain. Parts available for Caterpillar, John Deere, Bobcat, Case, Kubota, and Takeuchi track loader models.',
            'image_url'   => 'https://assets.amsparts.com/assets/img/track-loader-parts.jpg',
        ],
        [
            'name'        => 'Wheel Loader',
            'sort_order'  => 5,
            'description' => 'Front-end loaders and articulated wheel loaders for loading, lifting, and transporting bulk materials. Parts available for Caterpillar 900-series, Komatsu WA-series, Volvo L-series, John Deere K-series, and more.',
            'image_url'   => 'https://assets.amsparts.com/assets/img/wheel-loader-parts.jpg',
        ],
        [
            'name'        => 'Skid Steer',
            'sort_order'  => 6,
            'description' => 'Skid steer loaders for tight-space construction, landscaping, and agriculture work. Parts available for Bobcat S-series, Caterpillar 200-series, Case, John Deere, New Holland, and other skid steer brands.',
            'image_url'   => 'https://assets.amsparts.com/assets/img/skid-steer-parts.jpg',
        ],
        [
            'name'        => 'Feller Buncher',
            'sort_order'  => 7,
            'description' => 'Tracked and wheeled feller bunchers for forestry harvesting operations. Parts available for Timberjack, John Deere, Caterpillar, Tigercat, and other feller buncher manufacturers.',
            'image_url'   => 'https://assets.amsparts.com/assets/img/feller-buncher-parts.jpg',
        ],
        [
            'name'        => 'Off Road Truck',
            'sort_order'  => 8,
            'description' => 'Off-highway rigid and articulated haul trucks for mining and heavy construction. Parts available for Caterpillar, Komatsu, Volvo, and Terex off-road truck models.',
            'image_url'   => 'https://assets.amsparts.com/assets/img/off-road-truck-parts.jpg',
        ],
        [
            'name'        => 'Motor Grader',
            'sort_order'  => 9,
            'description' => 'Road graders for leveling, shaping, and finishing road surfaces and construction grades. Parts available for Caterpillar 100M/140M/160M series, Komatsu GD-series, and John Deere 600-series.',
            'image_url'   => null,
        ],
        [
            'name'        => 'Compactor',
            'sort_order'  => 10,
            'description' => 'Soil and asphalt compaction equipment including single-drum rollers, double-drum rollers, pneumatic tire rollers, and plate compactors for road and site construction.',
            'image_url'   => null,
        ],
    ];

    public function run(): void
    {
        $this->command->info('Seeding Equipment Types...');

        foreach ($this->types as $typeData) {
            $imageUrl = $typeData['image_url'];
            unset($typeData['image_url']);

            $this->command->line("  → {$typeData['name']}");

            $imageMediaId = null;
            if ($imageUrl) {
                $imageMediaId = $this->downloadImage(
                    $imageUrl,
                    'equipment-types',
                    $typeData['name'] . ' Equipment'
                );
            }

            EquipmentType::updateOrCreate(
                ['slug' => Str::slug($typeData['name'])],
                array_merge($typeData, [
                    'slug'           => Str::slug($typeData['name']),
                    'is_active'      => true,
                    'image_media_id' => $imageMediaId,
                ])
            );
        }

        $this->command->info('  ✓ Equipment Types seeded: ' . count($this->types));
    }
}
