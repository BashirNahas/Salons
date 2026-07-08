@extends('layouts.dashboard')

@section('title', 'Regular Customers')
@section('page-title', 'Regular Customers')

@section('dashboard-content')

<div class="mb-5 flex items-center justify-between">
    <p class="text-sm text-gray-500">Customers with a standing weekly appointment. Upcoming appointments are scheduled automatically and blocked from online booking.</p>
    <a href="{{ route('dashboard.recurring-bookings.create') }}" class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Regular Customer
    </a>
</div>

@if($recurringBookings->isEmpty())
    <div class="rounded-xl border border-dashed border-gray-200 bg-white py-16 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-brand-500">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <p class="mt-4 font-semibold text-gray-700">No regular customers yet</p>
        <p class="mt-1 text-sm text-gray-400">Add a customer with a standing weekly appointment (e.g. every Saturday at 10:00).</p>
    </div>
@else
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Customer</th>
                    <th class="px-4 py-3">Day / Time</th>
                    <th class="px-4 py-3">Service</th>
                    <th class="px-4 py-3 hidden sm:table-cell">Staff</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($recurringBookings as $rb)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="font-medium">{{ $rb->customer_name }}</span><br>
                            <span class="text-gray-400">{{ $rb->customer_phone }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $rb->dayName() }}, {{ $rb->time->format('H:i') }}</td>
                        <td class="px-4 py-3">{{ $rb->service->name }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell text-gray-500">{{ $rb->employee?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-green-100 text-green-700' => $rb->is_active,
                                'bg-gray-100 text-gray-500' => !$rb->is_active,
                            ])>{{ $rb->is_active ? 'Active' : 'Paused' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('dashboard.recurring-bookings.edit', $rb) }}" class="font-medium text-brand-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('dashboard.recurring-bookings.destroy', $rb) }}" class="inline" onsubmit="return confirm('Cancel this recurring booking and remove upcoming appointments for {{ addslashes($rb->customer_name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:underline">Cancel</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
