@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        {{ __('My Travel Requests') }}
    </h2>
@endsection

@section('content')
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow rounded border border-gray-100">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-200">
                        <h1 class="text-lg font-bold text-gray-800">My Travel Requests</h1>
                        <a href="{{ route('president.travel-orders.create') }}" class="inline-flex items-center px-3 py-1.5 bg-[#1e6031] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#164f2a] focus:bg-[#164f2a] active:bg-[#1e6031] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create New Request
                        </a>
                    </div>
                    

                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure Time</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($travelOrders as $travelOrder)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $travelOrder->destination }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->date_from->format('M d, Y') }} - {{ $travelOrder->date_to->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            @if($travelOrder->departure_time)
                                                {{ date('g:i A', strtotime($travelOrder->departure_time)) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900 max-w-xs truncate">{{ $travelOrder->purpose }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($travelOrder->status === 'approved') bg-green-100 text-green-800
                                                @elseif($travelOrder->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($travelOrder->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->remarks }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('president.travel-orders.show', $travelOrder) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                                            @if($travelOrder->status === 'pending')
                                                <a href="{{ route('president.travel-orders.edit', $travelOrder) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                                <form action="{{ route('president.travel-orders.destroy', $travelOrder) }}" method="POST" class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="delete-btn text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                                            No travel requests found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($travelOrders->hasPages())
                        <div class="mt-4">
                            {{ $travelOrders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete button clicks for travel orders in president index
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