@extends('layouts.motorpool-admin')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            {{ __('Create Trip Ticket') }}
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('trip-tickets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Trip Tickets
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('trip-tickets.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Itinerary Selection -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Select Itinerary</h3>
                                
                                <div>
                                    <label for="itinerary_id" class="block text-sm font-medium text-gray-700 mb-1">Itinerary *</label>
                                    <select name="itinerary_id" id="itinerary_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" required onchange="updateItineraryDetails()">
                                        <option value="">Select an Itinerary</option>
                                        @foreach($itineraries as $itinerary)
                                            <option value="{{ $itinerary->id }}" 
                                                data-driver="{{ $itinerary->driver?->first_name . ' ' . $itinerary->driver?->last_name }}"
                                                data-vehicle="{{ $itinerary->vehicle?->model ?? 'N/A' }}"
                                                data-destination="{{ $itinerary->destination ?? 'N/A' }}"
                                                data-purpose="{{ $itinerary->purpose ?? 'N/A' }}">
                                                #{{ $itinerary->id }} - {{ $itinerary->driver?->first_name . ' ' . $itinerary->driver?->last_name }} - {{ $itinerary->destination }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('itinerary_id')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Itinerary Details Preview -->
                            <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Itinerary Details</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                                        <div id="driver-preview" class="w-full rounded-lg border-gray-300 bg-gray-100 p-2 min-h-[40px]">Select an itinerary to see details</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle</label>
                                        <div id="vehicle-preview" class="w-full rounded-lg border-gray-300 bg-gray-100 p-2 min-h-[40px]">Select an itinerary to see details</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                                        <div id="destination-preview" class="w-full rounded-lg border-gray-300 bg-gray-100 p-2 min-h-[40px]">Select an itinerary to see details</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                                        <div id="purpose-preview" class="w-full rounded-lg border-gray-300 bg-gray-100 p-2 min-h-[40px]">Select an itinerary to see details</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Add Passengers Section -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Passengers</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="travel_order_id" class="block text-sm font-medium text-gray-700 mb-1">Select Travel Order</label>
                                        <select name="travel_order_id" id="travel_order_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50" onchange="loadPassengersFromTravelOrder()">
                                            <option value="">Select a Travel Order</option>
                                            @foreach($travelOrdersWithoutItinerary as $travelOrder)
                                                <option value="{{ $travelOrder->id }}">
                                                    #{{ $travelOrder->id }} - {{ $travelOrder->employee->first_name ?? 'N/A' }} {{ $travelOrder->employee->last_name ?? 'N/A' }} - {{ Str::limit($travelOrder->purpose, 30) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    

                                </div>
                                

                                
                                <div id="passenger-list" class="mt-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <h4 class="text-md font-medium text-gray-700">Selected Passengers:</h4>
                                        <span id="head-of-party-info" class="text-sm text-gray-500">Head of Party: <span id="current-head">None selected</span></span>
                                    </div>
                                    <ul id="passenger-items" class="list-disc list-inside bg-white p-2 rounded border"></ul>
                                    <input type="hidden" name="passenger_names[]" id="passenger_hidden_inputs">
                                    <input type="hidden" name="head_of_party" id="head_of_party" value="">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-6 py-3 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                Create Trip Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function updateItineraryDetails() {
            const select = document.getElementById('itinerary_id');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption.value) {
                document.getElementById('driver-preview').textContent = selectedOption.getAttribute('data-driver') || 'N/A';
                document.getElementById('vehicle-preview').textContent = selectedOption.getAttribute('data-vehicle') || 'N/A';
                document.getElementById('destination-preview').textContent = selectedOption.getAttribute('data-destination') || 'N/A';
                document.getElementById('purpose-preview').textContent = selectedOption.getAttribute('data-purpose') || 'N/A';
                
                // Automatically add the itinerary creator as a passenger and head of party
                const itineraryId = selectedOption.value;
                if (itineraryId) {
                    fetch(`/api/itineraries/${itineraryId}/creator`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.creator_name) {
                                // Check if creator is already added
                                const existingItems = document.querySelectorAll('#passenger-items li');
                                let alreadyAdded = false;
                                for (let item of existingItems) {
                                    if (item.textContent.includes(data.creator_name)) {
                                        alreadyAdded = true;
                                        break;
                                    }
                                }
                                
                                if (!alreadyAdded) {
                                    addPassengerAutomatically(data.creator_name, true);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching itinerary creator:', error);
                        });
                }
            } else {
                document.getElementById('driver-preview').textContent = 'Select an itinerary to see details';
                document.getElementById('vehicle-preview').textContent = 'Select an itinerary to see details';
                document.getElementById('destination-preview').textContent = 'Select an itinerary to see details';
                document.getElementById('purpose-preview').textContent = 'Select an itinerary to see details';
                // Clear passengers when no itinerary is selected
                document.getElementById('passenger-items').innerHTML = '';
                document.getElementById('head_of_party').value = '';
                document.getElementById('current-head').textContent = 'None selected';
            }
        }
        
        // Load passengers from selected travel order
        function loadPassengersFromTravelOrder() {
            const travelOrderId = document.getElementById('travel_order_id').value;
            
            if (travelOrderId) {
                // Fetch passengers from the API
                fetch(`/api/travel-orders/${travelOrderId}/passengers`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.passengers && data.passengers.length > 0) {
                            data.passengers.forEach(passenger => {
                                // Check if passenger is already selected
                                const existingItems = document.querySelectorAll('#passenger-items li');
                                let alreadyAdded = false;
                                for (let item of existingItems) {
                                    if (item.textContent.includes(passenger.name)) {
                                        alreadyAdded = true;
                                        break;
                                    }
                                }
                                
                                if (!alreadyAdded) {
                                    // Add the first passenger automatically
                                    addPassengerAutomatically(passenger.name, false);
                                    return;
                                }
                            });
                        } else {
                            // Fallback: add the employee who created the travel order
                            const travelOrderSelect = document.getElementById('travel_order_id');
                            const selectedOption = travelOrderSelect.options[travelOrderSelect.selectedIndex];
                            const optionText = selectedOption.text;
                            
                            // Extract the employee name from the option text
                            // Format: #ID - FirstName LastName - Purpose
                            const parts = optionText.split(' - ');
                            if (parts.length >= 2) {
                                const employeeName = parts[1].trim();
                                
                                if (employeeName && employeeName !== 'N/A') {
                                    // Check if passenger is already selected
                                    const existingItems = document.querySelectorAll('#passenger-items li');
                                    let alreadyAdded = false;
                                    for (let item of existingItems) {
                                        if (item.textContent.includes(employeeName)) {
                                            alreadyAdded = true;
                                            break;
                                        }
                                    }
                                    
                                    if (!alreadyAdded) {
                                        addPassengerAutomatically(employeeName, false);
                                    }
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading passengers:', error);
                        
                        // Fallback: add the employee who created the travel order
                        const travelOrderSelect = document.getElementById('travel_order_id');
                        const selectedOption = travelOrderSelect.options[travelOrderSelect.selectedIndex];
                        const optionText = selectedOption.text;
                        
                        // Extract the employee name from the option text
                        // Format: #ID - FirstName LastName - Purpose
                        const parts = optionText.split(' - ');
                        if (parts.length >= 2) {
                            const employeeName = parts[1].trim();
                            
                            if (employeeName && employeeName !== 'N/A') {
                                // Check if passenger is already selected
                                const existingItems = document.querySelectorAll('#passenger-items li');
                                let alreadyAdded = false;
                                for (let item of existingItems) {
                                    if (item.textContent.includes(employeeName)) {
                                        alreadyAdded = true;
                                        break;
                                    }
                                }
                                
                                if (!alreadyAdded) {
                                    addPassengerAutomatically(employeeName, false);
                                }
                            }
                        }
                    });
            }
        }
        

        

        
        // Add passenger automatically (used for itinerary creator)
        function addPassengerAutomatically(passengerName, isHead) {
            // Check if passenger is already added
            const existingItems = document.querySelectorAll('#passenger-items li');
            for (let item of existingItems) {
                if (item.textContent.includes(passengerName)) {
                    if (isHead) {
                        // If this is the creator and they're already added, make them head
                        const headBtn = item.querySelector('button');
                        if (headBtn) {
                            setAsHeadOfParty(passengerName, passengerName, headBtn);
                        }
                    }
                    return;
                }
            }
            
            // Create list item
            const listItem = document.createElement('li');
            listItem.className = 'flex justify-between items-center';
            
            // Create passenger info div
            const passengerInfo = document.createElement('div');
            passengerInfo.textContent = passengerName;
            
            // Create buttons container
            const buttonsContainer = document.createElement('div');
            
            // Create head of party button
            const headOfPartyBtn = document.createElement('button');
            headOfPartyBtn.textContent = ' Set as Head';
            headOfPartyBtn.className = 'text-sm bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded ml-2';
            headOfPartyBtn.onclick = function() {
                setAsHeadOfParty(passengerName, passengerName, this);
            };
            
            // Create delete button
            const deleteBtn = document.createElement('button');
            deleteBtn.textContent = ' ×';
            deleteBtn.className = 'text-red-600 ml-2';
            deleteBtn.onclick = function() {
                // Remove the corresponding hidden input
                const inputs = document.querySelectorAll('input[name="passenger_names[]"]');
                for (let input of inputs) {
                    if (input.value === passengerName) {
                        input.remove();
                        break;
                    }
                }
                
                // If this passenger was the head of party, clear the head
                if (document.getElementById('head_of_party').value === passengerName) {
                    document.getElementById('head_of_party').value = '';
                    document.getElementById('current-head').textContent = 'None selected';
                }
                
                // Remove the list item
                this.parentElement.parentElement.remove();
                
                // Refresh travel order dropdown to show the removed passenger's travel order
                refreshTravelOrderDropdown();
                

            };
            
            buttonsContainer.appendChild(headOfPartyBtn);
            buttonsContainer.appendChild(deleteBtn);
            
            listItem.appendChild(passengerInfo);
            listItem.appendChild(buttonsContainer);
            document.getElementById('passenger-items').appendChild(listItem);
            
            // Add hidden input for form submission
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'passenger_names[]';
            hiddenInput.value = passengerName;
            document.getElementById('passenger-list').appendChild(hiddenInput);
            

            
            // If this is the creator, automatically set them as head of party
            if (isHead) {
                setAsHeadOfParty(passengerName, passengerName, headOfPartyBtn);
            }
            
            // Refresh travel order dropdown to hide this passenger's travel order
            refreshTravelOrderDropdown();
        }
        
        // Set a passenger as head of party
        function setAsHeadOfParty(passengerName, passengerDisplay, element) {
            // Validate parameters to prevent errors
            if (typeof passengerDisplay === 'object' && passengerDisplay !== null) {
                // If passengerDisplay is an object (likely an HTML element), use passengerName instead
                passengerDisplay = passengerName;
            }
            
            // Set the hidden input value
            document.getElementById('head_of_party').value = passengerName;
            
            // Update the display
            document.getElementById('current-head').textContent = passengerDisplay || passengerName;
            
            // Visual feedback to show which passenger is the head of party
            const allPassengerItems = document.querySelectorAll('#passenger-items li');
            allPassengerItems.forEach(item => {
                item.style.backgroundColor = '';
                item.style.borderLeft = '1px solid #e5e7eb'; // gray-200
            });
            
            // Highlight the head of party item
            const headItem = element.closest('li');
            headItem.style.backgroundColor = '#fef3c7'; // yellow-100
            headItem.style.borderLeft = '4px solid #f59e0b'; // yellow-500
        }
        
        // Initialize the details when the page loads if an itinerary is pre-selected
        document.addEventListener('DOMContentLoaded', function() {
            updateItineraryDetails();
        });
        
        // Function to refresh travel order dropdown based on selected passengers
        function refreshTravelOrderDropdown() {
            // Get all selected passenger names
            const selectedPassengers = [];
            const passengerItems = document.querySelectorAll('#passenger-items li');
            passengerItems.forEach(item => {
                const passengerName = item.querySelector('div').textContent.split('(')[0].trim();
                selectedPassengers.push(passengerName);
            });
            
            // Get the travel order dropdown
            const travelOrderSelect = document.getElementById('travel_order_id');
            const allOptions = Array.from(travelOrderSelect.children);
            
            // Clear the dropdown
            travelOrderSelect.innerHTML = '<option value="">Select a Travel Order</option>';
            
            // Add back options that don't belong to selected passengers
            allOptions.slice(1).forEach(option => {  // Skip the first option "Select a Travel Order"
                const optionText = option.textContent;
                const parts = optionText.split(' - ');
                if (parts.length >= 2) {
                    const employeeName = parts[1].trim();
                    
                    // Only add this option if the employee is not in selected passengers
                    if (!selectedPassengers.includes(employeeName)) {
                        travelOrderSelect.appendChild(option.cloneNode(true));
                    }
                } else {
                    // If format doesn't match, add it anyway (fallback)
                    travelOrderSelect.appendChild(option.cloneNode(true));
                }
            });
        }
    </script>
@endsection