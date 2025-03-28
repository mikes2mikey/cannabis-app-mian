@php
    $qrCode = auth()->user()->getQrCode();
@endphp

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('QR Code') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Your unique QR code for membership identification.') }}
        </p>
    </header>

    <div class="bg-white p-4 sm:p-8 shadow sm:rounded-lg">
        <div class="max-w-xl">
            @if ($qrCode)
                <div class="flex flex-col items-center space-y-4">
                    <div class="border p-4 rounded-lg bg-white">
                        {!! $qrCode !!}
                    </div>
                    <p class="text-sm text-gray-600">
                        {{ __('Present this QR code when requested for membership verification.') }}
                    </p>
                </div>
            @else
                <div class="flex flex-col items-center space-y-4">
                    <p class="text-gray-600">
                        {{ __('No QR code has been generated yet.') }}
                    </p>
                    <form method="POST" action="{{ route('profile.generate-qr') }}">
                        @csrf
                        <x-primary-button>
                            {{ __('Generate QR Code') }}
                        </x-primary-button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</section> 