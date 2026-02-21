@extends('layouts.motorpool-admin')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Travel Order #{{ $travelOrder->id }}</h2>
                <div>
                    <a href="{{ route('motorpool.travel-orders.pdf', $travelOrder) }}" 
                       class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-2" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i> Download PDF
                    </a>
                    <a href="{{ route('motorpool.travel-orders.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Back to Travel Orders
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Employee Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Employee Name</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $travelOrder->employee?->first_name }} {{ $travelOrder->employee?->last_name }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $travelOrder->employee?->user?->email }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Position</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $travelOrder->employee?->position_name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Travel Details</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Destination</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $travelOrder->destination }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purpose</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $travelOrder->purpose }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date From</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($travelOrder->date_from instanceof \DateTimeInterface)
                                        {{ $travelOrder->date_from->format('F j, Y') }}
                                    @else
                                        {{ $travelOrder->date_from }}
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date To</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($travelOrder->date_to instanceof \DateTimeInterface)
                                        {{ $travelOrder->date_to->format('F j, Y') }}
                                    @else
                                        {{ $travelOrder->date_to }}
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Departure Time</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($travelOrder->departure_time instanceof \DateTimeInterface)
                                        {{ $travelOrder->departure_time->format('g:i A') }}
                                    @else
                                        {{ $travelOrder->departure_time }}
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($travelOrder->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($travelOrder->divisionhead_approved_at || $travelOrder->vp_approved_at)
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Approval Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($travelOrder->divisionhead_approved_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Division Head Approved</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        @if($travelOrder->divisionhead_approved_at instanceof \DateTimeInterface)
                                            {{ $travelOrder->divisionhead_approved_at->format('F j, Y g:i A') }}
                                        @else
                                            {{ $travelOrder->divisionhead_approved_at }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                            
                            @if($travelOrder->vp_approved_at)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">VP Approved</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        @if($travelOrder->vp_approved_at instanceof \DateTimeInterface)
                                            {{ $travelOrder->vp_approved_at->format('F j, Y g:i A') }}
                                        @else
                                            {{ $travelOrder->vp_approved_at }}
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
