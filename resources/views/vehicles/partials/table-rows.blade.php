@foreach($vehicles as $index => $vehicle)
    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-[#f0f8f0]">
        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $vehicles->firstItem() + $index }}</td>
        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#004d00] font-medium">{{ $vehicle->plate_number }}</td>
        <td class="px-3 py-2 whitespace-nowrap text-xs text-[#006400]">{{ $vehicle->model }}</td>
        <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-600 max-w-[200px]">
            <div class="text-[#004d00] font-medium">{{ $vehicle->type }}</div>
        </td>
        <td class="px-3 py-2 whitespace-nowrap text-center text-xs">
            <div class="action-buttons flex justify-center space-x-1">
                <a href="{{ route('vehicles.show', $vehicle) }}" 
                   class="btn view-btn border inline-flex items-center justify-center"
                   title="View Vehicle">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="{{ route('vehicles.edit', $vehicle) }}" 
                   class="btn edit-btn border inline-flex items-center justify-center"
                   title="Edit Vehicle">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            class="btn archive-btn border delete-btn inline-flex items-center justify-center"
                            title="Delete Vehicle">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </td>
    </tr>
@endforeach

@if($vehicles->isEmpty())
    <tr>
        <td colspan="6" class="px-3 py-6 text-center text-xs text-gray-500">
            No vehicles found.
        </td>
    </tr>
@endif