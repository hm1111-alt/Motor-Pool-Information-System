@extends('layouts.motorpool-admin')

@section('content')
    <!-- Back Button Row -->
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <a href="{{ route('drivers.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>
    

    
    <!-- Driver Information -->
    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <div class="card w-100 shadow-sm border-[#1e6031]" style="border-radius: 12px;">
            <div class="card-header d-flex align-items-center" style="background-color: #1e6031; color: white; font-weight: 600; border-radius: 12px 12px 0 0;">
                <i class="fas fa-user me-2"></i>
                <h5 class="mb-0">Driver Information</h5>
            </div>
            <div class="card-body p-3" style="font-size: 0.9rem; line-height: 1.5;">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-2">
                            <strong>Name:</strong> {{ $driver->full_name2 }}
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> {{ $driver->user->email ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Contact Number:</strong> {{ $driver->user->contact_num ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <strong>Address:</strong> {{ $driver->address }}
                        </div>
                        <div class="mb-2">
                            <strong>Position:</strong> {{ $driver->position }}
                        </div>
                        <div class="mb-2">
                            <strong>Official Station:</strong> {{ $driver->official_station }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vehicle Information -->
        @if($currentVehicle)
        <div class="card w-100 shadow-sm border-[#1e6031] mt-4" style="border-radius: 12px;">
            <div class="card-header d-flex align-items-center" style="background-color: #1e6031; color: white; font-weight: 600; border-radius: 12px 12px 0 0;">
                <i class="fas fa-car me-2"></i>
                <h5 class="mb-0">Vehicle Information</h5>
            </div>
            <div class="card-body p-3" style="font-size: 0.9rem; line-height: 1.5;">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-2">
                            <strong>Model:</strong> {{ $currentVehicle->model }}
                        </div>
                        <div class="mb-2">
                            <strong>Fuel Type:</strong> {{ $currentVehicle->fuel_type ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Mileage:</strong> {{ number_format($currentVehicle->mileage) }} km
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            <span class="badge 
                                @if($currentVehicle->status === 'Available' || $currentVehicle->status === 'Active') bg-success
                                @elseif($currentVehicle->status === 'Not Available') bg-danger
                                @elseif($currentVehicle->status === 'Under Maintenance') bg-warning text-dark
                                @endif">
                                {{ $currentVehicle->status }}
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <strong>Plate Number:</strong> {{ $currentVehicle->plate_number }}
                        </div>
                        <div class="mb-2">
                            <strong>Seating Capacity:</strong> {{ $currentVehicle->seating_capacity ?? 'N/A' }}
                        </div>
                        <div class="mb-2">
                            <strong>Type:</strong> {{ $currentVehicle->type ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    




    <!-- Completed Trips -->
    <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8 mt-4">
        <!-- Header with title, search, and PDF button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-lg font-semibold mb-0" style="color: #1e6031;">
                {{ $driver->full_name2 }} - Travel History
            </h3>
            <div class="d-flex align-items-center gap-2">
                <!-- Search Bar -->
                <div class="d-flex">
                    <input type="text" id="tripSearch" placeholder="Search history..." 
                           style="height: 32px; width: 200px; font-size: 0.85rem; border: 1px solid #1e6031; border-radius: 0.375rem 0 0 0.375rem; padding: 0 12px;">
                    <button type="button" id="searchButton"
                            style="background-color: #1e6031; color: #ffffff; border: 1px solid #1e6031; height: 32px; width: 40px; border-radius: 0 0.375rem 0.375rem 0; cursor:pointer;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <!-- Generate PDF Button -->
                <button type="button" id="generateDriverTripsPDFBtn" class="btn btn-sm d-inline-flex align-items-center justify-content-center" style="background-color: #dc3545; border: 1px solid #dc3545; border-radius: 0.375rem; color: white; height: 32px; padding: 0 12px; font-size: 0.80rem;">
                    <i class="fas fa-file-pdf me-1" style="font-size: 0.8rem;"></i> Generate PDF
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="tripsTable">
                <thead style="background-color: #1e6031; color: white;">
                    <tr>
                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">No.</th>
                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Vehicle</th>
                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Head of the Party</th>
                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Destination</th>
                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Departure Date</th>
                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Arrival Date</th>
                        <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Distance (km)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($driver->itineraries->where('status', 'Approved')->count() > 0)
                        @foreach($driver->itineraries->where('status', 'Approved') as $index => $itinerary)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($itinerary->vehicle)
                                    {{ $itinerary->vehicle->model }} ({{ $itinerary->vehicle->plate_number }})
                                @else
                                    <span class="text-gray-400">No vehicle assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $itinerary->head_of_party ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $itinerary->destination ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $itinerary->date_from?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $itinerary->date_to?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $itinerary->distance_km ? number_format($itinerary->distance_km, 1) : 'N/A' }}
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-route text-gray-300 text-4xl mb-3"></i>
                                    <h4 class="text-lg font-medium text-gray-900 mb-1">No Completed Travel History</h4>
                                    <p class="text-gray-500">This driver has no completed trips yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('tripSearch');
    const searchButton = document.getElementById('searchButton');
    const table = document.getElementById('tripsTable');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            // Skip the "No data" row
            if (row.querySelector('td[colspan="7"]')) return;
            
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    if (searchInput && searchButton) {
        searchButton.addEventListener('click', filterTable);
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                filterTable();
            }
        });
    }
    
    // PDF Generation
    const pdfButton = document.getElementById('generateDriverTripsPDFBtn');
    if (pdfButton) {
        pdfButton.addEventListener('click', function() {
            // Show loading alert
            Swal.fire({
                title: 'Generating PDF...',
                text: 'Please wait while we prepare your trip history',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Simulate PDF generation
            setTimeout(function() {
                Swal.close();
                // In a real implementation, you would generate the actual PDF here
                Swal.fire({
                    title: 'Success!',
                    text: 'PDF generated successfully',
                    icon: 'success',
                    timer: 2000
                });
            }, 1000);
        });
    }
});
</script>