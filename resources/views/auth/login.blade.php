@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <!-- Motorpool Logo -->
            <div class="mx-auto h-24 w-24 rounded-full bg-white flex items-center justify-center border-4 border-[#009639] shadow-lg">
                <svg class="h-16 w-16 text-[#009639]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" />
                    <circle cx="12" cy="12" r="10" stroke-width="1.5" fill="none" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Motorpool Information System
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Professional fleet management solution
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email Address
                </label>
                <div class="mt-1">
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#009639] focus:border-[#009639] sm:text-sm"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <div class="mt-1">
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        autocomplete="current-password" 
                        required 
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#009639] focus:border-[#009639] sm:text-sm"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input 
                    id="remember_me" 
                    name="remember" 
                    type="checkbox" 
                    class="h-4 w-4 text-[#009639] focus:ring-[#009639] border-gray-300 rounded"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                    Remember me
                </label>
            </div>

            <div>
                <button 
                    type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#009639] hover:bg-[#1e6031] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009639]"
                >
                    Sign in
                </button>
            </div>

            <!-- Forgot Password Link -->
            <div class="text-sm text-center">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="font-medium text-[#009639] hover:text-[#1e6031]">
                        Forgot your password?
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection