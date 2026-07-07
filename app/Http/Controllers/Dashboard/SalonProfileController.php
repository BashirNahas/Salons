<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SalonProfileController extends Controller
{
    public function edit(): View
    {
        $salon = currentSalon();

        return view('dashboard.profile', compact('salon'));
    }

    public function update(Request $request): RedirectResponse
    {
        $salon = currentSalon();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'brand_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        if ($request->hasFile('logo')) {
            if ($salon->logo) {
                Storage::disk('public')->delete($salon->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            unset($data['logo']);
        }

        $salon->update($data);

        return redirect()->route('dashboard.profile.edit')
            ->with('status', 'Salon profile updated.');
    }
}
