@if (session('status'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        {{ session('status') }}
    </div>
@endif

@if (session('generated_credentials'))
    @php($creds = session('generated_credentials'))
    <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        <p class="font-semibold">Owner account created — share these credentials securely:</p>
        <ul class="mt-1 space-y-0.5">
            <li><span class="font-medium">Email:</span> {{ $creds['email'] }}</li>
            <li><span class="font-medium">Password:</span> <code class="rounded bg-amber-100 px-1">{{ $creds['password'] }}</code></li>
            <li><span class="font-medium">Login URL:</span> <a class="underline" href="{{ $creds['login_url'] }}">{{ $creds['login_url'] }}</a></li>
        </ul>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-semibold">Please fix the following:</p>
        <ul class="mt-1 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
