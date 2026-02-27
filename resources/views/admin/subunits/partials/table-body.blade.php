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
        <div style="font-size: 0.7rem; color: #9ca3af; font-weight: normal; margin-top: 2px;">
            {{ $subunit->unit->unit_name ?? 'N/A' }}
        </div>
    </td>
    <td style="padding: 6px 4px; text-align: left; overflow: hidden; text-overflow: ellipsis;" class="whitespace-nowrap">
        <div style="font-size: 0.8rem; color: #6b7280; overflow: hidden; text-overflow: ellipsis;">{{ $subunit->subunit_abbr }}</div>
    </td>
    <td style="padding: 6px 4px; text-align: left; overflow: hidden; text-overflow: ellipsis;" class="whitespace-nowrap">
        @if($subunit->subunit_isactive)
        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800 status-active">
            Active
        </span>
        @else
        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 status-inactive">
            Inactive
        </span>
        @endif
    </td>
    <td style="padding: 6px 4px; text-align: center; overflow: hidden; text-overflow: ellipsis;" class="whitespace-nowrap">
        <div class="action-buttons">
            <button type="button" 
                class="btn edit-btn inline-flex items-center px-2 py-1 text-xs h-6"
                data-bs-toggle="modal" 
                data-bs-target="#editSubunitModal"
                data-id="{{ $subunit->id_subunit }}"
                data-name="{{ $subunit->subunit_name }}"
                data-abbr="{{ $subunit->subunit_abbr }}"
                data-unit-id="{{ $subunit->unit_id }}"
                data-status="{{ $subunit->subunit_isactive ? '1' : '0' }}">
                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </button>
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