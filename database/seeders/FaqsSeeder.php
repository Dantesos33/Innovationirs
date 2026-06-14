<?php
namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqsSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [

            // ── Ordering ───────────────────────────────────────────────────
            [
                'question'   => 'How do I place a parts order?',
                'answer'     => 'You can request a quote through our website by filling out the Quote Request form with your part number, make, model, and serial number. You can also call us directly at 1-800-255-6253 or email parts@example.com. Our team will confirm availability, pricing, and shipping before processing your order.',
                'category'   => 'ordering',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'question'   => 'Do you have a minimum order requirement?',
                'answer'     => 'No, there is no minimum order. We supply individual parts and filters just as readily as large fleet orders.',
                'category'   => 'ordering',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'question'   => 'Can I order online without calling?',
                'answer'     => 'We recommend calling or submitting a quote request for major parts to ensure correct fitment verification. Many parts have multiple supersessions and serial-number-specific variations that can only be confirmed by our team. For common maintenance items, you can reference the part number directly.',
                'category'   => 'ordering',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'question'   => 'Do you accept purchase orders from companies?',
                'answer'     => 'Yes. We work with many fleet operators and construction companies on net-30 terms. Contact our accounts department to set up a business account.',
                'category'   => 'ordering',
                'sort_order' => 4,
                'is_active'  => true,
            ],

            // ── Shipping ──────────────────────────────────────────────────
            [
                'question'   => 'How fast do you ship?',
                'answer'     => 'Most in-stock parts ship the same day when ordered by 2:00 PM EST. Rebuilt components typically ship within 24–48 hours. We ship via UPS, FedEx, and freight carriers depending on size and weight. Tracking information is emailed when your order ships.',
                'category'   => 'shipping',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'question'   => 'Do you ship internationally?',
                'answer'     => 'Yes, we ship to most countries worldwide. International shipments go via FedEx International, DHL, or freight forwarding depending on the item. The customer is responsible for all import duties, taxes, and customs fees. Contact us for an international shipping quote.',
                'category'   => 'shipping',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'question'   => 'What are your shipping costs?',
                'answer'     => 'Shipping is free on orders over $500 within the continental United States. For orders under $500, shipping is calculated based on weight and destination. We use actual carrier rates with no markup. Large items such as engines, transmissions, and undercarriage components ship via freight — we will quote freight cost at time of order.',
                'category'   => 'shipping',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'question'   => 'Can I pick up my order at your warehouse?',
                'answer'     => 'Yes. Our warehouse is located at 2710 S. Main Street, Middletown, OH 45044. Customer pick-up is available Monday through Friday, 8:00 AM to 5:00 PM EST. Please call ahead to confirm your part is ready.',
                'category'   => 'shipping',
                'sort_order' => 4,
                'is_active'  => true,
            ],

            // ── Parts & Compatibility ─────────────────────────────────────
            [
                'question'   => 'How do I know if a part will fit my machine?',
                'answer'     => 'The most reliable way to confirm fitment is to provide your machine\'s serial number along with the part number or description. Machine serial numbers encode the exact model specification including engine configuration, hydraulic system variant, and production dates — all of which affect part compatibility. Our team verifies fitment on every order.',
                'category'   => 'parts',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'question'   => 'What is the difference between OEM, aftermarket, and rebuilt parts?',
                'answer'     => 'OEM (Original Equipment Manufacturer) parts are made by or to the exact specification of the equipment manufacturer. Aftermarket parts are made by independent manufacturers to equivalent specifications, typically at lower cost. Rebuilt (remanufactured) parts are used cores that have been completely disassembled, cleaned, fitted with new wear components, and tested to OEM performance specifications. We carry all three types and will recommend the best option for your application.',
                'category'   => 'parts',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'question'   => 'Do your rebuilt parts come with a core charge?',
                'answer'     => 'Some rebuilt assemblies — particularly hydraulic pumps, motors, and torque converters — require a core exchange. Your serviceable old unit (the "core") is returned to us to be rebuilt in turn. Core charges are clearly stated at time of purchase. When we receive your core in serviceable condition, the core charge is fully refunded. We will provide a prepaid shipping label for core return on most items.',
                'category'   => 'parts',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'question'   => 'Can you help me identify a part if I don\'t know the part number?',
                'answer'     => 'Absolutely. Send us a photo of the part, your machine make/model/serial number, and a description of where the part is located. Our team has decades of experience identifying parts from photos and descriptions. Email parts@example.com or submit a quote request.',
                'category'   => 'parts',
                'sort_order' => 4,
                'is_active'  => true,
            ],

            // ── Warranty & Returns ────────────────────────────────────────
            [
                'question'   => 'What warranty do your parts carry?',
                'answer'     => 'New and rebuilt parts carry a 1-year unlimited-hour warranty against defects in material and workmanship. Used parts carry a 90-day warranty. Our warranty covers replacement of the defective part; we do not cover labor costs for removal and installation. See our full warranty policy on the Warranty page.',
                'category'   => 'warranty',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'question'   => 'What is your return policy?',
                'answer'     => 'Unused parts in original condition may be returned within 30 days for a full refund minus a restocking fee. Electrical components, special-order items, and cut hoses are non-returnable. Warranty claims for defective parts are handled separately — contact us and we will arrange for return and replacement.',
                'category'   => 'warranty',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'question'   => 'How do I file a warranty claim?',
                'answer'     => 'Contact us by phone or email with your order number, a description of the problem, and photos if applicable. We will review the claim and, if approved, ship a replacement part promptly. In most cases, we ship the replacement before requiring return of the defective unit, minimizing your downtime.',
                'category'   => 'warranty',
                'sort_order' => 3,
                'is_active'  => true,
            ],

            // ── Payment ───────────────────────────────────────────────────
            [
                'question'   => 'What payment methods do you accept?',
                'answer'     => 'We accept Visa, Mastercard, American Express, and Discover credit and debit cards, ACH/bank transfer, and business checks. We also offer net-30 terms for established business accounts. Wire transfer is available for international orders.',
                'category'   => 'payment',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'question'   => 'Is my payment information secure?',
                'answer'     => 'Yes. Our website uses SSL encryption and we do not store credit card numbers on our servers. Card transactions are processed through a PCI-compliant payment gateway.',
                'category'   => 'payment',
                'sort_order' => 2,
                'is_active'  => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
