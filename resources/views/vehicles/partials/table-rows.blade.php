@forelse($vehicles as $vehicle)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            @if($vehicle->picture)
                <img src="{{ asset('storage/' . $vehicle->picture) }}" alt="Vehicle Image" class="h-16 w-16 object-cover rounded-md" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'h-16 w-16 bg-gray-200 rounded-md flex items-center justify-center\'><svg class=\'h-8 w-8 text-gray-400\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\' /></svg></div>';"
            @else
                <div class="h-16 w-16 bg-gray-200 rounded-md flex items-center justify-center">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $vehicle->plate_number }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vehicle->model }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vehicle->type }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $vehicle->seating_capacity }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($vehicle->mileage) }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                @if($vehicle->status === 'Available') bg-green-100 text-green-800
                @elseif($vehicle->status === 'Not Available') bg-red-100 text-red-800
                @elseif($vehicle->status === 'Active') bg-blue-100 text-blue-800
                @elseif($vehicle->status === 'Under Maintenance') bg-yellow-100 text-yellow-800
                @endif">
                {{ $vehicle->status }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <a href="{{ route('vehicles.show', $vehicle) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="inline delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="delete-btn text-red-600 hover:text-red-900">Delete</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
            No vehicles found.
        </td>
    </tr>
@endforelse