<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('name')->get();

        return view('dashboard.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('dashboard.services.create');
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        Service::create($request->validated());

        return redirect()->route('dashboard.services.index')->with('status', __('Service created.'));
    }

    public function edit(Service $service): View
    {
        return view('dashboard.services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $service->update($data);

        return redirect()->route('dashboard.services.index')->with('status', __('Service updated.'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('dashboard.services.index')->with('status', __('Service deleted.'));
    }
}
