@extends('layouts.employee')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Travel Order Approvals
        </h2>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-300 flex items-center shadow-md hover:shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Page Header -->
                    <div class="mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-2">
                            Travel Orders Requiring Your Approval
                        </h3>
                        <p class="text-gray-600">Review and manage travel orders from your team members</p>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="flex space-x-8">
                            <a href="{{ route('travel-orders.index', ['status' => 'pending']) }}" 
                               class="py-4 px-1 text-sm font-medium {{ request()->get('status', 'pending') === 'pending' ? 'border-b-2 border-[#1e6031] text-[#1e6031]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Pending
                            </a>
                            <a href="{{ route('travel-orders.index', ['status' => 'approved']) }}" 
                               class="py-4 px-1 text-sm font-medium {{ request()->get('status') === 'approved' ? 'border-b-2 border-[#1e6031] text-[#1e6031]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Approved
                            </a>
                            <a href="{{ route('travel-orders.index', ['status' => 'cancelled']) }}" 
                               class="py-4 px-1 text-sm font-medium {{ request()->get('status') === 'cancelled' ? 'border-b-2 border-[#1e6031] text-[#1e6031]' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Cancelled
                            </a>
                        </nav>
                    </div>
                    
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('travel-orders.index') }}" class="mb-6">
                        <input type="hidden" name="status" value="{{ request()->get('status', 'pending') }}">
                        <div class="flex">
                            <div class="flex-grow">
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search by destination, purpose, or employee name..." class="block w-full rounded-l-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50">
                            </div>
                            <button type="submit" class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 rounded-r-lg transition duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Travel Orders Table -->
                    @if($travelOrders->count() > 0)
                        <div class="overflow-x-auto rounded-lg shadow">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($travelOrders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->employee->first_name }} {{ $order->employee->last_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->employee->position_name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->destination }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($order->date_from)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($order->date_to)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if(request()->get('status', 'pending') === 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Not yet Approved
                                                    </span>
                                                @elseif(request()->get('status') === 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Approved by Head
                                                    </span>
                                                @elseif(request()->get('status') === 'cancelled')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Cancelled by Head
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if(request()->get('status', 'pending') === 'pending')
                                                    <!-- Pending tab actions -->
                                                    <form action="{{ route('travel-orders.approve', $order) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="approval_type" value="head">
                                                        <button type="submit" name="action" value="approve" 
                                                                class="text-green-600 hover:text-green-900 mr-3"
                                                                onclick="return confirm('Are you sure you want to approve this travel order?')">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('travel-orders.approve', $order) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="approval_type" value="head">
                                                        <button type="submit" name="action" value="decline" 
                                                                class="text-red-600 hover:text-red-900 mr-3"
                                                                onclick="return confirm('Are you sure you want to cancel this travel order?')">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('travel-orders.show', $order) }}" class="text-[#1e6031] hover:text-[#164f2a]">
                                                        View
                                                    </a>
                                                @else
                                                    <!-- Approved/Cancelled tab actions -->
                                                    <a href="{{ route('travel-orders.show', $order) }}" class="text-[#1e6031] hover:text-[#164f2a]">
                                                        View
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $travelOrders->appends(['status' => request()->get('status', 'pending'), 'search' => $search ?? ''])->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No travel orders found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                @if(request()->get('status', 'pending') === 'pending')
                                    There are currently no travel orders requiring your approval.
                                @elseif(request()->get('status') === 'approved')
                                    There are currently no travel orders approved by you.
                                @elseif(request()->get('status') === 'cancelled')
                                    There are currently no travel orders cancelled by you.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection