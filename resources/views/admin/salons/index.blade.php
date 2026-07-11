@extends('layouts.admin')

@section('title', __('Salons'))
@section('page-title', __('Salons'))

@section('admin-content')

<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <form method="GET" class="relative">
        <svg class="pointer-events-none absolute start-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('Search salons…') }}"
               class="w-64 rounded-lg border-0 py-2 ps-9 text-sm shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-brand-600">
    </form>
    <x-btn href="{{ route('admin.salons.create') }}" variant="dark" size="sm">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add Salon') }}
    </x-btn>
</div>

@if ($salons->isEmpty())
    <x-empty-state :title="__('No salons found')" :description="__('Create the first salon to get started.')">
        <x-slot:icon>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21"/></svg>
        </x-slot:icon>
        <x-slot:action>
            <x-btn href="{{ route('admin.salons.create') }}" variant="dark" size="sm">{{ __('Add Salon') }}</x-btn>
        </x-slot:action>
    </x-empty-state>
@else
    <x-card :padding="false" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                    <th class="px-5 py-3 text-start">{{ __('Salon') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Owner') }}</th>
                    <th class="hidden px-5 py-3 text-start lg:table-cell">{{ __('Bookings') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Subscription') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($salons as $salon)
                    <tr class="transition hover:bg-gray-50/60">
                        <td class="px-5 py-3.5">
                            <p class="font-medium text-gray-900">{{ $salon->name }}</p>
                            <a href="{{ $salon->url() }}" target="_blank" dir="ltr" class="text-xs text-brand-600 hover:underline">{{ $salon->subdomain() }}</a>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-gray-700">{{ $salon->owner->name }}</p>
                            <p dir="ltr" class="text-xs text-gray-400">{{ $salon->owner->email }}</p>
                        </td>
                        <td class="hidden px-5 py-3.5 tabular-nums text-gray-700 lg:table-cell">{{ $salon->bookings_count }}</td>
                        <td class="px-5 py-3.5">
                            @if ($salon->subscription_ends_at === null)
                                <x-badge tone="info">{{ __('Unlimited') }}</x-badge>
                            @elseif ($salon->subscriptionExpired())
                                <x-badge tone="danger">{{ __('Expired') }}</x-badge>
                                <p class="mt-0.5 text-xs text-gray-400">{{ $salon->subscription_ends_at->translatedFormat('j M Y') }}</p>
                            @else
                                <x-badge tone="success">{{ trans_choice('{1}:count day left|[2,*]:count days left', $salon->subscriptionDaysLeft(), ['count' => $salon->subscriptionDaysLeft()]) }}</x-badge>
                                <p class="mt-0.5 text-xs text-gray-400">{{ __('until') }} {{ $salon->subscription_ends_at->translatedFormat('j M Y') }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <form method="POST" action="{{ route('admin.salons.toggle', $salon) }}">
                                @csrf
                                <button type="submit" role="switch" aria-checked="{{ $salon->is_active ? 'true' : 'false' }}"
                                        title="{{ $salon->is_active ? __('Turn off') : __('Turn on') }}"
                                        class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition {{ $salon->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition ltr:{{ $salon->is_active ? 'translate-x-6' : 'translate-x-1' }} rtl:{{ $salon->is_active ? '-translate-x-6' : '-translate-x-1' }}"></span>
                                </button>
                            </form>
                            <p class="mt-1 text-xs {{ $salon->is_active ? 'text-emerald-600' : 'text-gray-400' }}">{{ $salon->is_active ? __('On') : __('Off') }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-end">
                            <div class="flex items-center justify-end gap-1">
                                <x-btn href="{{ route('admin.salons.edit', $salon) }}" variant="ghost" size="sm">{{ __('Edit') }}</x-btn>
                                <form method="POST" action="{{ route('admin.salons.destroy', $salon) }}"
                                      onsubmit="return confirm(@js(__('Delete this salon and its owner account? This cannot be undone.')));">
                                    @csrf
                                    @method('DELETE')
                                    <x-btn variant="ghost" size="sm" class="!text-red-600">{{ __('Delete') }}</x-btn>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>

    <div class="mt-4">{{ $salons->links() }}</div>
@endif

@endsection
