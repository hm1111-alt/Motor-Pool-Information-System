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
            @if($travelOrder->departure_time)
                {{ date('g:i A', strtotime($travelOrder->departure_time)) }}
            @else
                <span class="text-gray-400">N/A</span>
            @endif
        </td>
        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
            {{ $travelOrder->created_at->format('M d, Y') }}
        </td>
        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('travel-orders.show', $travelOrder) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-2">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View
            </a>
            
            <a href="#" class="inline-flex items-center text-green-600 hover:text-green-900">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Assign Vehicle
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
            No approved travel orders found.
        </td>
    </tr>
@endforelse