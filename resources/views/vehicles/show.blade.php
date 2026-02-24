@extends('layouts.motorpool-admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button and View Maintenance Button -->
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('vehicles.index') }}" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <a href="{{ route('vehicles.maintenance', $vehicle) }}" class="inline-flex items-center px-4 py-2 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#103c1e] focus:outline-none focus:ring-2 focus:ring-[#1e6031] focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-wrench mr-2"></i> View Maintenance
            </a>
        </div>

        <div class="row g-3">
    <!-- Vehicle Image -->
    <div class="col-md-4 d-flex align-items-start">
        <div class="card shadow-sm w-100 border-[#1e6031]" style="height: 200px;">
            @php
                // Check if the image exists in the new public directory
                $imagePath = $vehicle->picture && file_exists(public_path('vehicles/images/' . $vehicle->picture))
                    ? asset('vehicles/images/' . $vehicle->picture)
                    : asset('vehicles/images/vehicle_default.png');
            @endphp
            <img 
                src="{{ $imagePath }}" 
                class="card-img-top img-fluid" 
                alt="{{ $vehicle->model ?? 'Vehicle Image' }}"
                style="object-fit: cover; height: 100%; width: 100%;"
                onerror="this.src='{{ asset('storage/vehicles/images/vehicle_default.png') }}'">
        </div>
    </div>

    <!-- Vehicle Info -->
    <div class="col-md-8 d-flex align-items-start">
        <div class="card w-100 shadow-sm border-[#1e6031]" style="height: 200px; border-radius: 12px;">
            <div class="card-header d-flex align-items-center" style="background-color: #1e6031; color: white; font-weight: 600; border-radius: 12px 12px 0 0;">
                <i class="fas fa-car me-2"></i>
                <h5 class="mb-0">Vehicle Information</h5>
            </div>
            <div class="card-body p-3" style="font-size: 0.9rem; line-height: 1.5;">
                <div class="row">
                    <div class="col-6 mb-2">
                        <strong>Model:</strong> {{ $vehicle->model }}
                    </div>
                    <div class="col-6 mb-2">
                        <strong>Plate Number:</strong> {{ $vehicle->plate_number }}
                    </div>
                    <div class="col-6 mb-2">
                        <strong>Fuel Type:</strong> {{ $vehicle->fuel_type ?? 'N/A' }}
                    </div>
                    <div class="col-6 mb-2">
                        <strong>Seating Capacity:</strong> {{ $vehicle->seating_capacity ?? 'N/A' }}
                    </div>
                    <div class="col-6 mb-2">
                        <strong>Mileage:</strong> {{ number_format($vehicle->mileage) }} km
                    </div>
                    <div class="col-6 mb-2">
                        <strong>Type:</strong> {{ $vehicle->type ?? 'N/A' }}
                    </div>
                    <div class="col-6 mb-2">
                        <strong>Status:</strong>
                        <span class="badge 
                            @if($vehicle->status === 'Available') bg-success
                            @elseif($vehicle->status === 'Not Available') bg-danger
                            @elseif($vehicle->status === 'Active') bg-primary
                            @elseif($vehicle->status === 'Under Maintenance') bg-warning text-dark
                            @endif">
                            {{ $vehicle->status }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                        </div>
                    </div>

        <!-- Travel History Section -->
        <div class="mt-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold" style="color: #1e6031;">
                        {{ $vehicle->model }} {{ $vehicle->plate_number }} - Travel History
                    </h3>
                    <div class="flex items-center gap-2">
                        <!-- Search Bar -->
                        <div class="flex">
                            <input type="text" placeholder="Search history..." 
                                   style="height: 32px; width: 200px; font-size: 0.85rem; border: 1px solid #1e6031; border-radius: 0.375rem 0 0 0.375rem; padding: 0 12px;">
                            <button type="button" 
                                    style="background-color: #1e6031; color: #ffffff; border: 1px solid #1e6031; height: 32px; width: 40px; border-radius: 0 0.375rem 0.375rem 0; cursor:pointer;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        
                        <!-- Generate PDF Button -->
                        <button type="button" id="generateVehicleTravelHistoryPDFBtn" class="inline-flex items-center justify-center bg-red-600 border border-red-600 rounded text-xs text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" style="height: 32px; padding: 0 12px; font-size: 0.80rem;">
                            <i class="fas fa-file-pdf mr-1 text-xs"></i> Generate PDF
                        </button>
                    </div>
                </div>

                <!-- Travel History Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color: #1e6031; color: white;">
                            <tr>
                                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">No.</th>
                                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Driver</th>
                                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Head of Party</th>
                                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Destination</th>
                                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Departure Date</th>
                                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Arrival Date</th>
                                <th style="padding: 10px; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Distance (km)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($travelHistory->isEmpty())
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-car text-gray-300 text-4xl mb-3"></i>
                                            <h4 class="text-lg font-medium text-gray-900 mb-1">No Completed Travel History</h4>
                                            <p class="text-gray-500">This vehicle has no recorded travel history yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach($travelHistory as $index => $history)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $travelHistory->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $history->driver->first_name ?? '' }} {{ $history->driver->last_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $history->head_of_party }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $history->destination }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $history->departure_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $history->arrival_date ? $history->arrival_date->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $history->distance_km ? number_format($history->distance_km) : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(!$travelHistory->isEmpty())
                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm text-gray-600">
                            Showing {{ $travelHistory->firstItem() }} to {{ $travelHistory->lastItem() }} of {{ $travelHistory->total() }} records
                        </div>
                        <div class="flex items-center space-x-2">
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item {{ $travelHistory->currentPage() <= 1 ? 'disabled' : '' }}">
                                        <a class="page-link {{ $travelHistory->currentPage() <= 1 ? 'disabled-link' : '' }}" href="{{ $travelHistory->previousPageUrl() }}" {{ $travelHistory->currentPage() <= 1 ? 'aria-disabled="true"' : '' }}>Prev</a>
                                    </li>
                                    <li class="page-item active">
                                        <span class="page-link">{{ $travelHistory->currentPage() }}</span>
                                    </li>
                                    <li class="page-item {{ $travelHistory->currentPage() >= $travelHistory->lastPage() ? 'disabled' : '' }}">
                                        <a class="page-link {{ $travelHistory->currentPage() >= $travelHistory->lastPage() ? 'disabled-link' : '' }}" href="{{ $travelHistory->nextPageUrl() }}" {{ $travelHistory->currentPage() >= $travelHistory->lastPage() ? 'aria-disabled="true"' : '' }}>Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

<style>
/* Pagination Styles */
.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 0.375rem;
}

.page-item {
    margin: 0 2px;
}

.page-link {
    position: relative;
    display: block;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    color: #1e6031;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #1e6031;
    border-radius: 0.375rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

.page-link:hover {
    color: #fff;
    background-color: #1e6031;
    border-color: #1e6031;
}

.page-item.active .page-link {
    color: #fff;
    background-color: #1e6031;
    border-color: #1e6031;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

.disabled-link {
    color: #6c757d !important;
    pointer-events: none;
    cursor: not-allowed;
}
</style>

<!-- Hidden data container for PDF generation -->
<div id="js-vehicle-travel-history-data" data-vehicle="@json(['id' => $vehicle->id, 'model' => $vehicle->model, 'plate_number' => $vehicle->plate_number])" data-travel-history="@json($travelHistory->items())"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('generateVehicleTravelHistoryPDFBtn');
        if (!btn) return;

        btn.addEventListener('click', function () {
            // Show loading alert
            Swal.fire({
                title: 'Generating PDF...',
                text: 'Please wait while we prepare your vehicle travel history',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Wait 2 seconds then generate PDF and close loading
            setTimeout(function() {
                // Close the loading alert
                Swal.close();
                
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();

                const vehicleData = JSON.parse(document.getElementById('js-vehicle-travel-history-data').dataset.vehicle);
                const travelHistory = JSON.parse(document.getElementById('js-vehicle-travel-history-data').dataset.travelHistory);
                
                // Even if there's no travel history data, we can still generate a PDF with the header and a note

                const logo = new Image();
                logo.src = "{{ asset('assets/images/clsu-logo.png') }}";

                logo.onload = function () {
                    const logoSize = 30;  // Increased from 20 to 30 for better visibility
                    const marginTop = 12;
                    const logoX = pageWidth / 2 - 70;  // Adjusted position for larger logo

                    // Header
                    doc.addImage(logo, "PNG", logoX, marginTop, logoSize, logoSize);
                    doc.setFont("helvetica", "normal").setFontSize(10);
                    doc.text("Republic of the Philippines", pageWidth / 2, marginTop + 2, { align: "center" });
                    doc.setFont("helvetica", "bold").setFontSize(14);
                    doc.text("CENTRAL LUZON STATE UNIVERSITY", pageWidth / 2, marginTop + 8, { align: "center" });
                    doc.setFont("helvetica", "normal").setFontSize(10);
                    doc.text("Science City of Muñoz, Nueva Ecija", pageWidth / 2, marginTop + 14, { align: "center" });
                    doc.setFont("helvetica", "bold").setFontSize(11);
                    doc.text("TRANSPORTATION SERVICES", pageWidth / 2, marginTop + 20, { align: "center" });

                    // Line under header
                    doc.setDrawColor(0, 77, 0);
                    doc.setLineWidth(0.5);
                    doc.line(15, marginTop + 33, pageWidth - 15, marginTop + 33);

                    // Title
                    doc.setFontSize(12);
                    doc.setFont("helvetica", "bold");
                    doc.text(`${vehicleData.model} ${vehicleData.plate_number} - TRAVEL HISTORY`, pageWidth / 2, marginTop + 42, { align: 'center' });

                    // Prepare table data
                    let tableData = [];
                    if (travelHistory.length > 0) {
                        tableData = travelHistory.map((record, index) => [
                            index + 1,
                            `${record.driver?.first_name || ''} ${record.driver?.last_name || ''}`.trim() || 'N/A',
                            record.head_of_party || 'N/A',
                            record.destination || 'N/A',
                            record.departure_date ? new Date(record.departure_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A',
                            record.arrival_date ? new Date(record.arrival_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A',
                            record.distance_km ? parseFloat(record.distance_km).toFixed(1) : 'N/A'
                        ]);
                    } else {
                        // Add a row indicating no data
                        tableData = [['', 'No Completed Travel History', 'This vehicle has no recorded travel history yet.', '', '', '', '']];
                    }

                    // Add table
                    doc.autoTable({
                        head: [['No.', 'Driver', 'Head of the Party', 'Destination', 'Departure Date', 'Arrival Date', 'Distance (km)']],
                        body: tableData,
                        startY: marginTop + 50,
                        styles: {
                            fontSize: 10,
                            cellPadding: 5
                        },
                        headStyles: {
                            fillColor: [30, 96, 49], // Green color
                            textColor: [255, 255, 255],
                            fontStyle: 'bold'
                        },
                        alternateRowStyles: {
                            fillColor: [245, 245, 245]
                        },
                        margin: { left: 15, right: 15 }
                    });

                    // Save the PDF
                    doc.save(`Vehicle_${vehicleData.plate_number}_Travel_History.pdf`);
                };

                logo.onerror = function () {
                    console.error('Failed to load logo image');
                    // Continue with PDF generation without logo
                    // Title
                    doc.setFontSize(12);
                    doc.setFont("helvetica", "bold");
                    doc.text(`${vehicleData.model} ${vehicleData.plate_number} - TRAVEL HISTORY`, pageWidth / 2, marginTop + 20, { align: 'center' });

                    // Prepare table data
                    let tableData = [];
                    if (travelHistory.length > 0) {
                        tableData = travelHistory.map((record, index) => [
                            index + 1,
                            `${record.driver?.first_name || ''} ${record.driver?.last_name || ''}`.trim() || 'N/A',
                            record.head_of_party || 'N/A',
                            record.destination || 'N/A',
                            record.departure_date ? new Date(record.departure_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A',
                            record.arrival_date ? new Date(record.arrival_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : 'N/A',
                            record.distance_km ? parseFloat(record.distance_km).toFixed(1) : 'N/A'
                        ]);
                    } else {
                        // Add a row indicating no data
                        tableData = [['', 'No Completed Travel History', 'This vehicle has no recorded travel history yet.', '', '', '', '']];
                    }

                    // Add table
                    doc.autoTable({
                        head: [['No.', 'Driver', 'Head of the Party', 'Destination', 'Departure Date', 'Arrival Date', 'Distance (km)']],
                        body: tableData,
                        startY: marginTop + 30,
                        styles: {
                            fontSize: 10,
                            cellPadding: 5
                        },
                        headStyles: {
                            fillColor: [30, 96, 49], // Green color
                            textColor: [255, 255, 255],
                            fontStyle: 'bold'
                        },
                        alternateRowStyles: {
                            fillColor: [245, 245, 245]
                        },
                        margin: { left: 15, right: 15 }
                    });

                    // Save the PDF
                    doc.save(`Vehicle_${vehicleData.plate_number}_Travel_History.pdf`);
                };
            }, 500); // 500ms delay to ensure data is ready
        });
    });
</script>