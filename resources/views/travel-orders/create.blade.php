@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Create Travel Order') }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Page Header -->
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-800">Create New Travel Request</h1>
                        <p class="text-gray-600 mt-1">Fill in the details below to submit a new travel request</p>
                    </div>
                    
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        There were {{ $errors->count() }} error(s) with your submission
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('travel-orders.store') }}">
                        @csrf
                        
                        <div class="space-y-6">
                            <div>
                                <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                                <input type="text" name="destination" id="destination" value="{{ old('destination') }}" required
                                    class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm transition duration-200">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                                    <input type="date" name="date_from" id="date_from" value="{{ old('date_from') }}" required
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm transition duration-200">
                                </div>
                                
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                                    <input type="date" name="date_to" id="date_to" value="{{ old('date_to') }}" required
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm transition duration-200">
                                </div>
                                
                                <div>
                                    <label for="departure_time" class="block text-sm font-medium text-gray-700 mb-1">Departure Time</label>
                                    <input type="time" name="departure_time" id="departure_time" value="{{ old('departure_time') }}"
                                        class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm transition duration-200">
                                </div>
                            </div>
                            
                            <div>
                                <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                                <textarea name="purpose" id="purpose" rows="4" required
                                    class="block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm transition duration-200">{{ old('purpose') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Provide a brief description of the purpose of your travel</p>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex items-center justify-end space-x-3">
                            <a href="{{ route('travel-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#1e6031] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create Travel Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection