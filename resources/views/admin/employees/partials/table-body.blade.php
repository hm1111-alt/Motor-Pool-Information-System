@php 
    $rowNumber = ($employees->currentPage() - 1) * $employees->perPage() + 1; 
    $rowIndex = 0;
@endphp
@foreach($employees as $employee)
<tr class="employee-row hover:bg-gray-50 transition duration-150 {{ $rowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}" 
    data-name="{{ strtolower($employee->first_name . ' ' . $employee->last_name) }}" 
    data-position="{{ strtolower($employee->position_name ?? '') }}" 
    data-status="{{ $employee->emp_status ? 'active' : 'inactive' }}">
@php $rowIndex++; @endphp
    <td style="padding: 8px 6px; text-align: center;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; font-weight: 500; color: #374151;">{{ $rowNumber++ }}</div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; font-weight: 500; color: #374151;">{{ $employee->first_name }} {{ $employee->last_name }}</div>
        @if($employee->user)
            <div style="font-size: 0.75rem; color: #9ca3af; font-weight: normal; margin-top: 2px;">
                {{ $employee->user->email }}
            </div>
        @endif
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        <div style="font-size: 0.875rem; color: #6b7280;">{{ $employee->position_name }}</div>
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        @if($employee->emp_status)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 status-active">
            Active
        </span>
        @else
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 status-inactive">
            Inactive
        </span>
        @endif
    </td>
    <td style="padding: 8px 6px; text-align: left;" class="whitespace-nowrap">
        @if($employee->is_president)
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                President
            </span>
        @elseif($employee->is_vp)
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                VP
            </span>
        @elseif($employee->is_head)
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                Unit Head
            </span>
        @elseif($employee->is_divisionhead)
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                Division Head
            </span>
        @else
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                None
            </span>
        @endif
    </td>
    <td style="padding: 8px 6px; text-align: center;" class="whitespace-nowrap">
        <div class="action-buttons">
            <a href="{{ route('admin.employees.show', $employee) }}" class="btn inline-flex items-center mr-1" style="color: #3b82f6 !important; border: 1px solid #3b82f6 !important; background-color: transparent !important;">
                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View
            </a>
            <button type="button" class="btn edit-btn inline-flex items-center mr-1" onclick="openEditEmployeeModal({{ $employee->id }})">
                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </button>
            @if(!$employee->emp_status)
            <span class="text-gray-400 text-sm">
                <svg class="h-4 w-4 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Inactive
            </span>
            @endif
        </div>
    </td>
</tr>
@endforeach

@if($employees->isEmpty())
<tr id="no-results-row" class="bg-white">
    <td colspan="6" style="padding: 8px 6px; text-align: center;" class="whitespace-nowrap">
        <div class="flex flex-col items-center justify-center py-8">
            <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <p class="text-lg font-medium text-gray-900">No employees found</p>
            <p class="mt-1 text-gray-500">Try adjusting your search or filter criteria</p>
        </div>
    </td>
</tr>
@endif

