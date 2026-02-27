<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Pass employee data to JavaScript
        window.employeeData = [
            @foreach(\App\Models\Employee::with('officer')->get() as $emp)
                {
                    id: {{ $emp->id }},
                    first_name: "{{ addslashes($emp->first_name) }}",
                    last_name: "{{ addslashes($emp->last_name) }}",
                    position_name: "{{ addslashes($emp->position_name) }}",
                    emp_status: {{ $emp->emp_status }},
                    is_president: {{ $emp->officer && $emp->officer->president ? 'true' : 'false' }}
                },
            @endforeach
        ];
    </script>

    <style>
    :root{
        --clsu-primary:#00703C;
        --clsu-light:#DFF0D8;
        --clsu-mid:#A8DFAA;
        --clsu-border:#00703C;
        --clsu-text:#004d00;
    }

    .stats-card{
        background:var(--clsu-light);
        border:2px solid #a3d9b1;
        border-radius:12px;
        padding:16px;
        display:flex;
        align-items:center;
        gap:14px;
        box-shadow:0 6px 12px rgba(0,112,60,0.15);
        cursor:pointer;
        transition:all 0.25s ease;
    }

    .stats-card:hover{
        transform:translateY(-4px);
        box-shadow:0 12px 20px rgba(0,112,60,0.25);
    }

    .stats-icon{
        width:42px;
        height:42px;
        border-radius:50%;
        background:var(--clsu-mid);
        display:flex;
        align-items:center;
        justify-content:center;
        flex-shrink:0;
    }

    .stats-icon svg{
        width:22px;
        height:22px;
        color:var(--clsu-primary);
    }

    .stats-number{
        font-size:20px;
        font-weight:bold;
        color:var(--clsu-primary);
    }

    .stats-label{
        font-size:12px;
        font-weight:600;
        color:var(--clsu-text);
        text-transform:uppercase;
        letter-spacing:.5px;
    }
    </style>


<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Action buttons styling to match offices page */
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

/* Remove underlines from leadership cards */
a[href*="leaders"] {
    text-decoration: none !important;
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

.page-item {
    margin: 0 2px;
}
</style>
    <div class="py-4">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 text-center">
                <h3 class="text-3xl font-bold text-[#1e6031]">
                    Organizational Leadership Hierarchy
                </h3>

                <p class="text-md text-[#1e6031]">
                    Manage leadership roles across the organization structure
                </p>
            </div>


            <!-- Leadership Status Summary -->
            <div class="mb-8">

                <div class="flex items-center mb-4">

                    <svg class="h-6 w-6 text-green-600 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 11c1.657 0 3-1.343 3-3S17.657 5 16 5s-3
                            1.343-3 3 1.343 3 3 3zM8 11c1.657 0 3-1.343 3-3S9.657
                            5 8 5 5 6.343 5 8s1.343 3 3 3zM8
                            13c-2.667 0-8 1.333-8 4v3h16v-3c0-2.667-5.333-4-8-4zM16
                            13c-.29 0-.62.02-.97.05C17.16
                            14.16 18 15.55 18 17v3h6v-3c0-2.667-5.333-4-8-4z"/>
                    </svg>

                    <h4 class="text-base font-bold text-gray-800">
                        Leadership Status Summary
                    </h4>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <!-- Presidents -->
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>

                        <div>
                            <div class="stats-number">
                                {{ \App\Models\Officer::where('president', true)->count() }}
                            </div>

                            <div class="stats-label">
                                Presidents
                            </div>
                        </div>

                    </div>


                    <!-- Vice Presidents -->
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>

                        <div>
                            <div class="stats-number">
                                {{ \App\Models\Officer::where('vp', true)->count() }}
                            </div>

                            <div class="stats-label">
                                Vice Presidents
                            </div>
                        </div>

                    </div>


                    <!-- Division Heads -->
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>

                        <div>
                            <div class="stats-number">
                                {{ \App\Models\Officer::where('division_head', true)->count() }}
                            </div>

                            <div class="stats-label">
                                Division Heads
                            </div>
                        </div>

                    </div>


                    <!-- Unit Heads -->
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>

                        <div>
                            <div class="stats-number">
                                {{ \App\Models\Officer::where('unit_head', true)->count() }}
                            </div>

                            <div class="stats-label">
                                Unit Heads
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            <!-- Navigation Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
<!-- University President Card (Light Blue) -->
<div class="flex flex-col items-center justify-center bg-blue-50 rounded-xl border-2 border-blue-200 p-6 transition-all duration-300 text-center hover:shadow-md">

    <!-- Icon -->
    <div class="rounded-lg bg-blue-100 p-3 flex-shrink-0 shadow-sm mb-3 border border-blue-100">
        <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </div>

    <!-- Header -->
    <h3 class="text-lg font-bold text-blue-600 leading-tight m-0">
        University President
    </h3>
    <p class="text-blue-500 text-xs leading-tight m-0">
        Top-level executive leadership
    </p>

    <?php
        $president = \App\Models\Officer::with('employee')->where('president', true)->first();
    ?>

    @if($president && $president->employee)
        <!-- Name + Title + Button -->
        <div class="mt-3 flex flex-col items-center">
            <p class="font-semibold text-blue-800 text-xl leading-tight m-0">
                {{ $president->employee->prefix ?? '' }} {{ $president->employee->first_name }} {{ $president->employee->middle_name ? substr($president->employee->middle_name, 0, 1).'.' : '' }} {{ $president->employee->last_name }}{{ $president->employee->ext_name ? ' '.$president->employee->ext_name : '' }}
            </p>
            <p class="text-blue-600 text-sm leading-tight m-0">Current President</p>

            <!-- Small spacing above button -->
            <button type="button" onclick="openPresidentModal()" 
                    class="mt-3 inline-block px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm border-2 border-blue-700 hover:bg-blue-700 hover:border-blue-800 transition-all">
                CHANGE PRESIDENT
            </button>
        </div>
    @else
        <div class="mt-3 flex flex-col items-center">
            <p class="font-semibold text-blue-800 text-base leading-tight m-0 text-gray-500">
                No assigned yet
            </p>

            <!-- Small spacing above button -->
            <button type="button" onclick="openPresidentModal()" 
                    class="mt-3 inline-block px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm border-2 border-blue-700 hover:bg-blue-700 hover:border-blue-800 transition-all">
                ASSIGN PRESIDENT
            </button>
        </div>
    @endif

</div>

<!-- Offices Card (Soft Violet/Purple) -->
<div class="flex flex-col items-center justify-center bg-purple-50 rounded-xl border-2 border-purple-200 p-6 transition-all duration-300 text-center hover:shadow-md">

    <!-- Icon -->
    <div class="rounded-lg bg-purple-100 p-3 flex-shrink-0 shadow-sm mb-3 border border-purple-100">
        <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
    </div>

    <!-- Header -->
    <h3 class="text-lg font-bold text-purple-600 leading-tight m-0">
        Offices
    </h3>
    <p class="text-purple-500 text-sm leading-tight m-0">
        Manage office leadership
    </p>

    <!-- Total Offices -->
    <p class="text-purple-600 text-xs font-semibold mt-3 mb-2">
        Total Offices: {{ \App\Models\Office::count() }}
    </p>

    <!-- Button (height now same as CHANGE PRESIDENT) -->
    <a href="{{ route('admin.leaders.offices') }}"
       class="inline-block px-3 py-1.5 bg-purple-600 text-white text-xs font-bold rounded-lg shadow-sm border-2 border-purple-700 hover:bg-purple-700 hover:border-purple-800 transition-all mt-1">
        MANAGE OFFICES
    </a>

</div>

            </div>

        </div>

    </div>

    <!-- Modal for President Assignment -->
    <div class="modal fade" id="presidentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                
                <!-- Modal header -->
                <div class="modal-header d-flex align-items-center" style="background-color:#1e6031; color:white;">
                    <h5 class="modal-title fw-bold mb-0">University President Assignment</h5>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body px-4 py-3">
                    <div class="alert alert-warning d-flex align-items-start mb-3" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                            <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                            <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                        </svg>
                        <div>
                            <p class="mb-1 small" style="font-size: 0.65rem;">
                                Select the University President who will oversee all operations and strategic direction.
                            </p>
                            <p class="mb-0 small" style="font-size: 0.65rem;">
                                <strong>Note:</strong> Only one President can be assigned at a time.
                            </p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_employee_id" class="form-label small fw-semibold text-success mb-2" style="font-size: 0.75rem;">
                            Select Employee
                        </label>
                        <select name="employee_id" id="modal_employee_id" 
                                class="form-select" style="font-size: 0.75rem;">
                            <option value="">Loading...</option>
                        </select>
                        <div class="form-text mt-2" style="font-size: 0.65rem;">
                            Select an employee to assign this leadership role. Choose "None" to remove the current assignment.
                        </div>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="modal-footer py-2 justify-content-end">
                    <button type="button" 
                            class="btn btn-sm btn-outline-secondary me-2 py-1"
                            style="font-size: 0.75rem; height: 30px;" 
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    
                    <button type="button" 
                            onclick="updatePresidentRole()"
                            class="btn btn-sm btn-success py-1"
                            style="font-size: 0.75rem; height: 30px; background-color: #1e6031; border-color: #1e6031;">
                        Update Leadership Role
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS if not already included -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function openPresidentModal() {
            // Load employees via AJAX
            loadEmployees();
            
            // Show the modal using Bootstrap's modal API
            const modalElement = document.getElementById('presidentModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }

        function loadEmployees() {
            // Show loading state
            const selectElement = document.getElementById('modal_employee_id');
            selectElement.innerHTML = '<option value="">Loading...</option>';
            selectElement.disabled = true;

            // Wait for the employee data to be available
            setTimeout(() => {
                // Clear the select element
                selectElement.innerHTML = '';
                
                // Add the "None" option
                const noneOption = document.createElement('option');
                noneOption.value = '';
                noneOption.textContent = 'None - Remove current assignment';
                selectElement.appendChild(noneOption);
                
                // Add employees to the select element
                window.employeeData.forEach(emp => {
                    const option = document.createElement('option');
                    option.value = emp.id;
                    option.textContent = `${emp.first_name} ${emp.last_name} - ${emp.position_name}${emp.emp_status === 0 ? ' (Inactive)' : ''}`;
                    
                    // Pre-select the current president if applicable
                    @if(isset($president) && $president->employee)
                        if (emp.id == {{ $president->employee->id }}) {
                            option.selected = true;
                        }
                    @endif
                    
                    selectElement.appendChild(option);
                });
                
                selectElement.disabled = false;
            }, 100); // Small delay to ensure data is loaded
        }

        function updatePresidentRole() {
            const selectedEmployeeId = document.getElementById('modal_employee_id').value;
            
            // Show loading alert
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while we update the leadership role.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('type', 'president');
            formData.append('employee_id', selectedEmployeeId);
            
            // Submit form via AJAX to show success message
            fetch('{{ route("admin.leaders.update") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                // Handle the response based on content type
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    // If it's JSON, parse it normally
                    return response.json();
                } else {
                    // If it's HTML (redirect response), check status to determine success/error
                    if (response.ok || response.status === 302) { // 302 is redirect
                        // Return success since it's likely a successful redirect
                        return { success: true, message: 'Leadership role updated successfully!' };
                    } else {
                        return { success: false, message: 'An error occurred while updating the leadership role.' };
                    }
                }
            })
            .then(data => {
                if(data.success) {
                    // Close the loading alert and show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Leadership role updated successfully!',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        // Close the modal and refresh the page to show updated information
                        const modalElement = document.getElementById('presidentModal');
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if(modal) {
                            modal.hide();
                        }
                        location.reload();
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'An error occurred while updating the leadership role.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while updating the leadership role.'
                });
            });
        }
    </script>
</x-admin-layout>