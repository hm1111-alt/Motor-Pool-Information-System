<!-- View Itinerary Modal -->
<div class="modal fade" id="viewItineraryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content rounded-3 shadow">

      <!-- Header -->
      <div class="modal-header d-flex align-items-center" style="background-color:#1e6031; color:white; padding: 0.75rem 1rem;">
        <h5 class="modal-title fw-bold mb-0 flex-grow-1">Itinerary Details</h5>
      </div>

      <!-- Content -->
      <div class="modal-body px-4 py-3">
        <div class="row mb-2">
          <div class="col-md-4">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Itinerary No.:</label>
          </div>
          <div class="col-md-8">
            <p class="mb-0" id="view_itinerary_no" style="font-size: 0.85rem; font-weight: 500;"></p>
          </div>
        </div>
        
        <div class="row mb-2">
          <div class="col-md-4">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Driver:</label>
          </div>
          <div class="col-md-8">
            <p class="mb-0" id="view_driver" style="font-size: 0.85rem; font-weight: 500;"></p>
          </div>
        </div>
        
        <div class="row mb-2">
          <div class="col-md-4">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Vehicle:</label>
          </div>
          <div class="col-md-8">
            <p class="mb-0" id="view_vehicle" style="font-size: 0.85rem; font-weight: 500;"></p>
          </div>
        </div>
        
        <div class="row mb-2">
          <div class="col-md-4">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Purpose:</label>
          </div>
          <div class="col-md-8">
            <p class="mb-0" id="view_purpose" style="font-size: 0.85rem; font-weight: 500;"></p>
          </div>
        </div>
        
        <div class="row mb-2">
          <div class="col-md-4">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Destination:</label>
          </div>
          <div class="col-md-8">
            <p class="mb-0" id="view_destination" style="font-size: 0.85rem; font-weight: 500;"></p>
          </div>
        </div>
        
        <div class="row mb-2">
          <div class="col-md-4">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Date:</label>
          </div>
          <div class="col-md-8">
            <p class="mb-0" id="view_date" style="font-size: 0.85rem; font-weight: 500;"></p>
          </div>
        </div>
        
        <div class="row mb-0">
          <div class="col-md-4">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Departure Time:</label>
          </div>
          <div class="col-md-8">
            <p class="mb-0" id="view_departure_time" style="font-size: 0.85rem; font-weight: 500;"></p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer py-2 justify-content-end">
        <button type="button" class="btn btn-sm btn-outline-secondary me-1" style="font-size: 0.75rem; height: 30px;" data-bs-dismiss="modal"> Close
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// Function to populate and show the view itinerary modal
window.showViewItineraryModal = function(itineraryData) {
    // Populate the modal fields
    document.getElementById('view_itinerary_no').textContent = itineraryData.id || '';
    document.getElementById('view_date').textContent = itineraryData.date_from ? formatDate(itineraryData.date_from) : '';
    document.getElementById('view_driver').textContent = itineraryData.driver ? 
        `${itineraryData.driver.first_name || ''} ${itineraryData.driver.last_name || ''}`.trim() || 'N/A' : 'N/A';
    document.getElementById('view_vehicle').textContent = itineraryData.vehicle ? 
        `${itineraryData.vehicle.plate_number || ''} - ${itineraryData.vehicle.model || ''}`.trim().replace(/-\s*$/, '') || 'N/A' : 'N/A';
    document.getElementById('view_purpose').textContent = itineraryData.purpose || '';
    document.getElementById('view_destination').textContent = itineraryData.destination || '';
    document.getElementById('view_departure_time').textContent = itineraryData.departure_time ? formatTime(itineraryData.departure_time) : '';
    
    // Show the modal
    var myModal = new bootstrap.Modal(document.getElementById('viewItineraryModal'));
    myModal.show();
};

// Helper function to format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString(undefined, options);
}

// Helper function to format time
function formatTime(timeString) {
    if (!timeString) return '';
    
    const [hours, minutes] = timeString.split(':');
    let hour = parseInt(hours);
    const minute = minutes;
    const ampm = hour >= 12 ? 'PM' : 'AM';
    
    hour = hour % 12;
    hour = hour ? hour : 12; // Convert 0 to 12
    
    return `${hour}:${minute} ${ampm}`;
}
</script>