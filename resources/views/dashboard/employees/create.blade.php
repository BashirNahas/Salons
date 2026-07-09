@extends('layouts.dashboard')

@section('title', __('Add Staff Member'))
@section('page-title', __('Add Staff Member'))

@section('dashboard-content')
<div class="mx-auto max-w-xl">
    <x-card>
        <form method="POST" action="{{ route('dashboard.employees.store') }}">
            @csrf
            @include('dashboard.employees._form')

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <x-btn href="{{ route('dashboard.employees.index') }}" variant="secondary">{{ __('Cancel') }}</x-btn>
                <x-btn>{{ __('Add Staff Member') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>
@endsection
