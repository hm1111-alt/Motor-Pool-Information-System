@php 
    $rowNumber = ($divisions->currentPage() - 1) * $divisions->perPage() + 1; 
    $rowIndex = 0;
@endphp
@foreach($divisions as $division)
<tr class="division-row hover:bg-gray-50 transition duration-150 {{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}" 
    data-name="{{ strtolower($division->division_name) }}" 
    data-abbr="{{ strtolower($division->division_abbr) }}" 
    data-code="{{ strtolower($division->division_code) }}" 
    data-status="{{ $division->division_isactive ? 'active' : 'inactive' }}">
@php $rowIndex++; @endphp
    <td style="padding: 8px 6px; text-align: center;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; font-weight: 500; color: #374151;">{{ $rowNumber++ }}</div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; font-weight: 500; color: #374151;">{{ $division->division_name }}</div>
        <div style="font-size: 0.75rem; color: #9ca3af; font-weight: normal; margin-top: 2px;">
            {{ $division->office->office_name ?? 'N/A' }}
        </div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; color: #6b7280;">{{ $division->division_abbr }}</div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; color: #6b7280;">{{ $division->division_code }}</div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        @if($division->division_isactive)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 status-active">
            Active
        </span>
        @else
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 status-inactive">
            Inactive
        </span>
        @endif
    </td>
    <td style="padding: 8px 6px; text-align: center;" class="whitespace-nowrap">
        <div class="action-buttons">
   <button type="button" 
        class="btn edit-btn inline-flex items-center mr-1" 
        data-bs-toggle="modal" 
        data-bs-target="#editDivisionModal"
        data-id="{{ $division->id_division }}"
        data-name="{{ $division->division_name }}"
        data-abbreviation="{{ $division->division_abbr }}"
        data-code="{{ $division->division_code }}"
        data-status="{{ $division->division_isactive ? '1' : '0' }}"
        data-office-id="{{ $division->office_id }}">
    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
    Edit
</button>
        </div>
    </td>
</tr>
@endforeach

@if($divisions->isEmpty())
<tr id="no-results-row" class="bg-white">
    <td colspan="6" style="padding: 8px 6px; text-align: center;" class="whitespace-nowrap">
        <div class="flex flex-col items-center justify-center py-8">
            <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <p class="text-lg font-medium text-gray-900">No divisions found</p>
            <p class="mt-1 text-gray-500">Try adjusting your search or filter criteria</p>
        </div>
    </td>
</tr>
@endif