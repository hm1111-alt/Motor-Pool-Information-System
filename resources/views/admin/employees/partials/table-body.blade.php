@foreach($employees as $employee)
    <tr>
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
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Active
                </span>
            @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
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
            <a href="{{ route('admin.employees.edit', $employee) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3 edit-employee">Edit</a>
            <a href="{{ route('admin.employees.destroy', $employee) }}" class="text-red-600 hover:text-red-900 delete-employee">Delete</a>
        </td>
    </tr>
@endforeach