<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Leadership Role') }}
            </h2>
            <a href="{{ route('admin.leaders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Leadership
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.leaders.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        
                        <!-- Organization information for specific roles -->
                        @if(in_array($type, ['vp', 'division_head', 'unit_head']))
                            <input type="hidden" name="organization_id" value="{{ $organization->id ?? '' }}">
                        @endif
                        
                        <div class="mb-8">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">
                                            @if($type == 'president')
                                                University President Assignment
                                            @elseif($type == 'vp')
                                                Vice President Assignment
                                            @elseif($type == 'division_head')
                                                Division Head Assignment
                                            @elseif($type == 'unit_head')
                                                Unit Head Assignment
                                            @endif
                                        </h3>
                                        <div class="text-sm text-gray-600">
                                            @if($type == 'president')
                                                <p>Select the University President who will oversee all operations and strategic direction.</p>
                                                <p class="mt-1 text-xs text-gray-500">Note: Only one President can be assigned at a time.</p>
                                            @elseif($type == 'vp')
                                                <p>Assign a Vice President to lead {{ $organization->office_name ?? 'this office' }}.</p>
                                                <p class="mt-1 text-xs text-gray-500">VPs report directly to the University President.</p>
                                            @elseif($type == 'division_head')
                                                <p>Assign a Division Head to manage {{ $organization->division_name ?? 'this division' }}.</p>
                                                <p class="mt-1 text-xs text-gray-500">Division Heads report to their respective Vice Presidents.</p>
                                            @elseif($type == 'unit_head')
                                                <p>Assign a Unit Head to lead {{ $organization->unit_name ?? 'this unit' }}.</p>
                                                <p class="mt-1 text-xs text-gray-500">Unit Heads report to their Division Heads.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Current Assignment Information -->
                            @if(isset($employee))
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-yellow-800">Current Assignment</h4>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>This role is currently assigned to <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong> ({{ $employee->position_name }}).</p>
                                            <p class="mt-1">Selecting a new employee will reassign this leadership role.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="space-y-6">
                                <div>
                                    <label for="employee_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Select Employee
                                        </div>
                                    </label>
                                    <div class="relative">
                                        <select name="employee_id" id="employee_id" 
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 py-3 pl-4 pr-10 text-base">
                                            <option value="">None - Remove current assignment</option>
                                            @foreach($employees as $emp)
                                                {{-- Exclude President from VP assignments --}}
                                                @if($type != 'vp' || !$emp->is_president)
                                                <option value="{{ $emp->id }}" {{ (isset($employee) && $employee->id == $emp->id) ? 'selected' : '' }}>
                                                    {{ $emp->first_name }} {{ $emp->last_name }} - {{ $emp->position_name }}
                                                    @if($emp->emp_status == 0)
                                                        (Inactive)
                                                    @endif
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500">
                                        <p>Select an employee to assign this leadership role. Choose "None" to remove the current assignment.</p>
                                        @if($type == 'vp')
                                            <p class="mt-1 text-xs text-gray-400">Note: The University President cannot be assigned as a Vice President.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.leaders.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e6031] transition duration-150">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-[#1e6031] hover:bg-[#164f2a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1e6031] transition duration-150">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Leadership Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>