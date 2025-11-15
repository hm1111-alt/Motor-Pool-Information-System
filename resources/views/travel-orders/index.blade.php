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
                                class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-[#009639] focus:ring focus:ring-[#009639] focus:ring-opacity-50"
                            >
                            <button 
                                id="searchButton"
                                class="bg-[#009639] hover:bg-[#1e6031] text-white px-4 py-2 rounded-r-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009639]"
                            >
                                Search
                            </button>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="flex space-x-8">
                            <a 
                                href="{{ route('travel-orders.index', ['tab' => 'pending']) }}" 
                                class="py-4 px-1 border-b-2 font-medium text-sm @if($activeTab === 'pending') border-[#009639] text-[#009639] @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif"
                                data-tab="pending"
                            >
                                Pending
                            </a>
                            <a 
                                href="{{ route('travel-orders.index', ['tab' => 'approved']) }}" 
                                class="py-4 px-1 border-b-2 font-medium text-sm @if($activeTab === 'approved') border-[#009639] text-[#009639] @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif"
                                data-tab="approved"
                            >
                                Approved
                            </a>
                            <a 
                                href="{{ route('travel-orders.index', ['tab' => 'cancelled']) }}" 
                                class="py-4 px-1 border-b-2 font-medium text-sm @if($activeTab === 'cancelled') border-[#009639] text-[#009639] @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif"
                                data-tab="cancelled"
                            >
                                Cancelled
                            </a>
                        </nav>
                    </div>

                    <!-- Travel Orders Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Employee Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Destination
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Purpose
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date From
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date To
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Departure Time
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Remarks
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="travelOrdersTable">
                                @forelse($travelOrders as $travelOrder)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->employee->first_name ?? '' }} {{ $travelOrder->employee->last_name ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->destination }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ Str::limit($travelOrder->purpose, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->date_from ? \Carbon\Carbon::parse($travelOrder->date_from)->format('M d, Y') : '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->date_to ? \Carbon\Carbon::parse($travelOrder->date_to)->format('M d, Y') : '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $travelOrder->departure_time }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($activeTab === 'pending')
                                                @if(is_null($travelOrder->divisionhead_approved) && is_null($travelOrder->vp_approved))
                                                    Not yet approved
                                                @elseif($travelOrder->divisionhead_approved == 1 && $travelOrder->vp_approved == 0)
                                                    For VP approval
                                                @endif
                                            @elseif($activeTab === 'approved')
                                                Approved
                                            @elseif($activeTab === 'cancelled')
                                                @if($travelOrder->divisionhead_approved == 0 && $travelOrder->vp_approved == 0)
                                                    Cancelled
                                                @elseif($travelOrder->divisionhead_approved == 0 && is_null($travelOrder->vp_approved))
                                                    Cancelled
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($activeTab === 'pending')
                                                <a href="{{ route('travel-orders.edit', $travelOrder) }}" class="text-[#009639] hover:text-[#1e6031] mr-3">Edit</a>
                                                <a href="#" class="text-[#009639] hover:text-[#1e6031] mr-3 view-travel-order" data-id="{{ $travelOrder->id }}">View</a>
                                                <form action="{{ route('travel-orders.destroy', $travelOrder) }}" method="POST" class="inline delete-form" data-id="{{ $travelOrder->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 delete-button">Delete</button>
                                                </form>
                                            @elseif($activeTab === 'approved')
                                                <a href="#" class="text-[#009639] hover:text-[#1e6031] view-travel-order" data-id="{{ $travelOrder->id }}">View</a>
                                            @elseif($activeTab === 'cancelled')
                                                <a href="#" class="text-[#009639] hover:text-[#1e6031] mr-3 view-travel-order" data-id="{{ $travelOrder->id }}">View</a>
                                                <form action="{{ route('travel-orders.destroy', $travelOrder) }}" method="POST" class="inline delete-form" data-id="{{ $travelOrder->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 delete-button">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No travel orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6" id="pagination">
                        {{ $travelOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Scripts -->
    <script>
        // Display success message if it exists
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#009639'
            });
        @endif

        // Display error messages if they exist
        @if($errors->any())
            let errorMessages = '';
            @foreach($errors->all() as $error)
                errorMessages += '{{ $error }}\n';
            @endforeach
            
            Swal.fire({
                title: 'Error!',
                text: errorMessages,
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#009639'
            });
        @endif

        // Real-time search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const activeTab = '{{ $activeTab }}';
            
            // Function to perform search
            function performSearch() {
                const searchTerm = searchInput.value;
                const url = new URL(window.location);
                url.searchParams.set('tab', activeTab);
                url.searchParams.set('search', searchTerm);
                window.location.href = url;
            }
            
            // Search on button click
            searchButton.addEventListener('click', performSearch);
            
            // Search on Enter key press
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            // Real-time search (debounced)
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500); // 500ms delay
            });
            
            // Delete confirmation
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#009639',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>