@extends('layouts.dashboard')

@section('title', __('Regular Customers'))
@section('page-title', __('Regular Customers'))

@section('dashboard-content')

<div class="mb-5 flex items-center justify-between gap-3">
    <p class="text-sm text-gray-500">{{ __('Customers with a standing weekly appointment. Their slots are reserved automatically.') }}</p>
    <x-btn href="{{ route('dashboard.recurring-bookings.create') }}" size="sm" class="shrink-0">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add Regular Customer') }}
    </x-btn>
</div>

@if ($recurringBookings->isEmpty())
    <x-empty-state :title="__('No regular customers yet')" :description="__('Add a customer with a standing weekly appointment (e.g. every Saturday at 10:00).')">
        <x-slot:icon>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </x-slot:icon>
        <x-slot:action>
            <x-btn href="{{ route('dashboard.recurring-bookings.create') }}" size="sm">{{ __('Add Regular Customer') }}</x-btn>
        </x-slot:action>
    </x-empty-state>
@else
    <x-card :padding="false" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                    <th class="px-5 py-3 text-start">{{ __('Customer') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Day & Time') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Service') }}</th>
                    <th class="hidden px-5 py-3 text-start sm:table-cell">{{ __('Staff') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($recurringBookings as $rb)
                    <tr class="transition hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <span class="font-medium text-gray-900">{{ $rb->customer_name }}</span><br>
                            <span dir="ltr" class="text-xs text-gray-400">{{ $rb->customer_phone }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700">{{ __($rb->dayName()) }} · <span dir="ltr" class="tabular-nums">{{ $rb->time->format('H:i') }}</span></td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $rb->service->name }}</td>
                        <td class="hidden px-5 py-3.5 text-gray-500 sm:table-cell">{{ $rb->employee?->name ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <x-badge :tone="$rb->is_active ? 'success' : 'neutral'">{{ $rb->is_active ? __('Active') : __('Paused') }}</x-badge>
                        </td>
                        <td class="px-5 py-3.5 text-end">
                            <div class="flex items-center justify-end gap-1">
                                <x-btn href="{{ route('dashboard.recurring-bookings.edit', $rb) }}" variant="ghost" size="sm">{{ __('Edit') }}</x-btn>
                                <form method="POST" action="{{ route('dashboard.recurring-bookings.destroy', $rb) }}"
                                      onsubmit="return confirm(@js(__('Cancel this recurring booking and remove the upcoming appointments for :name?', ['name' => $rb->customer_name])));">
                                    @csrf
                                    @method('DELETE')
                                    <x-btn variant="ghost" size="sm" class="!text-red-600">{{ __('Cancel') }}</x-btn>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
@endif

@endsection
