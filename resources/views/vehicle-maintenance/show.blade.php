@extends('layouts.motorpool-admin')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    VEHICLE MAINTENANCE DETAILS
                </h2>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Maintenance Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vehicle</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->vehicle->plate_number ?? 'N/A' }} - {{ $vehicleMaintenance->vehicle->model ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Odometer Reading</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->odometer_reading ? $vehicleMaintenance->odometer_reading . ' km' : 'N/A' }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date Started</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->date_started->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date Completed</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->date_completed ? $vehicleMaintenance->date_completed->format('M d, Y') : 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Make or Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->make_or_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Person/Office/Unit</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->person_office_unit }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Place</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->place }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Mechanic Assigned</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->mechanic_assigned }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Conforme</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $vehicleMaintenance->conforme }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($vehicleMaintenance->status === 'Pending') bg-yellow-100 text-yellow-800
                                            @elseif($vehicleMaintenance->status === 'Ongoing') bg-blue-100 text-blue-800
                                            @elseif($vehicleMaintenance->status === 'Completed') bg-green-100 text-green-800
                                            @endif">
                                            {{ $vehicleMaintenance->status }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Work Details</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nature of Work</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $vehicleMaintenance->nature_of_work }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Materials/Parts</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $vehicleMaintenance->materials_parts ?: 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('vehicle-maintenance.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Back to List
                        </a>
                        <a href="{{ route('vehicle-maintenance.edit', $vehicleMaintenance) }}" class="mr-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Maintenance
                        </a>
                        <form action="{{ route('vehicle-maintenance.destroy', $vehicleMaintenance) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-btn inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Maintenance
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete button
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
    });
    </script>
@endsection