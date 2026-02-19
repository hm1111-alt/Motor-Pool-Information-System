@extends('layouts.motorpool-admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Driver</h2>
                    <p class="mt-1 text-sm text-gray-600">Update driver record</p>
                </div>

                <form method="POST" action="{{ route('drivers.update', $driver) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- User Selection -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">User Account</label>
                        <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            <option value="">Select User Account</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $driver->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $driver->first_name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="middle_initial" class="block text-sm font-medium text-gray-700">Middle Initial</label>
                            <input type="text" name="middle_initial" id="middle_initial" value="{{ old('middle_initial', $driver->middle_initial) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]" maxlength="10">
                            @error('middle_initial')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $driver->last_name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_num" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" name="contact_num" id="contact_num" value="{{ old('contact_num', $driver->user->contact_num ?? '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            @error('contact_num')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $driver->user->email ?? '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password (Optional) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password (Leave blank to keep current)</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" id="address" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">{{ old('address', $driver->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position and Station -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                            <input type="text" name="position" id="position" value="{{ old('position', $driver->position) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="official_station" class="block text-sm font-medium text-gray-700">Official Station</label>
                            <input type="text" name="official_station" id="official_station" value="{{ old('official_station', $driver->official_station) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            @error('official_station')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Availability Status -->
                    <div>
                        <label for="availability_status" class="block text-sm font-medium text-gray-700">Availability Status</label>
                        <select name="availability_status" id="availability_status" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            <option value="Available" {{ old('availability_status', $driver->availability_status) == 'Available' ? 'selected' : '' }}>Available</option>
                            <option value="Not Available" {{ old('availability_status', $driver->availability_status) == 'Not Available' ? 'selected' : '' }}>Not Available</option>
                            <option value="On Duty" {{ old('availability_status', $driver->availability_status) == 'On Duty' ? 'selected' : '' }}>On Duty</option>
                            <option value="Off Duty" {{ old('availability_status', $driver->availability_status) == 'Off Duty' ? 'selected' : '' }}>Off Duty</option>
                        </select>
                        @error('availability_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('drivers.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-[#1e6031] border border-transparent rounded-md font-bold text-lg text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Update Driver
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection