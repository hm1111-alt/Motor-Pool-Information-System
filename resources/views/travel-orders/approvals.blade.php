@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Travel Order Approvals
    </h2>
@endsection

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-6 text-gray-900">
                    <!-- Page Title -->
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Travel Orders Requiring Your Approval</h3>
                        <p class="text-gray-600 mt-1">Review and manage travel orders from your team members</p>
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="flex flex-col sm:flex-row gap-2">
                            <form method="GET" action="{{ route('travel-orders.index') }}" class="flex flex-col sm:flex-row gap-2 flex-grow">
                                <input type="hidden" name="status" value="{{ request()->get('status', 'pending') }}">
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search ?? '' }}"
                                       placeholder="Search by destination, purpose, or employee name..." 
                                       class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring focus:ring-[#1e6031] focus:ring-opacity-50 py-2 px-4 text-base transition duration-300 min-h-[42px]">
                                <button type="submit" 
                                        class="bg-[#1e6031] hover:bg-[#164f2a] text-white px-4 py-2 rounded-lg transition duration-300 flex items-center text-base font-medium shadow-sm hover:shadow-md min-h-[42px] justify-center">
                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Search
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-6 overflow-x-auto">
                        <nav class="-mb-px flex space-x-6 md:space-x-8 min-w-max">
                            <a href="{{ route('travel-orders.index', ['status' => 'pending']) }}" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ request()->get('status', 'pending') === 'pending' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Pending
                            </a>
                            <a href="{{ route('travel-orders.index', ['status' => 'approved']) }}" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ request()->get('status') === 'approved' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Approved
                            </a>
                            <a href="{{ route('travel-orders.index', ['status' => 'cancelled']) }}" 
                               class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium text-sm {{ request()->get('status') === 'cancelled' ? 'border-[#1e6031] text-[#1e6031]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Cancelled
                            </a>
                        </nav>
                    </div>
                    
                    <!-- Travel Orders Table -->
                    <div class="rounded-lg shadow overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="travelOrdersTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Range</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($travelOrders as $index => $order)
                                        <tr class="hover:bg-gray-50 travel-order-row">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->employee->first_name }} {{ $order->employee->last_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $order->employee->position_name }}</div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate">
                                                {{ $order->destination }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                <div>
                                                    {{ \Carbon\Carbon::parse($order->date_from)->format('M d, Y') }}<br>
                                                    <span class="text-gray-500 text-xs">to</span><br>
                                                    {{ \Carbon\Carbon::parse($order->date_to)->format('M d, Y') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                @if(request()->get('status', 'pending') === 'pending')
                                                    @if($order->head_approved == 1 && $order->divisionhead_approved == 1 && is_null($order->vp_approved))
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            For VP Approval
                                                        </span>
                                                    @elseif($order->head_approved == 1 && is_null($order->divisionhead_approved))
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            For Division Head Approval
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Not yet Approved
                                                        </span>
                                                    @endif
                                                @elseif(request()->get('status') === 'approved')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Approved
                                                    </span>
                                                @elseif(request()->get('status') === 'cancelled')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Cancelled
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
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
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-3 text-center text-sm text-gray-500">
                                                @if(request()->get('status', 'pending') === 'pending')
                                                    No travel orders found requiring your approval.
                                                @elseif(request()->get('status') === 'approved')
                                                    No travel orders found approved by you.
                                                @elseif(request()->get('status') === 'cancelled')
                                                    No travel orders found cancelled by you.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $travelOrders->appends(['status' => request()->get('status', 'pending'), 'search' => $search ?? ''])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection