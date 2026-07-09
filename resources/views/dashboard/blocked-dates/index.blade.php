@extends('layouts.dashboard')

@section('title', __('Days Off'))
@section('page-title', __('Days Off & Holidays'))

@section('dashboard-content')

<div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
    <x-card class="h-fit">
        <h2 class="mb-4 text-sm font-semibold text-gray-900">{{ __('Block a date') }}</h2>
        <form method="POST" action="{{ route('dashboard.blocked-dates.store') }}" class="space-y-4">
            @csrf
            <x-input :label="__('Date')" name="date" type="date" required min="{{ now()->format('Y-m-d') }}" />
            <x-input :label="__('Reason')" name="reason" optional :placeholder="__('e.g. Public holiday')" />
            <x-btn class="w-full">{{ __('Block Date') }}</x-btn>
        </form>
    </x-card>

    <div class="lg:col-span-2">
        @if ($blockedDates->isEmpty())
            <x-empty-state :title="__('No blocked dates')" :description="__('Block holidays or days off so customers cannot book them.')">
                <x-slot:icon>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </x-slot:icon>
            </x-empty-state>
        @else
            <x-card :padding="false" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead>
                        <tr class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                            <th class="px-5 py-3 text-start">{{ __('Date') }}</th>
                            <th class="px-5 py-3 text-start">{{ __('Reason') }}</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($blockedDates as $blocked)
                            <tr class="transition hover:bg-gray-50/60">
                                <td class="px-5 py-3.5 font-medium text-gray-900">{{ $blocked->date->translatedFormat(app()->getLocale() === 'ar' ? 'j F Y' : 'M j, Y') }}</td>
                                <td class="px-5 py-3.5 text-gray-500">{{ $blocked->reason ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-end">
                                    <form method="POST" action="{{ route('dashboard.blocked-dates.destroy', $blocked) }}" onsubmit="return confirm(@js(__('Unblock this date?')));">
                                        @csrf
                                        @method('DELETE')
                                        <x-btn variant="ghost" size="sm" class="!text-red-600">{{ __('Remove') }}</x-btn>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-card>
            <div class="mt-4">{{ $blockedDates->links() }}</div>
        @endif
    </div>
</div>

@endsection
