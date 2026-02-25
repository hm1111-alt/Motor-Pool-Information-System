@php 
    $rowNumber = ($units->currentPage() - 1) * $units->perPage() + 1; 
    $rowIndex = 0;
@endphp
@foreach($units as $unit)
<tr class="unit-row hover:bg-gray-50 transition duration-150 {{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}" 
    data-name="{{ strtolower($unit->unit_name) }}" 
    data-abbr="{{ strtolower($unit->unit_abbr) }}" 
    data-code="{{ strtolower($unit->unit_code) }}" 
    data-status="{{ $unit->unit_isactive ? 'active' : 'inactive' }}">
@php $rowIndex++; @endphp
    <td style="padding: 6px 4px; text-align: center;" class="whitespace-nowrap">
        <div style="font-size: 0.8rem; font-weight: 500; color: #374151;">{{ $rowNumber++ }}</div>
    </td>
    <td style="padding: 6px 4px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.8rem; font-weight: 500; color: #374151;">{{ $unit->unit_name }}</div>
        <div style="font-size: 0.7rem; color: #9ca3af; font-weight: normal; margin-top: 1px;">
            {{ $unit->division->division_name ?? 'N/A' }}
        </div>
    </td>
    <td style="padding: 6px 4px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.8rem; color: #6b7280;">{{ $unit->unit_abbr }}</div>
    </td>
    <td style="padding: 6px 4px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.8rem; color: #6b7280;">{{ $unit->unit_code }}</div>
    </td>
    <td style="padding: 6px 4px; text-align: left;" class="whitespace-nowrap">
        @if($unit->unit_isactive)
        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800 status-active">
            Active
        </span>
        @else
        <span class="px-1.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 status-inactive">
            Inactive
        </span>
        @endif
    </td>
</tr>
@endforeach

@if($units->isEmpty())
<tr id="no-results-row" class="bg-white">
    <td colspan="5" style="padding: 6px 4px; text-align: center;" class="whitespace-nowrap">
        <div class="flex flex-col items-center justify-center py-6">
            <svg class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <p class="text-base font-medium text-gray-900">No units found</p>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria</p>
        </div>
    </td>
</tr>
@endif