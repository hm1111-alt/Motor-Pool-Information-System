<!-- View Itinerary Modal -->
<div class="modal fade" id="viewItineraryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-3 shadow">

      <!-- Header -->
      <div class="modal-header d-flex align-items-center" style="background-color:#1e6031; color:white; padding: 0.75rem 1rem;">
        <h5 class="modal-title fw-bold mb-0 flex-grow-1">Itinerary Details</h5>
      </div>

      <!-- Content -->
      <div class="modal-body px-4 py-3">
        <div class="row mb-3">
          <div class="col-md-12">
            <div class="card border-success">
              <div class="card-header bg-success text-white py-2">
                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Itinerary Information</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Itinerary No.:</label>
                      <p class="mb-0" id="view_itinerary_no" style="font-size: 0.9rem; font-weight: 500;"></p>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Driver:</label>
                      <p class="mb-0" id="view_driver" style="font-size: 0.9rem; font-weight: 500;"></p>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Vehicle:</label>
                      <p class="mb-0" id="view_vehicle" style="font-size: 0.9rem; font-weight: 500;"></p>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Status:</label>
                      <p class="mb-0" id="view_status" style="font-size: 0.9rem; font-weight: 500;"></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Date From:</label>
                      <p class="mb-0" id="view_date_from" style="font-size: 0.9rem; font-weight: 500;"></p>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Date To:</label>
                      <p class="mb-0" id="view_date_to" style="font-size: 0.9rem; font-weight: 500;"></p>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Departure Time:</label>
                      <p class="mb-0" id="view_departure_time" style="font-size: 0.9rem; font-weight: 500;"></p>
                    </div>
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Return Time:</label>
                      <p class="mb-0" id="view_return_time" style="font-size: 0.9rem; font-weight: 500;"></p>
                    </div>
                  </div>
                </div>
                
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="mb-2">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Purpose:</label>
                      <p class="mb-0" id="view_purpose" style="font-size: 0.9rem; font-weight: 500; white-space: pre-wrap;"></p>
                    </div>
                    <div class="mb-0">
                      <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Destination:</label>
                      <p class="mb-0" id="view_destination" style="font-size: 0.9rem; font-weight: 500; white-space: pre-wrap;"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer py-2 justify-content-between">
        <div>
          <button type="button" class="btn btn-sm btn-outline-secondary" style="font-size: 0.75rem; height: 30px;" data-bs-dismiss="modal"> 
            <i class="fas fa-times me-1"></i>Close
          </button>
        </div>
        <div>
          <button type="button" class="btn btn-sm btn-primary me-2" style="font-size: 0.75rem; height: 30px;" onclick="downloadItineraryPDF()">
            <i class="fas fa-file-pdf me-1"></i> View PDF
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Function to populate and show the view itinerary modal
window.showViewItineraryModal = function(itineraryData) {
    // Populate the modal fields
    document.getElementById('view_itinerary_no').textContent = itineraryData.id || '';
    document.getElementById('view_date_from').textContent = itineraryData.date_from ? formatDate(itineraryData.date_from) : '';
    document.getElementById('view_date_to').textContent = itineraryData.date_to ? formatDate(itineraryData.date_to) : '';
    document.getElementById('view_driver').textContent = itineraryData.driver ? 
        `${itineraryData.driver.first_name || ''} ${itineraryData.driver.last_name || ''}`.trim() || 'N/A' : 'N/A';
    document.getElementById('view_vehicle').textContent = itineraryData.vehicle ? 
        `${itineraryData.vehicle.plate_number || ''} - ${itineraryData.vehicle.model || ''}`.trim().replace(/-\s*$/, '') || 'N/A' : 'N/A';
    document.getElementById('view_purpose').textContent = itineraryData.purpose || '';
    document.getElementById('view_destination').textContent = itineraryData.destination || '';
    document.getElementById('view_departure_time').textContent = itineraryData.departure_time ? formatTime(itineraryData.departure_time) : '';
    document.getElementById('view_return_time').textContent = itineraryData.return_time ? formatTime(itineraryData.return_time) : '';
    document.getElementById('view_status').textContent = itineraryData.status || '';
    
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

// Function to download itinerary PDF
function downloadItineraryPDF() {
    // Get the current itinerary ID from the modal
    const itineraryId = document.getElementById('view_itinerary_no').textContent;
    
    if (!itineraryId) {
        alert('No itinerary selected.');
        return;
    }
    
    // Open the PDF in a new tab
    window.open(`/itinerary/${itineraryId}/pdf`, '_blank');
}
</script>