<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalonRequest;
use App\Http\Requests\UpdateSalonRequest;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SalonController extends Controller
{
    public function index(Request $request): View
    {
        $salons = Salon::withCount('bookings')
            ->with('owner')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q
                    ->where('name', 'like', $term)
                    ->orWhere('slug', 'like', $term)
                    ->orWhereHas('owner', fn ($o) => $o->where('email', 'like', $term)->orWhere('name', 'like', $term)));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

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
                'subscription_starts_at' => $data['subscription_starts_at'] ?? null,
                'subscription_ends_at' => $data['subscription_ends_at'] ?? null,
            ]);
        });

        return redirect()->route('admin.salons.index')
            ->with('status', __('Salon ":name" created.', ['name' => $salon->name]))
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
                'subscription_starts_at' => $data['subscription_starts_at'] ?? null,
                'subscription_ends_at' => $data['subscription_ends_at'] ?? null,
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

        return redirect()->route('admin.salons.index')
            ->with('status', __('Salon ":name" updated.', ['name' => $salon->name]));
    }

    /**
     * The super admin's master ON/OFF switch. OFF makes the salon
     * immediately unreachable (public site, booking, and owner login all
     * show the "unavailable" page) regardless of subscription dates.
     */
    public function toggle(Salon $salon): RedirectResponse
    {
        $salon->update(['is_active' => ! $salon->is_active]);

        return back()->with('status', $salon->is_active
            ? __('Salon ":name" has been activated.', ['name' => $salon->name])
            : __('Salon ":name" has been deactivated.', ['name' => $salon->name]));
    }

    public function destroy(Salon $salon): RedirectResponse
    {
        $name = $salon->name;
        $owner = $salon->owner;

        $salon->delete();
        $owner?->delete();

        return redirect()->route('admin.salons.index')
            ->with('status', __('Salon ":name" deleted.', ['name' => $name]));
    }
}
