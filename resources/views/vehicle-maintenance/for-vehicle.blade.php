@extends('layouts.motorpool-admin')

@section('content')
    <!-- Back Button Row -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <a href="{{ route('vehicles.show', $vehicle) }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </div>
    
    <!-- Title and Search/Add Record Row -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Title on the left -->
                <h4 style="color: #004d00; font-weight: 700; margin:0;">
                    {{ $vehicle->model ?? 'Unknown Vehicle' }} {{ $vehicle->plate_number ?? '' }} - Maintenance History
                </h4>

                <!-- Search and Add Record on the right -->
                <div class="d-flex align-items-center gap-2">

                    <!-- Search Bar -->
                    <div class="input-group" style="width: 250px;">
                        <input type="text" id="searchMaintenance" class="form-control" 
                               placeholder="Search maintenance..." 
                               style="height: 32px; font-size: 0.85rem; border: 1px solid #008000;" 
                               value="">
                        <span class="input-group-text" 
                              style="background-color: #008000; color: #ffffff; border: 1px solid #008000;">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>

                    <!-- Add Record Button -->
                    <a href="{{ route('vehicle-maintenance.create') }}?vehicle_id={{ $vehicle->id }}" 
                       class="btn btn-success d-flex align-items-center" 
                       style="height:32px; font-size:0.85rem; background-color:#008000; color:#ffffff; border:1px solid #008000; display:flex; align-items:center; gap:5px; padding:0 10px; border-radius:4px;">
                        <i class="fas fa-plus-circle"></i> Add Record
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Generate PDF Row -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
            <div class="d-flex justify-content-end">
                <div id="maintenance-pdf-btn-container" style="display: inline-flex; align-items: center; gap: 5px;">
                    <button id="generateMaintenancePDF" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.80rem;">
                        <i class="fas fa-file-pdf" style="margin-right: 5px;"></i> Generate PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabs Row -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
            <ul class="nav nav-tabs" id="maintenanceTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">Pending</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">Completed</button>
                </li>
            </ul>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Vehicle Information -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Vehicle Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Plate Number:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $vehicle->plate_number }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Model:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $vehicle->model }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Type:</span>
                                <span class="ml-2 text-sm text-gray-900">{{ $vehicle->type }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tab Content -->
                    <div class="tab-content" id="maintenanceTabContent">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            <!-- Pending Maintenance Records List -->
                            @php
                                $pendingRecords = $maintenanceRecords->filter(function($record) {
                                    return $record->status === 'Pending' || $record->status === 'Ongoing';
                                });
                            @endphp
                            @if($pendingRecords->count() > 0)
                                <div id="pendingRecordsList" class="space-y-4">
                                    @foreach($pendingRecords as $index => $record)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200 bg-white" data-record-id="{{ $record->id }}" data-nature-of-work="{{ strtolower($record->nature_of_work) }}" data-make-or-type="{{ strtolower($record->make_or_type) }}" data-mechanic-assigned="{{ strtolower($record->mechanic_assigned) }}" data-date-started="{{ $record->date_started->format('M d, Y') }}">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Record #</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $index + 1 }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Nature of Work</p>
                                                            <p class="text-sm font-medium text-[#006400]">{{ $record->nature_of_work }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Maintenance Type</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $record->make_or_type }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Date Started</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $record->date_started->format('M d, Y') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Mechanics Assigned</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $record->mechanic_assigned }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                                @if($record->status === 'Completed') bg-green-100 text-green-800
                                                                @elseif($record->status === 'Ongoing') bg-yellow-100 text-yellow-800
                                                                @elseif($record->status === 'Pending') bg-blue-100 text-blue-800
                                                                @endif">
                                                                {{ $record->status }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex flex-col space-y-2">
                                                    <a href="{{ route('vehicle-maintenance.show', $record) }}" 
                                                       class="btn btn-sm btn-outline-primary border inline-flex items-center justify-center text-xs px-3 py-1.5"
                                                       title="View Maintenance">
                                                        <i class="fas fa-eye" style="font-size: 10px;"></i> View
                                                    </a>
                                                    <a href="{{ route('vehicle-maintenance.edit', $record) }}" 
                                                       class="btn btn-sm btn-outline-warning border inline-flex items-center justify-center text-xs px-3 py-1.5"
                                                       title="Edit Maintenance">
                                                        <i class="fas fa-edit" style="font-size: 10px;"></i> Edit
                                                    </a>
                                                    <form action="{{ route('vehicle-maintenance.destroy', $record) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger border delete-btn w-full text-xs px-3 py-1.5" title="Delete Maintenance">
                                                            <i class="fas fa-trash" style="font-size: 10px;"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Pending/Ongoing Maintenance Records</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new maintenance record for this vehicle.</p>
                                </div>
                            @endif
                        </div> <!-- End of Pending tab -->
                        
                        <div class="tab-pane fade" id="completed" role="tabpanel">
                            @php
                                $completedRecords = $maintenanceRecords->filter(function($record) {
                                    return $record->status === 'Completed';
                                });
                            @endphp
                            @if($completedRecords->count() > 0)
                                <div id="completedRecordsList" class="space-y-4">
                                    @foreach($completedRecords as $index => $record)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200 bg-white" data-record-id="{{ $record->id }}" data-nature-of-work="{{ strtolower($record->nature_of_work) }}" data-make-or-type="{{ strtolower($record->make_or_type) }}" data-mechanic-assigned="{{ strtolower($record->mechanic_assigned) }}" data-date-started="{{ $record->date_started->format('M d, Y') }}">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Record #</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $index + 1 }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Nature of Work</p>
                                                            <p class="text-sm font-medium text-[#006400]">{{ $record->nature_of_work }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Maintenance Type</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $record->make_or_type }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Date Started</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $record->date_started->format('M d, Y') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Mechanics Assigned</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $record->mechanic_assigned }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                                @if($record->status === 'Completed') bg-green-100 text-green-800
                                                                @elseif($record->status === 'Ongoing') bg-yellow-100 text-yellow-800
                                                                @elseif($record->status === 'Pending') bg-blue-100 text-blue-800
                                                                @endif">
                                                                {{ $record->status }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex flex-col space-y-2">
                                                    <a href="{{ route('vehicle-maintenance.show', $record) }}" 
                                                       class="btn btn-sm btn-outline-primary border inline-flex items-center justify-center text-xs px-3 py-1.5"
                                                       title="View Maintenance">
                                                        <i class="fas fa-eye" style="font-size: 10px;"></i> View
                                                    </a>
                                                    <a href="{{ route('vehicle-maintenance.edit', $record) }}" 
                                                       class="btn btn-sm btn-outline-warning border inline-flex items-center justify-center text-xs px-3 py-1.5"
                                                       title="Edit Maintenance">
                                                        <i class="fas fa-edit" style="font-size: 10px;"></i> Edit
                                                    </a>
                                                    <form action="{{ route('vehicle-maintenance.destroy', $record) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger border delete-btn w-full text-xs px-3 py-1.5" title="Delete Maintenance">
                                                            <i class="fas fa-trash" style="font-size: 10px;"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Completed Maintenance Records</h3>
                                    <p class="mt-1 text-sm text-gray-500">There are no completed maintenance records for this vehicle.</p>
                                </div>
                            @endif
                        </div> <!-- End of Completed tab -->
                    </div> <!-- End of tab content -->
                    
                    <div class="mt-6">
                        <a href="{{ route('vehicles.show', $vehicle) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Vehicle Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Are you sure you want to delete this maintenance record? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
            
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchMaintenance');
        
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                const table = document.querySelector('table');
                const tbody = table.querySelector('tbody');
                    
                // Get all rows except the header
                const rows = Array.from(tbody.querySelectorAll('tr'));
        
                rows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                    
                // Renumber first column for visible rows
                let count = 1;
                rows.forEach(row => {
                    if (window.getComputedStyle(row).display !== 'none') {
                        const firstCell = row.querySelector('td:first-child');
                        if (firstCell) firstCell.textContent = count++;
                    }
                });
            });
        });
    });
    
    // Search functionality for maintenance records
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchMaintenance');

        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            
            // Get the active tab's records
            const activeTab = document.querySelector('.tab-pane.active.show');
            if (activeTab) {
                const records = activeTab.querySelectorAll('div[data-record-id]');
                
                records.forEach(record => {
                    const recordData = [
                        record.getAttribute('data-nature-of-work'),
                        record.getAttribute('data-make-or-type'),
                        record.getAttribute('data-mechanic-assigned'),
                        record.getAttribute('data-date-started')
                    ].join(' ').toLowerCase();
                    
                    if (recordData.includes(query)) {
                        record.style.display = '';
                    } else {
                        record.style.display = 'none';
                    }
                });
            }
        });
    });
    </script>
@endsection

<style>
/* Action Buttons Styles */
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
    margin: 0 1px;            /* small gap between buttons */
}

/* Icons inside buttons */
.action-buttons .btn i {
    font-size: 10px;
    margin-right: 2px;
}

/* Hover states */
.action-buttons .btn:hover {
    opacity: 0.8;
}
</style>