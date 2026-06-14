<?php
namespace Database\Seeders;

use App\Models\Admin;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Database\Seeders\Traits\DownloadsImages;

class BlogSeeder extends Seeder
{
    use DownloadsImages;
    public function run(): void
    {
        $admin = Admin::first();

        // ── Blog Categories ────────────────────────────────────────────────
        $categories = [
            ['name' => 'Maintenance Tips', 'sort_order' => 1, 'description' => 'Expert advice on maintaining your heavy equipment to extend service life and reduce downtime.'],
            ['name' => 'Parts Guides', 'sort_order' => 2, 'description' => 'Detailed guides on identifying, selecting, and installing replacement parts.'],
            ['name' => 'Industry News', 'sort_order' => 3, 'description' => 'News and updates from the heavy equipment and construction industry.'],
            ['name' => 'Troubleshooting', 'sort_order' => 4, 'description' => 'Diagnose and resolve common issues with heavy equipment.'],
            ['name' => 'Company Updates', 'sort_order' => 5, 'description' => 'News and updates from the Parts Plus Innovation Solutions team.'],
        ];

        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat['name']] = BlogCategory::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                array_merge($cat, ['slug' => Str::slug($cat['name']), 'is_active' => true])
            );
        }

        // ── Blog Tags ──────────────────────────────────────────────────────
        $tagNames = [
            'Caterpillar', 'Komatsu', 'John Deere', 'Volvo', 'Excavator', 'Bulldozer',
            'Hydraulics', 'Engine', 'Undercarriage', 'Maintenance', 'Filters',
            'Rebuilt Parts', 'OEM vs Aftermarket', 'Downtime Reduction', 'Cost Savings',
        ];

        $tagMap = [];
        foreach ($tagNames as $name) {
            $tagMap[$name] = BlogTag::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'slug' => Str::slug($name)]
            );
        }

        // ── Blog Posts ─────────────────────────────────────────────────────
        $posts = [
            [
                'title'        => 'How to Extend the Life of Your CAT 320D Hydraulic System',
                'category'     => 'Maintenance Tips',
                'tags'         => ['Caterpillar', 'Hydraulics', 'Maintenance'],
                'excerpt'      => 'Your hydraulic system is the heart of your 320D. Follow these proven maintenance steps to avoid costly pump and cylinder failures.',
                'image_url'    => 'https://assets.amsparts.com/assets/blog/cat-320d-hydraulics.jpg',
                'content'      => '<h2>Why Hydraulic Maintenance Matters</h2><p>The hydraulic system on a Caterpillar 320D operates at pressures up to 5,000 PSI and moves over 60 gallons per minute. Even minor contamination or neglected maintenance can lead to premature pump failure, scored cylinders, and stuck control valves — all of which mean expensive downtime.</p><h2>1. Change Hydraulic Fluid on Schedule</h2><p>CAT recommends changing hydraulic fluid every 2,000 hours under normal operating conditions. In dusty or high-temperature environments, shorten this interval to 1,500 hours. Always use CAT HYDO Advanced or equivalent ISO VG 46 hydraulic fluid.</p><h2>2. Replace the Return Filter Every 500 Hours</h2><p>The hydraulic return filter is your last line of defense against metal particles circulating through the system. A clogged or bypassed filter allows wear particles from the pump to score your control valve spools and cylinder bores. Replace it at every 500-hour service interval — no exceptions.</p><h2>3. Inspect Hoses and Fittings Monthly</h2><p>Walk around the machine each month and visually inspect all hydraulic hoses for cracking, chafing, and signs of weeping. Pay special attention to the hoses on the boom, arm, and bucket cylinders, as these flex thousands of times per day. Replace any hose showing cracks or soft spots immediately — a burst hose in the field costs far more in labor and lost production than a proactive replacement.</p><h2>4. Keep Your Fluid Clean — Sample It</h2><p>Fluid analysis is the most powerful tool you have to catch problems before they become failures. Take a sample every 500 hours and send it to a certified lab. Elevated iron or copper particles indicate internal wear; high water content points to a compromised cooler or breather.</p><h2>5. Check the Hydraulic Cooler</h2><p>The cooler is often overlooked until the machine overheats. Clean it monthly with compressed air or low-pressure water, blowing from the clean side toward the dirty side. A blocked cooler raises system temperatures by 20–40°F, dramatically accelerating seal degradation and fluid oxidation.</p><h2>Common Signs of Hydraulic Problems</h2><p>Watch for these early warning signs: slow boom or stick cycle times (pump wear), excessive drift when load is held (control valve or cylinder bypass), high system temperature alarms (low fluid, blocked cooler, or internal bypass), and milky or foamy fluid (water contamination).</p><p>Catching problems early means the difference between a $300 seal kit and a $3,000 pump replacement. Contact our team if you need help diagnosing a hydraulic issue on your 320D.</p>',
                'published_at' => Carbon::now()->subDays(5),
                'status'       => 'published',
                'views'        => 842,
            ],
            [
                'title'        => 'OEM vs. Aftermarket vs. Rebuilt: Which Part Is Right for You?',
                'category'     => 'Parts Guides',
                'tags'         => ['OEM vs Aftermarket', 'Rebuilt Parts', 'Cost Savings'],
                'excerpt'      => 'Not every repair demands a brand-new OEM part. Learn when rebuilt and aftermarket parts offer equal quality at a fraction of the cost.',
                'image_url'    => 'https://assets.amsparts.com/assets/blog/parts-options-guide.jpg',
                'content'      => '<h2>Understanding Your Options</h2><p>When a part fails on your excavator or loader, you have three primary choices: buy a genuine OEM part, buy a quality aftermarket part, or buy a professionally rebuilt part. Each option has its place, and choosing the right one depends on the component, your warranty situation, and your budget.</p><h2>OEM (Original Equipment Manufacturer)</h2><p>OEM parts are manufactured by or to the specifications of the original equipment maker — Caterpillar, Komatsu, Volvo, etc. They are the gold standard for fit, finish, and compatibility, and they are required if your machine is still under manufacturer warranty. The trade-off is price: OEM parts typically cost 40–80% more than equivalent aftermarket or rebuilt options.</p><p>Best for: Warranty repairs, safety-critical electronic components (ECUs, sensors), and situations where downtime risk is extremely high.</p><h2>Quality Aftermarket</h2><p>High-quality aftermarket parts are manufactured by independent companies to match or exceed OEM specifications. Reputable aftermarket suppliers invest heavily in engineering and quality control, and in many cases their parts outlast the OEM equivalent. The key word is "quality" — there is a large range of quality in the aftermarket, from premium manufacturers to low-grade imports.</p><p>Best for: Filters, seals, gaskets, wear parts (teeth, cutting edges), and most mechanical components on out-of-warranty machines.</p><h2>Professionally Rebuilt (Remanufactured)</h2><p>Rebuilt components are returned to OEM specifications through complete disassembly, cleaning, inspection, and replacement of all worn or failed components. A professionally rebuilt hydraulic pump, for example, will have new pistons, a new barrel, new bearings, and new seals — and will be tested to verify flow and pressure before shipping.</p><p>Best for: Major assemblies — hydraulic pumps and motors, final drives, swing drives, alternators, starters, torque converters, and cylinder heads. Rebuilt parts typically cost 30–60% less than OEM replacements and carry the same 1-year warranty we offer on new parts.</p><h2>Our Recommendation</h2><p>For consumables and maintenance items — use quality aftermarket. For major assemblies — use rebuilt. For electronics and warranty repairs — use OEM. Call our team with the part number and we will tell you exactly what we recommend for your specific situation.</p>',
                'published_at' => Carbon::now()->subDays(12),
                'status'       => 'published',
                'views'        => 1204,
            ],
            [
                'title'        => 'Komatsu PC200-8 Undercarriage Wear Guide: When to Replace What',
                'category'     => 'Maintenance Tips',
                'tags'         => ['Komatsu', 'Undercarriage', 'Maintenance', 'Downtime Reduction'],
                'excerpt'      => 'Understanding undercarriage wear limits on your PC200-8 can save thousands in premature replacement costs. Here is what to measure and when to act.',
                'image_url'    => 'https://assets.amsparts.com/assets/blog/undercarriage-wear-guide.jpg',
                'content'      => '<h2>Why Undercarriage Is Your Biggest Operating Cost</h2><p>On a tracked excavator like the Komatsu PC200-8, undercarriage components account for up to 50% of total operating costs. Yet most operators do not inspect wear until something fails completely. Regular measurement and proactive replacement at the right wear limits dramatically reduces total cost of ownership.</p><h2>The Components to Measure</h2><p>A complete undercarriage system includes: track chain and shoes, bottom rollers (track rollers), top rollers (carrier rollers), front idlers, rear sprockets, and the track frame itself. Each component wears at a different rate, and replacing them together at similar wear levels avoids the classic mistake of putting new track chain on worn sprockets — which destroys the chain in half the normal time.</p><h2>Track Chain Wear</h2><p>Measure track pitch elongation. On a PC200-8, the service limit is typically 3% elongation from new. Measure across 5 links with a measuring tape or track wear gauge. For a standard 600mm pitch PC200 chain, new pitch is 190mm per link; replace when you reach 196mm (3% over). At this point the chain is also riding high on the sprocket, increasing stress on the teeth.</p><h2>Sprocket Wear</h2><p>Sprocket teeth wear in a hooked pattern. When the tooth profile becomes "shark fin" shaped, the sprocket is worn and will rapidly destroy new track chain. Inspect the tooth height and profile — replace when tips are hooked or height is reduced by more than 20% from new.</p><h2>Roller and Idler Wear</h2><p>Check the flange height and shell diameter on both bottom rollers and idlers. A worn flange allows the track to de-rail; a worn shell diameter causes irregular chain contact and accelerated wear. Replace rollers when flange height is reduced by 50% or shell diameter is below the manufacturer service limit (consult your Komatsu service manual for exact dimensions by serial number).</p><h2>Putting It All Together</h2><p>The goal of undercarriage management is coordinated replacement: replace chain and sprockets together, replace all rollers and idlers together when they reach their wear limits, and you will get maximum component life with minimum waste. Contact us for a complete PC200-8 undercarriage package quote.</p>',
                'published_at' => Carbon::now()->subDays(21),
                'status'       => 'published',
                'views'        => 673,
            ],
            [
                'title'        => '5 Signs Your Excavator Hydraulic Pump Is Failing',
                'category'     => 'Troubleshooting',
                'tags'         => ['Hydraulics', 'Excavator', 'Downtime Reduction'],
                'excerpt'      => 'A failing hydraulic pump does not usually die without warning. Here are five symptoms to catch the problem before it leaves your machine dead on the job.',
                'image_url'    => 'https://assets.amsparts.com/assets/blog/hydraulic-pump-failure-signs.jpg',
                'content'      => '<h2>Don\'t Wait for a Complete Failure</h2><p>A hydraulic pump replacement costs anywhere from $2,000 to $8,000+ depending on the machine. Catching a failing pump early — before it destroys downstream components like control valves and cylinders with metal contamination — can mean the difference between a planned shop repair and an emergency rebuild.</p><h2>1. Slow Cycle Times</h2><p>If your boom, stick, or bucket are noticeably slower than they used to be, and the problem cannot be attributed to a control valve issue, internal pump wear is the most likely cause. As pistons and barrel wear, volumetric efficiency drops — the pump moves less fluid per revolution. Use a flow meter if possible: a new pump should deliver within 5% of rated flow; more than 15% loss typically justifies replacement.</p><h2>2. High System Temperature</h2><p>A worn pump generates more heat than it should because internal bypass — fluid leaking from high pressure back to the case — converts hydraulic energy into heat instead of work. If your machine is running hot without an obvious external cause (blocked cooler, low fluid level), suspect internal pump bypass.</p><h2>3. Whining or Moaning Noises</h2><p>A hydraulic pump should be relatively quiet at operating temperature. A high-pitched whine usually indicates cavitation — the pump is being starved for fluid, often due to a clogged suction strainer or a failing charge pump. A low moaning sound under load is more often internal wear. Either way, investigate immediately.</p><h2>4. Metal Particles in the Hydraulic Filter</h2><p>If your return filter is capturing small, bright metal particles (cut the filter element open and inspect it), you have internal wear generating debris. This is an urgent warning — those particles are circulating through your entire system and scoring every precision-machined surface they contact. Change the fluid, replace the filter, and get the pump rebuilt or replaced before the debris causes a cascade failure.</p><h2>5. Erratic or Jerky Machine Movements</h2><p>Intermittent loss of pressure due to a sticking or worn pump regulator or worn internal seals can cause unpredictable, jerky boom and stick movements. This is both a productivity problem and a safety issue — an excavator that does not respond predictably is a hazard on a job site.</p><p>If your machine is showing any of these symptoms, call us. We can help you diagnose whether you are looking at a pump, control valve, or another hydraulic issue, and we stock rebuilt pumps for most major excavator models ready to ship.</p>',
                'published_at' => Carbon::now()->subDays(35),
                'status'       => 'published',
                'views'        => 1876,
            ],
            [
                'title'        => 'John Deere 200G LC vs Komatsu PC200-8: Parts Availability Compared',
                'category'     => 'Parts Guides',
                'tags'         => ['John Deere', 'Komatsu', 'Excavator', 'OEM vs Aftermarket'],
                'excerpt'      => 'If you are choosing between these two popular mid-size excavators, parts availability and cost should be major factors in your decision.',
                'image_url'    => 'https://assets.amsparts.com/assets/blog/deere-vs-komatsu-comparison.jpg',
                'content'      => '<h2>Both Are Excellent Machines — But Parts Cost Differs</h2><p>The John Deere 200G LC and Komatsu PC200-8 are two of the most popular mid-size excavators in North America, both offering excellent performance and reliability. But when it comes to parts availability and cost of ownership, there are some notable differences worth understanding before you commit to a fleet purchase.</p><h2>Engine Parts</h2><p>Both machines use well-supported industrial engines with strong aftermarket ecosystems. The Komatsu SAA6D107E-1 engine in the PC200-8 has an extensive aftermarket — filter kits, gasket sets, and rebuild components are widely available and competitively priced. The John Deere PowerTech 4045 in the 200G LC is similarly well-supported, with the added advantage that it is shared with John Deere agricultural equipment, meaning parts availability even in rural areas tends to be excellent.</p><h2>Hydraulic Components</h2><p>This is where costs diverge more significantly. Komatsu hydraulic components — pumps, motors, and cylinders — have a strong rebuilt component market, and aftermarket seal kits are available from multiple suppliers at 30–50% below OEM pricing. John Deere hydraulic parts tend to have fewer aftermarket alternatives, meaning you are more often buying OEM or paying a premium for rebuilt units.</p><h2>Undercarriage</h2><p>Both machines have excellent aftermarket undercarriage availability. Multiple suppliers produce track chains, rollers, and idlers for both platforms at competitive prices. This is generally the largest recurring parts cost on any excavator, so strong aftermarket availability matters significantly here — and both machines score well.</p><h2>Our Verdict</h2><p>Both machines offer strong parts support. The PC200-8 has a slight edge in hydraulic component availability and cost. The 200G LC benefits from John Deere\'s extensive dealer network and agricultural-sector parts overlap. For most buyers, the difference in parts cost over a machine life will be modest. Make your decision on work requirements, dealer relationship, and machine performance — both are well-supported platforms.</p>',
                'published_at' => Carbon::now()->subDays(48),
                'status'       => 'published',
                'views'        => 542,
            ],
            [
                'title'        => 'Parts Plus Innovation Solutions Now Stocking Volvo EC220D and EC300D Rebuilt Hydraulics',
                'category'     => 'Company Updates',
                'tags'         => ['Volvo', 'Hydraulics', 'Rebuilt Parts'],
                'excerpt'      => 'We are pleased to announce expanded inventory of rebuilt hydraulic components for Volvo EC220D and EC300D excavators.',
                'image_url'    => 'https://assets.amsparts.com/assets/blog/volvo-hydraulics-announcement.jpg',
                'content'      => '<h2>Expanded Volvo Coverage</h2><p>Parts Plus Innovation Solutions is pleased to announce that we have significantly expanded our rebuilt hydraulic component inventory for the Volvo EC220D and EC300D excavator series. Following consistent customer demand, we have invested in new test equipment and tooling to properly service the Kawasaki hydraulic pumps and motors used in these machines.</p><h2>What We Now Stock</h2><p>Our expanded Volvo inventory includes: rebuilt main hydraulic pumps (dual tandem units), rebuilt swing motors, rebuilt travel motors with integral final drives, rebuilt boom, arm, and bucket cylinders, and complete seal kit sets for all cylinder sizes. All rebuilt units carry our standard 1-year unlimited-hour warranty.</p><h2>Fast Turnaround</h2><p>Most rebuilt Volvo hydraulic components ship within 24–48 hours from our Middletown, Ohio warehouse. For emergency breakdowns, we offer same-day shipping on in-stock units when ordered by 2:00 PM EST.</p><p>Call us at 1-800-255-6253 or submit a quote request to get pricing on Volvo hydraulic components for your fleet.</p>',
                'published_at' => Carbon::now()->subDays(60),
                'status'       => 'published',
                'views'        => 284,
            ],
            [
                'title'        => 'The Complete Guide to Caterpillar 3116 Engine Rebuilding',
                'category'     => 'Parts Guides',
                'tags'         => ['Caterpillar', 'Engine', 'Rebuilt Parts'],
                'excerpt'      => 'The CAT 3116 is one of the most popular engines in mid-range Caterpillar equipment. Here is everything you need to know about rebuilding one.',
                'image_url'    => 'https://assets.amsparts.com/assets/blog/cat-3116-engine-rebuild.jpg',
                'content'      => '<h2>About the CAT 3116 Engine</h2><p>The Caterpillar 3116 is a 6-cylinder, 6.6-liter turbocharged diesel engine that powered a wide range of mid-size Caterpillar equipment from the late 1980s through the early 2000s, including 315, 320, and 322 series excavators, 950F and 960F wheel loaders, and numerous on-highway trucks. It remains a common engine in the field due to the sheer number of units produced and the longevity of the equipment it powered.</p><h2>Common Failure Points</h2><p>The 3116 is generally a reliable engine, but several wear points are well-known. The cylinder sleeves tend to be the first major wear item — the 3116 uses wet sleeves that can develop cavitation erosion on the outside surface over high hours. The fuel injection pump is another common failure point after 10,000+ hours. Valve train components, particularly the rocker arms and camshaft lobes, also wear more quickly than in some competitive engines of the era.</p><h2>In-Frame vs. Complete Rebuild</h2><p>An in-frame rebuild replaces the rings, bearings, cylinder sleeves, and gaskets without removing the engine from the machine or splitting the block. This is appropriate when the crankshaft and camshaft are within tolerance and the block is not cracked. A complete out-of-frame rebuild is required when the crank needs grinding, the block needs boring, or there is structural damage to the block or main bearing saddles.</p><h2>Parts Required for a Standard In-Frame</h2><p>A typical 3116 in-frame rebuild requires: a complete overhaul gasket set, 6 cylinder sleeve and piston assemblies, connecting rod bearings, main bearings, a thrust washer set, a complete valve train kit, and timing chain and gear set. We stock all of these components individually and as complete rebuild kits — see our 3116 parts category for current pricing and availability.</p>',
                'published_at' => Carbon::now()->subDays(75),
                'status'       => 'published',
                'views'        => 965,
            ],
            // Draft post
            [
                'title'        => 'Understanding Hydraulic Cylinder Seal Failure Modes',
                'category'     => 'Troubleshooting',
                'tags'         => ['Hydraulics'],
                'excerpt'      => 'Why do cylinder seals fail? Understanding the root cause of seal failure helps you choose the right replacement and prevent recurrence.',
                'content'      => '<p>Draft content — coming soon.</p>',
                'published_at' => null,
                'status'       => 'draft',
                'views'        => 0,
            ],
        ];

        foreach ($posts as $postData) {
            $category = $catMap[$postData['category']] ?? null;
            $tagIds   = collect($postData['tags'])->map(fn($t) => $tagMap[$t]->id ?? null)->filter();

            $imageUrl = $postData['image_url'] ?? null;
            unset($postData['category'], $postData['tags'], $postData['image_url']);

            $content  = $postData['content'];
            $words    = str_word_count(strip_tags($content));
            $readTime = (int) ceil($words / 200);

            $mediaId = null;
            if ($imageUrl) {
                $mediaId = $this->downloadImage(
                    $imageUrl,
                    'blog',
                    $postData['title']
                );
            }

            $post = BlogPost::updateOrCreate(
                ['slug' => Str::slug($postData['title'])],
                array_merge($postData, [
                    'slug'              => Str::slug($postData['title']),
                    'admin_id'          => $admin?->id,
                    'blog_category_id'  => $category?->id,
                    'featured_image_id' => $mediaId,
                    'read_time_minutes' => max(1, $readTime),
                ])
            );

            if ($tagIds->isNotEmpty()) {
                $post->tags()->syncWithoutDetaching($tagIds->all());
            }
        }
    }
}
