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
                <a href="{{ isset($approvalShowRoute) ? route($approvalShowRoute, $travelOrder) : route('travel-orders.approval-show.head', $travelOrder) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-2">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                </a>
                
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
</script>