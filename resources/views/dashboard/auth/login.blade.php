@extends('layouts.guest')

@section('title', 'Salon Login')
@section('page-title', currentSalon()->name)
@section('page-subtitle', 'Sign in to manage your salon')

@section('guest-content')
<form method="POST" action="{{ route('dashboard.login') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" required
               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <label class="flex items-center gap-2 text-sm text-gray-600">
        <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600">
        Remember me
    </label>
    <button type="submit" class="w-full rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">
        Sign In
    </button>
</form>
@endsection
