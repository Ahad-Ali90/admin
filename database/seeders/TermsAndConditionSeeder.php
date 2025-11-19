<?php

namespace Database\Seeders;

use App\Models\TermsAndCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermsAndConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing terms
        TermsAndCondition::truncate();

        // Customer Terms and Conditions - All in one entry
        TermsAndCondition::create([
            'title' => 'Customer Terms and Conditions',
            'content' => '
<h4>Booking and Payment</h4>
<ul>
    <li>All bookings must be confirmed with a valid payment method</li>
    <li>A deposit may be required for certain services</li>
    <li>Full payment is due on the day of service completion unless otherwise agreed in writing</li>
    <li>We accept cash, card, and bank transfer payments</li>
</ul>

<h4>Cancellation Policy</h4>
<ul>
    <li>Cancellations made 48 hours or more before the scheduled service date will receive a <strong>full refund</strong></li>
    <li>Cancellations made within 24-48 hours will incur a <strong>50% cancellation fee</strong></li>
    <li>Cancellations made less than 24 hours before the service date will <strong>forfeit the full deposit</strong></li>
    <li>No-shows will be charged the full booking amount</li>
</ul>

<h4>Service Delivery</h4>
<ul>
    <li>We will make every effort to arrive within the scheduled time window</li>
    <li>Delays may occur due to traffic, weather, or previous job overruns</li>
    <li>Customers will be notified of any significant delays</li>
    <li>Service times are estimates and may vary depending on the actual scope of work</li>
</ul>

<h4>Customer Responsibilities</h4>
<ul>
    <li>Customers must ensure that all items are properly packed and ready for collection at the scheduled time</li>
    <li>Access to the property must be clear and safe</li>
    <li>Customers must inform us of any special requirements, fragile items, or access restrictions in advance</li>
    <li>Any additional charges due to incorrect information will be the customer\'s responsibility</li>
</ul>

<h4>Liability and Insurance</h4>
<ul>
    <li>While we take utmost care in handling your belongings, we cannot accept liability for damage to items that are not properly packed or are inherently fragile</li>
    <li>We recommend customers obtain appropriate insurance for valuable items</li>
    <li>Our liability is limited to the declared value of goods as stated at the time of booking</li>
</ul>

<h4>Complaints and Disputes</h4>
<ul>
    <li>Any complaints must be made in writing within <strong>7 days</strong> of the service completion</li>
    <li>We will investigate all complaints thoroughly and respond within <strong>14 working days</strong></li>
    <li>In case of disputes, we will attempt to resolve the matter amicably before pursuing any legal action</li>
</ul>
',
            'type' => 'customer',
            'is_active' => true,
            'display_order' => 1,
        ]);

        // Company Terms and Conditions - All in one entry
        TermsAndCondition::create([
            'title' => 'Company Terms and Conditions',
            'content' => '
<h4>Company Account Terms</h4>
<ul>
    <li>Company accounts must be pre-approved and set up with our accounts department</li>
    <li>All company bookings are subject to credit terms and payment schedules as agreed in the signed contract</li>
    <li>Invoices will be issued upon service completion</li>
    <li>Payment is due within <strong>30 days</strong> unless otherwise specified</li>
</ul>

<h4>Booking Authorization</h4>
<ul>
    <li>Company bookings must be made by <strong>authorized personnel only</strong></li>
    <li>Unauthorized bookings will not be accepted</li>
    <li>Changes to authorized personnel must be communicated to us in writing</li>
    <li>We reserve the right to verify authorization before confirming any booking</li>
</ul>

<h4>Volume Discounts and Commission</h4>
<ul>
    <li>Companies meeting minimum monthly booking volumes may be eligible for volume discounts as per the agreed contract</li>
    <li>Commission rates for corporate partners will be applied as specified in the partnership agreement</li>
    <li>Regular reviews of pricing and commission structures will be conducted quarterly</li>
</ul>

<h4>Service Level Agreement</h4>
<ul>
    <li>We commit to providing <strong>priority booking slots</strong> for company accounts</li>
    <li>Service quality standards as outlined in the corporate agreement will be maintained at all times</li>
    <li>Any service failures will be addressed within <strong>24 hours</strong></li>
    <li>Compensation will be provided according to the SLA terms</li>
</ul>

<h4>Invoicing and Payment Terms</h4>
<ul>
    <li>Invoices will be sent electronically to the designated billing contact within <strong>48 hours</strong> of service completion</li>
    <li>Payment must be made within <strong>30 days</strong> of invoice date</li>
    <li>Late payments will incur interest charges as specified in the contract</li>
    <li>Credit limits may be reduced or suspended for accounts with overdue payments</li>
</ul>

<h4>Contract Termination</h4>
<ul>
    <li>Either party may terminate the company account agreement with <strong>30 days written notice</strong></li>
    <li>All outstanding invoices must be settled before termination becomes effective</li>
    <li>Any ongoing bookings at the time of termination will be honored at the agreed rates</li>
</ul>
',
            'type' => 'company',
            'is_active' => true,
            'display_order' => 1,
        ]);
    }
}
