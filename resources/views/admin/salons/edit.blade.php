@extends('layouts.admin')

@section('title', __('Edit Salon'))
@section('page-title', __('Edit Salon'))

@section('admin-content')
<div class="mx-auto max-w-3xl">
    <x-card>
        <form method="POST" action="{{ route('admin.salons.update', $salon) }}">
            @csrf
            @method('PUT')
            @include('admin.salons._form')

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                <x-btn href="{{ route('admin.salons.index') }}" variant="secondary">{{ __('Cancel') }}</x-btn>
                <x-btn variant="dark">{{ __('Save Changes') }}</x-btn>
            </div>
        </form>
    </x-card>
</div>
@endsection
