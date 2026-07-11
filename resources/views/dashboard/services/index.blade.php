@extends('layouts.dashboard')

@section('title', __('Services'))
@section('page-title', __('Services'))

@section('dashboard-content')

<div class="mb-5 flex items-center justify-between gap-3">
    <p class="text-sm text-gray-500">{{ __('The services customers can book online.') }}</p>
    <x-btn href="{{ route('dashboard.services.create') }}" size="sm" class="shrink-0">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        {{ __('Add Service') }}
    </x-btn>
</div>

@if ($services->isEmpty())
    <x-empty-state :title="__('No services yet')" :description="__('Add your first service so customers can start booking online.')">
        <x-slot:icon>
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </x-slot:icon>
        <x-slot:action>
            <x-btn href="{{ route('dashboard.services.create') }}" size="sm">{{ __('Add Service') }}</x-btn>
        </x-slot:action>
    </x-empty-state>
@else
    <x-card :padding="false" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead>
                <tr class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                    <th class="px-5 py-3 text-start">{{ __('Name') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Duration') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Price') }}</th>
                    <th class="px-5 py-3 text-start">{{ __('Status') }}</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($services as $service)
                    <tr class="transition hover:bg-gray-50/60">
                        <td class="px-5 py-3.5 font-medium text-gray-900">{{ $service->name }}</td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $service->duration_minutes }} {{ __('min') }}</td>
                        <td class="px-5 py-3.5 tabular-nums text-gray-700">{{ $service->price !== null ? number_format($service->price, 2) : '—' }}</td>
                        <td class="px-5 py-3.5">
                            <x-badge :tone="$service->is_active ? 'success' : 'neutral'">{{ $service->is_active ? __('Active') : __('Inactive') }}</x-badge>
                        </td>
                        <td class="px-5 py-3.5 text-end">
                            <div class="flex items-center justify-end gap-1">
                                <x-btn href="{{ route('dashboard.services.edit', $service) }}" variant="ghost" size="sm">{{ __('Edit') }}</x-btn>
                                <form method="POST" action="{{ route('dashboard.services.destroy', $service) }}" onsubmit="return confirm(@js(__('Delete this service?')));">
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
@endif

@endsection
