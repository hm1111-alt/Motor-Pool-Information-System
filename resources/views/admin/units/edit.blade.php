<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Edit Unit') }}
            </h2>
            <a href="{{ route('admin.units.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Units
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="unitForm" action="{{ route('admin.units.update', $unit) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="unit_name" class="block text-sm font-medium text-gray-700 mb-1">Unit Name *</label>
                                <input type="text" name="unit_name" id="unit_name" value="{{ old('unit_name', $unit->unit_name) }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="unit_abbr" class="block text-sm font-medium text-gray-700 mb-1">Abbreviation *</label>
                                <input type="text" name="unit_abbr" id="unit_abbr" value="{{ old('unit_abbr', $unit->unit_abbr) }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Division *</label>
                                <select name="division_id" id="division_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="">Select Division</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id', $unit->division_id) == $division->id ? 'selected' : '' }}>
                                            {{ $division->division_name }} ({{ $division->office->office_abbr }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="unit_code" class="block text-sm font-medium text-gray-700 mb-1">Code *</label>
                                <input type="text" name="unit_code" id="unit_code" value="{{ old('unit_code', $unit->unit_code) }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <div class="flex items-center">
                                    <input type="hidden" name="unit_isactive" value="0">
                                    <input type="checkbox" name="unit_isactive" id="unit_isactive" value="1" 
                                        {{ old('unit_isactive', $unit->unit_isactive) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-[#1e6031] shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <label for="unit_isactive" class="ml-2 block text-sm text-gray-900">Active</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.units.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                Cancel
                            </a>
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300">
                                Update Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>