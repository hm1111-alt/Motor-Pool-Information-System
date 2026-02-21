@forelse($travelOrders as $travelOrder)
    <tr>
        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
            {{ $travelOrder->employee->first_name }} {{ $travelOrder->employee->last_name }}
        </td>
        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
            @if($travelOrder->position)
                {{ $travelOrder->position->position_name }}
                @if($travelOrder->position->office) - {{ $travelOrder->position->office->office_name }} @endif
                @if($travelOrder->position->is_unit_head) (Unit Head) @elseif($travelOrder->position->is_division_head) (Division Head) @elseif($travelOrder->position->is_vp) (VP) @elseif($travelOrder->position->is_president) (President) @endif
            @else
                <span class="text-gray-400">N/A</span>
            @endif
        </td>
        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $travelOrder->destination }}</td>
        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
            {{ $travelOrder->date_from->format('M d, Y') }} - {{ $travelOrder->date_to->format('M d, Y') }}
        </td>
        <td class="px-4 py-2 text-sm text-gray-900 max-w-xs truncate">{{ $travelOrder->purpose }}</td>
        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
            {{ $travelOrder->remarks }}
        </td>
        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
            {{ $travelOrder->created_at->format('M d, Y') }}
        </td>
        @if((isset($tab) && $tab == 'pending') || !isset($tab))
            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                <button type="button" 
                        onclick="viewTravelOrderDetails({{ $travelOrder->id }}, 'head')" 
                        class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-2">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                </button>
                
                @php
                    // Determine the correct approve route based on the current approval context
                    $approveRoute = 'travel-orders.approve.head';
                    if(isset($approvalShowRoute)) {
                        if(str_contains($approvalShowRoute, 'divisionhead')) {
                            $approveRoute = 'travel-orders.approve.divisionhead';
                        } elseif(str_contains($approvalShowRoute, 'vp')) {
                            $approveRoute = 'travel-orders.approve.vp';
                        } elseif(str_contains($approvalShowRoute, 'president')) {
                            $approveRoute = 'travel-orders.approve.president';
                        } elseif(str_contains($approvalShowRoute, 'motorpool')) {
                            $approveRoute = 'travel-orders.approve.motorpool';
                        }
                    }
                @endphp
                <form action="{{ route($approveRoute, $travelOrder) }}" method="POST" class="inline-block mr-2 approve-form">
                    @csrf
                    @method('PUT')
                    <button type="button" class="approve-btn inline-flex items-center text-green-600 hover:text-green-900">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Approve
                    </button>
                </form>
                
                @php
                    // Determine the correct reject route based on the current approval context
                    $rejectRoute = 'travel-orders.reject.head';
                    if(isset($approvalShowRoute)) {
                        if(str_contains($approvalShowRoute, 'divisionhead')) {
                            $rejectRoute = 'travel-orders.reject.divisionhead';
                        } elseif(str_contains($approvalShowRoute, 'vp')) {
                            $rejectRoute = 'travel-orders.reject.vp';
                        } elseif(str_contains($approvalShowRoute, 'president')) {
                            $rejectRoute = 'travel-orders.reject.president';
                        } elseif(str_contains($approvalShowRoute, 'motorpool')) {
                            $rejectRoute = 'travel-orders.reject.motorpool';
                        }
                    }
                @endphp
                <form action="{{ route($rejectRoute, $travelOrder) }}" method="POST" class="inline-block reject-form">
                    @csrf
                    @method('PUT')
                    <button type="button" class="reject-btn inline-flex items-center text-red-600 hover:text-red-900">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Reject
                    </button>
                </form>
            </td>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="{{ (isset($tab) && $tab == 'pending') || !isset($tab) ? '8' : '7' }}" class="px-4 py-4 text-center text-sm text-gray-500">
            @if(isset($tab))
                @switch($tab)
                    @case('pending')
                        No travel orders pending your approval.
                        @break
                    @case('approved')
                        No approved travel orders found.
                        @break
                    @case('cancelled')
                        No cancelled travel orders found.
                        @break
                    @default
                        No travel orders found.
                @endswitch
            @else
                No travel orders pending your approval.
            @endif
        </td>
    </tr>
@endforelse

<script>
// Approve and reject button handlers are now handled by table-search.js
// This prevents duplicate event listeners

// Modal for travel order details
function viewTravelOrderDetails(id, context = 'regular') {
    // Fetch travel order details via AJAX
    let url = `/api/travel-orders/${id}`;
    if (context === 'head') {
        url = `/api/head/travel-orders/${id}`;
    } else if (context === 'divisionhead') {
        url = `/api/divisionhead/travel-orders/${id}`;
    } else if (context === 'vp') {
        url = `/api/vp/travel-orders/${id}`;
    } else if (context === 'president') {
        url = `/api/president/travel-orders/${id}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showTravelOrderModal(data.data, context);
            } else {
                alert('Error loading travel order details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading travel order details');
        });
}

function showTravelOrderModal(travelOrder, context) {
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
                                    onclick="viewTravelOrderPDF(${travelOrder.id}, '${context}')" 
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

function viewTravelOrderPDF(id, context) {
    let pdfUrl;
    switch(context) {
        case 'head':
            pdfUrl = '/head/travel-orders/' + id + '/pdf';
            break;
        case 'divisionhead':
            pdfUrl = '/divisionhead/travel-orders/' + id + '/pdf';
            break;
        case 'vp':
            pdfUrl = '/vp/travel-orders/' + id + '/pdf';
            break;
        case 'president':
            pdfUrl = '/president/travel-orders/' + id + '/pdf';
            break;
        default:
            pdfUrl = '/travel-orders/' + id + '/pdf';
    }
    
    // Open PDF directly in new tab
    window.open(pdfUrl, '_blank');
}
</script>