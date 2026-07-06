@extends('layouts.public')

@section('title', 'Book an Appointment — ' . $salon->name)

@section('public-content')

<div class="mx-auto max-w-2xl" x-data="bookingWizard()" x-init="init()">

    {{-- Progress header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Book an Appointment</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $salon->name }}</p>

        <div class="mt-5 flex items-center gap-0">
            @php
                $steps = $employees->isNotEmpty()
                    ? [['num' => 1, 'label' => 'Service'], ['num' => 2, 'label' => 'Staff'], ['num' => 3, 'label' => 'Date & Time'], ['num' => 4, 'label' => 'Your Info']]
                    : [['num' => 1, 'label' => 'Service'], ['num' => 2, 'label' => 'Date & Time'], ['num' => 3, 'label' => 'Your Info']];
            @endphp

            @foreach($steps as $i => $step)
                @if($i > 0)
                    <div class="h-px flex-1 bg-gray-200" :class="currentStep > {{ $step['num'] - 1 }} ? 'bg-brand-400' : ''"></div>
                @endif
                <div class="flex flex-col items-center">
                    <div :class="currentStep === {{ $step['num'] }} ? 'bg-brand-600 text-white' : (currentStep > {{ $step['num'] }} ? 'bg-brand-600 text-white' : 'bg-gray-200 text-gray-500')"
                         class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold transition-colors">
                        <template x-if="currentStep > {{ $step['num'] }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </template>
                        <template x-if="currentStep <= {{ $step['num'] }}">
                            <span>{{ $step['num'] }}</span>
                        </template>
                    </div>
                    <p class="mt-1 text-xs font-medium" :class="currentStep >= {{ $step['num'] }} ? 'text-brand-700' : 'text-gray-400'">{{ $step['label'] }}</p>
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
            <h2 class="mb-4 font-semibold text-gray-800">Choose a Service</h2>
            @error('service_id') <p class="mb-3 text-sm text-red-600">{{ $message }}</p> @enderror

            @if($services->isEmpty())
                <p class="text-gray-400">No services available yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($services as $service)
                        <button type="button"
                                @click="selectService({{ $service->id }}, {{ $service->duration_minutes }})"
                                :class="selectedService == {{ $service->id }} ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-500' : 'border-gray-200 bg-white hover:border-brand-300'"
                                class="w-full rounded-xl border px-5 py-4 text-left transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $service->name }}</p>
                                    <p class="mt-0.5 text-sm text-gray-500">{{ $service->duration_minutes }} minutes</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($service->price !== null)
                                        <span class="text-base font-bold text-brand-700">{{ number_format($service->price, 2) }}</span>
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
                        class="w-full rounded-xl bg-brand-600 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    Continue
                </button>
            </div>
        </div>

        @if($employees->isNotEmpty())
        {{-- Step 2: Staff (only when salon has employees) --}}
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="mb-4 font-semibold text-gray-800">Choose a Staff Member</h2>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                <button type="button" @click="selectEmployee(null)"
                        :class="selectedEmployee === null ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-500' : 'border-gray-200 bg-white hover:border-brand-300'"
                        class="flex flex-col items-center rounded-xl border px-3 py-4 transition-all">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-2xl">🎲</div>
                    <p class="mt-2 text-sm font-medium text-gray-700">Any Staff</p>
                    <p class="text-xs text-gray-400">No preference</p>
                </button>

                @foreach($employees as $employee)
                    <button type="button" @click="selectEmployee({{ $employee->id }})"
                            :class="selectedEmployee == {{ $employee->id }} ? 'border-brand-500 bg-brand-50 ring-2 ring-brand-500' : 'border-gray-200 bg-white hover:border-brand-300'"
                            class="flex flex-col items-center rounded-xl border px-3 py-4 transition-all">
                        @if($employee->avatar_url)
                            <img src="{{ $employee->avatar_url }}" alt="{{ $employee->name }}" class="h-14 w-14 rounded-full object-cover">
                        @else
                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-brand-400 to-brand-600 text-xl font-bold text-white">
                                {{ $employee->initials() }}
                            </div>
                        @endif
                        <p class="mt-2 text-sm font-semibold text-gray-800">{{ $employee->name }}</p>
                        @if($employee->specialties)
                            <p class="mt-0.5 text-xs text-gray-400 text-center">{{ $employee->specialties }}</p>
                        @endif
                    </button>
                @endforeach
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="prevStep()" class="flex-1 rounded-xl border border-gray-300 py-3.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Back</button>
                <button type="button" @click="nextStep()" class="flex-1 rounded-xl bg-brand-600 py-3.5 text-sm font-semibold text-white hover:bg-brand-700 transition-colors">Continue</button>
            </div>
        </div>
        @endif

        {{-- Step 3 (or 2 without employees): Date & Time --}}
        <div x-show="currentStep === dateTimeStep" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="mb-4 font-semibold text-gray-800">Pick a Date & Time</h2>
            @error('time') <p class="mb-3 rounded-lg bg-red-50 px-4 py-2 text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Date</label>
                    <input type="date" x-model="selectedDate" :min="today()"
                           @change="loadSlots()"
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Times</label>
                    <div class="min-h-[60px]">
                        <p x-show="slotsState === 'idle'" class="text-sm text-gray-400">Select a date to see available times.</p>
                        <p x-show="slotsState === 'loading'" class="text-sm text-gray-400">Loading available times…</p>
                        <p x-show="slotsState === 'empty'" class="text-sm text-gray-400">No available times for this date.</p>
                        <p x-show="slotsState === 'error'" class="text-sm text-red-500">Could not load available times. Please try again.</p>
                        <div x-show="slotsState === 'loaded'" class="grid grid-cols-3 gap-2 sm:grid-cols-4">
                            <template x-for="slot in slots" :key="slot">
                                <button type="button" @click="selectedTime = slot"
                                        :class="selectedTime === slot ? 'bg-brand-600 text-white border-brand-600' : 'border-gray-200 text-gray-700 hover:border-brand-400 hover:bg-brand-50'"
                                        class="rounded-lg border px-3 py-2.5 text-sm font-medium transition-colors"
                                        x-text="slot"></button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="prevStep()" class="flex-1 rounded-xl border border-gray-300 py-3.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Back</button>
                <button type="button" @click="nextStep()" :disabled="!selectedTime"
                        class="flex-1 rounded-xl bg-brand-600 py-3.5 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    Continue
                </button>
            </div>
        </div>

        {{-- Step 4 (or 3 without employees): Contact Info --}}
        <div x-show="currentStep === infoStep" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="mb-4 font-semibold text-gray-800">Your Details</h2>

            {{-- Booking summary --}}
            <div class="mb-5 rounded-xl bg-brand-50 px-5 py-4 text-sm">
                <p class="font-semibold text-brand-900 mb-1">Booking Summary</p>
                <p class="text-brand-700"><span class="font-medium" x-text="serviceName"></span></p>
                <p class="text-brand-600 mt-0.5" x-show="selectedDate && selectedTime">
                    <span x-text="formatDate(selectedDate)"></span> at <span x-text="selectedTime"></span>
                </p>
                <p class="text-brand-600 mt-0.5" x-show="staffName" x-text="'Staff: ' + staffName"></p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                               placeholder="Your name">
                        @error('customer_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required
                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                               placeholder="+963 ...">
                        @error('customer_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                           placeholder="you@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="notes" rows="2"
                              class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"
                              placeholder="Any special requests?">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="prevStep()" class="rounded-xl border border-gray-300 px-5 py-3.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Back</button>
                <button type="submit" class="flex-1 rounded-xl bg-brand-600 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700 transition-colors">
                    Confirm Booking
                </button>
            </div>
        </div>
    </form>
</div>

@push('head')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function bookingWizard() {
    const hasEmployees = @json($employees->isNotEmpty());
    const slotsUrl = @json(route('public.booking.slots'));
    const preselectedService = @json(request('service'));

    const serviceNames = @json($services->pluck('name', 'id'));
    const staffNames = @json($employees->pluck('name', 'id'));

    return {
        currentStep: 1,
        dateTimeStep: hasEmployees ? 3 : 2,
        infoStep: hasEmployees ? 4 : 3,

        selectedService: preselectedService || null,
        selectedServiceDuration: null,
        selectedEmployee: null,
        selectedDate: '',
        selectedTime: '',

        slots: [],
        slotsState: 'idle', // idle | loading | loaded | empty | error

        get serviceName() {
            return this.selectedService ? (serviceNames[this.selectedService] || '') : '';
        },
        get staffName() {
            if (!this.selectedEmployee) return '';
            return staffNames[this.selectedEmployee] || '';
        },

        init() {
            if (preselectedService) {
                this.selectedService = preselectedService;
                if (hasEmployees) {
                    this.currentStep = 2;
                } else {
                    this.currentStep = 2;
                }
            }
        },

        selectService(id, duration) {
            this.selectedService = id;
            this.selectedServiceDuration = duration;
        },

        selectEmployee(id) {
            this.selectedEmployee = id;
        },

        nextStep() {
            this.currentStep++;
        },

        prevStep() {
            this.currentStep--;
            if (this.currentStep === this.dateTimeStep) {
                this.selectedTime = '';
            }
        },

        today() {
            return new Date().toISOString().split('T')[0];
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        },

        loadSlots() {
            if (!this.selectedService || !this.selectedDate) return;

            this.selectedTime = '';
            this.slots = [];
            this.slotsState = 'loading';

            let url = `${slotsUrl}?service_id=${encodeURIComponent(this.selectedService)}&date=${encodeURIComponent(this.selectedDate)}`;
            if (this.selectedEmployee) {
                url += `&employee_id=${encodeURIComponent(this.selectedEmployee)}`;
            }

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    if (!data.slots || data.slots.length === 0) {
                        this.slots = [];
                        this.slotsState = 'empty';
                        return;
                    }
                    this.slots = data.slots;
                    this.slotsState = 'loaded';
                })
                .catch(() => {
                    this.slotsState = 'error';
                });
        },
    };
}
</script>
@endpush
@endsection
