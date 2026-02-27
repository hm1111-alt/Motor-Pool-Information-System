<!-- View Employee Modal -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header" style="background-color:#1e6031; color:white;">
                <h5 class="modal-title fw-bold" id="viewEmployeeModalLabel">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View Employee
                </h5>
            </div>
            
            <!-- Custom CSS for extra small text -->
            <style>
                .x-small {
                    font-size: 0.75rem !important;
                }
            </style>
            
            <!-- Modal Body -->
            <div class="modal-body px-4 py-3">
                <!-- Loading indicator -->
                <div id="viewModalLoading" class="text-center py-4">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading employee data...</p>
                </div>
                
                <!-- Main Content Container -->
                <div id="viewMainContent" style="display: none;">
                    <!-- Personal Information -->
                    <div class="mb-3">
                        <h6 class="fw-bold text-success border-bottom pb-1 mb-2 small">Employee Information</h6>
                        
                        <div class="container-fluid px-0">
                            <!-- 1st Row -->
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <div class="text-success fw-semibold small">Full Name:</div>
                                    <div class="small" id="view_full_name"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-success fw-semibold small">Email:</div>
                                    <div class="small" id="view_email"></div>
                                </div>
                            </div>
                            
                            <!-- 2nd Row -->
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-success fw-semibold x-small">Sex:</div>
                                    <div class="x-small" id="view_sex"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-success fw-semibold x-small">Contact Number:</div>
                                    <div class="x-small" id="view_contact_num"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-success fw-semibold x-small">Role:</div>
                                    <div class="x-small" id="view_roles_text"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-success fw-semibold x-small">Status:</div>
                                    <div class="x-small" id="view_emp_status"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Positions Section -->
                    <div>
                        <h6 class="fw-bold text-success border-bottom pb-1 mb-2 small">All Positions</h6>
                        <p class="text-muted x-small mb-1">All positions assigned to this employee</p>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-success fw-semibold x-small">Position</th>
                                        <th class="text-success fw-semibold x-small">Office</th>
                                        <th class="text-success fw-semibold x-small">Division</th>
                                        <th class="text-success fw-semibold x-small">Unit</th>
                                        <th class="text-success fw-semibold x-small">Subunit</th>
                                        <th class="text-success fw-semibold x-small">Class</th>
                                    </tr>
                                </thead>
                                <tbody id="view_positions_table_body" class="x-small">
                                    <!-- Positions will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for view modal functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('viewEmployeeModal');
    const loadingIndicator = document.getElementById('viewModalLoading');
    const mainContent = document.getElementById('viewMainContent');
    
    // Track whether the modal was closed by clicking the close button
    let isCancelled = false;
    
    // Store current employee ID being viewed
    let currentEmployeeId = null;
    
    // Modal event listeners
    if (modal) {
        // Set flag when close button is clicked
        const closeButton = modal.querySelector('.btn-secondary[data-bs-dismiss="modal"]');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                isCancelled = true;
            });
        }
        
        // Reset modal when hidden (except when cancelled)
        modal.addEventListener('hidden.bs.modal', function () {
            if (!isCancelled) {
                // Hide content and show loading
                mainContent.style.display = 'none';
                loadingIndicator.style.display = 'block';
                // Clear current employee ID
                currentEmployeeId = null;
            }
            
            // Reset the cancelled flag
            isCancelled = false;
        });
    }
    
    // Function to open view modal
    window.openViewEmployeeModal = function(employeeId) {
        if (!employeeId) {
            console.error('Employee ID is required');
            return;
        }
        
        // Set current employee ID
        currentEmployeeId = employeeId;
        
        // Show the modal
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();
        
        // Fetch employee data
        fetch(`/admin/employees/${employeeId}/data`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.employee) {
                populateViewModal(data.employee);
            } else {
                console.error('Failed to load employee data:', data.message);
                // Hide loading and show error
                loadingIndicator.style.display = 'none';
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to load employee data.',
                });
            }
        })
        .catch(error => {
            console.error('Error fetching employee data:', error);
            // Hide loading and show error
            loadingIndicator.style.display = 'none';
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An unexpected error occurred while loading employee data.',
            });
        });
    };
    
    // Function to populate the view modal with employee data
    function populateViewModal(employee) {
        console.log('Populating view modal with employee data:', employee);
        
        // Hide loading indicator
        loadingIndicator.style.display = 'none';
        
        // Show main content
        mainContent.style.display = 'block';
        
        // Helper function to display "-" for empty values
        const displayValue = (value) => value || '-';
        
        // Populate personal information
        const fullName = `${employee.prefix ? employee.prefix + ' ' : ''}${employee.first_name || ''} ${employee.middle_name ? employee.middle_name.charAt(0) + '. ' : ''}${employee.last_name || ''}${employee.ext_name ? ' ' + employee.ext_name : ''}`.trim();
        const sex = employee.sex ? (employee.sex === 'M' ? 'Male' : 'Female') : '-';
        const email = employee.user?.email || '-';
        const contactNum = employee.contact_num || '-';
        const empStatus = employee.emp_status ? 'Active' : 'Inactive';
        
        document.getElementById('view_full_name').textContent = displayValue(fullName);
        document.getElementById('view_sex').textContent = displayValue(sex);
        document.getElementById('view_email').textContent = displayValue(email);
        document.getElementById('view_contact_num').textContent = displayValue(contactNum);
        document.getElementById('view_emp_status').textContent = displayValue(empStatus);
        
        // Populate roles
        let rolesText = '';
        if (employee.is_president) {
            rolesText += 'President';
        }
        if (employee.is_vp) {
            rolesText += (rolesText ? ', ' : '') + 'VP';
        }
        if (employee.is_head) {
            rolesText += (rolesText ? ', ' : '') + 'Unit Head';
        }
        if (employee.is_divisionhead) {
            rolesText += (rolesText ? ', ' : '') + 'Division Head';
        }
        if (!employee.is_president && !employee.is_vp && !employee.is_head && !employee.is_divisionhead) {
            rolesText = 'Regular Employee';
        }
        
        document.getElementById('view_roles_text').textContent = displayValue(rolesText || 'None');
        
        // Populate positions table
        const positionsTableBody = document.getElementById('view_positions_table_body');
        positionsTableBody.innerHTML = '';
        
        if (employee.positions && employee.positions.length > 0) {
            employee.positions.forEach(position => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td>${displayValue(position.position_name)}</td>
                    <td>${displayValue(position.office?.office_name)}</td>
                    <td>${displayValue(position.division?.division_name)}</td>
                    <td>${displayValue(position.unit?.unit_name)}</td>
                    <td>${displayValue(position.subunit?.subunit_name)}</td>
                    <td>${displayValue(position.class?.class_name)}</td>
                `;
                
                positionsTableBody.appendChild(row);
            });
        } else {
            // No positions found
            const row = document.createElement('tr');
            row.innerHTML = '<td colspan="6" class="text-center text-muted x-small">No positions assigned</td>';
            positionsTableBody.appendChild(row);
        }
    }
});
</script>