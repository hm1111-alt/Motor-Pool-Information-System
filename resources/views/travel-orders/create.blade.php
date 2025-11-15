<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Travel Order
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('travel-orders.store') }}" method="POST" id="travelOrderForm">
                        @csrf
                        
                        <!-- Purpose of Travel -->
                        <div class="mb-6">
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                                Purpose of the Travel
                            </label>
                            <textarea 
                                id="purpose" 
                                name="purpose" 
                                rows="3" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009639] focus:ring focus:ring-[#009639] focus:ring-opacity-50"
                                placeholder="Enter the purpose of your travel..."
                                required
                            ></textarea>
                            @error('purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Date From -->
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date From
                                </label>
                                <input 
                                    type="date" 
                                    id="date_from" 
                                    name="date_from" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009639] focus:ring focus:ring-[#009639] focus:ring-opacity-50"
                                    required
                                >
                                @error('date_from')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date To -->
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date To
                                </label>
                                <input 
                                    type="date" 
                                    id="date_to" 
                                    name="date_to" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009639] focus:ring focus:ring-[#009639] focus:ring-opacity-50"
                                    required
                                >
                                @error('date_to')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Destination -->
                        <div class="mb-6">
                            <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">
                                Destination
                            </label>
                            <input 
                                type="text" 
                                id="destination" 
                                name="destination" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009639] focus:ring focus:ring-[#009639] focus:ring-opacity-50"
                                placeholder="Enter your destination..."
                                required
                            >
                            @error('destination')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Departure Time -->
                        <div class="mb-6">
                            <label for="departure_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Departure Time
                            </label>
                            <input 
                                type="time" 
                                id="departure_time" 
                                name="departure_time" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009639] focus:ring focus:ring-[#009639] focus:ring-opacity-50"
                                required
                            >
                            @error('departure_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('travel-orders.index', ['tab' => 'pending']) }}" class="mr-4 text-gray-600 hover:text-gray-900">
                                Back
                            </a>
                            <button 
                                type="submit" 
                                class="bg-[#009639] hover:bg-[#1e6031] text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009639]"
                            >
                                Submit Travel Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Scripts -->
    <script>
        // Display success message if it exists
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#009639'
            });
        @endif

        // Display error messages if they exist
        @if($errors->any())
            let errorMessages = '';
            @foreach($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach
            
            Swal.fire({
                title: 'Error!',
                text: errorMessages,
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#009639'
            });
        @endif
    </script>
</x-app-layout>