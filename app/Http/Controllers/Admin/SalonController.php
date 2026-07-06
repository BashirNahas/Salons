<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalonRequest;
use App\Http\Requests\UpdateSalonRequest;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SalonController extends Controller
{
    public function index(): View
    {
        $salons = Salon::withCount('bookings')->with('owner')->latest()->paginate(15);

        return view('admin.salons.index', compact('salons'));
    }

    public function create(): View
    {
        return view('admin.salons.create');
    }

    public function store(StoreSalonRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $generatedPassword = $data['owner_password'] ?? Str::password(12);

        $salon = DB::transaction(function () use ($data, $generatedPassword) {
            $owner = User::create([
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
                'password' => $generatedPassword,
                'role' => User::ROLE_SALON_OWNER,
            ]);

            return Salon::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'owner_id' => $owner->id,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'description' => $data['description'] ?? null,
            ]);
        });

        return redirect()->route('admin.salons.index')
            ->with('status', "Salon \"{$salon->name}\" created.")
            ->with('generated_credentials', [
                'email' => $salon->owner->email,
                'password' => $generatedPassword,
                'login_url' => $salon->url('dashboard/login'),
            ]);
    }

    public function edit(Salon $salon): View
    {
        return view('admin.salons.edit', compact('salon'));
    }

    public function update(UpdateSalonRequest $request, Salon $salon): RedirectResponse
    {
        $data = $request->validated();
        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($data, $salon, $isActive) {
            $salon->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'description' => $data['description'] ?? null,
                'is_active' => $isActive,
            ]);

            $ownerUpdate = [
                'name' => $data['owner_name'],
                'email' => $data['owner_email'],
            ];

            if (! empty($data['owner_password'])) {
                $ownerUpdate['password'] = $data['owner_password'];
            }

            $salon->owner->update($ownerUpdate);
        });

        return redirect()->route('admin.salons.index')->with('status', "Salon \"{$salon->name}\" updated.");
    }

    public function destroy(Salon $salon): RedirectResponse
    {
        $name = $salon->name;
        $owner = $salon->owner;

        $salon->delete();
        $owner?->delete();

        return redirect()->route('admin.salons.index')->with('status', "Salon \"{$name}\" deleted.");
    }
}
