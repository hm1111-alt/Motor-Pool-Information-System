@forelse($travelOrders as $travelOrder)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $travelOrder->destination }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            @if($travelOrder->position)
                {{ $travelOrder->position->position_name }}
                @if($travelOrder->position->office) - {{ $travelOrder->position->office->office_name }} @endif
                @if($travelOrder->position->is_unit_head) (Unit Head) @elseif($travelOrder->position->is_division_head) (Division Head) @elseif($travelOrder->position->is_vp) (VP) @elseif($travelOrder->position->is_president) (President) @endif
            @else
                <span class="text-gray-400">N/A</span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $travelOrder->date_from->format('M d, Y') }} - {{ $travelOrder->date_to->format('M d, Y') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            @if($travelOrder->departure_time)
                {{ date('g:i A', strtotime($travelOrder->departure_time)) }}
            @else
                <span class="text-gray-400">N/A</span>
            @endif
        </td>
        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $travelOrder->purpose }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                @if($travelOrder->status === 'approved') bg-green-100 text-green-800
                @elseif($travelOrder->status === 'pending') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800 @endif">
                {{ ucfirst($travelOrder->status) }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $travelOrder->remarks }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button type="button" 
                    onclick="viewTravelOrderDetails({{ $travelOrder->id }})" 
                    class="inline-flex items-center text-[#1e6031] hover:text-[#164f2a] mr-3 border border-[#1e6031] hover:border-[#164f2a] px-3 py-1 rounded text-sm transition-colors duration-200">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View
            </button>
            @if((isset($tab) && $tab == 'pending') || !isset($tab))
                @if((!$travelOrder->head_approved && !$travelOrder->vp_approved) || $travelOrder->employee->is_president)
                    <a href="{{ route('travel-orders.edit', $travelOrder) }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 mr-3">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('travel-orders.destroy', $travelOrder) }}" method="POST" class="inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="delete-btn inline-flex items-center text-red-600 hover:text-red-900">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                @endif
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? '8' : '7' }}" class="px-6 py-8 text-center text-sm text-gray-500">
            <div class="flex flex-col items-center justify-center">
                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <div class="mt-2">
                    @if(isset($tab))
                        @switch($tab)
                            @case('pending')
                                <h3 class="text-lg font-medium text-gray-900">No pending travel requests</h3>
                                <p class="mt-1">You don't have any pending travel requests at the moment.</p>
                                @break
                            @case('approved')
                                <h3 class="text-lg font-medium text-gray-900">No approved travel requests</h3>
                                <p class="mt-1">You don't have any approved travel requests yet.</p>
                                @break
                            @case('cancelled')
                                <h3 class="text-lg font-medium text-gray-900">No cancelled travel requests</h3>
                                <p class="mt-1">You don't have any cancelled travel requests.</p>
                                @break
                            @default
                                <h3 class="text-lg font-medium text-gray-900">No travel requests found</h3>
                                <p class="mt-1">There are no travel requests matching your criteria.</p>
                        @endswitch
                    @else
                        <h3 class="text-lg font-medium text-gray-900">No pending travel requests</h3>
                        <p class="mt-1">You don't have any pending travel requests at the moment.</p>
                    @endif
                </div>
            </div>
        </td>
    </tr>
@endforelse

<script>
// Delete confirmation is now handled by table-search.js
// This prevents duplicate event listeners

// Modal for travel order details
function viewTravelOrderDetails(id) {
    // Fetch travel order details via AJAX
    fetch(`/api/travel-orders/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showTravelOrderModal(data.data);
            } else {
                alert('Error loading travel order details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading travel order details');
        });
}

function showTravelOrderModal(travelOrder) {
    // Create modal HTML
    const modalHtml = `
        <div id="travelOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Travel Order Details</h3>
                        <button onclick="closeTravelOrderModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Employee</label>
                                <p class="mt-1 text-sm text-gray-900">${travelOrder.employee.first_name} ${travelOrder.employee.last_name}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Destination</label>
                                <p class="mt-1 text-sm text-gray-900">${travelOrder.destination}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date From</label>
                                <p class="mt-1 text-sm text-gray-900">${new Date(travelOrder.date_from).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date To</label>
                                <p class="mt-1 text-sm text-gray-900">${new Date(travelOrder.date_to).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Departure Time</label>
                                <p class="mt-1 text-sm text-gray-900">${travelOrder.departure_time ? new Date(`1970-01-01T${travelOrder.departure_time}`).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }) : 'N/A'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                    ${travelOrder.status === 'approved' ? 'bg-green-100 text-green-800' : 
                                      travelOrder.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                      'bg-red-100 text-red-800'}">
                                    ${travelOrder.status.charAt(0).toUpperCase() + travelOrder.status.slice(1)}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Purpose</label>
                            <p class="mt-1 text-sm text-gray-900">${travelOrder.purpose || 'N/A'}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Remarks</label>
                            <p class="mt-1 text-sm text-gray-900">${travelOrder.remarks || 'N/A'}</p>
                        </div>
                        
                        <!-- PDF Button -->
                        <div class="pt-4 border-t border-gray-200">
                            <button type="button" 
                                    onclick="viewTravelOrderPDF(${travelOrder.id})" 
                                    class="inline-flex items-center px-4 py-2 bg-[#1e6031] text-white rounded-md hover:bg-[#164f2a] transition-colors duration-200">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeTravelOrderModal() {
    const modal = document.getElementById('travelOrderModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'travelOrderModal') {
        closeTravelOrderModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTravelOrderModal();
    }
});
</script>