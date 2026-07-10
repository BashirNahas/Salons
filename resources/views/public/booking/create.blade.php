@extends('layouts.public')

@section('title', __('Book an Appointment — :salon', ['salon' => $salon->name]))

@section('public-content')

<div class="mx-auto max-w-2xl" x-data="bookingWizard()" x-init="init()">

    {{-- Progress header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-gray-900">{{ __('Book an Appointment') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $salon->name }}</p>

        <div class="mt-6 flex items-center">
            @php
                $steps = $employees->isNotEmpty()
                    ? [['num' => 1, 'label' => __('Service')], ['num' => 2, 'label' => __('Staff')], ['num' => 3, 'label' => __('Date & Time')], ['num' => 4, 'label' => __('Your Info')]]
                    : [['num' => 1, 'label' => __('Service')], ['num' => 2, 'label' => __('Date & Time')], ['num' => 3, 'label' => __('Your Info')]];
            @endphp

            @foreach ($steps as $i => $step)
                @if ($i > 0)
                    <div class="mx-2 h-px flex-1 rounded bg-gray-200" :class="currentStep > {{ $step['num'] - 1 }} ? '!bg-brand-400' : ''"></div>
                @endif
                <div class="flex flex-col items-center">
                    <div :class="currentStep >= {{ $step['num'] }} ? 'bg-brand-600 text-white' : 'bg-gray-200 text-gray-500'"
                         class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold transition-colors">
                        <template x-if="currentStep > {{ $step['num'] }}">
                            <svg aria-hidden="true" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </template>
                        <template x-if="currentStep <= {{ $step['num'] }}">
                            <span>{{ $step['num'] }}</span>
                        </template>
                    </div>
                    <p class="mt-1.5 text-xs font-medium" :class="currentStep >= {{ $step['num'] }} ? 'text-brand-700' : 'text-gray-400'">{{ $step['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <form method="POST" action="{{ route('public.booking.store') }}" id="booking-form">
        @csrf
        <input type="hidden" name="service_id" x-model="selectedService">
        <input type="hidden" name="employee_id" x-model="selectedEmployee">
        <input type="hidden" name="date" x-model="selectedDate">
        <input type="hidden" name="time" x-model="selectedTime">

        {{-- Step 1: Service --}}
        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="mb-4 text-sm font-semibold text-gray-900">{{ __('Choose a Service') }}</h2>
            @error('service_id') <p class="mb-3 text-sm font-medium text-red-600">{{ $message }}</p> @enderror

            @if ($services->isEmpty())
                <p class="text-sm text-gray-400">{{ __('No services available yet.') }}</p>
            @else
                <div class="space-y-3">
                    @foreach ($services as $service)
                        <button type="button"
                                @click="selectService({{ $service->id }}, {{ $service->duration_minutes }})"
                                :class="selectedService == {{ $service->id }} ? 'ring-2 ring-brand-600 bg-brand-50/60' : 'ring-1 ring-gray-950/5 bg-white hover:ring-brand-300'"
                                class="w-full rounded-2xl px-5 py-4 text-start shadow-card transition">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900">{{ $service->name }}</p>
                                    <p class="mt-0.5 text-sm text-gray-500">{{ $service->duration_minutes }} {{ __('minutes') }}</p>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    @if ($service->price !== null)
                                        <span class="text-base font-semibold tabular-nums text-brand-700">{{ number_format($service->price, 2) }}</span>
                                    @endif
                                    <div :class="selectedService == {{ $service->id }} ? 'border-brand-600 bg-brand-600' : 'border-gray-300'"
                                         class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-colors">
                                        <div x-show="selectedService == {{ $service->id }}" class="h-2 w-2 rounded-full bg-white"></div>
                                    </div>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="mt-6">
                <button type="button" @click="nextStep()" :disabled="!selectedService"
                        class="w-full rounded-xl bg-brand-600 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-40">
                    {{ __('Continue') }}
                </button>
            </div>
        </div>

        @if ($employees->isNotEmpty())
        {{-- Step 2: Staff --}}
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="mb-4 text-sm font-semibold text-gray-900">{{ __('Choose a Staff Member') }}</h2>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                <button type="button" @click="selectEmployee(null)"
                        :class="selectedEmployee === null ? 'ring-2 ring-brand-600 bg-brand-50/60' : 'ring-1 ring-gray-950/5 bg-white hover:ring-brand-300'"
                        class="flex flex-col items-center rounded-2xl px-3 py-5 shadow-card transition">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                        <svg aria-hidden="true" class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <p class="mt-2.5 text-sm font-medium text-gray-800">{{ __('Any Staff') }}</p>
                    <p class="mt-0.5 text-xs text-gray-400">{{ __('No preference') }}</p>
                </button>

                @foreach ($employees as $employee)
                    <button type="button" @click="selectEmployee({{ $employee->id }})"
                            :class="selectedEmployee == {{ $employee->id }} ? 'ring-2 ring-brand-600 bg-brand-50/60' : 'ring-1 ring-gray-950/5 bg-white hover:ring-brand-300'"
                            class="flex flex-col items-center rounded-2xl px-3 py-5 shadow-card transition">
                        @if ($employee->avatar_url)
                            <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="h-14 w-14 rounded-full object-cover">
                        @else
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-lg font-semibold text-white">
                                {{ $employee->initials() }}
                            </div>
                        @endif
                        <p class="mt-2.5 text-sm font-semibold text-gray-900">{{ $employee->name }}</p>
                        @if ($employee->specialties)
                            <p class="mt-0.5 text-center text-xs text-gray-400">{{ $employee->specialties }}</p>
                        @endif
                    </button>
                @endforeach
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="prevStep()" class="flex-1 rounded-xl bg-white py-3.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50">{{ __('Back') }}</button>
                <button type="button" @click="nextStep()" class="flex-1 rounded-xl bg-brand-600 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">{{ __('Continue') }}</button>
            </div>
        </div>
        @endif

        {{-- Date & Time --}}
        <div x-show="currentStep === dateTimeStep" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="mb-4 text-sm font-semibold text-gray-900">{{ __('Pick a Date & Time') }}</h2>
            @error('time') <p class="mb-3 rounded-xl bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600">{{ $message }}</p> @enderror

            <div class="space-y-5">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                    <input type="date" x-model="selectedDate" :min="today()" @change="loadSlots()"
                           class="block w-full rounded-lg border-0 py-2.5 text-sm shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-brand-600">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">{{ __('Available Times') }}</label>
                    <div class="min-h-[60px]">
                        <p x-show="slotsState === 'idle'" class="text-sm text-gray-400">{{ __('Select a date to see available times.') }}</p>
                        <p x-show="slotsState === 'loading'" class="text-sm text-gray-400">{{ __('Loading available times…') }}</p>
                        <p x-show="slotsState === 'empty'" class="text-sm text-gray-400">{{ __('No available times for this date.') }}</p>
                        <p x-show="slotsState === 'error'" class="text-sm font-medium text-red-500">{{ __('Could not load available times. Please try again.') }}</p>
                        <div x-show="slotsState === 'loaded'" class="grid grid-cols-3 gap-2 sm:grid-cols-4" dir="ltr">
                            <template x-for="slot in slots" :key="slot">
                                <button type="button" @click="selectedTime = slot"
                                        :class="selectedTime === slot ? 'bg-brand-600 text-white ring-brand-600' : 'bg-white text-gray-700 ring-gray-950/10 hover:ring-brand-400 hover:bg-brand-50'"
                                        class="rounded-lg px-3 py-2.5 text-sm font-medium tabular-nums shadow-sm ring-1 ring-inset transition-colors"
                                        x-text="slot"></button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="prevStep()" class="flex-1 rounded-xl bg-white py-3.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50">{{ __('Back') }}</button>
                <button type="button" @click="nextStep()" :disabled="!selectedTime"
                        class="flex-1 rounded-xl bg-brand-600 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 disabled:cursor-not-allowed disabled:opacity-40">
                    {{ __('Continue') }}
                </button>
            </div>
        </div>

        {{-- Contact Info --}}
        <div x-show="currentStep === infoStep" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="mb-4 text-sm font-semibold text-gray-900">{{ __('Your Details') }}</h2>

            {{-- Booking summary --}}
            <div class="mb-5 rounded-2xl bg-brand-50 px-5 py-4 text-sm">
                <p class="mb-1 font-semibold text-brand-900">{{ __('Booking Summary') }}</p>
                <p class="font-medium text-brand-700" x-text="serviceName"></p>
                <p class="mt-0.5 text-brand-600" x-show="selectedDate && selectedTime">
                    <span x-text="formatDate(selectedDate)"></span> {{ __('at') }} <span dir="ltr" x-text="selectedTime"></span>
                </p>
                <p class="mt-0.5 text-brand-600" x-show="staffName" x-text="staffLine"></p>
            </div>

            <div class="space-y-5">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <x-input :label="__('Full Name')" name="customer_name" required :placeholder="__('Your name')" />
                    <x-phone-input :label="__('Phone')" name="customer_phone" required />
                </div>
                <x-input :label="__('Email')" name="customer_email" type="email" optional dir="ltr" placeholder="you@example.com" />
                <x-textarea :label="__('Notes')" name="notes" rows="2" optional :placeholder="__('Any special requests?')" />
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="prevStep()" class="rounded-xl bg-white px-5 py-3.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50">{{ __('Back') }}</button>
                <button type="submit" class="flex-1 rounded-xl bg-brand-600 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    {{ __('Confirm Booking') }}
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Page data for the wizard (JSON only — behavior lives in assets/js/booking-wizard.js) --}}
@php
    $wizardConfig = [
        'hasEmployees' => $employees->isNotEmpty(),
        'slotsUrl' => route('public.booking.slots'),
        'preselectedService' => request('service'),
        'serviceNames' => $services->pluck('name', 'id'),
        'staffNames' => $employees->pluck('name', 'id'),
        'staffLabel' => __('Staff:'),
        'dateLocale' => app()->getLocale() === 'ar' ? 'ar' : 'en-US',
    ];
@endphp
<script type="application/json" id="booking-wizard-config">@json($wizardConfig)</script>

@push('head')
<script defer src="{{ asset('assets/js/booking-wizard.js') }}?v=20260710"></script>
@endpush
@endsection
