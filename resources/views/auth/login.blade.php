@extends('layouts.guest')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#1e6031] via-[#164f2a] to-[#0f3d21] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Branding Section -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-[#ffd700] rounded-full blur-xl opacity-30 animate-pulse"></div>
                        <div class="relative h-24 w-24 rounded-full bg-white flex items-center justify-center shadow-2xl border-4 border-[#1e6031] transition-all duration-500 hover:rotate-6">
                            <svg class="h-14 w-14 text-[#1e6031]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <h1 class="mt-6 text-3xl font-extrabold text-white tracking-tight">
                    MOTOR POOL SYSTEM
                </h1>
                <p class="mt-2 text-lg text-[#e0e0e0] font-medium">
                    Central Luzon State University
                </p>
                <div class="mt-4 w-24 h-1 bg-[#ffd700] mx-auto rounded-full"></div>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 sm:p-10 transition-all duration-500 hover:shadow-3xl">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Welcome Back
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Sign in to access your account
                    </p>
                </div>

                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-5">
                        <!-- Email Field -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-gray-700">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Email Address
                                </div>
                            </label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="email" name="email" type="email" autocomplete="email" required 
                                    class="appearance-none block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm transition duration-300 bg-gray-50 hover:bg-white"
                                    placeholder="you@clsu.edu.ph">
                            </div>
                        </div>
                        
                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-gray-700">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Password
                                </div>
                            </label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password" name="password" type="password" autocomplete="current-password" required 
                                    class="appearance-none block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm transition duration-300 bg-gray-50 hover:bg-white"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" 
                                class="h-5 w-5 text-[#1e6031] focus:ring-[#1e6031] border-gray-300 rounded cursor-pointer transition duration-200">
                            <label for="remember_me" class="ml-3 block text-sm font-medium text-gray-700 cursor-pointer">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="font-semibold text-[#1e6031] hover:text-[#164f2a] transition duration-200 hover:underline">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button type="submit" 
                            class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-[#1e6031] to-[#164f2a] hover:from-[#164f2a] hover:to-[#0f3d21] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e6031] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-6 w-6 text-[#ffd700] group-hover:text-yellow-300 transition duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </span>
                            SIGN IN
                        </button>
                    </div>
                </form>
                
                <!-- Footer -->
                <div class="mt-8 text-center text-xs text-gray-500 border-t border-gray-100 pt-6">
                    <p>© {{ date('Y') }} Central Luzon State University. All rights reserved.</p>
                    <p class="mt-1">Motor Pool Information System</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Custom Styles -->
    <style>
        @keyframes pulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.5; }
        }
        .animate-pulse {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
@endsection