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
            <a href="{{ route('travel-orders.show', $travelOrder) }}" class="inline-flex items-center text-[#1e6031] hover:text-[#164f2a] mr-3">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View
            </a>
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
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete button clicks for travel orders
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