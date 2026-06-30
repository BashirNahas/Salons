@extends('layouts.public')

@section('title', 'Book an Appointment — ' . $salon->name)

@section('public-content')
<div class="mx-auto max-w-xl rounded-2xl bg-white p-8 shadow-sm ring-1 ring-gray-200">
    <h1 class="text-2xl font-bold text-gray-900">Book an Appointment</h1>
    <p class="mt-1 text-sm text-gray-500">{{ $salon->name }}</p>

    <form method="POST" action="{{ route('public.booking.store') }}" class="mt-6 space-y-4" id="booking-form">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Service</label>
            <select name="service_id" id="service_id" required
                    class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                <option value="">Select a service…</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" {{ (old('service_id', request('service')) == $service->id) ? 'selected' : '' }}>
                        {{ $service->name }} ({{ $service->duration_minutes }} min)
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" name="date" id="date" required min="{{ now()->format('Y-m-d') }}" value="{{ old('date') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Available Times</label>
            <div id="slots" class="mt-2 grid grid-cols-3 gap-2 text-sm">
                <p class="col-span-3 text-gray-400">Choose a service and date to see available times.</p>
            </div>
            <input type="hidden" name="time" id="time" value="{{ old('time') }}">
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Your Name</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                       class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email (optional)</label>
            <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Notes (optional)</label>
            <textarea name="notes" rows="2" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-semibold text-white hover:bg-brand-700">
            Request Booking
        </button>
    </form>
</div>

@push('head')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviceSelect = document.getElementById('service_id');
    const dateInput = document.getElementById('date');
    const slotsContainer = document.getElementById('slots');
    const timeInput = document.getElementById('time');
    const slotsUrl = @json(route('public.booking.slots'));

    function loadSlots() {
        const serviceId = serviceSelect.value;
        const date = dateInput.value;
        timeInput.value = '';

        if (!serviceId || !date) {
            slotsContainer.innerHTML = '<p class="col-span-3 text-gray-400">Choose a service and date to see available times.</p>';
            return;
        }

        slotsContainer.innerHTML = '<p class="col-span-3 text-gray-400">Loading…</p>';

        fetch(`${slotsUrl}?service_id=${encodeURIComponent(serviceId)}&date=${encodeURIComponent(date)}`)
            .then((res) => res.json())
            .then((data) => {
                if (!data.slots || data.slots.length === 0) {
                    slotsContainer.innerHTML = '<p class="col-span-3 text-gray-400">No available times for this date.</p>';
                    return;
                }

                slotsContainer.innerHTML = '';
                data.slots.forEach((slot) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = slot;
                    btn.className = 'rounded-lg border border-gray-300 px-3 py-2 hover:border-brand-500 hover:text-brand-600';
                    btn.addEventListener('click', () => {
                        timeInput.value = slot;
                        slotsContainer.querySelectorAll('button').forEach((b) => b.classList.remove('bg-brand-600', 'text-white', 'border-brand-600'));
                        btn.classList.add('bg-brand-600', 'text-white', 'border-brand-600');
                    });
                    slotsContainer.appendChild(btn);
                });
            })
            .catch(() => {
                slotsContainer.innerHTML = '<p class="col-span-3 text-red-500">Could not load available times.</p>';
            });
    }

    serviceSelect.addEventListener('change', loadSlots);
    dateInput.addEventListener('change', loadSlots);

    if (serviceSelect.value && dateInput.value) {
        loadSlots();
    }
});
</script>
@endpush
@endsection
