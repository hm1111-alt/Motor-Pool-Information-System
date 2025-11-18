<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <svg class="inline-block h-6 w-6 text-[#1e6031] mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                {{ __('Add New Class') }}
            </h2>
            <a href="{{ route('admin.classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Classes
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form id="classForm" action="{{ route('admin.classes.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="class_name" class="block text-sm font-medium text-gray-700 mb-1">Class Name *</label>
                                <input type="text" name="class_name" id="class_name" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.classes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-300">
                                Cancel
                            </a>
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300">
                                Save Class
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>