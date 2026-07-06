<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Employee;
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
        // Use create() rather than factory()->create() so the demo seeder
        // works on production installs (composer install --no-dev), where
        // fakerphp/faker is absent. All fields are explicit anyway, so the
        // factory added nothing here.
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@salons.synaptix.sy',
            'password' => 'password',
            'role' => User::ROLE_SUPER_ADMIN,
        ]);

        $this->seedSalon(
            slug: 'issa',
            name: "Issa's Barbershop",
            ownerName: 'Issa Khalil',
            ownerEmail: 'issa@salons.synaptix.sy',
            phone: '+963 11 123 4567',
            address: 'Damascus, Syria',
            description: 'A premium barbershop in the heart of Damascus. Expert cuts, fades, and beard grooming.',
            services: [
                ['name' => 'Haircut', 'duration_minutes' => 30, 'price' => 15.00],
                ['name' => 'Fade Cut', 'duration_minutes' => 45, 'price' => 20.00],
                ['name' => 'Beard Trim', 'duration_minutes' => 15, 'price' => 8.00],
                ['name' => 'Hair & Beard Combo', 'duration_minutes' => 60, 'price' => 25.00],
            ],
            employees: [
                ['name' => 'Issa Khalil', 'specialties' => 'Fades, Classic Cuts', 'sort_order' => 0],
                ['name' => 'Ahmad Nasser', 'specialties' => 'Beard Styling, Hot Towel Shave', 'sort_order' => 1],
                ['name' => 'Rami Saleh', 'specialties' => 'Modern Cuts, Coloring', 'sort_order' => 2],
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
                ['name' => 'Blow Dry & Style', 'duration_minutes' => 30, 'price' => 12.00],
                ['name' => 'Makeup Session', 'duration_minutes' => 60, 'price' => 40.00],
                ['name' => 'Manicure', 'duration_minutes' => 45, 'price' => 18.00],
            ],
            employees: [
                ['name' => 'Lina Haddad', 'specialties' => 'Skincare, Facials', 'sort_order' => 0],
                ['name' => 'Sara Mousa', 'specialties' => 'Hair Styling, Blow Dry', 'sort_order' => 1],
            ],
        );
    }

    /**
     * @param  array<int, array{name: string, duration_minutes: int, price: float}>  $services
     * @param  array<int, array{name: string, specialties: string, sort_order: int}>  $employees
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
        array $employees = [],
    ): void {
        $owner = User::create([
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

        $createdEmployees = collect($employees)->map(
            fn (array $emp) => Employee::create([
                'salon_id' => $salon->id,
                'name' => $emp['name'],
                'specialties' => $emp['specialties'] ?? null,
                'sort_order' => $emp['sort_order'] ?? 0,
                'is_active' => true,
            ])
        );

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
