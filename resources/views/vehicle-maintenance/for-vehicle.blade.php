@extends('layouts.motorpool-admin')

@section('content')
    <!-- Back Button Row -->
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <a href="{{ route('vehicles.show', $vehicle) }}" 
               class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>
    
    <!-- Title, Search, and Add Record Row -->
    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold" style="color: #1e6031;">
                {{ $vehicle->model ?? 'Unknown Vehicle' }} {{ $vehicle->plate_number ?? '' }} - Maintenance History
            </h3>
    
            <div class="flex items-center gap-2">
                <!-- Search Bar -->
                <div class="flex">
                    <input type="text" id="searchMaintenance" placeholder="Search history..." 
                           style="height: 32px; width: 200px; font-size: 0.85rem; border: 1px solid #1e6031; border-radius: 0.375rem 0 0 0.375rem; padding: 0 12px;" 
                           value="">
                    <button type="button" 
                            style="background-color: #1e6031; color: #ffffff; border: 1px solid #1e6031; height: 32px; width: 40px; border-radius: 0 0.375rem 0.375rem 0; cursor:pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <!-- Add Record Button -->
                <a href="{{ route('vehicle-maintenance.create') }}?vehicle_id={{ $vehicle->id }}" 
                   class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus-circle mr-2"></i> Add Record
                </a>
            </div>
        </div>
    </div>
    
    <!-- Tabs and PDF Row -->
    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex justify-between items-center">
                <div class="flex space-x-8">
                    <button type="button" 
                            class="tab-button pb-3 px-1 border-b-2 font-medium text-sm border-[#1e6031] text-[#1e6031]" 
                            data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                        Pending
                    </button>
                    <button type="button" 
                            class="tab-button pb-3 px-1 border-b-2 font-medium text-sm" 
                            data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                        Completed
                    </button>
                </div>
                <div id="maintenance-pdf-btn-container">
                    <button id="generateMaintenancePDF" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.80rem;">
                        <i class="fas fa-file-pdf" style="margin-right: 5px;"></i> Generate PDF
                    </button>
                </div>
            </nav>
        </div>
    </div>
    


    <div class="py-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 text-gray-900">


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
                    <div class="tab-content -mt-6" id="maintenanceTabContent">
                        <div class="tab-pane fade show active" id="pending" role="tabpanel">
                            <!-- Pending Maintenance Records List -->
                            @php
                                $pendingRecords = $maintenanceRecords->filter(function($record) {
                                    return $record->status === 'Pending' || $record->status === 'Ongoing';
                                });
                            @endphp
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-[#1e6031]">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nature of Work</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Maintenance Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mechanics Assigned</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date Started</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @if($pendingRecords->count() > 0)
                                            @foreach($pendingRecords as $index => $record)
                                            <tr class="hover:bg-gray-50" data-record-id="{{ $record->id }}" data-nature-of-work="{{ strtolower($record->nature_of_work) }}" data-make-or-type="{{ strtolower($record->make_or_type) }}" data-mechanic-assigned="{{ strtolower($record->mechanic_assigned) }}" data-date-started="{{ $record->date_started->format('M d, Y') }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 text-sm text-[#006400]">{{ $record->nature_of_work }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $record->make_or_type }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $record->mechanic_assigned }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->date_started->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('vehicle-maintenance.show', $record) }}" 
                                                           class="text-indigo-600 hover:text-indigo-900 inline-flex items-center"
                                                           title="View Maintenance">
                                                            <i class="fas fa-eye mr-1" style="font-size: 12px;"></i> View
                                                        </a>
                                                        <a href="{{ route('vehicle-maintenance.edit', $record) }}" 
                                                           class="text-yellow-600 hover:text-yellow-900 inline-flex items-center ml-2"
                                                           title="Edit Maintenance">
                                                            <i class="fas fa-edit mr-1" style="font-size: 12px;"></i> Edit
                                                        </a>
                                                        <form action="{{ route('vehicle-maintenance.destroy', $record) }}" method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="text-red-600 hover:text-red-900 delete-btn inline-flex items-center ml-2" title="Delete Maintenance">
                                                                <i class="fas fa-trash mr-1" style="font-size: 12px;"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <i class="fas fa-tools text-gray-300 text-4xl mb-3"></i>
                                                        <h4 class="text-lg font-medium text-gray-900 mb-1">No Pending Maintenance Records</h4>
                                                        <p class="text-gray-500">Get started by creating a new maintenance record for this vehicle.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- End of Pending tab -->
                        
                        <div class="tab-pane fade" id="completed" role="tabpanel">
                            @php
                                $completedRecords = $maintenanceRecords->filter(function($record) {
                                    return $record->status === 'Completed';
                                });
                            @endphp
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-[#1e6031]">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No.</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nature of Work</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Maintenance Type</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mechanics Assigned</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Date Started</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @if($completedRecords->count() > 0)
                                            @foreach($completedRecords as $index => $record)
                                            <tr class="hover:bg-gray-50" data-record-id="{{ $record->id }}" data-nature-of-work="{{ strtolower($record->nature_of_work) }}" data-make-or-type="{{ strtolower($record->make_or_type) }}" data-mechanic-assigned="{{ strtolower($record->mechanic_assigned) }}" data-date-started="{{ $record->date_started->format('M d, Y') }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 text-sm text-[#006400]">{{ $record->nature_of_work }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $record->make_or_type }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $record->mechanic_assigned }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->date_started->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('vehicle-maintenance.show', $record) }}" 
                                                           class="text-indigo-600 hover:text-indigo-900 inline-flex items-center"
                                                           title="View Maintenance">
                                                            <i class="fas fa-eye mr-1" style="font-size: 12px;"></i> View
                                                        </a>
                                                        <a href="{{ route('vehicle-maintenance.edit', $record) }}" 
                                                           class="text-yellow-600 hover:text-yellow-900 inline-flex items-center ml-2"
                                                           title="Edit Maintenance">
                                                            <i class="fas fa-edit mr-1" style="font-size: 12px;"></i> Edit
                                                        </a>
                                                        <form action="{{ route('vehicle-maintenance.destroy', $record) }}" method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="text-red-600 hover:text-red-900 delete-btn inline-flex items-center ml-2" title="Delete Maintenance">
                                                                <i class="fas fa-trash mr-1" style="font-size: 12px;"></i> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <i class="fas fa-tools text-gray-300 text-4xl mb-3"></i>
                                                        <h4 class="text-lg font-medium text-gray-900 mb-1">No Completed Maintenance Records</h4>
                                                        <p class="text-gray-500">There are no completed maintenance records for this vehicle.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- End of Completed tab -->
                    </div> <!-- End of tab content -->
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
            
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-button');
            
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('active', 'border-[#1e6031]', 'text-[#1e6031]');
                    btn.classList.add('border-transparent', 'text-gray-500');
                });
                    
                // Add active class to clicked button
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('active', 'border-[#1e6031]', 'text-[#1e6031]');
                    
                // Hide all tab content
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                    
                // Show corresponding tab content
                const targetId = this.getAttribute('data-bs-target').substring(1);
                document.getElementById(targetId).classList.add('show', 'active');
            });
        });
            
        // Search functionality for maintenance records
        const searchInput = document.getElementById('searchMaintenance');
    
        searchInput.addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
                
            // Get the active tab's records
            const activeTab = document.querySelector('.tab-pane.active.show');
            if (activeTab) {
                const records = activeTab.querySelectorAll('tr[data-record-id]');
                    
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