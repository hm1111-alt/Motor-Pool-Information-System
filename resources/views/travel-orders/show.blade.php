@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        {{ __('Travel Order Details') }}
    </h2>
@endsection

@section('content')
    <div class="py-2">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow rounded border border-gray-100">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
                        <h1 class="text-lg font-bold text-gray-800">Travel Request Details</h1>
                        <a href="{{ route('travel-orders.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to List
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Destination</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $travelOrder->destination }}</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                                    <p class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($travelOrder->status === 'approved') bg-green-100 text-green-800
                                            @elseif($travelOrder->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($travelOrder->status) }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Date From</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $travelOrder->date_from->format('F j, Y') }}</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Date To</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $travelOrder->date_to->format('F j, Y') }}</p>
                                </div>
                                
                                @if($travelOrder->departure_time)
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-500">Departure Time</h3>
                                        <p class="mt-1 text-sm text-gray-900">{{ date('g:i A', strtotime($travelOrder->departure_time)) }}</p>
                                    </div>
                                @endif
                                
                                <div class="md:col-span-2">
                                    <h3 class="text-sm font-medium text-gray-500">Purpose</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $travelOrder->purpose }}</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Remarks</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $travelOrder->remarks }}</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                                    <p class="mt-1 text-sm text-gray-900">{{ $travelOrder->created_at->format('F j, Y g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$travelOrder->head_approved && !$travelOrder->vp_approved)
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('travel-orders.edit', $travelOrder) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 border border-blue-300 rounded-md font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Edit
                                </a>
                                
                                <form action="{{ route('travel-orders.destroy', $travelOrder) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 border border-red-300 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this travel order?')">
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
@endsection