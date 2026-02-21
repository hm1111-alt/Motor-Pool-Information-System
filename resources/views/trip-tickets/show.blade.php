@extends('layouts.motorpool-admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Trip Ticket Details') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-6">
                        <!-- Trip Ticket Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Trip Ticket Information</h3>
                            <div class="mt-2 border-t border-gray-200 pt-2">
                                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $tripTicket->id }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($tripTicket->status === 'Pending') bg-yellow-100 text-yellow-800
                                                @elseif($tripTicket->status === 'Approved') bg-blue-100 text-blue-800
                                                @elseif($tripTicket->status === 'Completed') bg-green-100 text-green-800
                                                @elseif($tripTicket->status === 'Cancelled') bg-red-100 text-red-800
                                                @endif">
                                                {{ $tripTicket->status }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $tripTicket->created_at->format('M d, Y H:i') }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $tripTicket->updated_at->format('M d, Y H:i') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        
                        <!-- Itinerary Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Itinerary Information</h3>
                            <div class="mt-2 border-t border-gray-200 pt-2">
                                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Itinerary ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $tripTicket->itinerary->id ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Driver</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $tripTicket->itinerary->driver?->first_name . ' ' . $tripTicket->itinerary->driver?->last_name ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Vehicle</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $tripTicket->itinerary->vehicle?->model ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Destination</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $tripTicket->itinerary->destination ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">Purpose</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $tripTicket->itinerary->purpose ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        
                        <!-- Passengers -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Passengers</h3>
                            <div class="mt-2 border-t border-gray-200 pt-2">
                                <div class="mb-2">
                                    <strong>Head of Party:</strong>
                                    <span class="ml-2">{{ $tripTicket->head_of_party ?? 'None selected' }}</span>
                                </div>
                                <ul class="list-disc list-inside">
                                    @forelse($tripTicket->passengers ?? [] as $passenger)
                                        <li class="text-sm text-gray-900 @if($tripTicket->head_of_party == $passenger) bg-yellow-100 border-l-4 border-yellow-500 pl-2 @endif">
                                            {{ $passenger }}
                                            @if($tripTicket->head_of_party == $passenger) <span class="text-xs text-yellow-700 ml-1">(Head of Party)</span> @endif
                                        </li>
                                    @empty
                                        <li class="text-sm text-gray-500">No passengers added</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('trip-tickets.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Back to List
                        </a>
                        <a href="{{ route('trip-tickets.edit', $tripTicket) }}" class="mr-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Trip Ticket
                        </a>
                        <form action="{{ route('trip-tickets.destroy', $tripTicket) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this trip ticket? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Trip Ticket
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection