<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Edit Division') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.divisions.update', $division) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="division_name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <svg class="h-4 w-4 mr-1 text-[#1e6031]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    Division Name
                                </label>
                                <input 
                                    type="text" 
                                    name="division_name" 
                                    id="division_name" 
                                    value="{{ old('division_name', $division->division_name) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50"
                                    required
                                >
                                @error('division_name')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="division_abbr" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <svg class="h-4 w-4 mr-1 text-[#1e6031]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                    Abbreviation
                                </label>
                                <input 
                                    type="text" 
                                    name="division_abbr" 
                                    id="division_abbr" 
                                    value="{{ old('division_abbr', $division->division_abbr) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50"
                                    required
                                >
                                @error('division_abbr')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="office_id" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <svg class="h-4 w-4 mr-1 text-[#1e6031]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Office
                                </label>
                                <select 
                                    name="office_id" 
                                    id="office_id" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50"
                                    required
                                >
                                    <option value="">Select an Office</option>
                                    @foreach($offices as $office)
                                        <option value="{{ $office->id }}" {{ (old('office_id', $division->office_id) == $office->id) ? 'selected' : '' }}>{{ $office->office_name }}</option>
                                    @endforeach
                                </select>
                                @error('office_id')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="division_code" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <svg class="h-4 w-4 mr-1 text-[#1e6031]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                    Division Code
                                </label>
                                <input 
                                    type="text" 
                                    name="division_code" 
                                    id="division_code" 
                                    value="{{ old('division_code', $division->division_code) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50"
                                    required
                                >
                                @error('division_code')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="division_isactive" 
                                    id="division_isactive" 
                                    value="1"
                                    class="rounded border-gray-300 text-[#1e6031] shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50"
                                    {{ old('division_isactive', $division->division_isactive) ? 'checked' : '' }}
                                >
                                <label for="division_isactive" class="ml-2 block text-sm text-gray-900 flex items-center">
                                    <svg class="h-4 w-4 mr-1 text-[#1e6031]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Active
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.divisions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                <svg class="h-5 w-5 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-6 py-2 rounded-lg transition duration-300 shadow-md hover:shadow-lg flex items-center">
                                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Division
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Display success messages from session
        @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#1e6031'
        });
        @endif
        
        // Display error messages from session
        @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#1e6031'
        });
        @endif
    </script>
</x-app-layout>