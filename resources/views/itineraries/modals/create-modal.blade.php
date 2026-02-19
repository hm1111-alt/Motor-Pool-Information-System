<!-- Create Itinerary Modal -->
<div id="createItineraryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Create Itinerary</h3>
                <button id="closeCreateItineraryModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="createItineraryForm" method="POST" action="{{ route('itinerary.store') }}" class="mt-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Travel Order -->
                    <div class="md:col-span-2">
                        <label for="travel_order_id_modal" class="block text-sm font-medium text-gray-700 mb-1">Travel Order</label>
                        <select name="travel_order_id" id="travel_order_id_modal" 
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                onchange="loadTravelOrderDetails(this.value)">
                            <option value="">-- Select Travel Order --</option>
                            @foreach($travelOrders as $travelOrder)
                                <option value="{{ $travelOrder->id }}">
                                    Travel Order #{{ $travelOrder->id }} - {{ $travelOrder->employee->first_name ?? '' }} {{ $travelOrder->employee->last_name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Date From -->
                    <div>
                        <label for="date_from_modal" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                        <input type="date" name="date_from" id="date_from_modal" 
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               readonly>
                    </div>
                    
                    <!-- Date To -->
                    <div>
                        <label for="date_to_modal" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                        <input type="date" name="date_to" id="date_to_modal" 
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               readonly>
                    </div>
                    
                    <!-- Departure Time -->
                    <div>
                        <label for="departure_time_modal" class="block text-sm font-medium text-gray-700 mb-1">Departure Time</label>
                        <input type="time" name="departure_time" id="departure_time_modal" 
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               readonly>
                    </div>
                    
                    <!-- Driver -->
                    <div class="md:col-span-2">
                        <label for="driver_id_modal" class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                        <select name="driver_id" id="driver_id_modal" 
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <option value="">-- Select Driver --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" 
                                        data-position="{{ $driver->position ?? '' }}" 
                                        data-official-station="{{ $driver->official_station ?? '' }}">
                                    {{ $driver->first_name }} {{ $driver->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Position (readonly based on driver selection) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="text" id="driver_position" 
                               class="w-full rounded-lg border-gray-300 bg-gray-100" 
                               readonly>
                    </div>
                    
                    <!-- Official Station (readonly based on driver selection) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Official Station</label>
                        <input type="text" id="driver_official_station" 
                               class="w-full rounded-lg border-gray-300 bg-gray-100" 
                               readonly>
                    </div>
                    
                    <!-- Vehicle -->
                    <div class="md:col-span-2">
                        <label for="vehicle_id_modal" class="block text-sm font-medium text-gray-700 mb-1">Vehicle</label>
                        <select name="vehicle_id" id="vehicle_id_modal" 
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <option value="">-- Select Vehicle --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" 
                                        data-model="{{ $vehicle->model ?? '' }}" 
                                        data-type="{{ $vehicle->type ?? '' }}">
                                    {{ $vehicle->make ?? '' }} {{ $vehicle->model ?? '' }} - {{ $vehicle->plate_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Model (readonly based on vehicle selection) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <input type="text" id="vehicle_model" 
                               class="w-full rounded-lg border-gray-300 bg-gray-100" 
                               readonly>
                    </div>
                    
                    <!-- Type (readonly based on vehicle selection) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <input type="text" id="vehicle_type" 
                               class="w-full rounded-lg border-gray-300 bg-gray-100" 
                               readonly>
                    </div>
                    
                    <!-- Purpose -->
                    <div class="md:col-span-2">
                        <label for="purpose_modal" class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                        <textarea name="purpose" id="purpose_modal" rows="2" 
                                  class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                  placeholder="Enter purpose"></textarea>
                    </div>
                    
                    <!-- Destination -->
                    <div class="md:col-span-2">
                        <label for="destination_modal" class="block text-sm font-medium text-gray-700 mb-1">Destination</label>
                        <input type="text" name="destination" id="destination_modal" 
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               placeholder="Enter destination">
                    </div>
                </div>
                
                <div class="items-center gap-2 mt-6 flex justify-end">
                    <button type="button" id="cancelCreateItinerary" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-[#1e6031] text-white rounded-lg hover:bg-[#164f2a]">
                        Create Itinerary
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('createItineraryModal');
    const closeBtn = document.getElementById('closeCreateItineraryModal');
    const cancelBtn = document.getElementById('cancelCreateItinerary');
    
    // Close modal handlers
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    // Open modal function
    window.openCreateItineraryModal = function() {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    };
    
    // Close modal function
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
        // Reset form
        document.getElementById('createItineraryForm').reset();
        // Clear readonly fields
        document.getElementById('date_from_modal').value = '';
        document.getElementById('date_to_modal').value = '';
        document.getElementById('departure_time_modal').value = '';
        document.getElementById('driver_position').value = '';
        document.getElementById('driver_official_station').value = '';
        document.getElementById('vehicle_model').value = '';
        document.getElementById('vehicle_type').value = '';
        document.getElementById('destination_modal').value = '';
        document.getElementById('purpose_modal').value = '';
    }
});

// Load travel order details when a travel order is selected
function loadTravelOrderDetails(travelOrderId) {
    if (!travelOrderId) {
        // Clear fields if no travel order is selected
        document.getElementById('date_from_modal').value = '';
        document.getElementById('date_to_modal').value = '';
        document.getElementById('departure_time_modal').value = '';
        document.getElementById('destination_modal').value = '';
        return;
    }
    
    // Fetch travel order details from the server
    fetch(`/api/travel-orders/${travelOrderId}`)
        .then(response => response.json())
        .then(data => {
            // Format dates to YYYY-MM-DD for input[type=date]
            const dateFrom = data.date_from ? formatDateForInput(data.date_from) : '';
            const dateTo = data.date_to ? formatDateForInput(data.date_to) : '';
            
            // Set the values in the form
            document.getElementById('date_from_modal').value = dateFrom;
            document.getElementById('date_to_modal').value = dateTo;
            document.getElementById('departure_time_modal').value = data.departure_time || '';
            document.getElementById('destination_modal').value = data.destination || '';
            document.getElementById('purpose_modal').value = data.purpose || '';
        })
        .catch(error => console.error('Error loading travel order details:', error));
}

// Helper function to format date for input[type=date]
function formatDateForInput(dateString) {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Update driver details when a driver is selected
document.getElementById('driver_id_modal').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('driver_position').value = selectedOption.getAttribute('data-position') || '';
    document.getElementById('driver_official_station').value = selectedOption.getAttribute('data-official-station') || '';
});

// Update vehicle details when a vehicle is selected
document.getElementById('vehicle_id_modal').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    document.getElementById('vehicle_model').value = selectedOption.getAttribute('data-model') || '';
    document.getElementById('vehicle_type').value = selectedOption.getAttribute('data-type') || '';
});
</script>