<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Add Vehicle Button</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Vehicle Management Test</h1>
            
            <div class="text-center mb-6">
                <p class="text-gray-600 mb-4">Click the button below to add a new vehicle:</p>
                
                <a href="{{ route('vehicles.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-lg text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    ADD NEW VEHICLE
                </a>
            </div>
            
            <div class="text-center">
                <a href="{{ route('vehicles.index') }}" 
                   class="text-indigo-600 hover:text-indigo-900 underline">
                    ← Back to Vehicle List
                </a>
            </div>
        </div>
    </div>
</body>
</html>