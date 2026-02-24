@forelse($drivers as $driver)
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">
                {{ $driver->employee->first_name ?? 'N/A' }} {{ $driver->employee->last_name ?? '' }}
            </div>
            <div class="text-sm text-gray-500 mt-1">
                {{ $driver->user->email ?? 'No Email' }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $driver->employee->position_name ?? 'No Position' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                @if($driver->availability_status === 'Active') bg-green-100 text-green-800
                @elseif($driver->availability_status === 'Available') bg-green-100 text-green-800
                @elseif($driver->availability_status === 'Not Available') bg-red-100 text-red-800
                @elseif($driver->availability_status === 'On Duty') bg-blue-100 text-blue-800
                @elseif($driver->availability_status === 'Off Duty') bg-yellow-100 text-yellow-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $driver->availability_status }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="{{ route('drivers.show', $driver) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
            <a href="{{ route('drivers.edit', $driver) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
            <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="delete-btn text-red-600 hover:text-red-900">Delete</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
            No drivers found.
        </td>
    </tr>
@endforelse