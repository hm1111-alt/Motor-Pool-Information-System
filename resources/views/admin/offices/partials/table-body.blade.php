@php $rowNumber = ($offices->currentPage() - 1) * $offices->perPage() + 1; @endphp
@foreach($offices as $office)
<tr class="office-row hover:bg-gray-50 transition duration-150 {{ $loop->even ? 'bg-white' : 'bg-gray-50' }}" 
    data-name="{{ strtolower($office->office_name) }}" 
    data-program="{{ strtolower($office->office_program) }}" 
    data-abbr="{{ strtolower($office->office_abbr) }}" 
    data-code="{{ strtolower($office->officer_code) }}" 
    data-status="{{ $office->office_isactive ? 'active' : 'inactive' }}">
    <td style="padding: 8px 6px; text-align: center;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; font-weight: 500; color: #374151;">{{ $rowNumber++ }}</div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; font-weight: 500; color: #374151;">{{ $office->office_name }}</div>
        <div style="font-size: 0.75rem; color: #9ca3af; font-weight: normal; margin-top: 2px;">
            {{ $office->office_program }}
        </div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; color: #6b7280;">{{ $office->office_abbr }}</div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; color: #6b7280;">{{ $office->officer_code }}</div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        @if($office->office_isactive)
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
            <button type="button" class="btn edit-btn inline-flex items-center mr-1" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editOfficeModal"
                    data-id="{{ $office->id }}"
                    data-program="{{ $office->office_program }}"
                    data-name="{{ $office->office_name }}"
                    data-abbreviation="{{ $office->office_abbr }}"
                    data-code="{{ $office->officer_code }}"
                    data-status="{{ $office->office_isactive ? 'Active' : 'Inactive' }}">
                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </button>
        </div>
    </td>
</tr>
@endforeach

@if($offices->isEmpty())
<tr id="no-results-row" class="bg-white">
    <td colspan="6" style="padding: 8px 6px; text-align: center;" class="whitespace-nowrap">
        <div class="flex flex-col items-center justify-center py-8">
            <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <p class="text-lg font-medium text-gray-900">No offices found</p>
            <p class="mt-1 text-gray-500">Try adjusting your search or filter criteria</p>
        </div>
    </td>
</tr>
@endif