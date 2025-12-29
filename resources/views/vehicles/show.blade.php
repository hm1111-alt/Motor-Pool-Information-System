@extends('layouts.motorpool-admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Vehicle Details') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Success Message -->

                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Vehicle Image -->
                        <div class="md:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                @if($vehicle->picture)
                                    <img src="{{ asset('storage/' . $vehicle->picture) }}" alt="Vehicle Image" class="w-full h-48 object-cover rounded-md" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-48 bg-gray-200 rounded-md flex items-center justify-center\'><svg class=\'h-16 w-16 text-gray-400\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\' /></svg></div>';
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-md flex items-center justify-center">
                                        <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Vehicle Details -->
                        <div class="md:col-span-2">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Vehicle Information</h3>
                                    <div class="mt-2 border-t border-gray-200 pt-2">
                                        <dl class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                            <div class="sm:col-span-1">
                                                <dt class="text-sm font-medium text-gray-500">Plate Number</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->plate_number }}</dd>
                                            </div>
                                            <div class="sm:col-span-1">
                                                <dt class="text-sm font-medium text-gray-500">Model</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->model }}</dd>
                                            </div>
                                            <div class="sm:col-span-1">
                                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->type }}</dd>
                                            </div>
                                            <div class="sm:col-span-1">
                                                <dt class="text-sm font-medium text-gray-500">Seating Capacity</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->seating_capacity }}</dd>
                                            </div>
                                            <div class="sm:col-span-1">
                                                <dt class="text-sm font-medium text-gray-500">Mileage</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($vehicle->mileage) }}</dd>
                                            </div>
                                            <div class="sm:col-span-1">
                                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                                <dd class="mt-1">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @if($vehicle->status === 'Available') bg-green-100 text-green-800
                                                        @elseif($vehicle->status === 'Not Available') bg-red-100 text-red-800
                                                        @elseif($vehicle->status === 'Active') bg-blue-100 text-blue-800
                                                        @elseif($vehicle->status === 'Under Maintenance') bg-yellow-100 text-yellow-800
                                                        @endif">
                                                        {{ $vehicle->status }}
                                                    </span>
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('vehicles.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Back to List
                        </a>
                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="mr-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Vehicle
                        </a>
                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="delete-btn inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Vehicle
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete button clicks for vehicles in show page
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Are you sure you want to delete this vehicle? This action cannot be undone.',
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