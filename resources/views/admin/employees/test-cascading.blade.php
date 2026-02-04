<!DOCTYPE html>
<html>
<head>
    <title>Dropdown Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Cascading Dropdown Test</h1>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Office</label>
                <select id="office_id" class="w-full rounded-lg border-gray-300">
                    <option value="">Select Office</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}">{{ $office->office_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                <select id="division_id" class="w-full rounded-lg border-gray-300">
                    <option value="">Select Division</option>
                </select>
                <div id="division-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                <select id="unit_id" class="w-full rounded-lg border-gray-300">
                    <option value="">Select Unit</option>
                </select>
                <div id="unit-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subunit</label>
                <select id="subunit_id" class="w-full rounded-lg border-gray-300">
                    <option value="">Select Subunit</option>
                </select>
                <div id="subunit-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>
        </div>
        
        <div class="mt-8 p-4 bg-gray-100 rounded">
            <h2 class="font-bold mb-2">Debug Info:</h2>
            <div id="debug-info" class="text-sm font-mono"></div>
        </div>
    </div>

    <script>
        function updateDebugInfo(message) {
            const debugInfo = document.getElementById('debug-info');
            const timestamp = new Date().toLocaleTimeString();
            debugInfo.innerHTML += `[${timestamp}] ${message}<br>`;
            debugInfo.scrollTop = debugInfo.scrollHeight;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const officeSelect = document.getElementById('office_id');
            const divisionSelect = document.getElementById('division_id');
            const unitSelect = document.getElementById('unit_id');
            const subunitSelect = document.getElementById('subunit_id');
            const divisionError = document.getElementById('division-error');
            const unitError = document.getElementById('unit-error');
            const subunitError = document.getElementById('subunit-error');
            
            updateDebugInfo('Page loaded successfully');
            
            // Load divisions when office is selected
            officeSelect.addEventListener('change', function() {
                const officeId = this.value;
                updateDebugInfo(`Office selected: ${officeId}`);
                
                divisionSelect.innerHTML = '<option value="">Loading divisions...</option>';
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                divisionError.classList.add('hidden');
                
                if (officeId) {
                    updateDebugInfo(`Fetching divisions for office ${officeId}`);
                    
                    fetch('{{ route('test.ajax.divisions', ['office_id' => '']) }}' + officeId, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        updateDebugInfo(`Response status: ${response.status}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(divisions => {
                        updateDebugInfo(`Received ${divisions.length} divisions`);
                        console.log('Divisions data:', divisions);
                        
                        divisionSelect.innerHTML = '<option value="">Select Division</option>';
                        divisions.forEach(division => {
                            divisionSelect.innerHTML += `<option value="${division.id_division}">${division.division_name}</option>`;
                        });
                    })
                    .catch(error => {
                        updateDebugInfo(`Error fetching divisions: ${error.message}`);
                        console.error('Error:', error);
                        divisionSelect.innerHTML = '<option value="">Error loading divisions</option>';
                        divisionError.textContent = `Error: ${error.message}`;
                        divisionError.classList.remove('hidden');
                    });
                } else {
                    divisionSelect.innerHTML = '<option value="">Select Division</option>';
                }
            });
            
            // Load units when division is selected
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                updateDebugInfo(`Division selected: ${divisionId}`);
                
                unitSelect.innerHTML = '<option value="">Loading units...</option>';
                subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                unitError.classList.add('hidden');
                
                if (divisionId) {
                    updateDebugInfo(`Fetching units for division ${divisionId}`);
                    
                    fetch('{{ route('test.ajax.units', ['division_id' => '']) }}' + divisionId, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        updateDebugInfo(`Response status: ${response.status}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(units => {
                        updateDebugInfo(`Received ${units.length} units`);
                        console.log('Units data:', units);
                        
                        unitSelect.innerHTML = '<option value="">Select Unit</option>';
                        units.forEach(unit => {
                            unitSelect.innerHTML += `<option value="${unit.id_unit}">${unit.unit_name}</option>`;
                        });
                    })
                    .catch(error => {
                        updateDebugInfo(`Error fetching units: ${error.message}`);
                        console.error('Error:', error);
                        unitSelect.innerHTML = '<option value="">Error loading units</option>';
                        unitError.textContent = `Error: ${error.message}`;
                        unitError.classList.remove('hidden');
                    });
                } else {
                    unitSelect.innerHTML = '<option value="">Select Unit</option>';
                }
            });
            
            // Load subunits when unit is selected
            unitSelect.addEventListener('change', function() {
                const unitId = this.value;
                updateDebugInfo(`Unit selected: ${unitId}`);
                
                subunitSelect.innerHTML = '<option value="">Loading subunits...</option>';
                subunitError.classList.add('hidden');
                
                if (unitId) {
                    updateDebugInfo(`Fetching subunits for unit ${unitId}`);
                    
                    fetch('{{ route('test.ajax.subunits', ['unit_id' => '']) }}' + unitId, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        updateDebugInfo(`Response status: ${response.status}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(subunits => {
                        updateDebugInfo(`Received ${subunits.length} subunits`);
                        console.log('Subunits data:', subunits);
                        
                        subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                        subunits.forEach(subunit => {
                            subunitSelect.innerHTML += `<option value="${subunit.id_subunit}">${subunit.subunit_name}</option>`;
                        });
                    })
                    .catch(error => {
                        updateDebugInfo(`Error fetching subunits: ${error.message}`);
                        console.error('Error:', error);
                        subunitSelect.innerHTML = '<option value="">Error loading subunits</option>';
                        subunitError.textContent = `Error: ${error.message}`;
                        subunitError.classList.remove('hidden');
                    });
                } else {
                    subunitSelect.innerHTML = '<option value="">Select Subunit</option>';
                }
            });
        });
    </script>
</body>
</html>