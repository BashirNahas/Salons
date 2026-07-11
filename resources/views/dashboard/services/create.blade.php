@extends('layouts.dashboard')

@section('title', __('Add Service'))
@section('page-title', __('Add Service'))

@section('dashboard-content')
<div class="mx-auto max-w-xl">
    <x-card>
        <form method="POST" action="{{ route('dashboard.services.store') }}">
            @csrf
            @include('dashboard.services._form')

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <x-btn href="{{ route('dashboard.services.index') }}" variant="secondary">{{ __('Cancel') }}</x-btn>
                <x-btn>{{ __('Create Service') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>
@endsection
