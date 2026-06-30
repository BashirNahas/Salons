<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class SalonController extends Controller
{
    public function show(): View
    {
        $salon = currentSalon();
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('public.salon', compact('salon', 'services'));
    }
}
