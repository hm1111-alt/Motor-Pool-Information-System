@extends('layouts.motorpool-admin')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">{{ $itinerary->destination }}</h2>
                <div>
                    <a href="{{ auth()->user() && auth()->user()->employee ? (auth()->user()->employee->is_vp ? route('vp.travel-orders.index') : (auth()->user()->employee->is_head ? route('unithead.travel-orders.index') : route('dashboard'))) : route('dashboard') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 mr-2">
                        Back to Dashboard
                    </a>
                    <a href="{{ route('itinerary.edit', $itinerary) }}" 
                       class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Edit
                    </a>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Travel Order</label>
                                <p class="mt-1 text-sm text-gray-900">#{{ $itinerary->travel_order_id ?? 'N/A' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date Range</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $itinerary->date_from?->format('F j, Y') }} - {{ $itinerary->date_to?->format('F j, Y') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Departure Time</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($itinerary->departure_time instanceof \DateTimeInterface)
                                        {{ $itinerary->departure_time->format('g:i A') }}
                                    @else
                                        {{ $itinerary->departure_time }}
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($itinerary->status === 'Approved') bg-green-100 text-green-800
                                    @elseif($itinerary->status === 'Not yet Approved') bg-yellow-100 text-yellow-800
                                    @elseif($itinerary->status === 'Cancelled') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $itinerary->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Date Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date From</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($itinerary->date_from instanceof \DateTimeInterface)
                                        {{ $itinerary->date_from->format('F j, Y') }}
                                    @else
                                        {{ $itinerary->date_from }}
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date To</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($itinerary->date_to instanceof \DateTimeInterface)
                                        {{ $itinerary->date_to->format('F j, Y') }}
                                    @else
                                        {{ $itinerary->date_to }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Destination</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $itinerary->destination }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purpose</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $itinerary->purpose }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vehicle</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($itinerary->vehicle)
                                        {{ $itinerary->vehicle->model }} ({{ $itinerary->vehicle->plate_number }})
                                    @else
                                        Not Assigned
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Driver</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($itinerary->driver)
                                        {{ $itinerary->driver->first_name }} {{ $itinerary->driver->last_name }}
                                    @else
                                        Not Assigned
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                    <div class="flex space-x-3">
                        <form action="{{ route('itinerary.destroy', $itinerary) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    onclick="return confirm('Are you sure you want to delete this itinerary?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection