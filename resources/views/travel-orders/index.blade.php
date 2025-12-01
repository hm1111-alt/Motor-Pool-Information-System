@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        My Travel Requests
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900">
                    <!-- Page Title -->
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Travel Requests</h3>
                        <p class="text-gray-600 mt-1">Manage your travel orders and view their status</p>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input 
                                type="text" 
                                id="searchInput"
                                value="{{ $search ?? '' }}"
                                placeholder="Search travel orders..." 
                                class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 py-2 px-4 text-base transition duration-300 min-h-[42px]"
                            >
                            <button 
                                id="searchButton"
                                class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300 flex items-center text-base font-medium shadow-sm hover:shadow-md min-h-[42px] justify-center">
                                Search
                            </button>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-6 overflow-x-auto">
                        <nav class="-mb-px flex space-x-6 md:space-x-8 min-w-max">
                            <a href="?tab=pending" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'pending' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Pending
                            </a>
                            <a href="?tab=approved" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'approved' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Approved
                            </a>
                            <a href="?tab=cancelled" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'cancelled' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Cancelled
                            </a>
                        </nav>
                    </div>

                    <!-- Travel Orders Table -->
                    <div class="rounded-lg shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="travelOrdersTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($travelOrders as $index => $order)
                                        <tr class="hover:bg-gray-50 travel-order-row" 
                                            data-reference="{{ $index + 1 }}" 
                                            data-purpose="{{ $order->purpose }}"
                                            data-employee="{{ $order->employee->full_name ?? 'N/A' }}"
                                            data-destination="{{ $order->destination ?? 'N/A' }}">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate">{{ $order->purpose }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $order->destination ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div>
                                                    {{ $order->date_from ? $order->date_from->format('M d, Y') : 'N/A' }}<br>
                                                    <span class="text-gray-500 text-xs">to</span><br>
                                                    {{ $order->date_to ? $order->date_to->format('M d, Y') : 'N/A' }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                @if($activeTab == 'pending')
                                                    @if($order->head_approved == 1 && $order->divisionhead_approved == 1 && is_null($order->vp_approved))
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            For VP approval
                                                        </span>
                                                    @elseif($order->head_approved == 1 && is_null($order->divisionhead_approved))
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            For Division Head approval
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Not yet approved
                                                        </span>
                                                    @endif
                                                @elseif($activeTab == 'approved')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Cancelled
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                                @if($activeTab == 'pending')
                                                    <a href="{{ route('travel-orders.edit', $order) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3">Edit</a>
                                                    <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                    <button 
                                                        class="text-red-600 hover:text-red-900 delete-btn" 
                                                        data-id="{{ $order->id }}"
                                                        data-reference="{{ $index + 1 }}"
                                                    >
                                                        Delete
                                                    </button>
                                                @else
                                                    <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-3 text-center text-sm text-gray-500">
                                                No travel orders found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $travelOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Client-side live search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('.travel-order-row');
            
            // Function to filter table rows
            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                
                tableRows.forEach(row => {
                    const reference = row.getAttribute('data-reference').toLowerCase();
                    const purpose = row.getAttribute('data-purpose').toLowerCase();
                    const employee = row.getAttribute('data-employee').toLowerCase();
                    const destination = row.getAttribute('data-destination').toLowerCase();
                    
                    if (reference.includes(searchTerm) || 
                        purpose.includes(searchTerm) || 
                        employee.includes(searchTerm) || 
                        destination.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Add event listener for real-time search
            searchInput.addEventListener('input', filterTable);
            
            // Also trigger search on button click (for consistency)
            document.getElementById('searchButton').addEventListener('click', filterTable);
            
            // Search on Enter key press
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    filterTable();
                }
            });
        });

        // Delete confirmation with SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const travelOrderId = this.getAttribute('data-id');
                    const referenceNumber = this.getAttribute('data-reference');
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete travel order ${referenceNumber}. This action cannot be undone.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#1e6031',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to delete the travel order
                            fetch(`/travel-orders/${travelOrderId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: data.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#1e6031'
                                    }).then(() => {
                                        // Reload the page to reflect the changes
                                        window.location.href = data.redirect;
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message || 'An error occurred while deleting the travel order.',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#1e6031'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An unexpected error occurred. Please try again.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#1e6031'
                                });
                            });
                        }
                    });
                });
            });
        });
    </script>
@endsection