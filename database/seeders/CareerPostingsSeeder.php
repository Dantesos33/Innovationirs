<?php

namespace Database\Seeders;

use App\Models\CareerPosting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CareerPostingsSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Hydraulic Systems Technician',
                'department' => 'Technical Services',
                'location' => 'Middletown, OH',
                'job_type' => 'full_time',
                'description' => '<p>We are looking for an experienced Hydraulic Systems Technician to join our remanufacturing team. In this role, you will be responsible for disassembling, inspecting, remanufacturing, and testing hydraulic pumps, motors, cylinders, and control valves for heavy equipment applications.</p><p>You will work in a well-equipped shop environment with dedicated hydraulic test stands, calibration equipment, and precision measurement tools. This is a hands-on role with real impact — the components you rebuild keep construction equipment running across North America.</p>',
                'requirements' => '<ul><li>3+ years of experience working with hydraulic systems on heavy equipment (excavators, loaders, or similar)</li><li>Ability to read and interpret hydraulic schematics and service manuals</li><li>Experience with precision measurement tools (micrometers, dial indicators, bore gauges)</li><li>Familiarity with hydraulic test stand operation is a strong plus</li><li>High school diploma or vocational training certificate required; associate degree or heavy equipment technician certification preferred</li><li>Ability to lift 75 lbs. regularly</li></ul>',
                'benefits' => '<ul><li>Competitive hourly pay ($28–$38/hr depending on experience)</li><li>Health, dental, and vision insurance (company pays 80% of premiums)</li><li>401(k) with 4% company match</li><li>Paid time off starting at 10 days/year plus 8 paid holidays</li><li>Tool allowance program</li><li>Ongoing technical training</li></ul>',
                'salary_range' => '$28–$38/hr',
                'apply_email' => 'jobs@example.com',
                'is_active' => true,
                'posted_at' => Carbon::now()->subDays(7),
                'expires_at' => Carbon::now()->addDays(53),
            ],
            [
                'title' => 'Parts Sales Representative',
                'department' => 'Sales',
                'location' => 'Middletown, OH',
                'job_type' => 'full_time',
                'description' => '<p>Parts Plus Innovation Solutions is looking for a motivated Parts Sales Representative to join our inside sales team. You will be the first point of contact for customers seeking heavy equipment replacement parts — taking inbound calls, qualifying needs, confirming part compatibility, and closing sales.</p><p>Our best sales reps are trusted advisors to their customers. You do not need to know every part number by heart — we have systems and colleagues for that — but you do need genuine curiosity, a willingness to learn the technical side of our business, and a commitment to customer service excellence.</p>',
                'requirements' => '<ul><li>2+ years of experience in parts sales, equipment dealership, or a technically-oriented sales environment</li><li>Comfortable taking high volumes of inbound calls in a fast-paced environment</li><li>Strong verbal and written communication skills</li><li>Basic mechanical aptitude and willingness to learn heavy equipment part identification</li><li>Experience with CRM or order management software preferred</li><li>High school diploma required; some college preferred</li></ul>',
                'benefits' => '<ul><li>Base salary plus commission — total compensation $52,000–$75,000 based on performance</li><li>Full health, dental, and vision benefits</li><li>401(k) with 4% company match</li><li>Paid time off and paid holidays</li><li>Training provided — we will teach you the parts</li></ul>',
                'salary_range' => '$52,000–$75,000 OTE',
                'apply_email' => 'jobs@example.com',
                'is_active' => true,
                'posted_at' => Carbon::now()->subDays(14),
                'expires_at' => Carbon::now()->addDays(46),
            ],
            [
                'title' => 'Warehouse & Shipping Associate',
                'department' => 'Operations',
                'location' => 'Middletown, OH',
                'job_type' => 'full_time',
                'description' => '<p>Join our warehouse team and help keep heavy equipment running across the country. As a Warehouse & Shipping Associate, you will be responsible for accurately picking, packing, and shipping customer orders, receiving and inspecting incoming parts inventory, and maintaining an organized, safe warehouse environment.</p><p>This is a physically active, fast-paced role in a team-oriented environment. You will work with a variety of parts ranging from small filter kits to large hydraulic assemblies weighing several hundred pounds (forklift available).</p>',
                'requirements' => '<ul><li>Warehouse or shipping/receiving experience preferred but not required — we will train the right candidate</li><li>Forklift certification or willingness to become certified</li><li>Ability to lift 75 lbs. regularly and work on your feet for extended periods</li><li>Strong attention to detail and accuracy</li><li>Ability to use basic computer systems for order management</li><li>High school diploma or GED required</li></ul>',
                'benefits' => '<ul><li>Starting pay $18–$22/hr depending on experience</li><li>Health, dental, and vision insurance</li><li>401(k) with company match</li><li>Paid time off and holidays</li><li>Steel-toed footwear allowance</li></ul>',
                'salary_range' => '$18–$22/hr',
                'apply_email' => 'jobs@example.com',
                'is_active' => true,
                'posted_at' => Carbon::now()->subDays(3),
                'expires_at' => Carbon::now()->addDays(57),
            ],
            [
                'title' => 'Senior Parts Cataloger / Technical Writer',
                'department' => 'Technical Services',
                'location' => 'Middletown, OH (Hybrid)',
                'job_type' => 'full_time',
                'description' => '<p>We are expanding our online parts catalogue and need a detail-oriented Parts Cataloger and Technical Writer to help us accurately document, describe, and cross-reference our growing inventory. You will work closely with our technical and sales teams to create accurate part descriptions, fitment guides, and maintenance content for our website.</p>',
                'requirements' => '<ul><li>3+ years of experience in heavy equipment parts identification, dealer parts department, or equivalent</li><li>Ability to accurately read and interpret parts manuals, service manuals, and microfiche catalogs for Caterpillar, Komatsu, John Deere, and similar brands</li><li>Strong written communication skills — you will write product descriptions that are both technically accurate and useful to customers</li><li>Comfortable working with spreadsheets and database systems</li><li>Experience with e-commerce platforms or content management systems is a plus</li></ul>',
                'benefits' => '<ul><li>Salary $55,000–$70,000 depending on experience</li><li>Hybrid work arrangement (3 days in-office, 2 remote after training period)</li><li>Full benefits package including health, dental, vision, 401(k)</li><li>Paid time off and holidays</li></ul>',
                'salary_range' => '$55,000–$70,000',
                'apply_email' => 'jobs@example.com',
                'is_active' => true,
                'posted_at' => Carbon::now()->subDays(21),
                'expires_at' => Carbon::now()->addDays(39),
            ],
            [
                'title' => 'Accounts Receivable Specialist',
                'department' => 'Finance',
                'location' => 'Middletown, OH',
                'job_type' => 'full_time',
                'description' => '<p>We are seeking a detail-oriented Accounts Receivable Specialist to manage incoming commercial billing, customer invoice discrepancies, and commercial account credit review processes.</p>',
                'requirements' => '<ul><li>2+ years experience in AR or corporate billing</li><li>Proficiency with corporate accounting platforms</li></ul>',
                'benefits' => '<ul><li>Competitive salary with health benefits</li><li>Paid holidays and matching 401(k)</li></ul>',
                'salary_range' => '$45,000–$52,000',
                'apply_email' => 'jobs@example.com',
                'is_active' => false, // Expired job marker
                'posted_at' => Carbon::now()->subDays(60),
                'expires_at' => Carbon::now()->subDays(30),
            ],
        ];

        foreach ($jobs as $job) {
            $slug = Str::slug($job['title']);

            CareerPosting::updateOrCreate(
                ['slug' => $slug],
                array_merge($job, ['slug' => $slug])
            );
        }
    }
}
