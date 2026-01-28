@extends('layouts.employee')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Itinerary Approvals') }}
        </h2>
        <a href="{{ auth()->user()->employee->is_vp ? route('vp.travel-orders.index') : (auth()->user()->employee->is_head ? route('unithead.travel-orders.index') : route('employee.dashboard')) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Back to Dashboard
        </a>
    </div>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <a href="{{ route('itinerary.approvals.unit-head.pending', ['tab' => 'pending']) }}" 
                           class="{{ ($tab ?? 'pending') === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm flex items-center">
                            Pending
                            @if(isset($pendingCount))
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($tab ?? 'pending') === 'pending' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('itinerary.approvals.unit-head.pending', ['tab' => 'approved']) }}" 
                           class="{{ ($tab ?? 'pending') === 'approved' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm flex items-center">
                            Approved
                            @if(isset($approvedCount))
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($tab ?? 'pending') === 'approved' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $approvedCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('itinerary.approvals.unit-head.pending', ['tab' => 'cancelled']) }}" 
                           class="{{ ($tab ?? 'pending') === 'cancelled' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm flex items-center">
                            Cancelled
                            @if(isset($cancelledCount))
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($tab ?? 'pending') === 'cancelled' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $cancelledCount }}
                                </span>
                            @endif
                        </a>
                    </nav>
                </div>
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-6 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($itineraries->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Travel Order</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                        @if(($tab ?? 'pending') === 'pending')
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        @else
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved/Rejected By</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($itineraries as $itinerary)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $itinerary->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($itinerary->travelOrder)
                                                    #{{ $itinerary->travelOrder->id }} - {{ $itinerary->travelOrder->employee->first_name ?? 'N/A' }} {{ $itinerary->travelOrder->employee->last_name ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($itinerary->driver)
                                                    {{ $itinerary->driver->full_name }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($itinerary->vehicle)
                                                    {{ $itinerary->vehicle->make }} {{ $itinerary->vehicle->model }} ({{ $itinerary->vehicle->plate_number }})
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $itinerary->destination }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $itinerary->date_from->format('M d, Y') }} - {{ $itinerary->date_to->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($itinerary->purpose, 50) }}</td>
                                            @if(($tab ?? 'pending') === 'pending')
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('itinerary.show', $itinerary) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                    <form method="POST" action="{{ route('itinerary.approvals.unit-head.approve', $itinerary->id) }}" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3" onclick="return confirm('Are you sure you want to approve this itinerary?')">Approve</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('itinerary.approvals.unit-head.reject', $itinerary->id) }}" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to reject this itinerary?')">Reject</button>
                                                    </form>
                                                </td>
                                            @else
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($itinerary->unit_head_approved_by)
                                                        {{ App\Models\User::find($itinerary->unit_head_approved_by)?->name ?? 'Unknown User' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($itinerary->unit_head_approved_at)
                                                        {{ $itinerary->unit_head_approved_at->format('M d, Y g:i A') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            @if(($tab ?? 'pending') === 'pending')
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No itineraries pending for unit head approval</h3>
                                <p class="mt-1 text-sm text-gray-500">All itineraries have been processed or there are no pending itineraries.</p>
                            @elseif(($tab ?? 'pending') === 'approved')
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No approved itineraries</h3>
                                <p class="mt-1 text-sm text-gray-500">There are currently no itineraries approved by the unit head.</p>
                            @else
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No cancelled itineraries</h3>
                                <p class="mt-1 text-sm text-gray-500">There are currently no itineraries rejected by the unit head.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection