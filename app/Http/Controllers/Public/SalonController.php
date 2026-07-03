<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\View\View;

class SalonController extends Controller
{
    public function show(): View
    {
        $salon = currentSalon();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $employees = Employee::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('public.salon', compact('salon', 'services', 'employees'));
    }
}
