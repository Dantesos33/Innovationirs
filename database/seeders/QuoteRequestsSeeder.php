<?php
namespace Database\Seeders;

use App\Models\Admin;
use App\Models\QuoteReply;
use App\Models\QuoteRequest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class QuoteRequestsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        $quotes = [
            [
                'first_name'       => 'James',
                'last_name'        => 'Harrington',
                'email'            => 'jharrington@harringtonexcavating.com',
                'phone'            => '(614) 555-0142',
                'company'          => 'Harrington Excavating',
                'make'             => 'Caterpillar',
                'model'            => '320D',
                'serial_number'    => 'CAT0320DCAG00842',
                'part_number'      => '259-0814',
                'part_description' => 'Looking for a rebuilt main hydraulic pump for our 320D. Machine has been slow on the boom cycle.',
                'quantity'         => 1,
                'notes'            => 'Need it ASAP — machine is on a critical project.',
                'status'           => 'in_progress',
                'ip_address'       => '192.168.1.100',
                'created_at'       => Carbon::now()->subDays(2),
            ],
            [
                'first_name'       => 'Lisa',
                'last_name'        => 'Drummond',
                'email'            => 'lisa@drummondgrading.com',
                'phone'            => '(317) 555-0298',
                'company'          => 'Drummond Grading Services',
                'make'             => 'Komatsu',
                'model'            => 'PC200-8',
                'serial_number'    => 'PC200-8K-80124',
                'part_number'      => null,
                'part_description' => 'Need a full undercarriage kit — both sides. Track chain, rollers, idlers, sprockets.',
                'quantity'         => 1,
                'notes'            => null,
                'status'           => 'quoted',
                'ip_address'       => '10.0.0.55',
                'created_at'       => Carbon::now()->subDays(5),
            ],
            [
                'first_name'       => 'Robert',
                'last_name'        => 'Castellano',
                'email'            => 'rcastellano@castellanodemo.com',
                'phone'            => '(502) 555-0311',
                'company'          => 'Castellano Demolition',
                'make'             => 'Volvo',
                'model'            => 'EC300D',
                'serial_number'    => 'VCE0300DKDC12345',
                'part_number'      => 'VOE14620773',
                'part_description' => 'Swing motor assembly for EC300D. Machine spins slowly and jerks intermittently.',
                'quantity'         => 1,
                'notes'            => 'Will exchange core.',
                'status'           => 'new',
                'ip_address'       => '172.16.0.22',
                'created_at'       => Carbon::now()->subHours(3),
            ],
            [
                'first_name'       => 'Angela',
                'last_name'        => 'Morrow',
                'email'            => 'angela.morrow@morrowconstruction.net',
                'phone'            => '(937) 555-0477',
                'company'          => 'Morrow Construction LLC',
                'make'             => 'Caterpillar',
                'model'            => 'D6T',
                'serial_number'    => null,
                'part_number'      => '374-9929',
                'part_description' => 'Need a front idler — rebuilt is fine. Serial number not handy but it\'s a 2012 D6T.',
                'quantity'         => 1,
                'notes'            => null,
                'status'           => 'closed_won',
                'ip_address'       => '192.168.50.10',
                'created_at'       => Carbon::now()->subDays(18),
            ],
            [
                'first_name'       => 'Tony',
                'last_name'        => 'Bergstrom',
                'email'            => 'tbergstrom@bergstromsite.com',
                'phone'            => '(513) 555-0555',
                'company'          => 'Bergstrom Site Work',
                'make'             => 'John Deere',
                'model'            => '200G LC',
                'serial_number'    => '1FF200GXLEK123456',
                'part_number'      => 'RE500734',
                'part_description' => 'Water pump is leaking. Need a new one for the PowerTech 4045.',
                'quantity'         => 1,
                'notes'            => null,
                'status'           => 'closed_won',
                'ip_address'       => '10.10.10.1',
                'created_at'       => Carbon::now()->subDays(30),
            ],
            [
                'first_name'       => 'Derek',
                'last_name'        => 'Palumbo',
                'email'            => 'derek@palumboland.com',
                'phone'            => '(859) 555-0633',
                'company'          => 'Palumbo Land Services',
                'make'             => 'Bobcat',
                'model'            => 'S650',
                'serial_number'    => 'ABCD11000',
                'part_number'      => null,
                'part_description' => 'Left side drive motor on our S650 is making a grinding noise. Might need rebuild or replacement.',
                'quantity'         => 1,
                'notes'            => null,
                'status'           => 'new',
                'ip_address'       => '192.168.1.200',
                'created_at'       => Carbon::now()->subHours(10),
            ],
            [
                'first_name'       => 'Sandra',
                'last_name'        => 'Vo',
                'email'            => 'svo@vo-groundworks.com',
                'phone'            => '(513) 555-0710',
                'company'          => 'Vo Groundworks LLC',
                'make'             => 'Caterpillar',
                'model'            => '950H',
                'serial_number'    => 'CAT0950HAXK00344',
                'part_number'      => '291-8970',
                'part_description' => 'Torque converter slipping under load. Looking for rebuilt unit with core exchange.',
                'quantity'         => 1,
                'notes'            => 'Core is in good condition.',
                'status'           => 'open',
                'ip_address'       => '10.0.1.88',
                'created_at'       => Carbon::now()->subDays(4),
            ],
            [
                'first_name'       => 'Mike',
                'last_name'        => 'Lawson',
                'email'            => 'mlawson@lawsonheavy.com',
                'phone'            => null,
                'company'          => 'Lawson Heavy Equipment Repair',
                'make'             => 'Komatsu',
                'model'            => 'PC360LC-10',
                'serial_number'    => 'A10001',
                'part_number'      => '707-01-XD751',
                'part_description' => 'Boom cylinder rod — chrome is flaking. Need a new rod to rebuild the cylinder in-house.',
                'quantity'         => 1,
                'notes'            => 'Need to know lead time.',
                'status'           => 'open',
                'ip_address'       => '172.20.0.5',
                'created_at'       => Carbon::now()->subDays(6),
            ],
        ];

        foreach ($quotes as $quoteData) {
            $quote = QuoteRequest::create($quoteData);

            // Add a reply to closed/in-progress quotes
            if (in_array($quoteData['status'], ['in_progress', 'quoted', 'closed_won']) && $admin) {
                QuoteReply::create([
                    'quote_id'      => $quote->id,
                    'admin_id'      => $admin->id,
                    'message'       => 'Thank you for contacting AMS Parts. We have your request in hand and will have pricing and availability to you within the next 2 business hours. Please let us know if you have any questions in the meantime.',
                    'is_admin'      => true,
                    'email_sent'    => true,
                    'email_sent_at' => Carbon::parse($quoteData['created_at'])->addHours(1),
                    'created_at'    => Carbon::parse($quoteData['created_at'])->addHours(1),
                    'updated_at'    => Carbon::parse($quoteData['created_at'])->addHours(1),
                ]);
            }
        }
    }
}
