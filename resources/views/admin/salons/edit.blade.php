@extends('layouts.admin')

@section('title', 'Edit Salon')
@section('page-title', 'Edit Salon')

@section('admin-content')
<div class="max-w-3xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
    <form method="POST" action="{{ route('admin.salons.update', $salon) }}">
        @csrf
        @method('PUT')
        @include('admin.salons._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('admin.salons.index') }}" class="rounded-lg px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">Cancel</a>
            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Save Changes</button>
        </div>
    </form>
</div>
@endsection
