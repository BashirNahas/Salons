@if (session('status'))
    <div role="status" class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ session('status') }}</span>
    </div>
@endif

@if (session('generated_credentials'))
    @php($creds = session('generated_credentials'))
    <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        <p class="font-semibold">{{ __('Owner account created — share these credentials securely:') }}</p>
        <ul class="mt-1.5 space-y-1">
            <li><span class="font-medium">{{ __('Email') }}:</span> <span dir="ltr">{{ $creds['email'] }}</span></li>
            <li><span class="font-medium">{{ __('Password') }}:</span> <code dir="ltr" class="rounded bg-amber-100 px-1.5 py-0.5">{{ $creds['password'] }}</code></li>
            <li><span class="font-medium">{{ __('Login URL') }}:</span> <a dir="ltr" class="underline" href="{{ $creds['login_url'] }}">{{ $creds['login_url'] }}</a></li>
        </ul>
    </div>
@endif

@if ($errors->any())
    <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="flex items-center gap-2 font-semibold">
            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L9.13 3.378c.866-1.5 3.032-1.5 3.898 0l6.276 12.748zM12 15.75h.007v.008H12v-.008z"/></svg>
            {{ __('Please fix the following:') }}
        </p>
        <ul class="mt-1.5 list-inside list-disc space-y-0.5 ps-6">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
