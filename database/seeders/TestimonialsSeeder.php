<?php
namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialsSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'reviewer_name'  => 'Randy Kowalski',
                'reviewer_title' => 'Fleet Manager',
                'company'        => 'Kowalski Excavating LLC',
                'location'       => 'Columbus, OH',
                'content'        => 'We have been buying rebuilt hydraulic pumps from Parts Plus Innovation Solutionsfor three years now. Every pump has outlasted our expectations and the 1-year warranty gives us real peace of mind. Their turnaround time is incredible — ordered at 10 AM, shipped same day.',
                'rating'         => 5,
                'is_active'      => true,
                'is_featured'    => true,
                'sort_order'     => 1,
                'source'         => 'google',
            ],
            [
                'reviewer_name'  => 'Marcus Williams',
                'reviewer_title' => 'Equipment Superintendent',
                'company'        => 'Williams Heavy Construction',
                'location'       => 'Nashville, TN',
                'content'        => 'Had a CAT 320D main pump go out on a critical job. Called Parts Plus Innovation Solutionsat 7:30 AM and they had a rebuilt unit on a truck by noon. Machine was back running the next morning. That kind of service is hard to find.',
                'rating'         => 5,
                'is_active'      => true,
                'is_featured'    => true,
                'sort_order'     => 2,
                'source'         => 'google',
            ],
            [
                'reviewer_name'  => 'Tanya Breckenridge',
                'reviewer_title' => 'Owner',
                'company'        => 'Breckenridge Land Clearing',
                'location'       => 'Birmingham, AL',
                'content'        => 'I was skeptical about rebuilt parts at first, but the cylinder heads I bought from AMS for our 3116 engines have been running strong for two seasons. Saved us over $4,000 versus OEM replacement. Will not go anywhere else.',
                'rating'         => 5,
                'is_active'      => true,
                'is_featured'    => true,
                'sort_order'     => 3,
                'source'         => 'direct',
            ],
            [
                'reviewer_name'  => 'Doug Pfeiffer',
                'reviewer_title' => 'Shop Foreman',
                'company'        => 'Midwest Grading Inc.',
                'location'       => 'Indianapolis, IN',
                'content'        => 'The parts knowledge these guys have is on another level. I described a weird symptom on one of our Komatsu WA470s and they diagnosed the problem over the phone, had the right part in stock, and it fixed the machine. My team calls them before anyone else.',
                'rating'         => 5,
                'is_active'      => true,
                'is_featured'    => true,
                'sort_order'     => 4,
                'source'         => 'google',
            ],
            [
                'reviewer_name'  => 'Steve Nakamura',
                'reviewer_title' => 'Operations Manager',
                'company'        => 'Pacific Coast Demolition',
                'location'       => 'Portland, OR',
                'content'        => 'We ship parts requests to AMS from the West Coast all the time. Their communication is excellent — we always know exactly when our order ships and they flag back-order situations immediately. Zero surprise delays.',
                'rating'         => 5,
                'is_active'      => true,
                'is_featured'    => false,
                'sort_order'     => 5,
                'source'         => 'direct',
            ],
            [
                'reviewer_name'  => 'Carl Hutchinson',
                'reviewer_title' => 'Owner/Operator',
                'company'        => 'Hutchinson Trenching',
                'location'       => 'Lexington, KY',
                'content'        => 'Ordered a Bobcat drive motor on a Tuesday, had it installed and the machine running on Wednesday afternoon. Good quality rebuild, fair price. Exactly what a small operator needs.',
                'rating'         => 4,
                'is_active'      => true,
                'is_featured'    => false,
                'sort_order'     => 6,
                'source'         => 'google',
            ],
            [
                'reviewer_name'  => 'Jennifer Forsythe',
                'reviewer_title' => 'Procurement Manager',
                'company'        => 'Forsythe Infrastructure Group',
                'location'       => 'Charlotte, NC',
                'content'        => 'We manage a fleet of 40+ pieces of equipment and Parts Plus Innovation Solutionshandles the bulk of our repair parts needs. Their pricing is consistently 20–40% below OEM dealer pricing for the same quality. Highly recommend for fleet operations.',
                'rating'         => 5,
                'is_active'      => true,
                'is_featured'    => true,
                'sort_order'     => 7,
                'source'         => 'direct',
            ],
            [
                'reviewer_name'  => 'Phil Eckert',
                'reviewer_title' => 'Maintenance Supervisor',
                'company'        => 'Eckert Paving & Grading',
                'location'       => 'Dayton, OH',
                'content'        => 'Being local to Parts Plus Innovation Solutionshas been great — we can pick up parts directly when we need them immediately. Their warehouse team is always helpful and they maintain excellent stock levels on common Cat and Komatsu parts.',
                'rating'         => 5,
                'is_active'      => true,
                'is_featured'    => false,
                'sort_order'     => 8,
                'source'         => 'direct',
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                [
                    'reviewer_name' => $testimonial['reviewer_name'],
                    'company'       => $testimonial['company'],
                ],
                $testimonial
            );
        }
    }
}
