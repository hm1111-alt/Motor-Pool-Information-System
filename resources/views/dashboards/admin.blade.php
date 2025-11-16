<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- User Management Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#e0a70d] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">User Management</h3>
                                    <p class="text-gray-600">Manage system users</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Reports Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#ffd700]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#1e6031] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">System Reports</h3>
                                    <p class="text-gray-600">View system analytics</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>

                        <!-- Settings Card -->
                        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-[#1e6031]">
                            <div class="flex items-center">
                                <div class="rounded-full bg-[#ffd700] p-3 mr-4">
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">System Settings</h3>
                                    <p class="text-gray-600">Configure system parameters</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="text-[#1e6031] hover:text-[#1e6031] font-medium">View Details →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>