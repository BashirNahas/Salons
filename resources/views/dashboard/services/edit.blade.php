@extends('layouts.dashboard')

@section('title', __('Edit Service'))
@section('page-title', __('Edit Service'))

@section('dashboard-content')
<div class="mx-auto max-w-xl">
    <x-card>
        <form method="POST" action="{{ route('dashboard.services.update', $service) }}">
            @csrf
            @method('PUT')
            @include('dashboard.services._form')

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <x-btn href="{{ route('dashboard.services.index') }}" variant="secondary">{{ __('Cancel') }}</x-btn>
                <x-btn>{{ __('Save Changes') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>
@endsection
