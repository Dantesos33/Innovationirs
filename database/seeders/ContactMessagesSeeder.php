<?php
namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\ContactReply;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContactMessagesSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        $messages = [
            [
                'first_name' => 'Christine',
                'last_name'  => 'Yoder',
                'email'      => 'cyoder@yoderenterprise.com',
                'phone'      => '(740) 555-0201',
                'company'    => 'Yoder Enterprise',
                'subject'    => 'Fleet account setup inquiry',
                'message'    => 'Hi, we operate a fleet of about 25 machines — mostly Caterpillar and Komatsu — and we spend a significant amount each year on parts. I would like to discuss setting up a fleet account with net-30 terms. Who should I speak with?',
                'status'     => 'closed',
                'ip_address' => '10.0.2.1',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'first_name' => 'Paul',
                'last_name'  => 'Nettleton',
                'email'      => 'pnettleton@gmail.com',
                'phone'      => null,
                'company'    => null,
                'subject'    => 'Question about rebuilt part warranty',
                'message'    => 'Does your 1-year warranty on rebuilt parts cover labor costs if the part fails and needs to be re-installed? Just want to understand what is covered before I place an order.',
                'status'     => 'closed',
                'ip_address' => '192.168.0.45',
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'first_name' => 'Kayla',
                'last_name'  => 'Simmons',
                'email'      => 'ksimmons@simmonspaving.net',
                'phone'      => '(513) 555-0388',
                'company'    => 'Simmons Paving',
                'subject'    => 'International shipping to Canada',
                'message'    => 'We are a Canadian company. Do you ship to Ontario? We need rebuilt hydraulic cylinders for several Volvo excavators.',
                'status'     => 'in_progress',
                'ip_address' => '192.168.3.20',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'first_name' => 'George',
                'last_name'  => 'Whitfield',
                'email'      => 'gwhitfield@hotmail.com',
                'phone'      => '(606) 555-0412',
                'company'    => null,
                'subject'    => 'Seeking advice on 3116 engine rebuild',
                'message'    => 'I have a 1997 CAT 320L with a 3116 engine that has low compression on two cylinders. Before I commit to a full rebuild I just want to ask your team\'s opinion on whether this is worth rebuilding or if I should look for a replacement engine.',
                'status'     => 'new',
                'ip_address' => '172.16.5.5',
                'created_at' => Carbon::now()->subHours(5),
            ],
            [
                'first_name' => 'Rachel',
                'last_name'  => 'Obermeyer',
                'email'      => 'rachel@obermeyerexcavating.com',
                'phone'      => '(937) 555-0599',
                'company'    => 'Obermeyer Excavating',
                'subject'    => 'Emergency breakdown — Komatsu PC200',
                'message'    => 'We have a PC200-8 that dropped its hydraulic oil — looks like a burst hose caused the main pump to cavitate. The pump is making a terrible noise now. We are in the middle of a project with a hard deadline. Can someone call me ASAP?',
                'status'     => 'new',
                'ip_address' => '10.50.0.1',
                'created_at' => Carbon::now()->subHours(1),
            ],
        ];

        foreach ($messages as $msgData) {
            $msg = ContactMessage::create($msgData);

            // Add reply to closed contacts
            if ($msgData['status'] === 'closed' && $admin) {
                ContactReply::create([
                    'contact_id'    => $msg->id,
                    'admin_id'      => $admin->id,
                    'message'       => 'Thank you for reaching out to Parts Plus Innovation Solutions. We have reviewed your inquiry and are happy to help. Please see the details below or call us directly at 1-800-255-6253 if you have additional questions.',
                    'is_admin'      => true,
                    'email_sent'    => true,
                    'email_sent_at' => Carbon::parse($msgData['created_at'])->addHours(2),
                    'created_at'    => Carbon::parse($msgData['created_at'])->addHours(2),
                    'updated_at'    => Carbon::parse($msgData['created_at'])->addHours(2),
                ]);
            }
        }
    }
}
