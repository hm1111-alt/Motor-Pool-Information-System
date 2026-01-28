@extends('layouts.motorpool-admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Driver Details') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Success Message -->

                    
                    <div class="space-y-6">
                        <!-- Driver Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Driver Information</h3>
                            <div class="mt-2 border-t border-gray-200 pt-2">
                                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $driver->full_name }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Formal Name</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $driver->full_name2 }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Sex</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $driver->sex }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Prefix</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $driver->prefix ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">Availability Status</dt>
                                        <dd class="mt-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($driver->availability_status === 'Available') bg-green-100 text-green-800
                                                @elseif($driver->availability_status === 'Not Available') bg-red-100 text-red-800
                                                @elseif($driver->availability_status === 'On Duty') bg-blue-100 text-blue-800
                                                @elseif($driver->availability_status === 'Off Duty') bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ $driver->availability_status }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end">
                        <a href="{{ route('drivers.index') }}" class="mr-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Back to List
                        </a>
                        <a href="{{ route('drivers.edit', $driver) }}" class="mr-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Driver
                        </a>
                        <form action="{{ route('drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this driver? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Driver
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection