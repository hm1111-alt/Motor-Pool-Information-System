@extends('layouts.motorpool-admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header with Add Button -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Itinerary Management</h1>
            <a href="{{ route('itinerary.create') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Itinerary
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex -mb-px">
                <a href="{{ route('itinerary.index', ['tab' => 'pending']) }}" 
                   class="{{ ($tab ?? 'pending') === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm flex items-center">
                    Pending
                    @if(isset($pendingCount))
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($tab ?? 'pending') === 'pending' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('itinerary.index', ['tab' => 'approved']) }}" 
                   class="{{ ($tab ?? 'pending') === 'approved' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm flex items-center">
                    Approved
                    @if(isset($approvedCount))
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($tab ?? 'pending') === 'approved' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $approvedCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('itinerary.index', ['tab' => 'cancelled']) }}" 
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

        <!-- Itineraries Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Driver
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehicle
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Purpose
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Destination
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            @if(($tab ?? 'pending') === 'approved')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Unit Head Remarks
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    VP Remarks
                                </th>
                            @else
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($itineraries as $index => $itinerary)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $loop->iteration + (($itineraries->currentPage() - 1) * $itineraries->perPage()) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $itinerary->driver?->first_name ?? 'Not Assigned' }} {{ $itinerary->driver?->last_name ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $itinerary->vehicle?->model ?? 'Not Assigned' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $itinerary->purpose ?? 'No Purpose Specified' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $itinerary->destination }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($itinerary->date_from instanceof \DateTimeInterface)
                                        {{ $itinerary->date_from->format('M d, Y') }}
                                    @else
                                        {{ $itinerary->date_from ?: 'Not Assigned' }}
                                    @endif
                                </td>
                                @if(($tab ?? 'pending') === 'approved')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($itinerary->unit_head_approved_at)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved by Unit Head on {{ $itinerary->unit_head_approved_at->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Not Approved
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($itinerary->vp_approved_at)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved by VP on {{ $itinerary->vp_approved_at->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Not Approved
                                            </span>
                                        @endif
                                    </td>
                                @else
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($itinerary->vp_approved)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @elseif($itinerary->unit_head_approved)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending VP Approval
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Pending Unit Head Approval
                                            </span>
                                        @endif
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('itinerary.show', $itinerary) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    @if(($tab ?? 'pending') !== 'approved')
                                        <a href="{{ route('itinerary.edit', $itinerary) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('itinerary.destroy', $itinerary) }}" 
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to archive this itinerary?')">
                                                Archive
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ ($tab ?? 'pending') === 'approved' ? 9 : 8 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                    @if(($tab ?? 'pending') === 'pending')
                                        No pending itineraries found. <a href="{{ route('itinerary.create') }}" class="text-purple-600 hover:text-purple-800">Create your first itinerary</a>.
                                    @elseif(($tab ?? 'pending') === 'approved')
                                        No approved itineraries found.
                                    @else
                                        No cancelled itineraries found.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($itineraries->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $itineraries->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection