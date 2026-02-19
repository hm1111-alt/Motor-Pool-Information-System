@extends('layouts.motorpool-admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Driver Details</h2>
                        <p class="mt-1 text-sm text-gray-600">View driver information</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('drivers.edit', $driver) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Driver
                        </a>
                        <a href="{{ route('drivers.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to List
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Driver Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Driver Information</h3>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Full Name</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ $driver->full_name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Formal Name</label>
                                <p class="mt-1 text-lg font-medium text-gray-900">{{ $driver->full_name2 }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">First Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $driver->first_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Middle Initial</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $driver->middle_initial ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Last Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->last_name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Position</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->position }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Official Station</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->official_station }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Availability Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($driver->availability_status == 'Available') bg-green-100 text-green-800
                                    @elseif($driver->availability_status == 'Not Available') bg-red-100 text-red-800
                                    @elseif($driver->availability_status == 'On Duty') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $driver->availability_status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h3>
                        <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->user->email ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Contact Number</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->user->contact_num ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">Address</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $driver->address }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500">User Account</label>
                                @if($driver->user)
                                    <p class="mt-1 text-sm text-gray-900">{{ $driver->user->name }} ({{ $driver->user->email }})</p>
                                    <p class="text-xs text-gray-500">Role: {{ ucfirst($driver->user->role ?? 'driver') }}</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-900 text-red-600">No user account linked</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">System Information</h3>
                    <div class="bg-gray-50 rounded-lg p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Driver ID</label>
                            <p class="mt-1 text-sm font-mono text-gray-900">{{ $driver->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $driver->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $driver->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Assigned Itineraries -->
                @if($driver->itineraries->count() > 0)
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Assigned Itineraries</h3>
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Itinerary</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($driver->itineraries as $itinerary)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $itinerary->purpose ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($itinerary->vehicle)
                                            {{ $itinerary->vehicle->make }} {{ $itinerary->vehicle->model }}
                                        @else
                                            <span class="text-gray-400">No vehicle assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $itinerary->date?->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($itinerary->status == 'Approved') bg-green-100 text-green-800
                                            @elseif($itinerary->status == 'Pending') bg-yellow-100 text-yellow-800
                                            @elseif($itinerary->status == 'Rejected') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $itinerary->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection