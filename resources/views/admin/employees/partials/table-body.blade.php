@foreach($employees as $employee)
<tr class="employee-row hover:bg-gray-50 transition duration-150" 
    data-name="{{ strtolower($employee->first_name . ' ' . $employee->last_name) }}" 
    data-position="{{ strtolower($employee->position_name ?? '') }}" 
    data-office="{{ strtolower($employee->office->office_name ?? '') }}" 
    data-division="{{ strtolower($employee->division->division_name ?? '') }}" 
    data-unit="{{ strtolower($employee->unit->unit_name ?? '') }}" 
    data-subunit="{{ strtolower($employee->subunit->subunit_name ?? '') }}" 
    data-class="{{ strtolower($employee->class->class_name ?? '') }}" 
    data-status="{{ $employee->emp_status ? 'active' : 'inactive' }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10 flex items-center justify-center">
                    <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                @if($employee->user)
                    <div class="text-sm text-gray-500">{{ $employee->user->email }}</div>
                @endif
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->position_name }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->office->office_name ?? 'N/A' }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->division->division_name ?? 'N/A' }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->unit->unit_name ?? 'N/A' }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->subunit->subunit_name ?? 'N/A' }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->class->class_name ?? 'N/A' }}</td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($employee->emp_status)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 status-active">
            <svg class="h-3 w-3 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Active
        </span>
        @else
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 status-inactive">
            <svg class="h-3 w-3 mr-1 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Inactive
        </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                Head
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
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <a href="{{ route('admin.employees.edit', $employee) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3 inline-flex items-center edit-employee">
            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </a>
        <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center delete-employee" data-name="{{ $employee->first_name }} {{ $employee->last_name }}">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
            </button>
        </form>
    </td>
</tr>
@endforeach

@if($employees->isEmpty())
<tr id="no-results-row">
    <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
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