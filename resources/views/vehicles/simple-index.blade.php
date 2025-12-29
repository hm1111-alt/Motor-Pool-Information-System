<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Vehicle Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Vehicle Management</h1>
            <a href="{{ route('vehicles.create') }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium">
                + Add New Vehicle
            </a>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Vehicles List</h2>
            

            
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Plate Number</th>
                            <th class="py-3 px-6 text-left">Model</th>
                            <th class="py-3 px-6 text-left">Type</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @forelse($vehicles as $vehicle)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left whitespace-nowrap">
                                    {{ $vehicle->plate_number }}
                                </td>
                                <td class="py-3 px-6 text-left">
                                    {{ $vehicle->model }}
                                </td>
                                <td class="py-3 px-6 text-left">
                                    {{ $vehicle->type }}
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="bg-{{ $vehicle->status === 'Available' ? 'green' : ($vehicle->status === 'Not Available' ? 'red' : 'blue') }}-100 text-{{ $vehicle->status === 'Available' ? 'green' : ($vehicle->status === 'Not Available' ? 'red' : 'blue') }}-800 text-xs px-2 py-1 rounded">
                                        {{ $vehicle->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <div class="flex item-center justify-end">
                                        <a href="{{ route('vehicles.show', $vehicle) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                        <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-btn text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-3 px-6 text-center text-gray-500">
                                    No vehicles found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($vehicles->hasPages())
                <div class="mt-4">
                    {{ $vehicles->links() }}
                </div>
            @endif
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete button clicks for vehicles in simple index
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Are you sure you want to delete this vehicle? This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
        </script>

        <div class="mt-6">
            <a href="{{ url('/') }}" class="text-indigo-600 hover:text-indigo-900">← Back to Home</a>
        </div>
    </div>
</body>
</html>