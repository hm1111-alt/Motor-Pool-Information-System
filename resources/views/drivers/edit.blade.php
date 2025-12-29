@extends('layouts.motorpool-admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Driver') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('drivers.update', $driver) }}">
                        @csrf
                        @method('PUT')
                        

                        
                        @if ($errors->any())
                            <div class="mb-4 rounded-md bg-red-50 p-4">
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
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $driver->first_name) }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $driver->last_name) }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <!-- Middle Initial -->
                            <div>
                                <label for="middle_initial" class="block text-sm font-medium text-gray-700">Middle Initial</label>
                                <input type="text" name="middle_initial" id="middle_initial" value="{{ old('middle_initial', $driver->middle_initial) }}" maxlength="10"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <!-- Extension Name -->
                            <div>
                                <label for="ext_name" class="block text-sm font-medium text-gray-700">Extension Name</label>
                                <input type="text" name="ext_name" id="ext_name" value="{{ old('ext_name', $driver->ext_name) }}" maxlength="50"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <!-- Sex -->
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                                <select name="sex" id="sex" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Sex</option>
                                    <option value="Male" {{ old('sex', $driver->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', $driver->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            
                            <!-- Contact Number -->
                            <div>
                                <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', $driver->contact_number) }}" maxlength="50"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <!-- Position -->
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                                <input type="text" name="position" id="position" value="{{ old('position', $driver->position) }}" required maxlength="255"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <!-- Official Station -->
                            <div>
                                <label for="official_station" class="block text-sm font-medium text-gray-700">Official Station</label>
                                <input type="text" name="official_station" id="official_station" value="{{ old('official_station', $driver->official_station) }}" maxlength="255"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <!-- Availability Status -->
                            <div class="md:col-span-2">
                                <label for="availability_status" class="block text-sm font-medium text-gray-700">Availability Status</label>
                                <select name="availability_status" id="availability_status" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Availability Status</option>
                                    <option value="Available" {{ old('availability_status', $driver->availability_status) == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Not Available" {{ old('availability_status', $driver->availability_status) == 'Not Available' ? 'selected' : '' }}>Not Available</option>
                                    <option value="On Duty" {{ old('availability_status', $driver->availability_status) == 'On Duty' ? 'selected' : '' }}>On Duty</option>
                                    <option value="Off Duty" {{ old('availability_status', $driver->availability_status) == 'Off Duty' ? 'selected' : '' }}>Off Duty</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('drivers.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#1e6031] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Driver
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection