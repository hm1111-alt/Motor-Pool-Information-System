<!-- Edit Itinerary Modal -->
<div class="modal fade" id="editItineraryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content rounded-3 shadow">

      <!-- Header -->
      <div class="modal-header d-flex align-items-center" style="background-color:#1e6031; color:white;">
        <h5 class="modal-title fw-bold mb-0 flex-grow-1">Edit Itinerary</h5>
      </div>

      <!-- Form -->
      <form id="editItineraryForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body px-3 py-2">
          
          <!-- Dates -->
          <div class="row g-2 mb-2">
            <div class="col">
              <label for="edit_date_from" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Date From <span class="text-danger">*</span></label>
              <input type="date" name="date_from" id="edit_date_from" class="form-control form-control-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
            </div>
            <div class="col">
              <label for="edit_date_to" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Date To <span class="text-danger">*</span></label>
              <input type="date" name="date_to" id="edit_date_to" class="form-control form-control-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
            </div>
          </div>

          <!-- Departure Time -->
          <div class="mb-2">
            <label for="edit_departure_time" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Departure Time <span class="text-danger">*</span></label>
            <input type="time" name="departure_time" id="edit_departure_time" class="form-control form-control-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
          </div>

          <!-- Purpose -->
          <div class="mb-2">
            <label for="edit_purpose" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Purpose <span class="text-danger">*</span></label>
            <input type="text" name="purpose" id="edit_purpose" class="form-control form-control-sm rounded" placeholder="Enter purpose" style="height: 30px; font-size: 0.75rem;" required>
          </div>
          
          <!-- Destination -->
          <div class="mb-2">
            <label for="edit_destination" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Destination <span class="text-danger">*</span></label>
            <input type="text" name="destination" id="edit_destination" class="form-control form-control-sm rounded" placeholder="Enter destination" style="height: 30px; font-size: 0.75rem;" required>
          </div>
          
          <!-- Driver -->
          <div class="mb-2">
            <label for="edit_driver_id" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Driver <span class="text-danger">*</span></label>
            <select name="driver_id" id="edit_driver_id" class="form-select form-select-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
              <option value="" disabled>-- Select Driver --</option>
              @foreach($drivers->where('availability_status', 'Available')->sortBy(function($driver) {
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
              <input type="text" id="edit_driver_position" class="form-control form-control-sm rounded" placeholder="Position" readonly style="height: 30px; font-size: 0.75rem;">
            </div>
            <div class="col">
              <input type="text" id="edit_driver_station" class="form-control form-control-sm rounded" placeholder="Station" readonly style="height: 30px; font-size: 0.75rem;">
            </div>
          </div>

          <!-- Vehicle -->
          <div class="mb-2">
            <label for="edit_vehicle_id" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Vehicle <span class="text-danger">*</span></label>
            <select name="vehicle_id" id="edit_vehicle_id" class="form-select form-select-sm rounded" style="height: 30px; font-size: 0.75rem;" required>
              <option value="" disabled>-- Select Vehicle --</option>
              @foreach($vehicles->whereIn('status', ['Available', 'Active'])->sortBy('model') as $vehicle)
                <option value="{{ $vehicle->id }}" data-type="{{ $vehicle->type }}" data-capacity="{{ $vehicle->capacity }}">
                  {{ $vehicle->model }} - {{ $vehicle->plate_number }}
                </option>
              @endforeach
            </select>
          </div>
          
          <div class="row g-2 mb-0">
            <div class="col">
              <input type="text" id="edit_vehicle_type" class="form-control form-control-sm rounded" placeholder="Type" readonly style="height: 30px; font-size: 0.75rem;">
            </div>
            <div class="col">
              <input type="text" id="edit_vehicle_capacity" class="form-control form-control-sm rounded" placeholder="Capacity" readonly style="height: 30px; font-size: 0.75rem;">
            </div>
          </div>
          
        </div>

        <!-- Footer -->
        <div class="modal-footer py-2 justify-content-end">
          <button type="button" class="btn btn-sm btn-outline-secondary me-1" style="font-size: 0.75rem; height: 30px;" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-success" style="font-size: 0.75rem; height: 30px;">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Function to populate and show the edit itinerary modal
window.showEditItineraryModal = function(itineraryData) {
    // Set the form action URL
    const form = document.getElementById('editItineraryForm');
    form.action = `/itinerary/${itineraryData.id}`;
    
    // Populate the modal fields
    document.getElementById('edit_date_from').value = itineraryData.date_from ? formatDateForInput(itineraryData.date_from) : '';
    document.getElementById('edit_date_to').value = itineraryData.date_to ? formatDateForInput(itineraryData.date_to) : '';
    document.getElementById('edit_departure_time').value = itineraryData.departure_time || '';
    document.getElementById('edit_driver_id').value = itineraryData.driver_id || '';
    document.getElementById('edit_vehicle_id').value = itineraryData.vehicle_id || '';
    document.getElementById('edit_purpose').value = itineraryData.purpose || '';
    document.getElementById('edit_destination').value = itineraryData.destination || '';
    
    // Update driver details if driver is selected
    updateDriverDetailsEdit(document.getElementById('edit_driver_id'));
    
    // Update vehicle details if vehicle is selected
    updateVehicleDetailsEdit(document.getElementById('edit_vehicle_id'));
    
    // Show the modal
    var myModal = new bootstrap.Modal(document.getElementById('editItineraryModal'));
    myModal.show();
};

// Helper function to format date for input field
function formatDateForInput(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
}

// Function to update driver details
function updateDriverDetailsEdit(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const position = selectedOption.getAttribute('data-position');
    const station = selectedOption.getAttribute('data-station');
    
    document.getElementById('edit_driver_position').value = position || '';
    document.getElementById('edit_driver_station').value = station || '';
}

// Function to update vehicle details
function updateVehicleDetailsEdit(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const type = selectedOption.getAttribute('data-type');
    const capacity = selectedOption.getAttribute('data-capacity');
    
    document.getElementById('edit_vehicle_type').value = type || '';
    document.getElementById('edit_vehicle_capacity').value = capacity || '';
}

// Handle form submission
document.getElementById('editItineraryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
    submitBtn.disabled = true;
    
    // Submit the form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-HTTP-Method-Override': 'PUT'
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
                text: data.message || 'Itinerary updated successfully!',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Close the modal and reload the page
                const modal = bootstrap.Modal.getInstance(document.getElementById('editItineraryModal'));
                if (modal) {
                    modal.hide();
                }
                // Reload the page to show the updated itinerary
                location.reload();
            });
        } else {
            // Show error alert
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An error occurred while updating the itinerary.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An error occurred while updating the itinerary. Please try again.'
        });
    })
    .finally(() => {
        // Restore button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Add event listeners for driver and vehicle select changes
document.addEventListener('DOMContentLoaded', function() {
    const driverSelect = document.getElementById('edit_driver_id');
    const vehicleSelect = document.getElementById('edit_vehicle_id');
    
    if (driverSelect) {
        driverSelect.addEventListener('change', function() {
            updateDriverDetailsEdit(this);
        });
    }
    
    if (vehicleSelect) {
        vehicleSelect.addEventListener('change', function() {
            updateVehicleDetailsEdit(this);
        });
    }
});
</script>