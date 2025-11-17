@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Travel Order
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900">
                    <!-- Page Title -->
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-800">Create Travel Order</h1>
                        <p class="text-gray-600 mt-1">Fill in the details below to submit a new travel request</p>
                    </div>
                    
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 transition duration-200"
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
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 transition duration-200"
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
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 transition duration-200"
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
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 transition duration-200"
                                placeholder="Enter your destination..."
                                required
                            >
                            @error('destination')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Departure Time -->
                        <div class="mb-8">
                            <label for="departure_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Departure Time
                            </label>
                            <input 
                                type="time" 
                                id="departure_time" 
                                name="departure_time" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 transition duration-200"
                                required
                            >
                            @error('departure_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('travel-orders.index', ['tab' => 'pending']) }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-md transition duration-200">
                                Back
                            </a>
                            <button 
                                type="submit" 
                                class="bg-[#1e6031] hover:bg-[#164f2a] text-white font-medium py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e6031] transition duration-300 shadow-sm hover:shadow-md"
                                id="submitBtn"
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
                confirmButtonColor: '#1e6031'
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
                confirmButtonColor: '#1e6031'
            });
        @endif

        // Client-side form validation with SweetAlert
        document.getElementById('travelOrderForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            // Get form values
            const purpose = document.getElementById('purpose').value.trim();
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;
            const destination = document.getElementById('destination').value.trim();
            const departureTime = document.getElementById('departure_time').value;
            
            // Validate required fields
            if (!purpose || !dateFrom || !dateTo || !destination || !departureTime) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please fill in all required fields.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#1e6031'
                });
                return;
            }
            
            // Validate date range
            if (new Date(dateTo) < new Date(dateFrom)) {
                Swal.fire({
                    title: 'Error!',
                    text: 'End date must be after or equal to start date.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#1e6031'
                });
                return;
            }
            
            // Show loading indicator
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Submitting...';
            submitBtn.disabled = true;
            
            // Submit the form via AJAX
            const formData = new FormData(this);
            
            fetch('{{ route('travel-orders.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e6031'
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'An error occurred while submitting the travel order.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e6031'
                    });
                    
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#1e6031'
                });
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
@endsection