<title>TVKU E-CUTI | {{ $title }}</title>
<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/login">
                <img class="h-20 fill-current text-gray-500" src="{{ asset('img/tvku_logo_ori.png') }}">
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Lupa password anda? Silahkan isi email yang terdaftar pada akun dan kami akan kirimkan link untuk mengganti password anda melaui email tersebut. (Cek SPAM).') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                {{-- <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">Sudah punya akun?</a> --}}
                <x-button class="ml-3 btn btn-primary">
                    {{ __('Kirim Link Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
