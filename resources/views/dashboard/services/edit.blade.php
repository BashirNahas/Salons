@extends('layouts.dashboard')

@section('title', 'Edit Service')
@section('page-title', 'Edit Service')

@section('dashboard-content')
<div class="max-w-xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
    <form method="POST" action="{{ route('dashboard.services.update', $service) }}">
        @csrf
        @method('PUT')
        @include('dashboard.services._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('dashboard.services.index') }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Cancel</a>
            <button type="submit" class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700">Save Changes</button>
        </div>
    </form>
</div>
@endsection
