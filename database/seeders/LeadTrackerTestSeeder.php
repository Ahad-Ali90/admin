<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class LeadTrackerTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user (admin) for created_by
        $admin = User::first();
        
        if (!$admin) {
            $this->command->error('No user found! Please create a user first.');
            return;
        }

        $this->command->info('Creating test customers...');
        
        // Create 15 test customers
        $customers = [
            ['name' => 'Ahmed Khan', 'email' => 'ahmed@test.com', 'phone' => '03001234567'],
            ['name' => 'Sara Ali', 'email' => 'sara@test.com', 'phone' => '03001234568'],
            ['name' => 'Hassan Raza', 'email' => 'hassan@test.com', 'phone' => '03001234569'],
            ['name' => 'Fatima Shah', 'email' => 'fatima@test.com', 'phone' => '03001234570'],
            ['name' => 'Bilal Ahmed', 'email' => 'bilal@test.com', 'phone' => '03001234571'],
            ['name' => 'Aisha Malik', 'email' => 'aisha@test.com', 'phone' => '03001234572'],
            ['name' => 'Usman Ali', 'email' => 'usman@test.com', 'phone' => '03001234573'],
            ['name' => 'Zainab Khan', 'email' => 'zainab@test.com', 'phone' => '03001234574'],
            ['name' => 'Ali Raza', 'email' => 'ali@test.com', 'phone' => '03001234575'],
            ['name' => 'Maria Ahmed', 'email' => 'maria@test.com', 'phone' => '03001234576'],
            ['name' => 'Hamza Sheikh', 'email' => 'hamza@test.com', 'phone' => '03001234577'],
            ['name' => 'Ayesha Butt', 'email' => 'ayesha@test.com', 'phone' => '03001234578'],
            ['name' => 'Imran Malik', 'email' => 'imran@test.com', 'phone' => '03001234579'],
            ['name' => 'Nida Khan', 'email' => 'nida@test.com', 'phone' => '03001234580'],
            ['name' => 'Faisal Ahmed', 'email' => 'faisal@test.com', 'phone' => '03001234581'],
        ];

        $createdCustomers = [];
        foreach ($customers as $customerData) {
            $createdCustomers[] = Customer::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'phone' => $customerData['phone'],
                'address' => 'Test Address, Lahore',
            ]);
        }

        $this->command->info('Created ' . count($createdCustomers) . ' test customers.');
        $this->command->info('Creating test bookings for last 6 months...');

        // Booking statuses with probabilities
        $statuses = [
            'confirmed' => 30,    // 30%
            'completed' => 25,    // 25%
            'in_progress' => 15,  // 15%
            'pending' => 15,      // 15%
            'not_converted' => 10,// 10%
            'cancelled' => 5,     // 5%
        ];

        // Lead sources
        $leadSources = [
            'phone' => 35,
            'website' => 20,
            'facebook' => 15,
            'whatsapp' => 10,
            'instagram' => 10,
            'email' => 5,
            'referral' => 3,
            'walk_in' => 2,
        ];

        $bookingCount = 0;
        $startDate = now()->subMonths(6)->startOfMonth();
        $endDate = now();

        // Loop through each month
        for ($month = 0; $month < 6; $month++) {
            $currentMonth = now()->subMonths(5 - $month);
            $monthStart = $currentMonth->copy()->startOfMonth();
            $monthEnd = $currentMonth->copy()->endOfMonth();

            // Determine number of bookings for this month (random between 12-25)
            $bookingsThisMonth = rand(12, 25);
            
            $this->command->info("Creating {$bookingsThisMonth} bookings for {$currentMonth->format('F Y')}...");

            for ($i = 0; $i < $bookingsThisMonth; $i++) {
                // Random date within the month
                $bookingDate = Carbon::parse($monthStart)->addDays(rand(0, $monthStart->diffInDays($monthEnd)));
                
                // Get random customer
                $customer = $createdCustomers[array_rand($createdCustomers)];
                
                // Get random status based on probability
                $status = $this->getWeightedRandom($statuses);
                
                // Get random lead source based on probability
                $leadSource = $this->getWeightedRandom($leadSources);
                
                // Generate random amount
                $amount = rand(5000, 50000);
                
                // Create booking
                Booking::create([
                    'customer_id' => $customer->id,
                    'booking_reference' => 'TEST-' . strtoupper(uniqid()),
                    'booking_date' => $bookingDate,
                    'start_date' => $bookingDate,
                    'end_date' => $bookingDate,
                    'start_time' => $bookingDate->copy()->setTime(rand(8, 18), 0, 0),
                    'pickup_address' => 'Pickup Address ' . ($i + 1) . ', Lahore',
                    'delivery_address' => 'Delivery Address ' . ($i + 1) . ', Karachi',
                    'pickup_postcode' => '54000',
                    'delivery_postcode' => '75000',
                    'job_description' => 'Test transport job #' . ($bookingCount + 1),
                    'status' => $status,
                    'lead_source' => $leadSource,
                    'total_amount' => $amount,
                    'manual_amount' => $amount,
                    'total_fare' => $amount,
                    'is_manual_amount' => true,
                    'booking_type' => 'fixed',
                    'helpers_count' => rand(1, 4),
                    'deposit' => rand(1000, 5000),
                    'created_by' => $admin->id,
                    'week_start' => $bookingDate->copy()->startOfWeek(),
                    'created_at' => $bookingDate,
                    'updated_at' => $bookingDate,
                ]);

                $bookingCount++;
            }
        }

        $this->command->info("✅ Successfully created {$bookingCount} test bookings!");
        $this->command->info('📊 Breakdown by status:');
        
        foreach ($statuses as $status => $percentage) {
            $count = Booking::where('status', $status)->count();
            $this->command->line("   - {$status}: {$count} bookings");
        }
        
        $this->command->info('📱 Breakdown by lead source:');
        
        foreach ($leadSources as $source => $percentage) {
            $count = Booking::where('lead_source', $source)->count();
            $this->command->line("   - {$source}: {$count} bookings");
        }
    }

    /**
     * Get a weighted random value from array
     */
    private function getWeightedRandom($weighted)
    {
        $rand = mt_rand(1, array_sum($weighted));
        
        foreach ($weighted as $key => $weight) {
            $rand -= $weight;
            if ($rand <= 0) {
                return $key;
            }
        }
        
        return array_key_first($weighted);
    }
}
