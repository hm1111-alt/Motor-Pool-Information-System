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
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800">Itineraries</h2>
                
                <div class="flex items-center gap-3">
                    <form id="searchForm" method="GET" action="{{ route('itinerary.index') }}" class="flex">
                        <input type="text" name="search" 
                               class="px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:border-[#1e6031] text-sm"
                               placeholder="Search itineraries..." 
                               value="{{ request('search') }}"
                               style="width: 250px;">
                        <button type="submit" 
                                class="bg-[#1e6031] text-white px-3 py-2 rounded-r-md hover:bg-[#164f2a] transition duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    
                    <button type="button" 
                            onclick="openCreateItineraryModal()"
                            class="bg-[#1e6031] text-white px-4 py-2 rounded-md hover:bg-[#164f2a] transition duration-200 flex items-center text-sm font-medium">
                        <i class="fas fa-plus mr-2"></i> Create Itinerary
                    </button>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex space-x-8">
                    <button type="button" 
                            class="tab-button pb-3 px-1 border-b-2 font-medium text-sm {{ ($tab ?? 'pending') === 'pending' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            data-tab="pending">
                        Pending
                    </button>
                    <button type="button" 
                            class="tab-button pb-3 px-1 border-b-2 font-medium text-sm {{ ($tab ?? 'pending') === 'approved' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            data-tab="approved">
                        Approved
                    </button>
                    <button type="button" 
                            class="tab-button pb-3 px-1 border-b-2 font-medium text-sm {{ ($tab ?? 'pending') === 'cancelled' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                            data-tab="cancelled">
                        Cancelled
                    </button>
                </nav>
            </div>

            <!-- Current Tab Display -->
            <div class="tab-content" id="current-tab-display">
                @if($currentTabItineraries->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                                <thead style="background-color: #1e6031; color: white;">
                                    <tr>
                                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">No.</th>
                                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Driver</th>
                                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Vehicle</th>
                                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Purpose</th>
                                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Destination</th>
                                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Date</th>
                                        <th style="padding: 10px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="itinerary-table-body">
                                    @foreach($currentTabItineraries as $index => $itinerary)
                                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-[#f0f8f0]">
                                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">
                                                {{ $itinerary->driver?->full_name ?? 'Not Assigned' }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                                @if($itinerary->vehicle)
                                                    {{ $itinerary->vehicle->plate_number }} - {{ $itinerary->vehicle->model }}
                                                @else
                                                    Not Assigned
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-xs text-[#006400] max-w-[200px]">
                                                {{ Str::limit($itinerary->purpose ?? 'No Purpose Specified', 50) }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                                {{ $itinerary->destination }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">
                                                @if($itinerary->date_from instanceof \DateTimeInterface)
                                                    {{ $itinerary->date_from->format('M d, Y') }}
                                                @else
                                                    {{ $itinerary->date_from ?: 'Not Assigned' }}
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-center text-xs">
                                                <div class="action-buttons flex justify-center space-x-1">
                                                    <button type="button"
                                                            class="btn view-btn border inline-flex items-center justify-center"
                                                            title="View Itinerary Details"
                                                            onclick="loadAndShowItinerary({{ $itinerary->id }})">
                                                        <i class="fas fa-eye"></i> View
                                                    </button>
                                                    @if($itinerary->status !== 'Approved' && $itinerary->status !== 'Cancelled')
                                                        <button type="button"
                                                                class="btn edit-btn border inline-flex items-center justify-center"
                                                                title="Edit Itinerary"
                                                                onclick="loadAndShowEditItinerary({{ $itinerary->id }})">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button type="button" 
                                                                class="btn archive-btn border delete-btn inline-flex items-center justify-center"
                                                                title="Archive Itinerary"
                                                                onclick="confirmArchive({{ $itinerary->id }})">
                                                            <i class="fas fa-archive"></i> Archive
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                                            
                        <!-- Pagination -->
                        <div class="flex justify-between items-center mt-4">
                            <div class="text-sm text-gray-600">
                                Showing {{ $currentTabItineraries->firstItem() ?? 0 }} to {{ $currentTabItineraries->lastItem() ?? 0 }} of {{ $currentTabItineraries->total() }} applications
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($currentTabItineraries->lastPage() > 1)
                                    {{ $currentTabItineraries->appends(['search' => request('search'), 'tab' => request('tab')])->links() }}
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
                @else
                    <div class="text-center py-12 bg-white shadow sm:rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No {{ $tab === 'pending' ? 'pending' : ($tab === 'approved' ? 'approved' : 'cancelled') }} itineraries</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if($tab === 'pending')
                                Get started by creating a new itinerary.
                            @else
                                No {{ $tab === 'approved' ? 'approved' : 'cancelled' }} itineraries available.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
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

.action-buttons .btn i {
    font-size: 10px;
    margin-right: 2px;
}

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

/* Pagination styling to match drivers/vehicles */
.pagination {
    display: flex;
    justify-content: flex-end;
    list-style: none;
}

.pagination .page-link {
    color: #1e6031;
    border-color: #1e6031;
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    display: block;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #1e6031;
    border-radius: 0.25rem;
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

.page-item:first-child .page-link,
.page-item:last-child .page-link {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background-color: #1e6031;
    border-color: #1e6031;
    color: white;
    border-radius: 0.25rem;
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

.page-item {
    margin: 0 2px;
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

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
function confirmArchive(itineraryId) {
    if (confirm('Are you sure you want to archive this itinerary?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/itinerary/${itineraryId}`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}

// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const currentTabDisplay = document.getElementById('current-tab-display');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Update active tab button
            tabButtons.forEach(btn => {
                btn.classList.remove('border-[#1e6031]', 'text-[#1e6031]');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-[#1e6031]', 'text-[#1e6031]');
            
            // Update URL and reload page with new tab
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.location.href = url.toString();
        });
    });
});

// Function to view itinerary as PDF
function viewItineraryPDF(itineraryId) {
    // Open the PDF in a new tab
    window.open(`/itinerary/${itineraryId}/pdf`, '_blank');
}

// Function to load and show itinerary details in modal
function loadAndShowItinerary(itineraryId) {
    // Fetch itinerary details from API
    fetch(`/api/itineraries/${itineraryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show the view itinerary modal with the data
                showViewItineraryModal(data.itinerary);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to load itinerary details.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while loading itinerary details. Please try again.'
            });
        });
}

// Function to load and show edit itinerary modal
function loadAndShowEditItinerary(itineraryId) {
    // Fetch itinerary details from API
    fetch(`/api/itineraries/${itineraryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show the edit itinerary modal with the data
                showEditItineraryModal(data.itinerary);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Failed to load itinerary details.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while loading itinerary details. Please try again.'
            });
        });
}
</script>

@include('itineraries.modals.create-itinerary-modal')
@include('itineraries.modals.view-itinerary-modal')
@include('itineraries.modals.edit-itinerary-modal')
@endsection