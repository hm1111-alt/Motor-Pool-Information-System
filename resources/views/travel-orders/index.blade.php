<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Travel Requests
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input 
                                type="text" 
                                id="searchInput"
                                value="{{ $search ?? '' }}"
                                placeholder="Search travel orders..." 
                                class="w-full rounded-l-lg border-gray-300 shadow-sm focus:border-[#009639] focus:ring focus:ring-[#009639] focus:ring-opacity-50 py-3 px-4 text-base"
                            >
                            <button 
                                id="searchButton"
                                class="bg-[#009639] hover:bg-[#007d31] text-white px-6 py-3 rounded-r-lg transition duration-300 flex items-center text-base font-medium"
                            >
                                <i class="fas fa-search mr-2"></i> Search
                            </button>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-8">
                            <a href="?tab=pending" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'pending' ? 'border-[#009639] text-[#009639]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Pending
                            </a>
                            <a href="?tab=approved" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'approved' ? 'border-[#009639] text-[#009639]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Approved
                            </a>
                            <a href="?tab=cancelled" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ $activeTab == 'cancelled' ? 'border-[#009639] text-[#009639]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Cancelled
                            </a>
                        </nav>
                    </div>

                    <!-- Travel Orders Table -->
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full divide-y divide-gray-200" id="travelOrdersTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date From</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date To</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departure Time</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Filed</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($travelOrders as $index => $order)
                                    <tr class="hover:bg-gray-50 travel-order-row" 
                                        data-reference="{{ $index + 1 }}" 
                                        data-purpose="{{ $order->purpose }}"
                                        data-employee="{{ $order->employee->full_name ?? 'N/A' }}"
                                        data-destination="{{ $order->destination ?? 'N/A' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->employee->full_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $order->purpose }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ $order->destination ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->date_from ? $order->date_from->format('M d, Y') : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->date_to ? $order->date_to->format('M d, Y') : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->departure_time ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm 
                                            @if($activeTab == 'approved') text-green-600 
                                            @elseif($activeTab == 'cancelled') text-red-600 
                                            @else text-yellow-600 @endif">
                                            @if($activeTab == 'pending')
                                                @if($order->divisionhead_approved == 1 && is_null($order->vp_approved))
                                                    For VP approval
                                                @elseif($order->divisionhead_approved == 1 && $order->vp_approved == 0)
                                                    For VP approval
                                                @else
                                                    Not yet approved
                                                @endif
                                            @elseif($activeTab == 'approved')
                                                Approved
                                            @else
                                                Cancelled
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($activeTab == 'pending')
                                                <a href="{{ route('travel-orders.edit', $order) }}" class="text-[#009639] hover:text-[#007d31] mr-3">Edit</a>
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
                                        <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No travel orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                        cancelButtonColor: '#009639',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the delete form
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/travel-orders/${travelOrderId}`;
                            
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            
                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';
                            
                            form.appendChild(csrfToken);
                            form.appendChild(methodField);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>