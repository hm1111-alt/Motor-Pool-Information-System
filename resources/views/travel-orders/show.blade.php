@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Travel Order Details') }}
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Page Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-200">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Travel Request Details</h1>
                            <p class="text-gray-600 mt-1">View the details of your travel request</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('travel-orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wider shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to List
                            </a>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Position</h3>
                                    <p class="mt-1 text-base font-medium text-gray-900">
                                        @if($travelOrder->position)
                                            {{ $travelOrder->position->position_name }}
                                            @if($travelOrder->position->office) - {{ $travelOrder->position->office->office_name }} @endif
                                            @if($travelOrder->position->is_unit_head) (Unit Head) @elseif($travelOrder->position->is_division_head) (Division Head) @elseif($travelOrder->position->is_vp) (VP) @elseif($travelOrder->position->is_president) (President) @endif
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Destination</h3>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $travelOrder->destination }}</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                                    <p class="mt-1">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                            @if($travelOrder->status === 'approved') bg-green-100 text-green-800
                                            @elseif($travelOrder->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($travelOrder->status) }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Date From</h3>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $travelOrder->date_from->format('F j, Y') }}</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Date To</h3>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $travelOrder->date_to->format('F j, Y') }}</p>
                                </div>
                                
                                @if($travelOrder->departure_time)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Departure Time</h3>
                                        <p class="mt-1 text-base font-medium text-gray-900">{{ date('g:i A', strtotime($travelOrder->departure_time)) }}</p>
                                    </div>
                                @endif
                                
                                <div class="md:col-span-2">
                                    <h3 class="text-sm font-medium text-gray-500">Purpose</h3>
                                    <p class="mt-1 text-base text-gray-900">{{ $travelOrder->purpose }}</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Remarks</h3>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $travelOrder->remarks }}</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                                    <p class="mt-1 text-base font-medium text-gray-900">{{ $travelOrder->created_at->format('F j, Y g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$travelOrder->head_approved && !$travelOrder->vp_approved)
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('travel-orders.edit', $travelOrder) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 border border-blue-300 rounded-lg font-semibold text-sm text-blue-700 uppercase tracking-wider hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                
                                <form action="{{ route('travel-orders.destroy', $travelOrder) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-btn inline-flex items-center px-4 py-2 bg-red-100 border border-red-300 rounded-lg font-semibold text-sm text-red-700 uppercase tracking-wider hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete button clicks for travel orders in show page
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Are you sure you want to delete this travel order? This action cannot be undone.',
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