<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Edit Itinerary</h2>
            </div>
            
            <form action="{{ route('itinerary.update', $itinerary) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Travel Order -->
                    <div>
                        <label for="travel_order_id" class="block text-sm font-medium text-gray-700 mb-1">Travel Order</label>
                        <select name="travel_order_id" id="travel_order_id" 
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <option value="">Select Travel Order (Optional)</option>
                            @foreach($travelOrders as $travelOrder)
                                <option value="{{ $travelOrder->id }}" {{ old('travel_order_id', $itinerary->travel_order_id) == $travelOrder->id ? 'selected' : '' }}>
                                    #{{ $travelOrder->id }} - {{ $travelOrder->destination ?? 'Travel' }}
                                </option>
                            @endforeach
                        </select>
                        @error('travel_order_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From *</label>
                        <input type="date" name="date_from" id="date_from" 
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               value="{{ old('date_from', $itinerary->date_from instanceof \DateTimeInterface ? $itinerary->date_from->format('Y-m-d') : $itinerary->date_from) }}" required>
                        @error('date_from')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To *</label>
                        <input type="date" name="date_to" id="date_to" 
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               value="{{ old('date_to', $itinerary->date_to instanceof \DateTimeInterface ? $itinerary->date_to->format('Y-m-d') : $itinerary->date_to) }}" required>
                        @error('date_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Departure Time -->
                    <div>
                        <label for="departure_time" class="block text-sm font-medium text-gray-700 mb-1">Departure Time *</label>
                        <input type="time" name="departure_time" id="departure_time" 
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               value="{{ old('departure_time', $itinerary->departure_time instanceof \DateTimeInterface ? $itinerary->departure_time->format('H:i') : $itinerary->departure_time) }}" required>
                        @error('departure_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Destination -->
                    <div>
                        <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">Destination *</label>
                        <input type="text" name="destination" id="destination" 
                               class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                               value="{{ old('destination', $itinerary->destination) }}" required>
                        @error('destination')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Vehicle -->
                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-1">Vehicle</label>
                        <select name="vehicle_id" id="vehicle_id" 
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <option value="">Select Vehicle (Optional)</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ $itinerary->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->model }} - {{ $vehicle->plate_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Driver -->
                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                        <select name="driver_id" id="driver_id" 
                                class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <option value="">Select Driver (Optional)</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ $itinerary->driver_id == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->first_name }} {{ $driver->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('driver_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Purpose -->
                    <div class="md:col-span-2">
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose *</label>
                        <textarea name="purpose" id="purpose" rows="3" 
                                  class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                                  placeholder="Describe the purpose of this itinerary...">{{ old('purpose', $itinerary->purpose) }}</textarea>
                        @error('purpose')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ $backUrl ?? (auth()->user() && auth()->user()->employee ? (auth()->user()->employee->is_vp ? route('vp.travel-orders.index') : (auth()->user()->employee->is_head ? route('unithead.travel-orders.index') : route('dashboard'))) : route('dashboard')) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Back to Dashboard
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Update Itinerary
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>