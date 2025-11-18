@foreach($units as $unit)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $unit->unit_name }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $unit->unit_abbr }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $unit->division->division_name }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $unit->unit_code }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($unit->unit_isactive)
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    Active
                </span>
            @else
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Inactive
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.units.edit', $unit) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3 edit-unit">Edit</a>
            <a href="{{ route('admin.units.destroy', $unit) }}" class="text-red-600 hover:text-red-900 delete-unit">Delete</a>
        </td>
    </tr>
@endforeach