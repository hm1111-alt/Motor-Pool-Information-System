@forelse($travelOrders as $travelOrder)
    <tr>
        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
            {{ $travelOrder->employee->first_name }} {{ $travelOrder->employee->last_name }}
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
                <a href="{{ route('travel-orders.show', $travelOrder) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-2">
                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                </a>
                
                <form action="{{ route('travel-orders.approve.head', $travelOrder) }}" method="POST" class="inline-block mr-2">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="inline-flex items-center text-green-600 hover:text-green-900" onclick="return confirm('Are you sure you want to approve this travel order?')">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Approve
                    </button>
                </form>
                
                <form action="{{ route('travel-orders.reject.head', $travelOrder) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to reject this travel order?')">
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