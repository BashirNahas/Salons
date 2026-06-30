@extends('layouts.dashboard')

@section('title', 'Services')
@section('page-title', 'Services')

@section('dashboard-content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('dashboard.services.create') }}" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">
        + Add Service
    </a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Duration</th>
                <th class="px-4 py-3">Price</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($services as $service)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $service->name }}</td>
                    <td class="px-4 py-3">{{ $service->duration_minutes }} min</td>
                    <td class="px-4 py-3">{{ $service->price !== null ? number_format($service->price, 2) : '—' }}</td>
                    <td class="px-4 py-3">
                        @if ($service->is_active)
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                        @else
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('dashboard.services.edit', $service) }}" class="font-medium text-brand-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('dashboard.services.destroy', $service) }}" class="inline" onsubmit="return confirm('Delete this service?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-3 font-medium text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">No services yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
