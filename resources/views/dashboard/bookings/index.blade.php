@extends('layouts.dashboard')

@section('title', __('Appointments'))
@section('page-title', __('Appointments'))

@section('dashboard-content')

<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <form method="GET">
        <select name="status" onchange="this.form.submit()"
                class="rounded-lg border-0 py-2 text-sm text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-600">
            <option value="">{{ __('All Statuses') }}</option>
            @foreach (['pending', 'approved', 'rejected', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ __(ucfirst($status)) }}</option>
            @endforeach
        </select>
    </form>
    <div class="flex items-center gap-2">
        <x-btn href="{{ route('dashboard.bookings.calendar') }}" variant="secondary" size="sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ __('Calendar') }}
        </x-btn>
        <x-btn href="{{ route('dashboard.bookings.create') }}" size="sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            {{ __('Add Appointment') }}
        </x-btn>
    </div>
</div>

@if ($bookings->isEmpty())
    <x-empty-state :title="__('No bookings found')" :description="__('New booking requests from your customers will appear here.')">
        <x-slot:icon>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </x-slot:icon>
        <x-slot:action>
            <x-btn href="{{ route('dashboard.bookings.create') }}" size="sm">{{ __('Add Appointment') }}</x-btn>
        </x-slot:action>
    </x-empty-state>
@else
    <x-card :padding="false" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="text-start text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                    <th class="px-5 py-3 text-start">{{ __('Customer') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Service') }}</th>
                    <th class="hidden px-5 py-3 text-start sm:table-cell">{{ __('Staff') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Date & Time') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($bookings as $booking)
                    <tr class="transition hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5">
                                <span class="font-medium text-gray-900">{{ $booking->customer_name }}</span>
                                @if ($booking->source === 'manual')
                                    <x-badge tone="info">{{ __('Walk-in') }}</x-badge>
                                @elseif ($booking->source === 'recurring')
                                    <x-badge tone="purple">{{ __('Regular') }}</x-badge>
                                @endif
                            </div>
                            <span dir="ltr" class="text-xs text-gray-400">{{ $booking->customer_phone }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $booking->service->name }}</td>
                        <td class="hidden px-5 py-3.5 text-gray-500 sm:table-cell">{{ $booking->employee?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5 tabular-nums text-gray-700">{{ $booking->datetime->translatedFormat(app()->getLocale() === 'ar' ? 'j M Y، H:i' : 'M j, Y H:i') }}</td>
                        <td class="px-5 py-3.5">
                            <x-badge :tone="['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$booking->status] ?? 'neutral'">
                                {{ __(ucfirst($booking->status)) }}
                            </x-badge>
                        </td>
                        <td class="px-5 py-3.5 text-end">
                            @if ($booking->status === 'pending')
                                <div class="flex items-center justify-end gap-1">
                                    <form method="POST" action="{{ route('dashboard.bookings.approve', $booking) }}">
                                        @csrf
                                        <x-btn size="sm" variant="secondary" class="!text-emerald-700">{{ __('Accept') }}</x-btn>
                                    </form>
                                    <form method="POST" action="{{ route('dashboard.bookings.reject', $booking) }}">
                                        @csrf
                                        <x-btn size="sm" variant="ghost" class="!text-red-600">{{ __('Reject') }}</x-btn>
                                    </form>
                                </div>
                            @elseif ($booking->status === 'approved')
                                <form method="POST" action="{{ route('dashboard.bookings.cancel', $booking) }}"
                                      onsubmit="return confirm(@js(__('Cancel the appointment for :name? The time slot will become available again.', ['name' => $booking->customer_name])));">
                                    @csrf
                                    <x-btn size="sm" variant="ghost" class="!text-red-600">{{ __('Cancel Appointment') }}</x-btn>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">{{ $bookings->links() }}</div>
@endif

@endsection
