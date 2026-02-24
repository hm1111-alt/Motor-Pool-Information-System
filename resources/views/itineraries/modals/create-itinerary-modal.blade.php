<!-- Create Itinerary Modal -->
<div class="modal fade" id="createItineraryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md"><!-- medium modal -->
    <div class="modal-content rounded-3 shadow">

      <!-- Header -->
      <div class="modal-header d-flex align-items-center" style="background-color:#1e6031; color:white;">
        <h5 class="modal-title fw-bold mb-0 flex-grow-1">Create Itinerary</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Form -->
      <form id="itineraryForm" action="{{ route('itinerary.store') }}" method="POST">
        @csrf
        <div class="modal-body px-3 py-2">

        
          <!-- Travel Order-->
        <div class="mb-2">
          <label for="travel_order_id" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Travel Order</label>
          <select class="form-select form-select-sm rounded" id="travel_order_id" name="travel_order_id" onchange="prefillTravelOrderDataModal(this)" style="height: 30px; font-size: 0.75rem;">
              <option value="">Select Travel Order</option>
              @foreach($travelOrders as $travelOrder)
                <option value="{{ $travelOrder->id }}" 
                        data-date-from="{{ $travelOrder->date_from instanceof \DateTimeInterface ? $travelOrder->date_from->format('Y-m-d') : $travelOrder->date_from }}"
                        data-date-to="{{ $travelOrder->date_to instanceof \DateTimeInterface ? $travelOrder->date_to->format('Y-m-d') : $travelOrder->date_to }}"
                        data-departure-time="{{ $travelOrder->departure_time }}"
                        data-destination="{{ $travelOrder->destination }}"
                        data-purpose="{{ $travelOrder->purpose }}">
                    #{{ $travelOrder->id }} - {{ $travelOrder->destination ?? 'Travel' }}
                </option>
              @endforeach
          </select>
        </div>

          <!-- Dates -->
          <div class="row g-2 mb-2">
            <div class="col">
              <label for="date_from" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Date From <span class="text-danger">*</span></label>
              <input type="date" name="date_from" id="date_from" class="form-control form-control-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
            </div>
            <div class="col">
              <label for="date_to" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Date To <span class="text-danger">*</span></label>
              <input type="date" name="date_to" id="date_to" class="form-control form-control-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
            </div>
          </div>

          <!-- Departure Time -->
          <div class="mb-2">
            <label for="departure_time" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Departure Time <span class="text-danger">*</span></label>
            <input type="time" name="departure_time" id="departure_time" class="form-control form-control-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
          </div>
          
          <!-- Arrival Time -->
          <div class="mb-2">
            <label for="arrival_time" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Arrival Time <span class="text-danger">*</span></label>
            <input type="time" name="arrival_time" id="arrival_time" class="form-control form-control-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
          </div>
          


          <!-- Purpose -->
          <div class="mb-2">
            <label for="purpose" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Purpose <span class="text-danger">*</span></label>
            <input type="text" name="purpose" id="purpose" class="form-control form-control-sm rounded" placeholder="Enter purpose" style="height: 30px; font-size: 0.75rem;" required>
          </div>
          
          <!-- Destination -->
          <div class="mb-2">
            <label for="destination" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Destination <span class="text-danger">*</span></label>
            <input type="text" name="destination" id="destination" class="form-control form-control-sm rounded" placeholder="Enter destination" style="height: 30px; font-size: 0.75rem;" required>
          </div>
          
          <!-- Driver -->
          <div class="mb-2">
            <label for="driver_id" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Driver <span class="text-danger">*</span></label>
            <select name="driver_id" id="driver_id" class="form-select form-select-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
              <option value="" disabled selected>-- Select Driver --</option>
              @foreach($drivers->where('availability_status', 'Active')->sortBy(function($driver) {
                  return $driver->first_name . ' ' . $driver->last_name;
              }) as $driver)
                <option value="{{ $driver->id }}" data-position="{{ $driver->position }}" data-station="{{ $driver->official_station }}">
                  {{ $driver->first_name }} {{ $driver->last_name }}
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="row g-2 mb-2">
            <div class="col">
              <input type="text" id="driver_position" class="form-control form-control-sm rounded" placeholder="Position" readonly style="height: 30px; font-size: 0.75rem;">
            </div>
            <div class="col">
              <input type="text" id="driver_station" class="form-control form-control-sm rounded" placeholder="Station" readonly style="height: 30px; font-size: 0.75rem;">
            </div>
          </div>

          <!-- Vehicle -->
          <div class="mb-2">
            <label for="vehicle_id" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Vehicle <span class="text-danger">*</span></label>
            <select name="vehicle_id" id="vehicle_id" class="form-select form-select-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
              <option value="" disabled selected>-- Select Vehicle --</option>
              @php
                  $sortedVehicles = $vehicles->whereIn('status', ['Available', 'Active'])->sort(function($a, $b) {
                      return strnatcasecmp($a->plate_number, $b->plate_number);
                  });
              @endphp
              @foreach($sortedVehicles as $vehicle)
                <option value="{{ $vehicle->id }}" data-model="{{ $vehicle->model }}" data-type="{{ $vehicle->type }}">
                  {{ $vehicle->plate_number }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="row g-2 mb-2">
            <div class="col">
              <input type="text" id="vehicle_model" class="form-control form-control-sm rounded" placeholder="Model" readonly style="height: 30px; font-size: 0.75rem;">
            </div>
            <div class="col">
              <input type="text" id="vehicle_type" class="form-control form-control-sm rounded" placeholder="Type" readonly style="height: 30px; font-size: 0.75rem;">
            </div>
          </div>

          <input type="hidden" name="status" value="Not yet Approved">
        </div>

        <!-- Footer -->
        <div class="modal-footer py-1 justify-content-end">
          <button type="button" class="btn btn-sm btn-outline-secondary me-1" style="font-size: 0.75rem; height: 30px;" data-bs-dismiss="modal"> Cancel
          </button>
          <button type="button" id="saveItineraryBtn" class="btn btn-sm btn-success" style="font-size: 0.75rem; height: 30px; background-color: #1e6031; border-color: #1e6031;"> Save 
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Prefill travel order data when a travel order is selected (modal version)
function prefillTravelOrderDataModal(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    if (selectedOption.value === '') {
        // Clear date and destination fields if no travel order is selected
        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        document.getElementById('departure_time').value = '';
        document.getElementById('arrival_time').value = '';
        document.getElementById('destination').value = '';
        // Purpose should remain unchanged as it's independent and required to be manually entered
        // Reset driver and vehicle options to show all available
        resetDriverAndVehicleOptions();
        return;
    }
    
    // Get data from the selected option
    const dateFrom = selectedOption.getAttribute('data-date-from');
    const dateTo = selectedOption.getAttribute('data-date-to');
    const departureTime = selectedOption.getAttribute('data-departure-time');
    const destination = selectedOption.getAttribute('data-destination');
    // Note: Purpose is intentionally NOT pre-filled - it should be manually entered by the user
    
    // Prefill fields (excluding purpose)
    if (dateFrom) {
        document.getElementById('date_from').value = dateFrom;
    }
    if (dateTo) {
        document.getElementById('date_to').value = dateTo;
    }
    if (departureTime) {
        document.getElementById('departure_time').value = departureTime;
    }
    // For arrival time, we'll set a default based on departure time if available
    if (departureTime) {
        // Calculate a default arrival time (e.g., 2 hours after departure)
        document.getElementById('arrival_time').value = calculateDefaultArrivalTime(departureTime);
    }
    if (destination) {
        document.getElementById('destination').value = destination;
    }
    // Purpose field is intentionally NOT pre-filled - it remains empty for user input
    
    // Update available drivers and vehicles based on the selected date
    if (dateFrom) {
        updateAvailableDrivers(dateFrom);
        updateAvailableVehicles(dateFrom);
    }
}

// Helper function to calculate default arrival time (2 hours after departure)
function calculateDefaultArrivalTime(departureTime) {
    if (!departureTime) return '';
    
    const [hours, minutes] = departureTime.split(':');
    let departureHours = parseInt(hours);
    let departureMinutes = parseInt(minutes);
    
    // Add 2 hours to departure time for default arrival
    departureHours += 2;
    
    // Handle overflow
    if (departureHours >= 24) {
        departureHours = departureHours % 24;
    }
    
    // Format as HH:MM
    return `${departureHours.toString().padStart(2, '0')}:${minutes}`;
}

// Update available drivers based on selected date
function updateAvailableDrivers(date) {
    fetch(`/api/available-drivers/${date}`)
        .then(response => response.json())
        .then(drivers => {
            const driverSelect = document.getElementById('driver_id');
            const currentValue = driverSelect.value;
            
            // Clear existing options except the first placeholder
            driverSelect.innerHTML = '<option value="" disabled selected>-- Select Driver --</option>';
            
            drivers.forEach(driver => {
                const option = document.createElement('option');
                option.value = driver.id;
                option.textContent = `${driver.first_name} ${driver.last_name}`;
                option.setAttribute('data-position', driver.position || '');
                option.setAttribute('data-station', driver.official_station || '');
                driverSelect.appendChild(option);
            });
            
            // Restore the previous selection if the driver is still available
            if (currentValue && drivers.some(d => d.id == currentValue)) {
                driverSelect.value = currentValue;
            } else {
                driverSelect.value = '';
            }
        })
        .catch(error => console.error('Error fetching available drivers:', error));
}

// Update available vehicles based on selected date
function updateAvailableVehicles(date) {
    fetch(`/api/available-vehicles/${date}`)
        .then(response => response.json())
        .then(vehicles => {
            const vehicleSelect = document.getElementById('vehicle_id');
            const currentValue = vehicleSelect.value;
            
            // Clear existing options except the first placeholder
            vehicleSelect.innerHTML = '<option value="" disabled selected>-- Select Vehicle --</option>';
            
            vehicles.forEach(vehicle => {
                const option = document.createElement('option');
                option.value = vehicle.id;
                option.textContent = `${vehicle.plate_number}`;
                option.setAttribute('data-model', vehicle.model || '');
                option.setAttribute('data-type', vehicle.type || '');
                vehicleSelect.appendChild(option);
            });
            
            // Restore the previous selection if the vehicle is still available
            if (currentValue && vehicles.some(v => v.id == currentValue)) {
                vehicleSelect.value = currentValue;
            } else {
                vehicleSelect.value = '';
            }
        })
        .catch(error => console.error('Error fetching available vehicles:', error));
}

// Reset driver and vehicle options to show all available
function resetDriverAndVehicleOptions() {
    // Reset driver options
    const driverSelect = document.getElementById('driver_id');
    driverSelect.innerHTML = '<option value="" disabled selected>-- Select Driver --</option>';
    
    // We'll need to store the original driver options to restore them
    // For now, we'll just clear the selection
    driverSelect.value = '';
    
    // Reset vehicle options
    const vehicleSelect = document.getElementById('vehicle_id');
    vehicleSelect.innerHTML = '<option value="" disabled selected>-- Select Vehicle --</option>';
    vehicleSelect.value = '';
}

// Add event listeners to date inputs to update available drivers and vehicles
document.addEventListener('DOMContentLoaded', function() {
    const dateFromInput = document.getElementById('date_from');
    const dateToInput = document.getElementById('date_to');
    
    if (dateFromInput) {
        dateFromInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                updateAvailableDrivers(selectedDate);
                updateAvailableVehicles(selectedDate);
            } else {
                resetDriverAndVehicleOptions();
            }
        });
    }
    
    if (dateToInput) {
        dateToInput.addEventListener('change', function() {
            const selectedDate = this.value;
            if (selectedDate) {
                updateAvailableDrivers(selectedDate);
                updateAvailableVehicles(selectedDate);
            } else {
                resetDriverAndVehicleOptions();
            }
        });
    }
});

// Update driver details when a driver is selected
document.getElementById('driver_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const position = selectedOption.getAttribute('data-position');
    const station = selectedOption.getAttribute('data-station');
    
    document.getElementById('driver_position').value = position || '';
    document.getElementById('driver_station').value = station || '';
});

// Update vehicle details when a vehicle is selected
document.getElementById('vehicle_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const model = selectedOption.getAttribute('data-model');
    const type = selectedOption.getAttribute('data-type');
    
    document.getElementById('vehicle_model').value = model || '';
    document.getElementById('vehicle_type').value = type || '';
});

// Expose the function globally for the button to call
window.openCreateItineraryModal = function() {
    var myModal = new bootstrap.Modal(document.getElementById('createItineraryModal'));
    myModal.show();
};

// Handle itinerary form submission with SweetAlert2
document.getElementById('saveItineraryBtn').addEventListener('click', function() {
    // Get form data
    const formData = new FormData();
    const form = document.getElementById('itineraryForm');
    
    // Get all form elements and append to FormData
    const formElements = form.querySelectorAll('input, select, textarea');
    formElements.forEach(element => {
        if (element.name) {
            formData.append(element.name, element.value);
        }
    });
    
    // Manually add CSRF token
    const csrfToken = document.querySelector('input[name="_token"]').value;
    formData.append('_token', csrfToken);
    
    // Show loading alert
    Swal.fire({
        title: 'Saving...',
        text: 'Please wait while we save your itinerary.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Submit the form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Itinerary saved successfully!',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Close the modal and reload the page
                const modal = bootstrap.Modal.getInstance(document.getElementById('createItineraryModal'));
                if (modal) {
                    modal.hide();
                }
                // Reload the page to show the new itinerary
                location.reload();
            });
        } else {
            // Show error alert
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An error occurred while saving the itinerary.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An error occurred while saving the itinerary. Please try again.'
        });
    });
});
</script>