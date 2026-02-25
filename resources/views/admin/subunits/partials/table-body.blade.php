@php 
    $rowNumber = ($subunits->currentPage() - 1) * $subunits->perPage() + 1; 
    $rowIndex = 0;
@endphp
@foreach($subunits as $subunit)
<tr class="subunit-row hover:bg-gray-50 transition duration-150 {{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}" 
    data-name="{{ strtolower($subunit->subunit_name) }}" 
    data-abbr="{{ strtolower($subunit->subunit_abbr) }}" 
    data-status="{{ $subunit->subunit_isactive ? 'active' : 'inactive' }}">
@php $rowIndex++; @endphp
    <td style="padding: 6px 4px; text-align: center; overflow: hidden; text-overflow: ellipsis;" class="whitespace-nowrap">
        <div style="font-size: 0.8rem; font-weight: 500; color: #374151;">{{ $rowNumber++ }}</div>
    </td>
    <td style="padding: 6px 4px; text-align: left; overflow: hidden; text-overflow: ellipsis;" class="whitespace-nowrap">
        <div style="font-size: 0.8rem; font-weight: 500; color: #374151; overflow: hidden; text-overflow: ellipsis;">{{ $subunit->subunit_name }}</div>
    </td>
    <td style="padding: 6px 4px; text-align: left; overflow: hidden; text-overflow: ellipsis;" class="whitespace-nowrap">
        <div style="font-size: 0.8rem; color: #6b7280; overflow: hidden; text-overflow: ellipsis;">{{ $subunit->subunit_abbr }}</div>
    </td>
    <td style="padding: 6px 4px; text-align: left; overflow: hidden; text-overflow: ellipsis;" class="whitespace-nowrap">
        @if($subunit->subunit_isactive)
        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800 status-active">
            <svg class="h-2.5 w-2.5 mr-0.5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Active
        </span>
        @else
        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 status-inactive">
            <svg class="h-2.5 w-2.5 mr-0.5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Inactive
        </span>
        @endif
    </td>
    <td style="padding: 6px 4px; text-align: center; overflow: hidden; text-overflow: ellipsis;" class="whitespace-nowrap">
        <div class="action-buttons flex justify-center gap-1">
            <a href="{{ route('admin.subunits.edit', $subunit) }}" class="btn edit-btn inline-flex items-center px-2 py-1 text-xs h-6">
                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.subunits.destroy', $subunit) }}" method="POST" class="inline delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn delete-btn inline-flex items-center px-2 py-1 text-xs h-6" data-name="{{ $subunit->subunit_name }}">
                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </td>
</tr>
@endforeach

@if($subunits->isEmpty())
<tr id="no-results-row" class="bg-white">
    <td colspan="5" style="padding: 6px 4px; text-align: center;" class="whitespace-nowrap">
        <div class="flex flex-col items-center justify-center py-6">
            <svg class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <p class="text-base font-medium text-gray-900">No subunits found</p>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria</p>
        </div>
    </td>
</tr>
@endif