@extends('layouts.motorpool-admin')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    ADD NEW VEHICLE MAINTENANCE
                </h2>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('vehicle-maintenance.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Vehicle -->
                            <div>
                                <label for="vehicle_id" class="block text-sm font-medium text-gray-700">Vehicle <span class="text-red-500">*</span></label>
                                <select name="vehicle_id" id="vehicle_id" required
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                    <option value="">Select Vehicle</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}" {{ (old('vehicle_id') == $vehicle->id) || (request('vehicle_id') && request('vehicle_id') == $vehicle->id) ? 'selected' : '' }}>
                                            {{ $vehicle->plate_number }} - {{ $vehicle->model }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Date Started -->
                            <div>
                                <label for="odometer_reading" class="block text-sm font-medium text-gray-700">Odometer Reading (km)</label>
                                <input type="number" name="odometer_reading" id="odometer_reading" value="{{ old('odometer_reading') }}"
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm" min="0">
                                @error('odometer_reading')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="date_started" class="block text-sm font-medium text-gray-700">Date Started <span class="text-red-500">*</span></label>
                                <input type="date" name="date_started" id="date_started" value="{{ old('date_started') }}" required
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                @error('date_started')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Make or Type -->
                            <div>
                                <label for="make_or_type" class="block text-sm font-medium text-gray-700">Make or Type <span class="text-red-500">*</span></label>
                                <input type="text" name="make_or_type" id="make_or_type" value="{{ old('make_or_type') }}" required
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                @error('make_or_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Person/Office/Unit -->
                            <div>
                                <label for="person_office_unit" class="block text-sm font-medium text-gray-700">Person/Office/Unit <span class="text-red-500">*</span></label>
                                <input type="text" name="person_office_unit" id="person_office_unit" value="{{ old('person_office_unit') }}" required
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                @error('person_office_unit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Place -->
                            <div>
                                <label for="place" class="block text-sm font-medium text-gray-700">Place <span class="text-red-500">*</span></label>
                                <input type="text" name="place" id="place" value="{{ old('place') }}" required
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                @error('place')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Mechanic Assigned -->
                            <div>
                                <label for="mechanic_assigned" class="block text-sm font-medium text-gray-700">Mechanic Assigned <span class="text-red-500">*</span></label>
                                <input type="text" name="mechanic_assigned" id="mechanic_assigned" value="{{ old('mechanic_assigned') }}" required
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                @error('mechanic_assigned')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Conforme -->
                            <div>
                                <label for="conforme" class="block text-sm font-medium text-gray-700">Conforme <span class="text-red-500">*</span></label>
                                <input type="text" name="conforme" id="conforme" value="{{ old('conforme') }}" required
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                @error('conforme')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                    <option value="">Select Status</option>
                                    <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Date Completed -->
                            <div>
                                <label for="date_completed" class="block text-sm font-medium text-gray-700">Date Completed</label>
                                <input type="date" name="date_completed" id="date_completed" value="{{ old('date_completed') }}"
                                       class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">
                                @error('date_completed')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Nature of Work -->
                        <div class="mt-6">
                            <label for="nature_of_work" class="block text-sm font-medium text-gray-700">Nature of Work <span class="text-red-500">*</span></label>
                            <textarea name="nature_of_work" id="nature_of_work" rows="4" required
                                      class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">{{ old('nature_of_work') }}</textarea>
                            @error('nature_of_work')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Materials/Parts -->
                        <div class="mt-6">
                            <label for="materials_parts" class="block text-sm font-medium text-gray-700">Materials/Parts</label>
                            <textarea name="materials_parts" id="materials_parts" rows="3"
                                      class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[#1e6031] focus:border-[#1e6031] sm:text-sm">{{ old('materials_parts') }}</textarea>
                            @error('materials_parts')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('vehicle-maintenance.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Save Maintenance Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection