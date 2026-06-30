@extends('layouts.admin')

@section('title', 'Salons')
@section('page-title', 'Salons')

@section('admin-content')
<div class="mb-4 flex justify-end">
    <a href="{{ route('admin.salons.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
        + Add Salon
    </a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Subdomain</th>
                <th class="px-4 py-3">Owner</th>
                <th class="px-4 py-3">Bookings</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($salons as $salon)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $salon->name }}</td>
                    <td class="px-4 py-3">
                        <a class="text-brand-600 hover:underline" href="{{ $salon->url() }}" target="_blank">{{ $salon->subdomain() }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $salon->owner->name }} <span class="text-gray-400">({{ $salon->owner->email }})</span></td>
                    <td class="px-4 py-3">{{ $salon->bookings_count }}</td>
                    <td class="px-4 py-3">
                        @if ($salon->is_active)
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                        @else
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.salons.edit', $salon) }}" class="font-medium text-brand-600 hover:underline">Edit</a>
                        <form method="POST" action="{{ route('admin.salons.destroy', $salon) }}" class="inline" onsubmit="return confirm('Delete this salon and its owner account? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-3 font-medium text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-400">No salons yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $salons->links() }}</div>
@endsection
