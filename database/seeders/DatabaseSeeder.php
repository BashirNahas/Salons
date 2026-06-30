<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Salon;
use App\Models\Service;
use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with a super admin and two demo
     * salons (each with an owner, services, working hours, and a few
     * sample bookings) so the system is testable end-to-end out of the box.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@salons.synaptix.sy',
            'password' => 'password',
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $this->seedSalon(
            slug: 'issa',
            name: "Issa's Salon",
            ownerName: 'Issa Khalil',
            ownerEmail: 'issa@salons.synaptix.sy',
            phone: '+963 11 123 4567',
            address: 'Damascus, Syria',
            description: 'A premium hair and beauty salon in the heart of Damascus.',
            services: [
                ['name' => 'Haircut', 'duration_minutes' => 30, 'price' => 15.00],
                ['name' => 'Hair Coloring', 'duration_minutes' => 90, 'price' => 45.00],
                ['name' => 'Beard Trim', 'duration_minutes' => 15, 'price' => 8.00],
                ['name' => 'Manicure', 'duration_minutes' => 45, 'price' => 20.00],
            ],
        );

        $this->seedSalon(
            slug: 'glow',
            name: 'Glow Beauty Studio',
            ownerName: 'Lina Haddad',
            ownerEmail: 'lina@salons.synaptix.sy',
            phone: '+963 11 765 4321',
            address: 'Aleppo, Syria',
            description: 'Modern beauty studio specializing in skincare and styling.',
            services: [
                ['name' => 'Facial Treatment', 'duration_minutes' => 60, 'price' => 35.00],
                ['name' => 'Blow Dry', 'duration_minutes' => 30, 'price' => 12.00],
                ['name' => 'Makeup Session', 'duration_minutes' => 60, 'price' => 40.00],
            ],
        );
    }

    /**
     * @param  array<int, array{name: string, duration_minutes: int, price: float}>  $services
     */
    private function seedSalon(
        string $slug,
        string $name,
        string $ownerName,
        string $ownerEmail,
        string $phone,
        string $address,
        string $description,
        array $services,
    ): void {
        $owner = User::factory()->create([
            'name' => $ownerName,
            'email' => $ownerEmail,
            'password' => 'password',
            'role' => User::ROLE_SALON_OWNER,
        ]);

        $salon = Salon::create([
            'name' => $name,
            'slug' => $slug,
            'owner_id' => $owner->id,
            'phone' => $phone,
            'address' => $address,
            'description' => $description,
            'is_active' => true,
        ]);

        $createdServices = collect($services)->map(
            fn (array $service) => Service::create([
                'salon_id' => $salon->id,
                'name' => $service['name'],
                'duration_minutes' => $service['duration_minutes'],
                'price' => $service['price'],
                'is_active' => true,
            ])
        );

        // Open Monday-Saturday 09:00-18:00, closed Sunday.
        foreach (WorkingHour::DAYS as $day => $label) {
            WorkingHour::create([
                'salon_id' => $salon->id,
                'day_of_week' => $day,
                'start_time' => '09:00',
                'end_time' => '18:00',
                'is_closed' => $day === 0,
            ]);
        }

        $firstService = $createdServices->first();
        $secondService = $createdServices->get(1, $firstService);

        Booking::create([
            'salon_id' => $salon->id,
            'service_id' => $firstService->id,
            'customer_name' => 'Sample Customer',
            'customer_phone' => '+963 99 000 0001',
            'customer_email' => 'customer@example.com',
            'datetime' => Carbon::tomorrow()->setTime(10, 0),
            'status' => Booking::STATUS_PENDING,
            'notes' => null,
        ]);

        Booking::create([
            'salon_id' => $salon->id,
            'service_id' => $secondService->id,
            'customer_name' => 'Returning Customer',
            'customer_phone' => '+963 99 000 0002',
            'customer_email' => null,
            'datetime' => Carbon::tomorrow()->addDays(2)->setTime(14, 0),
            'status' => Booking::STATUS_APPROVED,
            'notes' => 'Prefers afternoon appointments.',
        ]);
    }
}
