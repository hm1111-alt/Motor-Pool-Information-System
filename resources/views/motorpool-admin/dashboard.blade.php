@extends('layouts.motorpool-admin')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <!-- Hero section with status dashboard overview -->
            <div class="border-gray-300  align relative">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-8 sm:px-12">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="text-center md:text-left mb-6 md:mb-0">
                            <h1 class="text-3xl font-bold text-white mb-2">Motorpool Admin Dashboard</h1>
                            <p class="text-green-100 text-lg">Manage travel orders and vehicle assignments</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-white">{{ $totalApprovedTravelOrders }}</div>
                                <div class="text-green-100 text-sm">Approved Travel Orders</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Travel Orders Section -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Recent Approved Travel Orders</h2>
            </div>
            
            @if($recentTravelOrders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Employee
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Destination
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Purpose
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Range
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentTravelOrders as $travelOrder)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $travelOrder->employee?->first_name }} {{ $travelOrder->employee?->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $travelOrder->employee?->position_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $travelOrder->destination }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ Str::limit($travelOrder->purpose, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($travelOrder->date_from instanceof \DateTimeInterface)
                                            {{ $travelOrder->date_from->format('M d, Y') }}
                                        @else
                                            {{ $travelOrder->date_from }}
                                        @endif
                                        @if($travelOrder->date_to instanceof \DateTimeInterface)
                                            - {{ $travelOrder->date_to->format('M d, Y') }}
                                        @elseif($travelOrder->date_to)
                                            - {{ $travelOrder->date_to }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($travelOrder->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('motorpool.travel-orders.show', $travelOrder) }}" 
                                           class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('motorpool.travel-orders.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        View All Travel Orders
                    </a>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No approved travel orders</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new travel order.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection