<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Test Cascading Dropdowns
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Office</label>
                            <select id="office_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                            <select id="division_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Division</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select id="unit_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Unit</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subunit</label>
                            <select id="subunit_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                                <option value="">Select Subunit</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Debug Info:</h3>
                        <div id="debug-info" class="bg-gray-100 p-4 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const officeSelect = document.getElementById('office_id');
            const divisionSelect = document.getElementById('division_id');
            const unitSelect = document.getElementById('unit_id');
            const subunitSelect = document.getElementById('subunit_id');
            const debugInfo = document.getElementById('debug-info');
            
            function updateDebugInfo(message) {
                debugInfo.innerHTML += '<p class="text-sm">' + new Date().toLocaleTimeString() + ': ' + message + '</p>';
                debugInfo.scrollTop = debugInfo.scrollHeight;
            }
            
            // Load divisions when office is selected
            officeSelect.addEventListener('change', function() {
                const officeId = this.value;
                updateDebugInfo('Office selected: ' + officeId);
                
                divisionSelect.innerHTML = '<option value="">Select Division</option>';
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (officeId) {
                    updateDebugInfo('Fetching divisions for office ' + officeId);
                    fetch('{{ route('admin.employees.get-divisions-by-office') }}?office_id=' + officeId)
                        .then(response => {
                            updateDebugInfo('Division fetch response status: ' + response.status);
                            return response.json();
                        })
                        .then(divisions => {
                            updateDebugInfo('Received ' + divisions.length + ' divisions');
                            divisions.forEach(division => {
                                divisionSelect.innerHTML += '<option value="' + division.id + '">' + division.division_name + '</option>';
                            });
                        })
                        .catch(error => {
                            updateDebugInfo('Error fetching divisions: ' + error.message);
                            console.error('Error:', error);
                        });
                }
            });
            
            // Load units when division is selected
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                updateDebugInfo('Division selected: ' + divisionId);
                
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (divisionId) {
                    updateDebugInfo('Fetching units for division ' + divisionId);
                    fetch('{{ route('admin.employees.get-units-by-division') }}?division_id=' + divisionId)
                        .then(response => {
                            updateDebugInfo('Unit fetch response status: ' + response.status);
                            return response.json();
                        })
                        .then(units => {
                            updateDebugInfo('Received ' + units.length + ' units');
                            units.forEach(unit => {
                                unitSelect.innerHTML += '<option value="' + unit.id + '">' + unit.unit_name + '</option>';
                            });
                        })
                        .catch(error => {
                            updateDebugInfo('Error fetching units: ' + error.message);
                            console.error('Error:', error);
                        });
                }
            });
            
            // Load subunits when unit is selected
            unitSelect.addEventListener('change', function() {
                const unitId = this.value;
                updateDebugInfo('Unit selected: ' + unitId);
                
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                
                if (unitId) {
                    updateDebugInfo('Fetching subunits for unit ' + unitId);
                    fetch('{{ route('admin.employees.get-subunits-by-unit') }}?unit_id=' + unitId)
                        .then(response => {
                            updateDebugInfo('Subunit fetch response status: ' + response.status);
                            return response.json();
                        })
                        .then(subunits => {
                            updateDebugInfo('Received ' + subunits.length + ' subunits');
                            subunits.forEach(subunit => {
                                subunitSelect.innerHTML += '<option value="' + subunit.id + '">' + subunit.subunit_name + '</option>';
                            });
                        })
                        .catch(error => {
                            updateDebugInfo('Error fetching subunits: ' + error.message);
                            console.error('Error:', error);
                        });
                }
            });
            
            updateDebugInfo('Page loaded - cascading dropdown test initialized');
        });
    </script>
</x-app-layout>