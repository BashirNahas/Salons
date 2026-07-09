@extends('layouts.admin')

@section('title', __('All Bookings'))
@section('page-title', __('All Bookings'))

@section('admin-content')

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <select name="salon_id" onchange="this.form.submit()"
            class="rounded-lg border-0 py-2 text-sm text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-600">
        <option value="">{{ __('All Salons') }}</option>
        @foreach ($salons as $salon)
            <option value="{{ $salon->id }}" @selected(request('salon_id') == $salon->id)>{{ $salon->name }}</option>
        @endforeach
    </select>
    <select name="status" onchange="this.form.submit()"
            class="rounded-lg border-0 py-2 text-sm text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-600">
        <option value="">{{ __('All Statuses') }}</option>
        @foreach (['pending', 'approved', 'rejected', 'cancelled'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ __(ucfirst($status)) }}</option>
        @endforeach
    </select>
</form>

@if ($bookings->isEmpty())
    <x-empty-state :title="__('No bookings found')" :description="__('Bookings across all salons will appear here.')">
        <x-slot:icon>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </x-slot:icon>
    </x-empty-state>
@else
    <x-card :padding="false" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                    <th class="px-5 py-3 text-start">{{ __('Salon') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Customer') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Service') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Date & Time') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($bookings as $booking)
                    <tr class="transition hover:bg-gray-50/60">
                        <td class="px-5 py-3.5 font-medium text-gray-900">{{ $booking->salon->name }}</td>
                        <td class="px-5 py-3.5">
                            {{ $booking->customer_name }}<br>
                            <span dir="ltr" class="text-xs text-gray-400">{{ $booking->customer_phone }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $booking->service->name }}</td>
                        <td class="px-5 py-3.5 tabular-nums text-gray-700">{{ $booking->datetime->translatedFormat(app()->getLocale() === 'ar' ? 'j M Y، H:i' : 'M j, Y H:i') }}</td>
                        <td class="px-5 py-3.5">
                            <x-badge :tone="['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$booking->status] ?? 'neutral'">
                                {{ __(ucfirst($booking->status)) }}
                            </x-badge>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">{{ $bookings->links() }}</div>
@endif

@endsection
