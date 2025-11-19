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
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                @if($type == 'president')
                                    Assign University President
                                @elseif($type == 'vp')
                                    Assign Vice President for {{ $organization->office_name ?? 'Office' }}
                                @elseif($type == 'division_head')
                                    Assign Division Head for {{ $organization->division_name ?? 'Division' }}
                                @elseif($type == 'unit_head')
                                    Assign Unit Head for {{ $organization->unit_name ?? 'Unit' }}
                                @endif
                            </h3>
                            
                            <div class="mb-4">
                                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Employee
                                </label>
                                <select name="employee_id" id="employee_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                    <option value="">None (Remove current assignment)</option>
                                    @foreach($employees as $emp)
                                        {{-- Exclude President from VP assignments --}}
                                        @if($type != 'vp' || !$emp->is_president)
                                        <option value="{{ $emp->id }}" {{ (isset($employee) && $employee->id == $emp->id) ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }} - {{ $emp->position_name }}
                                        </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-6 py-2 rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                                Update Leadership Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>