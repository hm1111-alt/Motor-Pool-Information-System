@extends('layouts.motorpool-admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-3">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-4 w-4 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="main">
            <div class="flex justify-between items-center mb-3">
                <!-- Left: Heading -->
                <h2 class="font-semibold text-xl text-gray-800">Drivers</h2>

                <!-- Right: Search Bar + Add Driver Button -->
                <div class="flex items-center gap-2">
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" action="{{ route('drivers.index') }}">
                        <div class="flex">
                            <input type="text" name="search" class="form-input" placeholder="Search drivers..." 
                                   value="{{ request('search') }}"
                                   style="height: 32px; width: 250px; font-size: 0.85rem; border: 1px solid #1e6031; border-radius: 0.375rem 0 0 0.375rem; padding: 0 12px;">
                            <button type="submit" 
                                    style="background-color: #1e6031; color: #ffffff; border: 1px solid #1e6031; height: 32px; width: 40px; border-radius: 0 0.375rem 0.375rem 0; cursor:pointer;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div> 
                    </form>
                    
                    <button type="button" class="inline-flex items-center px-3 py-1.5 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150" data-bs-toggle="modal" data-bs-target="#addDriverModal">
                        <i class="fas fa-plus mr-1 text-xs"></i> Add New Driver
                    </button>
                    
                    <!-- Edit Driver Modal Trigger (hidden, will be triggered by JavaScript) -->
                    <button type="button" id="editDriverModalTrigger" class="d-none" data-bs-toggle="modal" data-bs-target="#editDriverModal"></button>
                </div>
            </div>

            <!-- Filter + PDF Button -->
            <div class="flex justify-between items-center mb-2" style="display:flex; align-items:center; justify-content:space-between; padding:5px; border-bottom:1px solid #1e6031; margin-bottom:5px;">
                
                <!-- Position Filter -->
                <div style="display:flex; align-items:center;">
                    <label for="positionFilter" style="margin-right: 10px; font-weight: bold;">Filter by Position:</label>
                    <select id="positionFilter" name="position" class="border border-gray-300 rounded-md px-2 py-1 text-sm"
                        style="width:150px; padding:6px 8px; font-size:13px; border:1px solid #ccc; border-radius:5px;">
                        <option value="">All Positions</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos }}" {{ request('position') == $pos ? 'selected' : '' }}>
                                {{ $pos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Generate PDF Button -->
               <div class="flex justify-end mb-2">
               <button id="generatePDFBtn" class="inline-flex items-center px-2 py-1 bg-red-600 border border-red-600 rounded text-xs text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" style="padding: 4px 8px; font-size: 0.80rem;">
                <i class="fas fa-file-pdf mr-1 text-xs"></i> Generate PDF
            </button>
               </div>

            </div>

            <!-- Drivers Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead style="background-color: #1e6031; color: white;">
                        <tr>
                            <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">No.</th>
                            <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Name</th>
                            <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Contact</th>
                            <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Position</th>
                            <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Action</th>
                        </tr>
                    </thead>
                  <tbody id="driver-table-body">
                    @forelse($drivers as $index => $driver)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-[#f0f8f0]">
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $driver->full_name }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">{{ $driver->user ? $driver->user->contact_num : 'No Contact' }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-600 max-w-[200px]">
                                <div class="text-[#004d00] font-medium">{{ $driver->position }}</div>
                                <div class="text-[#006400] text-xs">{{ $driver->official_station }}</div>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-center text-xs">
                                <div class="action-buttons flex justify-center space-x-1">
                                    <a href="{{ route('drivers.show', $driver) }}" 
                                       class="btn view-btn border inline-flex items-center justify-center"
                                       title="View Driver">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <button type="button" 
                                       class="btn edit-btn border inline-flex items-center justify-center"
                                       title="Edit Driver"
                                       data-driver-id="{{ $driver->id }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn archive-btn border delete-btn inline-flex items-center justify-center"
                                                title="Archive Driver">
                                            <i class="fas fa-archive"></i> Archive
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-xs text-gray-500">
                                No drivers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm text-gray-600">
                    Showing {{ $drivers->firstItem() ?? 0 }} to {{ $drivers->lastItem() ?? 0 }} of {{ $drivers->total() }} applications
                </div>
                <div class="flex items-center space-x-2">
                    @if($drivers->lastPage() > 1)
                        {{ $drivers->appends(['search' => request('search'), 'position' => request('position')])->links() }}
                    @else
                        <nav>
                            <ul class="pagination">
                                <li class="page-item disabled">
                                    <span class="page-link">Prev</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.action-buttons .btn {
    font-size: 10px;          /* smaller text */
    padding: 2px 6px;         /* slightly wider padding */
    line-height: 1;           
    height: 25px;             /* small consistent height */
    min-width: 50px;          /* ensures buttons aren't too narrow */
    display: inline-flex;     
    align-items: center;      
    justify-content: center;
    gap: 3px;                 /* small gap between icon and text */
    border-radius: 4px;       /* tight corners */
}

/* Icons inside buttons */
.action-buttons .btn i {
    font-size: 10px;
    margin-right: 2px;
}

/* Colors (same as before) - More specific to override Bootstrap */
.action-buttons .view-btn {
    color: #0d6efd !important;
    border: 1px solid #0d6efd !important;
    background-color: transparent !important;
}
.action-buttons .view-btn:hover {
    background-color: #0d6efd !important;
    color: #fff !important;
    border-color: #0d6efd !important;
}

.action-buttons .edit-btn {
    color: #ffc107 !important;
    border: 1px solid #ffc107 !important;
    background-color: transparent !important;
}
.action-buttons .edit-btn:hover {
    background-color: #ffc107 !important;
    color: #000 !important;
    border-color: #ffc107 !important;
}

.action-buttons .archive-btn {
    color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    background-color: transparent !important;
}
.action-buttons .archive-btn:hover {
    background-color: #dc3545 !important;
    color: #fff !important;
    border-color: #dc3545 !important;
}

.btn-outline-danger {
    font-size: 12px;
    padding: 5px 10px;
}

.highlight-updated {
    background-color: #d4edda !important; /* light green */
    transition: background-color 0.5s ease;
}

/* Pagination styling */
.pagination {
    display: flex;
    justify-content: flex-end;
}

.pagination .page-link {
    color: #1e6031;
    border-color: #1e6031;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.pagination .page-link:hover {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

.pagination .active .page-link {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

/* Hide default Previous/Next links and style custom ones */
.page-item:first-child .page-link,
.page-item:last-child .page-link {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

.page-item:first-child .page-link:hover,
.page-item:last-child .page-link:hover {
    background-color: #164f2a;
    border-color: #164f2a;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
}

.items-per-page {
    display: flex;
    align-items: center;
    min-width: 100px;
}

/* Manual pagination for single page */
.pagination {
    display: flex;
    list-style: none;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    display: block;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    color: #1e6031;
    background-color: #fff;
    border: 1px solid #1e6031;
    text-decoration: none;
    border-radius: 0.25rem;
}

.page-item.active .page-link {
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
    cursor: not-allowed;
}
</style>

<!-- Add Driver Modal -->
<div class="modal fade" id="addDriverModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><!-- default width -->
    <div class="modal-content rounded-3 shadow">

      <!-- Header -->
      <div class="modal-header d-flex align-items-center" style="background-color:#1e6031; color:white;">
        <h5 class="modal-title fw-bold mb-0 flex-grow-1">Add New Driver</h5>
      </div>

      <!-- Form -->
      <form id="addDriverForm" action="{{ route('drivers.store') }}" method="POST">
        @csrf
        <div class="modal-body px-3 py-2">

          <!-- First Name, Middle Initial, Last Name Row -->
          <div class="row mb-2">
            <div class="col-md-4">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">First Name <span class="text-danger">*</span></label>
              <input type="text" name="first_name" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter first name" required>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Middle Initial</label>
              <input type="text" name="middle_initial" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter M.I." maxlength="10">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Last Name <span class="text-danger">*</span></label>
              <input type="text" name="last_name" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter last name" required>
            </div>
          </div>

          <!-- Contact Number Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Contact Number <span class="text-danger">*</span></label>
            <input type="text" name="contact_num" id="contactNumber" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter 11-digit contact number" required maxlength="11">
            <div class="invalid-feedback" style="display: none;">Please enter exactly 11 digits (numbers only)</div>
          </div>

          <!-- Email Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter email address" required>
            <div class="invalid-feedback" style="display: none;">Please enter a valid email address</div>
          </div>

          <!-- Address Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Address <span class="text-danger">*</span></label>
            <textarea name="address" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 42px;" rows="2" placeholder="Enter full address" required></textarea>
          </div>

          <!-- Position and Official Station Row -->
          <div class="row mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Position <span class="text-danger">*</span></label>
              <input type="text" name="position" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter position" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Official Station <span class="text-danger">*</span></label>
              <input type="text" name="official_station" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter official station" required>
            </div>
          </div>

          <!-- Password Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" id="password" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter password" required>
            <div class="invalid-feedback" style="display: none;">Password must be at least 8 characters long</div>
          </div>

          <!-- Confirm Password Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Confirm password" required>
            <div class="invalid-feedback" style="display: none;">Passwords do not match</div>
          </div>

          <!-- Hidden Availability Status (default: Available) -->
          <input type="hidden" name="availability_status" value="Available">

        </div>

        <!-- Footer -->
       <div class="modal-footer py-1 justify-content-end">
  <button type="button" class="btn btn-sm btn-outline-secondary me-2 py-1" style="font-size: 0.75rem; height: 30px;" data-bs-dismiss="modal" id="cancelDriverBtn">
    Cancel
  </button>
  <button type="submit" class="btn btn-sm btn-success py-1" style="font-size: 0.75rem; height: 30px;">
    Save
  </button>
</div>

      </form>
    </div>
  </div>
</div>

<!-- Edit Driver Modal -->
<div class="modal fade" id="editDriverModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><!-- default width -->
    <div class="modal-content rounded-3 shadow">

      <!-- Header -->
      <div class="modal-header d-flex align-items-center" style="background-color:#1e6031; color:white;">
        <h5 class="modal-title fw-bold mb-0 flex-grow-1">Edit Driver</h5>
      </div>

      <!-- Form -->
      <form id="editDriverForm" method="POST">
        @method('PUT')
        @csrf
        <div class="modal-body px-3 py-2">

          <!-- First Name, Middle Initial, Last Name Row -->
          <div class="row mb-2">
            <div class="col-md-4">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">First Name <span class="text-danger">*</span></label>
              <input type="text" name="first_name" id="edit_first_name" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter first name" required>
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Middle Initial</label>
              <input type="text" name="middle_initial" id="edit_middle_initial" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter M.I." maxlength="10">
            </div>
            <div class="col-md-4">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Last Name <span class="text-danger">*</span></label>
              <input type="text" name="last_name" id="edit_last_name" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter last name" required>
            </div>
          </div>

          <!-- Email Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="edit_email" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter email address" required>
          </div>

          <!-- Address Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Address <span class="text-danger">*</span></label>
            <textarea name="address" id="edit_address" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 42px;" rows="2" placeholder="Enter full address" required></textarea>
          </div>

          <!-- Contact Number Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Contact Number <span class="text-danger">*</span></label>
            <input type="text" name="contact_num" id="edit_contact_num" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter 11-digit contact number" required maxlength="11">
            <div class="invalid-feedback" style="display: block;">Please enter exactly 11 digits (numbers only)</div>
          </div>

          <!-- Position and Official Station Row -->
          <div class="row mb-2">
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Position <span class="text-danger">*</span></label>
              <input type="text" name="position" id="edit_position" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter position" required>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Official Station <span class="text-danger">*</span></label>
              <input type="text" name="official_station" id="edit_official_station" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter official station" required>
            </div>
          </div>

          <!-- Password Row (optional for editing) -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Password (Leave blank to keep current)</label>
            <input type="password" name="password" id="edit_password" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Enter new password">
          </div>

          <!-- Confirm Password Row -->
          <div class="mb-2">
            <label class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Confirm Password</label>
            <input type="password" name="password_confirmation" id="edit_password_confirmation" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" placeholder="Confirm new password">
          </div>

        </div>

        <!-- Footer -->
        <div class="modal-footer py-1 justify-content-end">
          <button type="button" class="btn btn-sm btn-outline-secondary me-2 py-1" style="font-size: 0.75rem; height: 30px;" data-bs-dismiss="modal" id="cancelEditDriverBtn">
            Cancel
          </button>
          <button type="submit" class="btn btn-sm btn-success py-1" style="font-size: 0.75rem; height: 30px;">
            Save Changes
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
    // Handle search input
    const searchInput = document.getElementById('searchForm').querySelector('input[name="search"]');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const search = this.value;
            let url = new URL('{{ route("drivers.index") }}', window.location.origin);
            
            if (search) {
                url.searchParams.set('search', search);
            }
            
            window.location.href = url.toString();
        }, 500);
    });
    
    // Handle position filter change
    document.getElementById('positionFilter').addEventListener('change', function() {
        const positionValue = this.value;
        const searchValue = document.querySelector('input[name="search"]').value;
        
        let url = new URL(window.location);
        
        if (positionValue) {
            url.searchParams.set('position', positionValue);
        } else {
            url.searchParams.delete('position');
        }
        
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }
        
        window.location.href = url.toString();
    });
    
    // Handle delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to archive this driver? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, archive it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    
    // Clear form when Cancel button is clicked
    document.getElementById('cancelDriverBtn').addEventListener('click', function() {
        document.getElementById('addDriverForm').reset();
        
        // Clear validation styling and error messages
        const contactInput = document.getElementById('contactNumber');
        if (contactInput) {
            contactInput.classList.remove('is-valid', 'is-invalid');
            // Hide error message
            const contactError = contactInput.parentNode.querySelector('.invalid-feedback');
            if (contactError) {
                contactError.style.display = 'none';
            }
        }
        
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.classList.remove('is-valid', 'is-invalid');
            // Hide error message
            const emailError = emailInput.parentNode.querySelector('.invalid-feedback');
            if (emailError) {
                emailError.style.display = 'none';
            }
        }
        
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.classList.remove('is-valid', 'is-invalid');
            // Hide error message
            const passwordError = passwordInput.parentNode.querySelector('.invalid-feedback');
            if (passwordError) {
                passwordError.style.display = 'none';
            }
        }
        
        const confirmPasswordInput = document.getElementById('password_confirmation');
        if (confirmPasswordInput) {
            confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
            // Hide error message
            const confirmPasswordError = confirmPasswordInput.parentNode.querySelector('.invalid-feedback');
            if (confirmPasswordError) {
                confirmPasswordError.style.display = 'none';
            }
        }
        
        // Reset touch flags
        confirmPasswordTouched = false;
    });
</script>

<script>
    function filterByPosition() {
        const position = document.getElementById('positionFilter').value;
        const search = document.querySelector('input[name="search"]').value;
        const url = new URL(window.location);
        
        if (position) {
            url.searchParams.set('position', position);
        } else {
            url.searchParams.delete('position');
        }
        
        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }
        
        window.location.href = url.toString();
    }

    function generateDriversPDF() {
        // Get current filters
        const position = document.getElementById('positionFilter').value;
        const search = document.querySelector('input[name="search"]').value;
        
        // Build URL with current filters
        const url = new URL('{{ route("drivers.generate-pdf") }}', window.location.origin);
        if (position) {
            url.searchParams.set('position', position);
        }
        if (search) {
            url.searchParams.set('search', search);
        }
        
        // Open PDF in new tab
        window.open(url.toString(), '_blank');
    }

    // Add event listener for search input
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const position = document.getElementById('positionFilter').value;
                    const search = searchInput.value;
                    const url = new URL(window.location);
                    
                    if (search) {
                        url.searchParams.set('search', search);
                    } else {
                        url.searchParams.delete('search');
                    }
                    
                    // Preserve position filter
                    if (position) {
                        url.searchParams.set('position', position);
                    }
                    
                    window.location.href = url.toString();
                }
            });
        }
        
        // Set the position filter to the current value from URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentPosition = urlParams.get('position');
        if (currentPosition) {
            const positionFilter = document.getElementById('positionFilter');
            if (positionFilter) {
                positionFilter.value = currentPosition;
            }
        }
    });
</script>

<!-- Contact Number Validation -->
<script>
  // Contact number validation - numbers only and exactly 11 digits
  document.addEventListener('DOMContentLoaded', function() {
    // Add validation for edit form contact number as well
    const editContactInput = document.getElementById('edit_contact_num');
    if (editContactInput) {
      editContactInput.addEventListener('input', function(e) {
        // Remove any non-digit characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to 11 digits
        if (this.value.length > 11) {
          this.value = this.value.slice(0, 11);
        }
        
        // Add validation styling
        if (this.value.length < 11) {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
          // Show the helper text when invalid
          const lengthHelper = this.parentNode.querySelector('.invalid-feedback');
          if (lengthHelper) {
            lengthHelper.textContent = 'Please enter exactly 11 digits (numbers only)';
            lengthHelper.style.display = 'block';
          }
        } else if (this.value.length === 11) {
          // Check if it's a valid 11-digit number
          if (/^\d{11}$/.test(this.value)) {
            // Valid length, remove error and wait for duplicate check
            this.classList.remove('is-invalid');
            this.classList.remove('is-valid'); // Don't add is-valid until duplicate check passes
            // Hide the length error message
            const lengthHelper = this.parentNode.querySelector('.invalid-feedback');
            if (lengthHelper) {
              lengthHelper.style.display = 'none';
            }
          } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
            // Show the helper text when invalid
            const lengthHelper = this.parentNode.querySelector('.invalid-feedback');
            if (lengthHelper) {
              lengthHelper.textContent = 'Please enter exactly 11 digits (numbers only)';
              lengthHelper.style.display = 'block';
            }
          }
        }
      });
      
      // Check for duplicate contact number on blur (after typing is complete) for edit form
      editContactInput.addEventListener('blur', function() {
        const contactNumber = this.value.replace(/[^0-9]/g, ''); // Clean the input
        
        if (this.value.length === 11) {
          fetch(`/api/check-contact-number/${contactNumber}`)
            .then(response => response.json())
            .then(data => {
              if (data.exists) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                // Show duplicate error message
                const helperText = this.parentNode.querySelector('.invalid-feedback');
                if (helperText) {
                  helperText.textContent = 'This contact number is already registered. Please use a different number.';
                  helperText.style.display = 'block';
                }
              } else {
                // Valid contact number (correct length and not duplicate)
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                // Hide error message
                const helperText = this.parentNode.querySelector('.invalid-feedback');
                if (helperText) {
                  helperText.style.display = 'none';
                }
              }
            })
            .catch(error => {
              console.error('Error checking contact number:', error);
            });
        }
      });
      
      // Reset to length validation message when user starts typing again (edit form)
      editContactInput.addEventListener('input', function() {
        // Reset to length validation message when user starts typing
        if (this.value.length < 11) {
          const helperText = this.parentNode.querySelector('.invalid-feedback');
          if (helperText) {
            helperText.textContent = 'Please enter exactly 11 digits (numbers only)';
            helperText.style.display = 'block';
          }
        }
      });
    }
    
    // Add email validation
    const emailInput = document.getElementById('email');
    const editEmailInput = document.getElementById('edit_email');
    
    // Email validation for add form
    if (emailInput) {
      // Validate email format on input
      emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
          // Show email format error
          const formatHelper = this.parentNode.querySelector('.invalid-feedback:not(#emailDuplicateError)');
          if (formatHelper) {
            formatHelper.style.display = 'block';
          }
          // Hide duplicate error
          const duplicateHelper = this.parentNode.querySelector('#emailDuplicateError');
          if (duplicateHelper) {
            duplicateHelper.style.display = 'none';
          }
        } else {
          this.classList.remove('is-invalid');
          // Don't add is-valid yet, wait until validation is complete
        }
      });
      
      // Check for duplicate email on blur
      emailInput.addEventListener('blur', function() {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && emailRegex.test(email)) {
          fetch(`/api/check-email/${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
              if (data.exists) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                // Show duplicate error message
                const helperText = this.parentNode.querySelector('.invalid-feedback');
                if (helperText) {
                  helperText.textContent = 'This email is already registered';
                  helperText.style.display = 'block';
                }
              } else {
                // Valid email
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                // Hide error message
                const helperText = this.parentNode.querySelector('.invalid-feedback');
                if (helperText) {
                  helperText.style.display = 'none';
                }
              }
            })
            .catch(error => {
              console.error('Error checking email:', error);
            });
        }
      });
      
      // Reset to email format validation when user starts typing again
      emailInput.addEventListener('input', function() {
        // Reset to format validation message when user starts typing
        const helperText = this.parentNode.querySelector('.invalid-feedback');
        if (helperText) {
          helperText.textContent = 'Please enter a valid email address';
          helperText.style.display = 'block';
        }
      });
    }
    
    // Email validation for edit form
    if (editEmailInput) {
      // Validate email format on input
      editEmailInput.addEventListener('input', function() {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
          // Show email format error
          const formatHelper = this.parentNode.querySelector('.invalid-feedback:not(#emailDuplicateError)');
          if (formatHelper) {
            formatHelper.style.display = 'block';
          }
          // Hide duplicate error
          const duplicateHelper = this.parentNode.querySelector('#emailDuplicateError');
          if (duplicateHelper) {
            duplicateHelper.style.display = 'none';
          }
        } else {
          this.classList.remove('is-invalid');
          // Don't add is-valid yet, wait until validation is complete
        }
      });
      
      // Check for duplicate email on blur
      editEmailInput.addEventListener('blur', function() {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && emailRegex.test(email)) {
          fetch(`/api/check-email/${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
              if (data.exists) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                // Show duplicate error message
                const helperText = this.parentNode.querySelector('.invalid-feedback');
                if (helperText) {
                  helperText.textContent = 'This email is already registered';
                  helperText.style.display = 'block';
                }
              } else {
                // Valid email
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                // Hide error message
                const helperText = this.parentNode.querySelector('.invalid-feedback');
                if (helperText) {
                  helperText.style.display = 'none';
                }
              }
            })
            .catch(error => {
              console.error('Error checking email:', error);
            });
        }
      });
      
      // Reset to email format validation when user starts typing again
      editEmailInput.addEventListener('input', function() {
        // Reset to format validation message when user starts typing
        const helperText = this.parentNode.querySelector('.invalid-feedback');
        if (helperText) {
          helperText.textContent = 'Please enter a valid email address';
          helperText.style.display = 'block';
        }
      });
    }
    
    // Password validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    // Track if user has interacted with the confirm password field
    let confirmPasswordTouched = false;
    
    if (passwordInput) {
      // Validate password strength on input
      passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length < 8) {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
          // Show password error
          const helper = this.parentNode.querySelector('.invalid-feedback');
          if (helper) {
            helper.style.display = 'block';
          }
        } else {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
          // Hide error
          const helper = this.parentNode.querySelector('.invalid-feedback');
          if (helper) {
            helper.style.display = 'none';
          }
        }
        
        // Re-validate confirm password when password changes
        if (confirmPasswordInput && confirmPasswordTouched) {
          validatePasswordMatch();
        }
      });
    }
    
    if (confirmPasswordInput) {
      // Mark that user has interacted with confirm password field
      confirmPasswordInput.addEventListener('focus', function() {
        confirmPasswordTouched = true;
      });
      
      // Validate confirm password match
      confirmPasswordInput.addEventListener('input', function() {
        if (!confirmPasswordTouched) {
          confirmPasswordTouched = true;
        }
        validatePasswordMatch();
      });
      
      // Also validate on blur
      confirmPasswordInput.addEventListener('blur', function() {
        validatePasswordMatch();
      });
    }
    
    // Function to validate password match
    function validatePasswordMatch() {
      if (passwordInput && confirmPasswordInput) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword && password && password !== confirmPassword) {
          confirmPasswordInput.classList.remove('is-valid');
          confirmPasswordInput.classList.add('is-invalid');
          // Show confirm password error
          const helper = confirmPasswordInput.parentNode.querySelector('.invalid-feedback');
          if (helper) {
            helper.style.display = 'block';
          }
        } else if (confirmPassword && password && password === confirmPassword) {
          confirmPasswordInput.classList.remove('is-invalid');
          confirmPasswordInput.classList.add('is-valid');
          // Hide confirm password error
          const helper = confirmPasswordInput.parentNode.querySelector('.invalid-feedback');
          if (helper) {
            helper.style.display = 'none';
          }
        } else {
          // If confirm password is empty or password is empty, remove validation classes
          // Only show error if user has touched the field and both fields have values
          if (confirmPasswordTouched && confirmPassword) {
            confirmPasswordInput.classList.remove('is-valid');
            confirmPasswordInput.classList.add('is-invalid');
            const helper = confirmPasswordInput.parentNode.querySelector('.invalid-feedback');
            if (helper) {
              helper.style.display = 'block';
            }
          } else {
            confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
            const helper = confirmPasswordInput.parentNode.querySelector('.invalid-feedback');
            if (helper) {
              helper.style.display = 'none';
            }
          }
        }
      }
    }
    
    // Similar validation for edit form password fields
    const editPasswordInput = document.getElementById('edit_password');
    const editConfirmPasswordInput = document.getElementById('edit_password_confirmation');
    let editConfirmPasswordTouched = false;
    
    if (editPasswordInput) {
      editPasswordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length < 8) {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
          // Show password error
          const helper = this.parentNode.querySelector('.invalid-feedback');
          if (helper) {
            helper.style.display = 'block';
          }
        } else {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
          // Hide error
          const helper = this.parentNode.querySelector('.invalid-feedback');
          if (helper) {
            helper.style.display = 'none';
          }
        }
        
        // Re-validate confirm password when password changes
        if (editConfirmPasswordInput && editConfirmPasswordTouched) {
          validateEditPasswordMatch();
        }
      });
    }
    
    if (editConfirmPasswordInput) {
      // Mark that user has interacted with confirm password field
      editConfirmPasswordInput.addEventListener('focus', function() {
        editConfirmPasswordTouched = true;
      });
      
      // Validate confirm password match
      editConfirmPasswordInput.addEventListener('input', function() {
        if (!editConfirmPasswordTouched) {
          editConfirmPasswordTouched = true;
        }
        validateEditPasswordMatch();
      });
      
      // Also validate on blur
      editConfirmPasswordInput.addEventListener('blur', function() {
        validateEditPasswordMatch();
      });
    }
    
    // Function to validate edit password match
    function validateEditPasswordMatch() {
      if (editPasswordInput && editConfirmPasswordInput) {
        const password = editPasswordInput.value;
        const confirmPassword = editConfirmPasswordInput.value;
        
        if (confirmPassword && password && password !== confirmPassword) {
          editConfirmPasswordInput.classList.remove('is-valid');
          editConfirmPasswordInput.classList.add('is-invalid');
          // Show confirm password error
          const helper = editConfirmPasswordInput.parentNode.querySelector('.invalid-feedback');
          if (helper) {
            helper.style.display = 'block';
          }
        } else if (confirmPassword && password && password === confirmPassword) {
          editConfirmPasswordInput.classList.remove('is-invalid');
          editConfirmPasswordInput.classList.add('is-valid');
          // Hide confirm password error
          const helper = editConfirmPasswordInput.parentNode.querySelector('.invalid-feedback');
          if (helper) {
            helper.style.display = 'none';
          }
        } else {
          // If confirm password is empty or password is empty, remove validation classes
          // Only show error if user has touched the field and both fields have values
          if (editConfirmPasswordTouched && confirmPassword) {
            editConfirmPasswordInput.classList.remove('is-valid');
            editConfirmPasswordInput.classList.add('is-invalid');
            const helper = editConfirmPasswordInput.parentNode.querySelector('.invalid-feedback');
            if (helper) {
              helper.style.display = 'block';
            }
          } else {
            editConfirmPasswordInput.classList.remove('is-valid', 'is-invalid');
            const helper = editConfirmPasswordInput.parentNode.querySelector('.invalid-feedback');
            if (helper) {
              helper.style.display = 'none';
            }
          }
        }
      }
    }
    
    const contactInput = document.getElementById('contactNumber');
    
    if (contactInput) {
      contactInput.addEventListener('input', function(e) {
        // Remove any non-digit characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to 11 digits
        if (this.value.length > 11) {
          this.value = this.value.slice(0, 11);
        }
        
        // Add validation styling
        if (this.value.length < 11) {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
          // Show the helper text when invalid
          const lengthHelper = this.parentNode.querySelector('.invalid-feedback');
          if (lengthHelper) {
            lengthHelper.textContent = 'Please enter exactly 11 digits (numbers only)';
            lengthHelper.style.display = 'block';
          }
        } else if (this.value.length === 11) {
          // Check if it's a valid 11-digit number
          if (/^\d{11}$/.test(this.value)) {
            // Valid length, remove error and wait for duplicate check
            this.classList.remove('is-invalid');
            this.classList.remove('is-valid'); // Don't add is-valid until duplicate check passes
            // Hide the length error message
            const lengthHelper = this.parentNode.querySelector('.invalid-feedback');
            if (lengthHelper) {
              lengthHelper.style.display = 'none';
            }
          } else {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
            // Show the helper text when invalid
            const lengthHelper = this.parentNode.querySelector('.invalid-feedback');
            if (lengthHelper) {
              lengthHelper.textContent = 'Please enter exactly 11 digits (numbers only)';
              lengthHelper.style.display = 'block';
            }
          }
        }
      });
      
      // Check for duplicate contact number on blur (after typing is complete)
      contactInput.addEventListener('blur', function() {
        const contactNumber = this.value.replace(/[^0-9]/g, ''); // Clean the input
        
        if (this.value.length === 11) {
          fetch(`/api/check-contact-number/${contactNumber}`)
            .then(response => response.json())
            .then(data => {
              if (data.exists) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                // Show duplicate error message
                const helperText = this.parentNode.querySelector('.invalid-feedback');
                if (helperText) {
                  helperText.textContent = 'This contact number is already registered. Please use a different number.';
                  helperText.style.display = 'block';
                }
              } else {
                // Valid contact number (correct length and not duplicate)
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                // Hide error message
                const helperText = this.parentNode.querySelector('.invalid-feedback');
                if (helperText) {
                  helperText.style.display = 'none';
                }
              }
            })
            .catch(error => {
              console.error('Error checking contact number:', error);
            });
        }
      });
      
      // Reset to length validation message when user starts typing again
      contactInput.addEventListener('input', function() {
        // Reset to length validation message when user starts typing
        if (this.value.length < 11) {
          const helperText = this.parentNode.querySelector('.invalid-feedback');
          if (helperText) {
            helperText.textContent = 'Please enter exactly 11 digits (numbers only)';
            helperText.style.display = 'block';
          }
        }
      });
      
      // Handle Edit button clicks
      document.querySelectorAll('.edit-btn').forEach(button => {
          button.addEventListener('click', function() {
              const driverId = this.dataset.driverId;
              // Load driver data dynamically
              const allDrivers = @json($drivers->toArray());
              const driverData = {};
              allDrivers.forEach(driver => {
                  driverData[driver.id] = {
                      id: driver.id,
                      firsts_name: driver.firsts_name,
                      middle_initial: driver.middle_initial,
                      last_name: driver.last_name,
                      email: driver.email,
                      address: driver.address,
                      contact_num: driver.user ? driver.user.contact_num : null,
                      position: driver.position,
                      official_station: driver.official_station,
                  };
              });
              const driver = driverData[driverId];
              console.log('Driver data:', driver); // Debug line
              
              if (driver) {
                  // Populate the edit form with driver data
                  document.getElementById('edit_first_name').value = driver.firsts_name || '';
                  document.getElementById('edit_middle_initial').value = driver.middle_initial || '';
                  document.getElementById('edit_last_name').value = driver.last_name || '';
                  document.getElementById('edit_email').value = driver.email || '';
                  document.getElementById('edit_address').value = driver.address || '';
                  document.getElementById('edit_contact_num').value = driver.contact_num || '';
                  document.getElementById('edit_position').value = driver.position || '';
                  document.getElementById('edit_official_station').value = driver.official_station || '';
                  
                  // Set the form action to the update route
                  const editForm = document.getElementById('editDriverForm');
                  editForm.action = `/drivers/${driverId}`;
                  
                  // Show the modal
                  const editModal = new bootstrap.Modal(document.getElementById('editDriverModal'));
                  editModal.show();
              }
          });
      });
      
      // Handle Edit form submission
      const editForm = document.getElementById('editDriverForm');
      let isEditSubmitting = false; // Prevent multiple submissions
      
      if (editForm) {
        editForm.addEventListener('submit', function(e) {
          e.preventDefault(); // Always prevent default form submission since we're using AJAX
          
          // Prevent multiple submissions
          if (isEditSubmitting) {
            return false;
          }
          
          isEditSubmitting = true; // Set flag to prevent multiple submissions
          
          // Show loading state with SweetAlert for 2 seconds
          Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we save the driver information.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            timer: 2000,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          
          // Submit form via AJAX
          const formData = new FormData(editForm);
          fetch(editForm.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'X-Requested-With': 'XMLHttpRequest',
              'X-HTTP-Method-Override': 'PUT'
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              // Close the loading SweetAlert and show success message for 2 seconds
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Driver has been updated successfully!',
                showConfirmButton: false,
                timer: 2000,

              });
              
              // Close the modal and reload the page after 2 seconds
              setTimeout(() => {
                // Close the modal if it's open
                const modalElement = document.getElementById('editDriverModal');
                if (modalElement) {
                  const modalInstance = bootstrap.Modal.getInstance(modalElement);
                  if (modalInstance) {
                    modalInstance.hide();
                  }
                }
                
                // Reset submitting flag
                isEditSubmitting = false;
                
                // Reload the page after showing success message
                window.location.reload();
              }, 2000);
            } else {
              // Close the loading SweetAlert and show error message
              Swal.close();
              
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Failed to update driver. Please try again.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
              });
            }
          })
          .catch(error => {
            console.error('Error updating driver:', error);
            
            // Close the loading SweetAlert and show error message
            Swal.close();
            
            // Reset submitting flag
            isEditSubmitting = false;
            
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'An unexpected error occurred. Please try again.',
              showConfirmButton: true,
              confirmButtonText: 'OK'
            });
          });
        });
      }
      
      // Form submission validation
      const form = document.getElementById('addDriverForm');
      let isSubmitting = false; // Prevent multiple submissions
      
      if (form) {
        form.addEventListener('submit', function(e) {
          e.preventDefault(); // Always prevent default form submission since we're using AJAX
          
          // Prevent multiple submissions
          if (isSubmitting) {
            return false;
          }
          
          const passwordInput = document.getElementById('password');
          const confirmPasswordInput = document.getElementById('password_confirmation');
          const contactInput = document.getElementById('contactNumber');
          const emailInput = document.getElementById('email');
          
          // Get values
          const password = passwordInput.value;
          const confirmPassword = confirmPasswordInput.value;
          const contactNumber = contactInput.value.replace(/[^0-9]/g, ''); // Clean the input
          const email = emailInput.value.trim();
          
          // Track validation status
          let isValid = true;
          
          // Validate contact number
          if (contactNumber.length !== 11) {
            contactInput.classList.remove('is-valid');
            contactInput.classList.add('is-invalid');
            const lengthHelper = contactInput.parentNode.querySelector('.invalid-feedback:not(#contactDuplicateError)');
            if (lengthHelper) {
              lengthHelper.style.display = 'block';
              lengthHelper.textContent = 'Contact number must be exactly 11 digits!';
            }
            isValid = false;
          }
          
          // Validate contact number is unique
          if (contactNumber.length === 11 && !contactInput.classList.contains('is-valid')) {
            contactInput.classList.remove('is-valid');
            contactInput.classList.add('is-invalid');
            const helperText = contactInput.parentNode.querySelector('.invalid-feedback');
            if (helperText) {
              helperText.style.display = 'block';
              helperText.textContent = 'This contact number is already registered. Please use a different number.';
            }
            isValid = false;
          }
          
          // Validate email
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(email)) {
            emailInput.classList.remove('is-valid');
            emailInput.classList.add('is-invalid');
            const formatHelper = emailInput.parentNode.querySelector('.invalid-feedback:not(#emailDuplicateError)');
            if (formatHelper) {
              formatHelper.style.display = 'block';
              formatHelper.textContent = 'Please enter a valid email address';
            }
            isValid = false;
          }
          
          // Validate password length
          if (password.length < 8) {
            passwordInput.classList.remove('is-valid');
            passwordInput.classList.add('is-invalid');
            const helper = passwordInput.parentNode.querySelector('.invalid-feedback');
            if (helper) {
              helper.style.display = 'block';
              helper.textContent = 'Password must be at least 8 characters long';
            }
            isValid = false;
          }
          
          // Validate passwords match
          if (password !== confirmPassword) {
            confirmPasswordInput.classList.remove('is-valid');
            confirmPasswordInput.classList.add('is-invalid');
            const helper = confirmPasswordInput.parentNode.querySelector('.invalid-feedback');
            if (helper) {
              helper.style.display = 'block';
              helper.textContent = 'Passwords do not match!';
            }
            isValid = false;
          }
          
          if (!isValid) {
            return false;
          }
          
          // If all validation passes, submit form
          isSubmitting = true; // Set flag to prevent multiple submissions
          
          // Show loading state with SweetAlert for 2 seconds
          Swal.fire({
            title: 'Saving...',
            text: 'Please wait while we save the driver information.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            timer: 2000,
            didOpen: () => {
              Swal.showLoading();
            }
          });
          
          // Submit form via AJAX to show success message
          const formData = new FormData(form);
          fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(async response => {
            // Log response for debugging
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
              // Handle error response
              if (response.headers.get('content-type')?.includes('application/json')) {
                const errorData = await response.json();
                // Check if this is a validation error (Laravel validation errors are in 'errors' object)
                if (errorData.errors) {
                  // Display validation errors inline
                  Object.keys(errorData.errors).forEach(field => {
                    let inputField = document.querySelector(`[name="${field}"]`);
                    if (field === 'contact_num') {
                      inputField = contactInput;
                    } else if (field === 'email') {
                      inputField = emailInput;
                    } else if (field === 'password') {
                      inputField = passwordInput;
                    }
                    
                    if (inputField) {
                      inputField.classList.remove('is-valid');
                      inputField.classList.add('is-invalid');
                      
                      // Find the appropriate error message container
                      let errorContainer;
                      if (field === 'contact_num' || field === 'email') {
                        errorContainer = inputField.parentNode.querySelector('.invalid-feedback');
                      } else {
                        errorContainer = inputField.parentNode.querySelector('.invalid-feedback');
                      }
                      
                      if (errorContainer) {
                        errorContainer.style.display = 'block';
                        errorContainer.textContent = errorData.errors[field][0];
                      }
                    }
                  });
                  throw new Error('Validation failed');
                } else {
                  throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                }
              } else {
                // If response is not JSON, try to extract error message from HTML or just show status
                const errorText = await response.text();
                console.error('Server response:', errorText);
                throw new Error(`HTTP error! status: ${response.status}`);
              }
            }
            
            return response.json();
          })
          .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
              // Close the loading SweetAlert and show success message for 2 seconds
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Driver has been added successfully!',
                showConfirmButton: false,
                timer: 2000,

              });
              
              // Close the modal and reload the page after 2 seconds
              setTimeout(() => {
                // Close the modal if it's open
                const modalElement = document.getElementById('addDriverModal');
                if (modalElement) {
                  const modalInstance = bootstrap.Modal.getInstance(modalElement);
                  if (modalInstance) {
                    modalInstance.hide();
                  }
                }
                
                // Clear the form
                form.reset();
                
                // Reset submitting flag
                isSubmitting = false;
                
                // Reload the page after showing success message
                window.location.reload();
              }, 2000);
            } else {
              // Close the loading SweetAlert and show error message
              Swal.close();
              
              Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Failed to add driver. Please try again.',
                showConfirmButton: true,
                confirmButtonText: 'OK'
              });
            }
          })
          .catch(error => {
            console.error('Error details:', error);
            
            // Close the loading SweetAlert and show error message
            Swal.close();
            
            // Reset submitting flag
            isSubmitting = false;
            
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'An unexpected error occurred. Please try again.',
              showConfirmButton: true,
              confirmButtonText: 'OK'
            });
          });
        });
      }
    }
    
    // Clear validation for edit form when Cancel button is clicked
    if (document.getElementById('cancelEditDriverBtn')) {
        document.getElementById('cancelEditDriverBtn').addEventListener('click', function() {
            // Clear validation styling and error messages for edit form
            const editEmailInput = document.getElementById('edit_email');
            if (editEmailInput) {
                editEmailInput.classList.remove('is-valid', 'is-invalid');
                // Hide error message
                const editEmailError = editEmailInput.parentNode.querySelector('.invalid-feedback');
                if (editEmailError) {
                    editEmailError.style.display = 'none';
                }
            }
            
            const editContactInput = document.getElementById('edit_contact_num');
            if (editContactInput) {
                editContactInput.classList.remove('is-valid', 'is-invalid');
                // Hide error message
                const editContactError = editContactInput.parentNode.querySelector('.invalid-feedback');
                if (editContactError) {
                    editContactError.style.display = 'none';
                }
            }
            
            const editPasswordInput = document.getElementById('edit_password');
            if (editPasswordInput) {
                editPasswordInput.classList.remove('is-valid', 'is-invalid');
                // Hide error message
                const editPasswordError = editPasswordInput.parentNode.querySelector('.invalid-feedback');
                if (editPasswordError) {
                    editPasswordError.style.display = 'none';
                }
            }
            
            const editConfirmPasswordInput = document.getElementById('edit_password_confirmation');
            if (editConfirmPasswordInput) {
                editConfirmPasswordInput.classList.remove('is-valid', 'is-invalid');
                // Hide error message
                const editConfirmPasswordError = editConfirmPasswordInput.parentNode.querySelector('.invalid-feedback');
                if (editConfirmPasswordError) {
                    editConfirmPasswordError.style.display = 'none';
                }
            }
            
            // Reset touch flags
            editConfirmPasswordTouched = false;
        });
    }
  });
</script>
@endsection