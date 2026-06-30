@extends('layouts.dashboard')

@section('title', 'Blocked Dates')
@section('page-title', 'Blocked Dates / Holidays')

@section('dashboard-content')
<div class="mb-6 max-w-md rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
    <form method="POST" action="{{ route('dashboard.blocked-dates.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" name="date" required min="{{ now()->format('Y-m-d') }}"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Reason (optional)</label>
            <input type="text" name="reason" placeholder="e.g. Public holiday"
                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Block Date</button>
    </form>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <tr>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Reason</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($blockedDates as $blocked)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $blocked->date->format('M j, Y') }}</td>
                    <td class="px-4 py-3">{{ $blocked->reason ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('dashboard.blocked-dates.destroy', $blocked) }}" onsubmit="return confirm('Unblock this date?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 hover:underline">Remove</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-gray-400">No blocked dates.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $blockedDates->links() }}</div>
@endsection
