<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Event;
use App\Models\Seat;
use App\Models\Ticket;

class PaymentMethodTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a test user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'user'
            ]
        );

        // Get or create a test event
        $event = Event::firstOrCreate(
            ['event_name' => 'Test Concert'],
            [
                'event_date' => now()->addDays(30),
                'description' => 'A test event for payment method testing',
                'organizer_id' => $user->id,
                'location' => 'Test Venue'
            ]
        );

        // Create test seats if they don't exist
        $seats = [];
        for ($i = 1; $i <= 10; $i++) {
            $seat = Seat::firstOrCreate(
                [
                    'event_id' => $event->id,
                    'label' => "A$i"
                ],
                [
                    'row' => 'A',
                    'number' => $i,
                    'zone' => 'VIP',
                    'price' => rand(50, 200),
                    'is_booked' => false
                ]
            );
            $seats[] = $seat;
        }

        // Payment methods to test
        $paymentMethods = ['credit_card', 'fpx', 'tng', 'grab', 'shopee', 'boost'];
        
        // Create test transactions with different payment methods
        foreach ($seats as $index => $seat) {
            if ($index < 6) { // Only create 6 transactions for testing
                $paymentMethod = $paymentMethods[$index];
                
                // Create ticket
                $ticket = Ticket::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'seat_id' => $seat->id,
                    'price' => $seat->price,
                    'type' => 'General'
                ]);

                // Create transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'seat_id' => $seat->id,
                    'ticket_id' => $ticket->id,
                    'amount' => $seat->price,
                    'payment_status' => 'paid',
                    'payment_method' => $paymentMethod,
                    'purchase_date' => now()->subDays(rand(1, 30))
                ]);

                // Mark seat as booked
                $seat->is_booked = true;
                $seat->save();
            }
        }

        // Create additional transactions with varying amounts for better distribution
        $additionalPaymentMethods = ['credit_card', 'fpx', 'tng', 'grab', 'shopee', 'boost'];
        for ($i = 0; $i < 10; $i++) {
            $paymentMethod = $additionalPaymentMethods[array_rand($additionalPaymentMethods)];
            
            // Create a new seat for each transaction
            $newSeat = Seat::create([
                'event_id' => $event->id,
                'label' => "B" . ($i + 1),
                'row' => 'B',
                'number' => $i + 1,
                'zone' => 'General',
                'price' => rand(30, 150),
                'is_booked' => true
            ]);

            // Create ticket
            $ticket = Ticket::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'seat_id' => $newSeat->id,
                'price' => $newSeat->price,
                'type' => 'General'
            ]);

            // Create transaction
            Transaction::create([
                'user_id' => $user->id,
                'seat_id' => $newSeat->id,
                'ticket_id' => $ticket->id,
                'amount' => $newSeat->price,
                'payment_status' => 'paid',
                'payment_method' => $paymentMethod,
                'purchase_date' => now()->subDays(rand(1, 30))
            ]);
        }

        $this->command->info('Payment method test data seeded successfully!');
    }
}
