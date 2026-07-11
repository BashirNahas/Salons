@extends('layouts.base')

@section('title', $salon->name)

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
        </div>

        <h1 class="mt-6 text-xl font-semibold tracking-tight text-gray-900">{{ $salon->name }}</h1>

        <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5">
            <p class="text-sm leading-6 text-gray-600">
                This salon is currently unavailable. Please contact the salon for more information.
            </p>
            <p dir="rtl" class="mt-4 border-t border-gray-100 pt-4 text-sm leading-7 text-gray-600">
                هذا الصالون غير متاح حاليًا. يُرجى التواصل مع الصالون لمزيد من المعلومات.
            </p>

            @if ($salon->phone)
                <a href="tel:{{ $salon->phone }}" dir="ltr"
                   class="mt-5 inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    {{ $salon->phone }}
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
