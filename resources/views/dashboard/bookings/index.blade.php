@extends('layouts.dashboard')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('dashboard-content')
<form method="GET" class="mb-4 flex flex-wrap gap-3">
    <select name="status" class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        @foreach (['pending', 'approved', 'rejected'] as $status)
            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
</form>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <tr>
                <th class="px-4 py-3">Customer</th>
                <th class="px-4 py-3">Service</th>
                <th class="px-4 py-3">Date / Time</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($bookings as $booking)
                <tr>
                    <td class="px-4 py-3">
                        <span class="font-medium">{{ $booking->customer_name }}</span><br>
                        <span class="text-gray-400">{{ $booking->customer_phone }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $booking->service->name }}</td>
                    <td class="px-4 py-3">{{ $booking->datetime->format('M j, Y H:i') }}</td>
                    <td class="px-4 py-3">
                        <span @class([
                            'rounded-full px-2 py-0.5 text-xs font-medium capitalize',
                            'bg-yellow-100 text-yellow-700' => $booking->status === 'pending',
                            'bg-green-100 text-green-700' => $booking->status === 'approved',
                            'bg-red-100 text-red-700' => $booking->status === 'rejected',
                        ])>{{ $booking->status }}</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if ($booking->status === 'pending')
                            <form method="POST" action="{{ route('dashboard.bookings.approve', $booking) }}" class="inline">
                                @csrf
                                <button type="submit" class="font-medium text-green-600 hover:underline">Accept</button>
                            </form>
                            <form method="POST" action="{{ route('dashboard.bookings.reject', $booking) }}" class="inline">
                                @csrf
                                <button type="submit" class="ml-3 font-medium text-red-600 hover:underline">Reject</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-400">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
