<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-center items-center">
            <!-- Centered Content -->
            <div class="flex justify-between items-center" style="width: 100%; max-width: 1200px;">
                <!-- Left: Heading -->
                <h2 class="font-bold flex items-center" style="color: #1e6031; font-size: 1.5rem; height: 32px; margin-top: 10px;">
                    <svg class="mr-2" style="width: 24px; height: 24px; color: #1e6031;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Employee Management
                </h2>

                <!-- Right: Search Bar + Add Employee Button -->
                <div class="flex items-center gap-2">
                    <!-- Search Bar -->
                    <form id="searchForm" method="GET" action="{{ route('admin.employees.index') }}">
                        <div class="flex">
                            <input type="text" name="search" class="form-input" placeholder="Search employees..." 
                                   value="{{ request('search') }}"
                                   style="height: 32px; width: 250px; font-size: 0.85rem; border: 1px solid #1e6031; border-radius: 0.375rem 0 0 0.375rem; padding: 0 12px;">
                            <button type="submit" 
                                    style="background-color: #1e6031; color: #ffffff; border: 1px solid #1e6031; height: 32px; width: 40px; border-radius: 0 0.375rem 0.375rem 0; cursor:pointer; display: flex; align-items: center; justify-content: center;">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div> 
                    </form>
                    
                    <!-- Add New Employee Button -->
                    <button type="button" class="inline-flex items-center px-3 py-1 bg-[#1e6031] border border-[#1e6031] rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150" style="height: 32px;" onclick="openAddEmployeeModal()">
                        <svg class="mr-1" style="width: 10px; height: 10px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Employee
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="-mt-5">
    <div class="flex justify-center">
        <div style="width: 100%; max-width: 1200px;">
                <!-- Filter -->
               <div class="flex justify-between items-center mb-0" style="display:flex; align-items:center; justify-content:space-between; padding:4px 0; border-bottom:1px solid #1e6031; margin-bottom:8px;">
                    <div style="display:flex; align-items:center;">
                        <label for="statusFilter" style="margin-right: 10px; font-weight: normal; font-size: 0.875rem;">Filter by Status: </label>
                        <select id="statusFilter" name="status" class="border border-gray-300 rounded-md px-2 py-1 text-sm"
                            style="width:150px; padding:6px 8px; font-size:13px; border:1px solid #ccc; border-radius:5px;">
                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status', 'all') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status', 'all') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
<!-- Employees Table -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead style="background-color: #1e6031; color: white;">
            <tr>
                <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 5%;">No.</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 30%;">Employee Name</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 20%;">Position</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Status</th>
                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Role</th>
                <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; width: 15%;">Actions</th>
            </tr>
        </thead>
        <tbody id="employees-table-body">
            @include('admin.employees.partials.table-body', ['employees' => $employees])
        </tbody>
    </table>
</div>

                <!-- Pagination -->
                <div class="flex justify-between items-center mt-4" id="pagination-container">
                    <div class="text-sm text-gray-600" id="pagination-info">
                        Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        <!-- Custom Pagination - Copied from vehicles page -->
                        <nav>
                            <ul class="pagination">
                                <li class="page-item {{ $employees->currentPage() <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link {{ $employees->currentPage() <= 1 ? 'disabled-link' : '' }}" 
                                       href="{{ $employees->currentPage() <= 1 ? 'javascript:void(0)' : $employees->previousPageUrl() }}"
                                       {{ $employees->currentPage() <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' }}>Prev</a>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">{{ $employees->currentPage() }}</span>
                                </li>
                                <li class="page-item {{ $employees->currentPage() >= $employees->lastPage() ? 'disabled' : '' }}">
                                    <a class="page-link {{ $employees->currentPage() >= $employees->lastPage() ? 'disabled-link' : '' }}" 
                                       href="{{ $employees->currentPage() >= $employees->lastPage() ? 'javascript:void(0)' : $employees->nextPageUrl() }}"
                                       {{ $employees->currentPage() >= $employees->lastPage() ? 'aria-disabled="true" tabindex="-1"' : '' }}>Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
/* Action buttons styling to match vehicles page */
.action-buttons .btn {
    font-size: 10px;
    padding: 2px 6px;
    line-height: 1;
    height: 25px;
    min-width: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    border-radius: 4px;
}

.action-buttons .btn i,
.action-buttons .btn svg {
    font-size: 10px;
    margin-right: 2px;
    width: 12px;
    height: 12px;
}

/* Colors for action buttons */
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

.action-buttons .delete-btn {
    color: #dc3545 !important;
    border: 1px solid #dc3545 !important;
    background-color: transparent !important;
}

.action-buttons .delete-btn:hover {
    background-color: #dc3545 !important;
    color: #fff !important;
    border-color: #dc3545 !important;
}

/* Pagination styling - Simplified version */
.pagination {
    display: flex;
    justify-content: flex-end;
    list-style: none;
}

.pagination .page-link {
    color: #1e6031 !important;
    padding: 0.15rem 0.4rem;
    font-size: 0.8125rem;
    display: block;
    text-decoration: none;
    background-color: #fff !important;
    border: 1px solid #1e6031;
    border-radius: 0.25rem;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
    cursor: not-allowed;
}

.page-item:not(.disabled) .page-link:hover {
    background-color: #1e6031 !important;
    color: white !important;
}

.page-link.disabled-link {
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
    pointer-events: none;
}

.pagination .active .page-link {
    background-color: #1e6031 !important;
    color: white !important;
    font-weight: bold;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
}

.page-item {
    margin: 0 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status filter change
    const statusFilter = document.getElementById('statusFilter');
    if(statusFilter) {
        statusFilter.addEventListener('change', function() {
            const searchValue = document.querySelector('[name="search"]').value;
            const statusValue = this.value;
            
            // Build new URL with filters
            let url = new URL(window.location.href);
            url.searchParams.set('search', searchValue);
            if(statusValue && statusValue !== 'all') {
                url.searchParams.set('status', statusValue);
            } else {
                url.searchParams.delete('status');
            }
            
            window.location.href = url.toString();
        });
    }
    
    // Handle search form submission
    const searchForm = document.getElementById('searchForm');
    if(searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = document.querySelector('[name="search"]');
            if(searchInput && searchInput.value.trim() === '') {
                // If search is empty, redirect to base URL to remove all parameters
                window.location.href = '{{ route("admin.employees.index") }}';
                e.preventDefault();
            }
        });
    }
});
</script>

    <!-- Add Employee Modal -->
    @include('admin.employees.modals.create-employee-modal')
    
    <!-- Edit Employee Modal -->
    @include('admin.employees.modals.edit-employee-modal')
    
    <!-- View Employee Modal -->
    @include('admin.employees.modals.view-employee-modal')
    
    <!-- Pass cascading data to JavaScript -->
    <script>
        // Make cascading data available globally
        window.cascadingData = @json($cascadingData);
    </script>
    
    <script>
        // Function to open the add employee modal
        function openAddEmployeeModal() {
            var myModal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
            myModal.show();
        }
        
        // Global variable to store cascading data
        let globalCascadingData = {};
        
        // Function to load employee data into the modal
        window.loadEmployeeData = function(employeeId) {
            console.log('Loading employee data for ID:', employeeId);
            
            // Show loading indicator
            const loadingIndicator = document.getElementById('editModalLoading');
            const personalInfoSection = document.getElementById('editPersonalInfoSection');
            const positionInfoSection = document.getElementById('editPositionInfoSection');
            const additionalPositionsSection = document.getElementById('editAdditionalPositionsSection');
            
            if (loadingIndicator) loadingIndicator.style.display = 'block';
            if (personalInfoSection) personalInfoSection.style.display = 'none';
            if (positionInfoSection) positionInfoSection.style.display = 'none';
            if (additionalPositionsSection) additionalPositionsSection.style.display = 'none';
            
            // Set form action
            const form = document.getElementById('editEmployeeForm');
            if (form) {
                form.action = '/admin/employees/' + employeeId;
            }
            
            // Check CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            console.log('CSRF token element:', csrfToken);
            console.log('CSRF token value:', csrfToken ? csrfToken.getAttribute('content') : 'Not found');
            
            if (!csrfToken) {
                console.error('CSRF token not found!');
                alert('Error: CSRF token not found. Please refresh the page.');
                return;
            }
            
            // Fetch employee data
            fetch('/admin/employees/' + employeeId + '/data', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('Full response data:', data);
                
                if (data.success) {
                    const employee = data.employee;
                    console.log('Employee data loaded:', employee);
                    console.log('Employee positions:', employee.positions);
                    console.log('Employee positions array:', Array.isArray(employee.positions) ? employee.positions : 'Not an array');
                    console.log('Employee user:', employee.user);
                    
                    // Log each position
                    if (employee.positions && Array.isArray(employee.positions)) {
                        employee.positions.forEach((pos, index) => {
                            console.log(`Position ${index}:`, {
                                id: pos.id,
                                position_name: pos.position_name,
                                is_primary: pos.is_primary,
                                class_id: pos.class_id,
                                office_id: pos.office_id,
                                division_id: pos.division_id,
                                unit_id: pos.unit_id,
                                subunit_id: pos.subunit_id
                            });
                        });
                    }
                    
                    // Populate personal information
                    const firstName = employee.first_name || '';
                    const middleName = employee.middle_name || '';
                    const lastName = employee.last_name || '';
                    const extName = employee.ext_name || '';
                    const sex = employee.sex || '';
                    const prefix = employee.prefix || '';
                    const email = employee.user?.email || '';
                    const contactNum = employee.contact_num || '';
                    const empStatus = employee.emp_status ? '1' : '0';
                    
                    console.log('Setting personal info:', { firstName, middleName, lastName, extName, sex, prefix, email, contactNum, empStatus });
                    
                    document.getElementById('edit_first_name').value = firstName;
                    document.getElementById('edit_middle_name').value = middleName;
                    document.getElementById('edit_last_name').value = lastName;
                    document.getElementById('edit_ext_name').value = extName;
                    document.getElementById('edit_sex').value = sex;
                    document.getElementById('edit_prefix').value = prefix;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_contact_num').value = contactNum;
                    document.getElementById('edit_emp_status').value = empStatus;
                    
                    // Populate primary position
                    const primaryPosition = employee.positions?.find(p => p.is_primary) || {};
                    console.log('Primary position:', primaryPosition);
                    
                    const positionName = primaryPosition.position_name || '';
                    const classId = primaryPosition.class_id || '';
                    const officeId = primaryPosition.office_id || '';
                    const divisionId = primaryPosition.division_id || '';
                    const unitId = primaryPosition.unit_id || '';
                    const subunitId = primaryPosition.subunit_id || '';
                    
                    console.log('Setting position info:', { positionName, classId, officeId, divisionId, unitId, subunitId });
                    console.log('Class ID to set:', classId);
                    
                    document.getElementById('edit_position_name').value = positionName;
                    
                    // Set class dropdown value
                    const classSelect = document.getElementById('edit_class_id');
                    if (classSelect) {
                        console.log('Class select element found, setting value to:', classId);
                        console.log('Available class options:');
                        for (let i = 0; i < classSelect.options.length; i++) {
                            console.log('Option', i, ':', classSelect.options[i].value, '=', classSelect.options[i].text);
                        }
                        classSelect.value = classId;
                        console.log('Class select value after setting:', classSelect.value);
                        
                        // If setting failed, try to find and select the option manually
                        if (classSelect.value != classId && classId) {
                            console.log('Direct value setting failed, trying manual selection');
                            for (let i = 0; i < classSelect.options.length; i++) {
                                if (classSelect.options[i].value == classId) {
                                    classSelect.selectedIndex = i;
                                    console.log('Manually selected option', i);
                                    break;
                                }
                            }
                        }
                    }
                    
                    // Set initial selections for cascading dropdowns
                    if (officeId) {
                        console.log('Setting office ID:', officeId);
                        document.getElementById('edit_office_id').value = officeId;
                        // Trigger change to populate divisions
                        const event = new Event('change');
                        document.getElementById('edit_office_id').dispatchEvent(event);
                    }
                    
                    if (divisionId) {
                        setTimeout(() => {
                            console.log('Setting division ID:', divisionId);
                            document.getElementById('edit_division_id').value = divisionId;
                            // Trigger change to populate units
                            const event = new Event('change');
                            document.getElementById('edit_division_id').dispatchEvent(event);
                        }, 100);
                    }
                    
                    if (unitId) {
                        setTimeout(() => {
                            console.log('Setting unit ID:', unitId);
                            document.getElementById('edit_unit_id').value = unitId;
                            // Trigger change to populate subunits
                            const event = new Event('change');
                            document.getElementById('edit_unit_id').dispatchEvent(event);
                        }, 200);
                    }
                    
                    if (subunitId) {
                        setTimeout(() => {
                            console.log('Setting subunit ID:', subunitId);
                            document.getElementById('edit_subunit_id').value = subunitId;
                        }, 300);
                    }
                    
                    // Populate additional positions
                    const additionalPositionsContainer = document.getElementById('edit_additionalPositionsContainer');
                    if (additionalPositionsContainer) {
                        additionalPositionsContainer.innerHTML = '';
                        
                        // Get non-primary positions
                        const additionalPositions = employee.positions?.filter(p => !p.is_primary) || [];
                        console.log('Additional positions found:', additionalPositions);
                        
                        // Populate additional positions
                        additionalPositions.forEach((position, index) => {
                            console.log('Processing additional position:', position);
                            
                            const positionDiv = document.createElement('div');
                            positionDiv.className = 'border p-3 rounded mb-3 position-relative';
                            positionDiv.dataset.positionIndex = index;
                            
                            positionDiv.innerHTML = `
                                <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-position-btn" aria-label="Remove"></button>
                                
                                <!-- First Row -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Position Name <span class="text-danger">*</span></label>
                                        <input type="hidden" name="additional_positions[${index}][id]" value="${position.id || ''}">
                                        <input type="text" name="additional_positions[${index}][position_name]" class="form-control form-control-sm border-success additional-position-name" value="${position.position_name || ''}" placeholder="Enter position name" required>
                                        <div class="invalid-feedback additional-position-error"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Class</label>
                                        <select name="additional_positions[${index}][class_id]" class="form-control form-control-sm border-success">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id_class }}" ${position.class_id == {{ $class->id_class }} ? 'selected' : ''}}>{{ addslashes($class->class_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Second Row -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Office</label>
                                        <select name="additional_positions[${index}][office_id]" class="form-control form-control-sm border-success edit-additional-office">
                                            <option value="">Select Office</option>
                                            @foreach($offices as $office)
                                                <option value="{{ $office->id }}" ${position.office_id == {{ $office->id }} ? 'selected' : ''}}>{{ addslashes($office->office_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Division</label>
                                        <select name="additional_positions[${index}][division_id]" class="form-control form-control-sm border-success edit-additional-division">
                                            <option value="">Select Division</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Third Row -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Unit</label>
                                        <select name="additional_positions[${index}][unit_id]" class="form-control form-control-sm border-success edit-additional-unit">
                                            <option value="">Select Unit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Subunit</label>
                                        <select name="additional_positions[${index}][subunit_id]" class="form-control form-control-sm border-success edit-additional-subunit">
                                            <option value="">Select Subunit</option>
                                        </select>
                                    </div>
                                </div>
                            `;
                            
                            additionalPositionsContainer.appendChild(positionDiv);
                            
                            // Add event listener to the remove button
                            positionDiv.querySelector('.remove-position-btn').addEventListener('click', function() {
                                positionDiv.remove();
                            });
                            
                            // Set up cascading dropdowns for this position
                            setTimeout(() => {
                                const officeSelect = positionDiv.querySelector('.edit-additional-office');
                                const divisionSelect = positionDiv.querySelector('.edit-additional-division');
                                const unitSelect = positionDiv.querySelector('.edit-additional-unit');
                                const subunitSelect = positionDiv.querySelector('.edit-additional-subunit');
                                
                                if (officeSelect) {
                                    officeSelect.addEventListener('change', function() {
                                        const officeId = this.value;
                                        divisionSelect.innerHTML = '<option value="">Select Division</option>';
                                        unitSelect.innerHTML = '<option value="">Select Unit</option>';
                                        subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                                        
                                        if (officeId && window.cascadingData.divisions[officeId]) {
                                            window.cascadingData.divisions[officeId].forEach(division => {
                                                divisionSelect.innerHTML += '<option value="' + division.id_division + '">' + division.division_name + '</option>';
                                            });
                                        }
                                    });
                                }
                                
                                if (divisionSelect) {
                                    divisionSelect.addEventListener('change', function() {
                                        const divisionId = this.value;
                                        unitSelect.innerHTML = '<option value="">Select Unit</option>';
                                        subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                                        
                                        if (divisionId && window.cascadingData.units[divisionId]) {
                                            window.cascadingData.units[divisionId].forEach(unit => {
                                                unitSelect.innerHTML += '<option value="' + unit.id + '">' + unit.unit_name + '</option>';
                                            });
                                        }
                                    });
                                }
                                
                                if (unitSelect) {
                                    unitSelect.addEventListener('change', function() {
                                        const unitId = this.value;
                                        subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                                        
                                        if (unitId && window.cascadingData.subunits[unitId]) {
                                            window.cascadingData.subunits[unitId].forEach(subunit => {
                                                subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '">' + subunit.subunit_name + '</option>';
                                            });
                                        }
                                    });
                                }
                                
                                // Set initial selections for all dropdowns
                                if (position.class_id) {
                                    const classSelect = positionDiv.querySelector('select[name*="[class_id]"]');
                                    if (classSelect) {
                                        console.log('Setting class ID for additional position:', position.class_id);
                                        console.log('Additional position class options:');
                                        for (let i = 0; i < classSelect.options.length; i++) {
                                            console.log('Option', i, ':', classSelect.options[i].value, '=', classSelect.options[i].text);
                                        }
                                        classSelect.value = position.class_id;
                                        
                                        // If setting failed, try manual selection
                                        if (classSelect.value != position.class_id) {
                                            console.log('Direct value setting failed for additional position, trying manual selection');
                                            for (let i = 0; i < classSelect.options.length; i++) {
                                                if (classSelect.options[i].value == position.class_id) {
                                                    classSelect.selectedIndex = i;
                                                    console.log('Manually selected additional position option', i);
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                                                            
                                if (position.office_id && officeSelect) {
                                    officeSelect.value = position.office_id;
                                    const event = new Event('change');
                                    officeSelect.dispatchEvent(event);
                                }
                                                            
                                if (position.division_id && divisionSelect) {
                                    setTimeout(() => {
                                        divisionSelect.value = position.division_id;
                                        const event = new Event('change');
                                        divisionSelect.dispatchEvent(event);
                                    }, 100);
                                }
                                                            
                                if (position.unit_id && unitSelect) {
                                    setTimeout(() => {
                                        unitSelect.value = position.unit_id;
                                        const event = new Event('change');
                                        unitSelect.dispatchEvent(event);
                                    }, 200);
                                }
                                                            
                                if (position.subunit_id && subunitSelect) {
                                    setTimeout(() => {
                                        subunitSelect.value = position.subunit_id;
                                    }, 300);
                                }
                            }, 100);
                        });
                    }
                    
                    // Hide loading and show sections
                    if (loadingIndicator) loadingIndicator.style.display = 'none';
                    if (personalInfoSection) personalInfoSection.style.display = 'block';
                    if (positionInfoSection) positionInfoSection.style.display = 'block';
                    if (additionalPositionsSection) additionalPositionsSection.style.display = 'block';
                    
                    console.log('Modal data population completed');
                    
                } else {
                    // Handle error
                    console.error('Failed to load employee data:', data.message);
                    console.error('Error details:', data);
                    alert('Error: ' + (data.message || 'Failed to load employee data.'));
                }
            })
            .catch(error => {
                console.error('Error loading employee data:', error);
                alert('Error loading employee data: ' + error.message);
            });
        };
        
        // Additional positions counter for edit modal
        let editPositionCounter = 0;
        
        // Function to open the edit employee modal
        function openEditEmployeeModal(employeeId) {
            console.log('Edit button clicked, employeeId:', employeeId);
            
            // Check if modal element exists
            const modalElement = document.getElementById('editEmployeeModal');
            if (!modalElement) {
                console.error('Edit modal element not found!');
                alert('Error: Edit modal not found. Please check the console for details.');
                return;
            }
            
            // Check if form elements exist
            const formElements = [
                'edit_first_name', 'edit_middle_name', 'edit_last_name', 'edit_ext_name',
                'edit_sex', 'edit_prefix', 'edit_email', 'edit_contact_num', 'edit_emp_status',
                'edit_position_name', 'edit_class_id', 'edit_office_id', 'edit_division_id',
                'edit_unit_id', 'edit_subunit_id'
            ];
            
            console.log('Checking form elements:');
            const missingElements = [];
            formElements.forEach(id => {
                const element = document.getElementById(id);
                if (!element) {
                    missingElements.push(id);
                }
                console.log(`${id}: ${element ? 'Found' : 'Missing'}`);
            });
            
            if (missingElements.length > 0) {
                console.error('Missing form elements:', missingElements);
                alert('Error: Missing form elements: ' + missingElements.join(', '));
                return;
            }
            
            try {
                // Load employee data and show modal
                loadEmployeeData(employeeId);
                var myModal = new bootstrap.Modal(modalElement);
                myModal.show();
                console.log('Modal opened successfully');
                
                // Reset position counter when modal opens
                editPositionCounter = 0;
                
                // Add event listener for add position button after modal is shown
                modalElement.addEventListener('shown.bs.modal', function() {
                    const addPositionBtn = document.getElementById('edit_addPositionBtn');
                    if (addPositionBtn) {
                        // Remove existing event listeners to prevent duplicates
                        const newAddPositionBtn = addPositionBtn.cloneNode(true);
                        addPositionBtn.parentNode.replaceChild(newAddPositionBtn, addPositionBtn);
                        
                        newAddPositionBtn.addEventListener('click', function() {
                            console.log('Add position button clicked');
                            
                            // Check if the primary position name is filled
                            const primaryPositionName = document.getElementById('edit_position_name');
                            if (primaryPositionName && primaryPositionName.value.trim() === '') {
                                // Show error message for primary position
                                const errorElement = document.getElementById('edit_position_name_error');
                                if (errorElement) {
                                    errorElement.textContent = 'Please fill in the primary position name first.';
                                    errorElement.style.display = 'block';
                                }
                                primaryPositionName.classList.add('is-invalid');
                                primaryPositionName.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                return;
                            }
                            
                            // Check if any existing additional position has an empty name
                            const additionalPositionInputs = document.querySelectorAll('.additional-position-name');
                            let hasEmptyPosition = false;
                            let emptyPositionInput = null;
                            
                            additionalPositionInputs.forEach(input => {
                                if (input.value.trim() === '') {
                                    hasEmptyPosition = true;
                                    emptyPositionInput = input;
                                    // Add error styling to empty position field
                                    input.classList.add('is-invalid');
                                    
                                    // Create or update error message
                                    const fieldName = input.name.replace('[]', '').replace('[', '_').replace(']', '');
                                    let errorElement = document.getElementById(fieldName + '_error');
                                    if (!errorElement) {
                                        errorElement = document.createElement('div');
                                        errorElement.id = fieldName + '_error';
                                        errorElement.className = 'invalid-feedback';
                                        input.parentNode.appendChild(errorElement);
                                    }
                                    errorElement.textContent = 'Position name is required.';
                                    errorElement.style.display = 'block';
                                } else {
                                    // Clear error if field is not empty
                                    input.classList.remove('is-invalid');
                                    const fieldName = input.name.replace('[]', '').replace('[', '_').replace(']', '');
                                    const errorElement = document.getElementById(fieldName + '_error');
                                    if (errorElement) {
                                        errorElement.style.display = 'none';
                                    }
                                }
                            });
                            
                            // If there are empty positions, don't add a new one
                            if (hasEmptyPosition && emptyPositionInput) {
                                emptyPositionInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                return;
                            }
                            
                            editPositionCounter++;
                            const container = document.getElementById('edit_additionalPositionsContainer');
                            
                            const positionDiv = document.createElement('div');
                            positionDiv.className = 'border p-3 rounded mb-3 position-relative';
                            positionDiv.dataset.positionIndex = editPositionCounter;
                            
                            positionDiv.innerHTML = `
                                <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-position-btn" aria-label="Remove"></button>
                                
                                <!-- First Row -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Position Name <span class="text-danger">*</span></label>
                                        <input type="hidden" name="additional_positions[${editPositionCounter}][id]" value="">
                                        <input type="text" name="additional_positions[${editPositionCounter}][position_name]" class="form-control form-control-sm border-success additional-position-name" placeholder="Enter position name" required>
                                        <div class="invalid-feedback additional-position-error"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Class</label>
                                        <select name="additional_positions[${editPositionCounter}][class_id]" class="form-control form-control-sm border-success">
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id_class }}">{{ addslashes($class->class_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Second Row -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Office</label>
                                        <select name="additional_positions[${editPositionCounter}][office_id]" class="form-control form-control-sm border-success edit-additional-office">
                                            <option value="">Select Office</option>
                                            @foreach($offices as $office)
                                                <option value="{{ $office->id }}">{{ addslashes($office->office_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Division</label>
                                        <select name="additional_positions[${editPositionCounter}][division_id]" class="form-control form-control-sm border-success edit-additional-division">
                                            <option value="">Select Division</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Third Row -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Unit</label>
                                        <select name="additional_positions[${editPositionCounter}][unit_id]" class="form-control form-control-sm border-success edit-additional-unit">
                                            <option value="">Select Unit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small fw-semibold text-success">Subunit</label>
                                        <select name="additional_positions[${editPositionCounter}][subunit_id]" class="form-control form-control-sm border-success edit-additional-subunit">
                                            <option value="">Select Subunit</option>
                                        </select>
                                    </div>
                                </div>
                            `;
                            
                            container.appendChild(positionDiv);
                            
                            // Add event listeners to the new dropdowns
                            setTimeout(() => {
                                const officeSelect = positionDiv.querySelector('.edit-additional-office');
                                const divisionSelect = positionDiv.querySelector('.edit-additional-division');
                                const unitSelect = positionDiv.querySelector('.edit-additional-unit');
                                const subunitSelect = positionDiv.querySelector('.edit-additional-subunit');
                                
                                if (officeSelect) {
                                    officeSelect.addEventListener('change', function() {
                                        const officeId = this.value;
                                        divisionSelect.innerHTML = '<option value="">Select Division</option>';
                                        unitSelect.innerHTML = '<option value="">Select Unit</option>';
                                        subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                                        
                                        if (officeId && window.cascadingData.divisions[officeId]) {
                                            window.cascadingData.divisions[officeId].forEach(division => {
                                                divisionSelect.innerHTML += '<option value="' + division.id_division + '">' + division.division_name + '</option>';
                                            });
                                        }
                                    });
                                }
                                
                                if (divisionSelect) {
                                    divisionSelect.addEventListener('change', function() {
                                        const divisionId = this.value;
                                        unitSelect.innerHTML = '<option value="">Select Unit</option>';
                                        subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                                        
                                        if (divisionId && window.cascadingData.units[divisionId]) {
                                            window.cascadingData.units[divisionId].forEach(unit => {
                                                unitSelect.innerHTML += '<option value="' + unit.id + '">' + unit.unit_name + '</option>';
                                            });
                                        }
                                    });
                                }
                                
                                if (unitSelect) {
                                    unitSelect.addEventListener('change', function() {
                                        const unitId = this.value;
                                        subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                                        
                                        if (unitId && window.cascadingData.subunits[unitId]) {
                                            window.cascadingData.subunits[unitId].forEach(subunit => {
                                                subunitSelect.innerHTML += '<option value="' + subunit.id_subunit + '">' + subunit.subunit_name + '</option>';
                                            });
                                        }
                                    });
                                }
                                
                                // Add event listener to the remove button
                                positionDiv.querySelector('.remove-position-btn').addEventListener('click', function() {
                                    positionDiv.remove();
                                });
                                
                                // Add input event listener to validate the new position name field in real-time
                                const newPositionInput = positionDiv.querySelector('.additional-position-name');
                                if (newPositionInput) {
                                    newPositionInput.addEventListener('input', function() {
                                        if (this.value.trim() !== '') {
                                            this.classList.remove('is-invalid');
                                            const errorElement = positionDiv.querySelector('.additional-position-error');
                                            if (errorElement) {
                                                errorElement.style.display = 'none';
                                            }
                                        }
                                    });
                                    
                                    // Add blur event listener to validate when field loses focus
                                    newPositionInput.addEventListener('blur', function() {
                                        if (this.value.trim() === '') {
                                            this.classList.add('is-invalid');
                                            const errorElement = positionDiv.querySelector('.additional-position-error');
                                            if (errorElement) {
                                                errorElement.textContent = 'Position name is required.';
                                                errorElement.style.display = 'block';
                                            }
                                        }
                                    });
                                }
                            }, 100);
                        });
                    }
                }, { once: true }); // Use once: true to prevent multiple event listeners
            } catch (error) {
                console.error('Error opening modal:', error);
                alert('Error opening modal: ' + error.message);
            }
        }
    </script>
</x-admin-layout>