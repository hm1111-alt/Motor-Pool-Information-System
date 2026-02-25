@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('My Travel Requests') }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Page Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-200">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">My Travel Requests</h1>
                            <p class="text-gray-600 mt-1">Manage your travel requests and view their status</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#1e6031] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow" data-bs-toggle="modal" data-bs-target="#createTravelOrderModal">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create New Request
                            </button>
                        </div>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="flex space-x-6" aria-label="Tabs">
                            <a href="{{ route('travel-orders.index', ['tab' => 'pending']) }}" 
                               class="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200"
                               data-tab-switch="pending">
                                Pending
                            </a>
                            <a href="{{ route('travel-orders.index', ['tab' => 'approved']) }}" 
                               class="{{ isset($tab) && $tab == 'approved' ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200"
                               data-tab-switch="approved">
                                Approved
                            </a>
                            <a href="{{ route('travel-orders.index', ['tab' => 'cancelled']) }}" 
                               class="{{ isset($tab) && $tab == 'cancelled' ? 'border-[#1e6031] text-[#1e6031] font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 text-sm transition-colors duration-200"
                               data-tab-switch="cancelled">
                                Cancelled
                            </a>
                        </nav>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   id="search-input" 
                                   class="table-search-input focus:ring-[#1e6031] focus:border-[#1e6031] block w-full pl-10 pr-12 py-2 sm:text-sm border-gray-300 rounded-lg"
                                   placeholder="Search destination or purpose..."
                                   data-table-id="travel-orders-table"
                                   data-url="{{ route('travel-orders.index') }}"
                                   value="{{ $search ?? '' }}">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                @if(!empty($search))
                                    <button type="button" class="clear-search text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Travel Orders Table -->
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg" data-table="travel-orders-table">
                        <div class="overflow-x-auto">
                            <table id="travel-orders-table" class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure Time</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                        @if((isset($tab) && $tab == 'pending') || !isset($tab))
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="travel-orders-table-body">
                                    @include('travel-orders.partials.table-rows', ['travelOrders' => $travelOrders, 'tab' => $tab ?? 'pending'])
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div id="travel-orders-pagination-section">
                        @if($travelOrders->hasPages())
                            <div class="mt-4">
                                {{ $travelOrders->withQueryString()->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include the table search JavaScript -->
    <script src="{{ asset('js/table-search.js') }}"></script>
    
    <script>
        function viewTravelOrderPDF(id) {
            // Check if the current user is a unit head
            const isUnitHead = {{ auth()->user()->employee && auth()->user()->employee->is_head && !auth()->user()->employee->is_divisionhead && !auth()->user()->employee->is_vp && !auth()->user()->employee->is_president ? 'true' : 'false' }};
            
            // Check if the current user is a division head
            const isDivisionHead = {{ auth()->user()->employee && auth()->user()->employee->is_divisionhead && !auth()->user()->employee->is_vp && !auth()->user()->employee->is_president ? 'true' : 'false' }};
            
            // Determine the correct route based on user role
            let pdfUrl;
            if (isUnitHead) {
                pdfUrl = '/unithead/travel-orders/' + id + '/pdf';
            } else if (isDivisionHead) {
                pdfUrl = '/divisionhead/travel-orders/' + id + '/pdf';
            } else {
                pdfUrl = '/travel-orders/' + id + '/pdf';
            }
            
            // Open PDF directly in new tab
            window.open(pdfUrl, '_blank');
        }
    </script>
@endsection

<!-- Create Travel Order Modal -->
<div class="modal fade" id="createTravelOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><!-- default width -->
    <div class="modal-content rounded-3 shadow">

      <!-- Header -->
      <div class="modal-header d-flex align-items-center" style="background-color:#1e6031; color:white;">
        <h5 class="modal-title fw-bold mb-0 flex-grow-1">Create New Travel Request</h5>
      </div>

      <!-- Form -->
      <form id="travelOrderForm" action="{{ route('travel-orders.store') }}" method="POST">
        @csrf
        <div class="modal-body px-3 py-2">

          <!-- Position -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Position <span class="text-danger">*</span></label>
            <select name="position_id" id="position_id" required class="form-select form-select-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
              <option value="">Select a position</option>
              @foreach($positions as $position)
                <option value="{{ $position->id }}">
                  {{ $position->position_name }}
                  @if($position->office) - {{ $position->office->office_name }} @endif
                  @if($position->is_unit_head) (Unit Head) @elseif($position->is_division_head) (Division Head) @elseif($position->is_vp) (VP) @elseif($position->is_president) (President) @endif
                </option>
              @endforeach
            </select>
          </div>

          <!-- Destination -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Destination <span class="text-danger">*</span></label>
            <input type="text" name="destination" id="destination" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter destination" required>
          </div>

          <!-- Date Range -->
          <div class="row mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">From Date <span class="text-danger">*</span></label>
              <input type="date" name="date_from" id="date_from" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">To Date <span class="text-danger">*</span></label>
              <input type="date" name="date_to" id="date_to" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" required>
            </div>
          </div>

          <!-- Departure Time -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Departure Time</label>
            <input type="time" name="departure_time" id="departure_time" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;">
          </div>

          <!-- Purpose -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Purpose <span class="text-danger">*</span></label>
            <textarea name="purpose" id="purpose" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 60px;" rows="2" placeholder="Provide a brief description of the purpose of your travel" required></textarea>
          </div>

        </div>

        <!-- Footer -->
        <div class="modal-footer py-1 justify-content-end">
          <button type="button" class="btn btn-sm btn-outline-secondary me-2 py-1" style="font-size: 0.75rem; height: 30px;" data-bs-dismiss="modal" id="cancelTravelOrderBtn">
            Cancel
          </button>
          <button type="submit" class="btn btn-sm btn-success py-1" style="font-size: 0.75rem; height: 30px;" id="createTravelOrderBtn">
            Save
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
// Handle form submission via AJAX
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('travelOrderForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Disable submit button and show loading state
      const submitBtn = document.getElementById('createTravelOrderBtn');
      const originalText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
      
      // Clear previous errors
      document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
      document.getElementById('errorMessage').classList.add('d-none');
      document.getElementById('errorList').innerHTML = '';
      
      // Prepare form data
      const formData = new FormData(form);
      
      // Send AJAX request
      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Show success message
          Swal.fire({
            title: 'Success!',
            text: data.message || 'Travel request created successfully!',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createTravelOrderModal'));
            if (modal) {
              modal.hide();
            }
            
            // Reset form
            form.reset();
            
            // Reload the page to show updated data
            location.reload();
          });
        } else {
          // Show validation errors
          if (data.errors) {
            Object.keys(data.errors).forEach(field => {
              const input = document.querySelector(`[name="${field}"]`);
              if (input) {
                input.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = data.errors[field][0];
                input.parentNode.appendChild(errorDiv);
              }
            });
          }
          
          // Show general error message
          if (data.message) {
            Swal.fire({
              title: 'Error!',
              text: data.message,
              icon: 'error'
            });
          }
          
          // Re-enable submit button
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire({
          title: 'Error!',
          text: 'An error occurred while creating the travel request. Please try again.',
          icon: 'error'
        });
        
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      });
    });
  }
  
  // Reset form when modal is closed
  const modal = document.getElementById('createTravelOrderModal');
  if (modal) {
    modal.addEventListener('hidden.bs.modal', function () {
      const form = document.getElementById('travelOrderForm');
      if (form) {
        form.reset();
        // Clear validation errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
      }
    });
  }
});
</script>