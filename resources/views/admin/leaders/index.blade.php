<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>

:root{
    --clsu-primary:#00703C;
    --clsu-light:#DFF0D8;
    --clsu-mid:#A8DFAA;
    --clsu-border:#00703C;
    --clsu-text:#004d00;
}

.stats-card{
    background:var(--clsu-light);
    border:2px solid var(--clsu-border);
    border-radius:12px;
    padding:16px;

    display:flex;
    align-items:center;
    gap:14px;

    box-shadow:0 6px 12px rgba(0,112,60,0.15);

    cursor:pointer;

    transition:all 0.25s ease;
}

.stats-card:hover{
    transform:translateY(-4px);
    box-shadow:0 12px 20px rgba(0,112,60,0.25);
}

.stats-icon{
    width:42px;
    height:42px;
    border-radius:50%;
    background:var(--clsu-mid);

    display:flex;
    align-items:center;
    justify-content:center;

    flex-shrink:0;
}

.stats-icon svg{
    width:22px;
    height:22px;
    color:var(--clsu-primary);
}

.stats-number{
    font-size:20px;
    font-weight:bold;
    color:var(--clsu-primary);
}

.stats-label{
    font-size:12px;
    font-weight:600;
    color:var(--clsu-text);
    text-transform:uppercase;
    letter-spacing:.5px;
}
</style><x-admin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                    <div class="mb-8">
                        <div class="text-center">
                            <h3 class="text-3xl font-bold text-[#1e6031]">Organizational Leadership Hierarchy</h3>
                            <p class="text-md text-[#1e6031]">Manage leadership roles across the organization structure</p>
                        </div>
                    </div>

              <!-- Quick Stats -->
<div class="mb-8">

    <div class="flex items-center mb-4">
        <svg class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 11c1.657 0 3-1.343 3-3S17.657 5 16 5s-3 1.343-3 3 1.343 3 3 3zM8 11c1.657 0 3-1.343 3-3S9.657 5 8 5 5 6.343 5 8s1.343 3 3 3zM8 13c-2.667 0-8 1.333-8 4v3h16v-3c0-2.667-5.333-4-8-4zM16 13c-.29 0-.62.02-.97.05C17.16 14.16 18 15.55 18 17v3h6v-3c0-2.667-5.333-4-8-4z"/>
        </svg>

        <h4 class="text-base font-bold text-gray-800">
            Leadership Status Summary
        </h4>
    </div>


    <!-- SINGLE ROW -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- PRESIDENT -->
        <div class="stats-card">

            <div class="stats-icon">
                <!-- PEOPLE ICON -->
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>

            <div>
                <div class="stats-number">
                    {{ \App\Models\Officer::where('president', true)->count() }}
                </div>

                <div class="stats-label">
                    Presidents
                </div>
            </div>

        </div>


        <!-- VP -->
        <div class="stats-card">

            <div class="stats-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2M7 20v-2M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>

            <div>
                <div class="stats-number">
                    {{ \App\Models\Officer::where('vp', true)->count() }}
                </div>

                <div class="stats-label">
                    Vice Presidents
                </div>
            </div>

        </div>


        <!-- DIVISION HEAD -->
        <div class="stats-card">

            <div class="stats-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2M7 20H2v-2M15 7a3 3 0 11-6 0"/>
                </svg>
            </div>

            <div>
                <div class="stats-number">
                    {{ \App\Models\Officer::where('division_head', true)->count() }}
                </div>

                <div class="stats-label">
                    Division Heads
                </div>
            </div>

        </div>


        <!-- UNIT HEAD -->
        <div class="stats-card">

            <div class="stats-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2M7 20H2v-2M12 7a3 3 0 110-6 3 3 0 010 6z"/>
                </svg>
            </div>

            <div>
                <div class="stats-number">
                    {{ \App\Models\Officer::where('unit_head', true)->count() }}
                </div>

                <div class="stats-label">
                    Unit Heads
                </div>
            </div>

        </div>

    </div>

</div>

                    <!-- Navigation Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- President Card -->
                        <div class="bg-white rounded-xl shadow-md p-5 hover:shadow-xl transition-all duration-300 border border-gray-200">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 mb-4 mx-auto">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-center text-gray-800 mb-2">University President</h4>
                            <p class="text-center text-gray-600 text-sm mb-4">Top-level executive leadership</p>
                            
                            @if(isset($president))
                                <div class="text-center mb-4">
                                    <div class="text-sm font-medium text-gray-800">{{ $president->first_name }} {{ $president->last_name }}</div>
                                    <div class="text-xs text-gray-600">{{ $president->position_name }}</div>
                                </div>
                                <div class="text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Assigned
                                    </span>
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="text-sm font-medium text-gray-500">No President Assigned</div>
                                    <div class="text-xs text-gray-500">University Leadership Role</div>
                                </div>
                                <div class="text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Unassigned
                                    </span>
                                </div>
                            @endif
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.leaders.edit', ['type' => 'president']) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white {{ isset($president) ? 'bg-purple-600 hover:bg-purple-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ isset($president) ? 'Change President' : 'Assign President' }}
                                </a>
                            </div>
                        </div>

                        <!-- Offices Card -->
                        <div class="bg-white rounded-xl shadow-md p-5 hover:shadow-xl transition-all duration-300 border border-gray-200">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 mb-4 mx-auto">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-center text-gray-800 mb-2">Offices</h4>
                            <p class="text-center text-gray-600 text-sm mb-4">Manage office-level leadership (Vice Presidents)</p>
                            
                            <div class="text-center mb-4">
                                <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Office::count() }}</div>
                                <div class="text-sm text-gray-600">Offices</div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('admin.leaders.offices') }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Manage Offices
                                </a>
                            </div>
                        </div>

                        
                    </div>

                    
                </div>
        </div>
    </div>
</x-admin-layout>

