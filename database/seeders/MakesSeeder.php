<?php
namespace Database\Seeders;

use App\Models\Make;
use Database\Seeders\Traits\DownloadsImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MakesSeeder extends Seeder
{
    use DownloadsImages;

    // Real brand logos from amsparts.com CDN
    private array $makes = [
        [
            'name'        => 'Caterpillar',
            'sort_order'  => 1,
            'description' => 'World\'s leading manufacturer of construction and mining equipment. CAT parts available for 3-series through Next Gen excavators, D-series bulldozers, 900-series wheel loaders, motor graders, and more.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/caterpillar-replacement-parts.png',
        ],
        [
            'name'        => 'Komatsu',
            'sort_order'  => 2,
            'description' => 'Japanese multinational producing a full range of construction and mining equipment. Komatsu parts available for PC-series excavators, WA wheel loaders, D-series bulldozers, and GD motor graders.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/komatsu-replacement-parts.png',
        ],
        [
            'name'        => 'John Deere',
            'sort_order'  => 3,
            'description' => 'American corporation producing construction, agricultural, and forestry machinery. John Deere parts available for G-series excavators, K-series wheel loaders, backhoes, and motor graders.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/john-deere-replacement-parts.png',
        ],
        [
            'name'        => 'Volvo',
            'sort_order'  => 4,
            'description' => 'Swedish heavy equipment manufacturer. Volvo parts available for EC-series excavators, L-series wheel loaders, and A-series articulated haulers.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/volvo-replacement-parts.png',
        ],
        [
            'name'        => 'Hitachi',
            'sort_order'  => 5,
            'description' => 'Japanese manufacturer producing hydraulic excavators, wheel loaders, and rigid dump trucks. Hitachi ZX-series parts available including hydraulic pumps, final drives, and swing machinery.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/hitachi-replacement-parts.png',
        ],
        [
            'name'        => 'Case',
            'sort_order'  => 6,
            'description' => 'American manufacturer of construction equipment including excavators, wheel loaders, backhoes, and dozers. Case CX-series excavator and 700-series loader parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/case-replacement-parts.png',
        ],
        [
            'name'        => 'Bobcat',
            'sort_order'  => 7,
            'description' => 'North American manufacturer of compact construction equipment. Bobcat S-series skid steers, T-series compact track loaders, and E-series mini excavator parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/bobcat-replacement-parts.png',
        ],
        [
            'name'        => 'Doosan',
            'sort_order'  => 8,
            'description' => 'South Korean heavy equipment manufacturer. Doosan DX-series excavator and DL-series wheel loader parts available including hydraulic pumps, final drives, and undercarriage.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/doosan-replacement-parts.png',
        ],
        [
            'name'        => 'Hyundai',
            'sort_order'  => 9,
            'description' => 'South Korean manufacturer producing excavators, wheel loaders, and forklifts. Hyundai HX-series and R-series construction equipment parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/hyundai-replacement-parts.png',
        ],
        [
            'name'        => 'Kobelco',
            'sort_order'  => 10,
            'description' => 'Japanese manufacturer specializing in hydraulic excavators. Kobelco SK-series excavator parts including hydraulic pumps, swing motors, cylinders, and undercarriage.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/kobelco-replacement-parts.png',
        ],
        [
            'name'        => 'Daewoo',
            'sort_order'  => 11,
            'description' => 'South Korean manufacturer of construction equipment. Daewoo Solar-series excavator and DL-series wheel loader parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/daewoo-replacement-parts.png',
        ],
        [
            'name'        => 'Kubota',
            'sort_order'  => 12,
            'description' => 'Japanese manufacturer known for compact and mini excavators and track loaders. Kubota KX, SVL, and U-series parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/kubota-replacement-parts.png',
        ],
        [
            'name'        => 'Link Belt',
            'sort_order'  => 13,
            'description' => 'American manufacturer of excavators and cranes. Link Belt X2, X3, and X4-series excavator parts including hydraulic and undercarriage components.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/link-belt-replacement-parts.png',
        ],
        [
            'name'        => 'New Holland',
            'sort_order'  => 14,
            'description' => 'Construction equipment manufacturer producing excavators, wheel loaders, and backhoes. New Holland E-series and W-series parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/new-holland-replacement-parts.png',
        ],
        [
            'name'        => 'Samsung',
            'sort_order'  => 15,
            'description' => 'South Korean manufacturer of heavy construction equipment including excavators and wheel loaders. Samsung SE and SL-series parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/samsung-replacement-parts.png',
        ],
        [
            'name'        => 'Takeuchi',
            'sort_order'  => 16,
            'description' => 'Japanese manufacturer of compact construction equipment. Takeuchi TB-series mini excavators and TL-series compact track loaders — hydraulic and undercarriage parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/takeuchi-replacement-parts.png',
        ],
        [
            'name'        => 'Timberjack',
            'sort_order'  => 17,
            'description' => 'Forestry equipment manufacturer producing feller bunchers, skidders, and forwarders. Timberjack 600-series and 800-series machine parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/timberjack-replacement-parts.png',
        ],
        [
            'name'        => 'Yanmar',
            'sort_order'  => 18,
            'description' => 'Japanese manufacturer of compact construction equipment. Yanmar ViO and SV-series mini excavator parts available including engines and hydraulics.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/yanmar-replacement-parts.png',
        ],
        [
            'name'        => 'JCB',
            'sort_order'  => 19,
            'description' => 'British manufacturer of construction and agricultural equipment. JCB backhoe loader, excavator, Fastrac tractor, and telehandler parts available.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/jcb-replacement-parts.png',
        ],
        [
            'name'        => 'Dresser',
            'sort_order'  => 20,
            'description' => 'American manufacturer of construction and mining equipment. Dresser 510, 540, and 570-series wheel loader parts including hydraulics and powertrain components.',
            'logo_url'    => 'https://assets.amsparts.com/assets/brands/dresser-replacement-parts.png',
        ],
    ];

    public function run(): void
    {
        $this->command->info('Seeding Makes...');

        foreach ($this->makes as $makeData) {
            $logoUrl = $makeData['logo_url'];
            unset($makeData['logo_url']);

            $this->command->line("  → {$makeData['name']}");

            $logoMediaId = $this->downloadImage(
                $logoUrl,
                'makes',
                $makeData['name'] . ' Logo'
            );

            Make::updateOrCreate(
                ['slug' => Str::slug($makeData['name'])],
                array_merge($makeData, [
                    'slug'          => Str::slug($makeData['name']),
                    'is_active'     => true,
                    'logo_media_id' => $logoMediaId,
                    'parts_count'   => 0,
                ])
            );
        }

        $this->command->info('  ✓ Makes seeded: ' . count($this->makes));
    }
}
