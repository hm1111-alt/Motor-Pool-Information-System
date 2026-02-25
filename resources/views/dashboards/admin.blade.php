<x-admin-layout>
    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-3 lg:px-5">
    <!-- Welcome Section -->
<div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200 shadow-sm mb-6">
    <div class="px-4 py-3">
        <div class="flex items-center">
            <!-- Icon -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg p-2 mr-3 flex-shrink-0">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            
            <!-- Text Content -->
            <div>
                <h1 class="text-lg font-semibold text-gray-800">
                    Welcome, {{ Auth::user()->name }}!
                </h1>
                <p class="text-gray-600 text-sm mt-1">
                    Manage CLSU organizational structure and employee information
                </p>
            </div>
        </div>
    </div>
</div><h2 class="text-lg font-semibold text-gray-800 mb-4">Organization Overview</h2>

<!-- Quick Overview - Matching Leadership Status Summary Style -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

    <!-- Total Offices -->
    <div class="stats-card">
        <div class="stats-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16h14zM9 7h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <div>
            <div class="stats-number">
                {{ \App\Models\Office::count() }}
            </div>
            <div class="stats-label">
                Total Offices
            </div>
        </div>
    </div>

    <!-- Total Divisions -->
    <div class="stats-card">
        <div class="stats-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16h14zM9 7h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <div>
            <div class="stats-number">
                {{ \App\Models\Division::count() }}
            </div>
            <div class="stats-label">
                Total Divisions
            </div>
        </div>
    </div>

    <!-- Total Units -->
    <div class="stats-card">
        <div class="stats-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16h14zM9 7h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <div>
            <div class="stats-number">
                {{ \App\Models\Unit::count() }}
            </div>
            <div class="stats-label">
                Total Units
            </div>
        </div>
    </div>

    <!-- Total Employees -->
    <div class="stats-card">
        <div class="stats-icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div>
            <div class="stats-number">
                {{ \App\Models\Employee::count() }}
            </div>
            <div class="stats-label">
                Total Employees
            </div>
        </div>
    </div>

</div>
         <!-- Quick Actions -->
<h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <!-- Offices Management -->
    <a href="{{ route('admin.offices.index') }}" class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 p-4 transition-all duration-300 hover:shadow-lg hover:scale-105 block">
        <div class="flex items-start">
            <div class="rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 p-3 flex-shrink-0 shadow-md">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-base font-bold text-gray-800">Offices</h3>
                <p class="text-gray-600 text-sm mt-1 mb-2">Manage organizational offices</p>
                <div class="flex items-center text-emerald-700 font-semibold text-sm">
                    <span>Manage</span>
                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </a>

    <!-- Divisions Management -->
    <a href="{{ route('admin.divisions.index') }}" class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-xl border border-teal-200 p-4 transition-all duration-300 hover:shadow-lg hover:scale-105 block">
        <div class="flex items-start">
            <div class="rounded-xl bg-gradient-to-br from-teal-500 to-cyan-600 p-3 flex-shrink-0 shadow-md">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-base font-bold text-gray-800">Divisions</h3>
                <p class="text-gray-600 text-sm mt-1 mb-2">Manage organizational divisions</p>
                <div class="flex items-center text-teal-700 font-semibold text-sm">
                    <span>Manage</span>
                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </a>

    <!-- Units Management -->
    <a href="{{ route('admin.units.index') }}" class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200 p-4 transition-all duration-300 hover:shadow-lg hover:scale-105 block">
        <div class="flex items-start">
            <div class="rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 p-3 flex-shrink-0 shadow-md">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-base font-bold text-gray-800">Units</h3>
                <p class="text-gray-600 text-sm mt-1 mb-2">Manage organizational units</p>
                <div class="flex items-center text-green-700 font-semibold text-sm">
                    <span>Manage</span>
                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </a>

    <!-- Subunits Management -->
    <a href="{{ route('admin.subunits.index') }}" class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 p-4 transition-all duration-300 hover:shadow-lg hover:scale-105 block">
        <div class="flex items-start">
            <div class="rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 p-3 flex-shrink-0 shadow-md">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-base font-bold text-gray-800">Subunits</h3>
                <p class="text-gray-600 text-sm mt-1 mb-2">Manage organizational subunits</p>
                <div class="flex items-center text-emerald-700 font-semibold text-sm">
                    <span>Manage</span>
                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </a>

    <!-- Classes Management -->
    <a href="{{ route('admin.classes.index') }}" class="bg-gradient-to-br from-cyan-50 to-blue-50 rounded-xl border border-cyan-200 p-4 transition-all duration-300 hover:shadow-lg hover:scale-105 block">
        <div class="flex items-start">
            <div class="rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 p-3 flex-shrink-0 shadow-md">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-base font-bold text-gray-800">Classes</h3>
                <p class="text-gray-600 text-sm mt-1 mb-2">Manage employee classifications</p>
                <div class="flex items-center text-cyan-700 font-semibold text-sm">
                    <span>Manage</span>
                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </a>

    <!-- Employees Management -->
    <a href="{{ route('admin.employees.index') }}" class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-4 transition-all duration-300 hover:shadow-lg hover:scale-105 block">
        <div class="flex items-start">
            <div class="rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 p-3 flex-shrink-0 shadow-md">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-base font-bold text-gray-800">Employees</h3>
                <p class="text-gray-600 text-sm mt-1 mb-2">Manage employee records</p>
                <div class="flex items-center text-blue-700 font-semibold text-sm">
                    <span>Manage</span>
                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </a>
</div>

<!-- Personnel Management -->
<h2 class="text-lg font-semibold text-gray-800 mb-4">Personnel Management</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <!-- Leadership Roles -->
    <a href="{{ route('admin.leaders.index') }}" class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl border border-indigo-200 p-4 transition-all duration-300 hover:shadow-lg hover:scale-105 block">
        <div class="flex items-start">
            <div class="rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 p-3 flex-shrink-0 shadow-md">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-base font-bold text-gray-800">Leadership Roles</h3>
                <p class="text-gray-600 text-sm mt-1 mb-2">Assign organizational leadership</p>
                <div class="flex items-center text-indigo-700 font-semibold text-sm">
                    <span>Manage</span>
                    <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>
    </a>
</div>  </div>
            </div>
            

            </div>
        </div>
    </div><!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* AGGRESSIVE RESET - Remove ALL lines/underlines from dashboard */
* {
    text-decoration: none !important;
}

a, a:hover, a:visited, a:active, a:focus {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
    box-shadow: none !important;
}

.bg-gradient-to-br a, 
.bg-gradient-to-br a:hover,
.bg-gradient-to-br a:visited,
.bg-gradient-to-br a:active,
.bg-gradient-to-br a:focus {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
    box-shadow: none !important;
}

.flex.items-center span,
.flex.items-center span:hover {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
}

:root{
    --clsu-primary:#00703C;
    --clsu-light:#DFF0D8;
    --clsu-mid:#A8DFAA;
    --clsu-border:#a3d9b1; /* Lighter green border */
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

/* Remove ALL possible lines/underlines/borders from dashboard cards */
.stats-card a,
.quick-action-card a,
.bg-gradient-to-br a {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
    box-shadow: none !important;
}

.stats-card a:hover,
.quick-action-card a:hover,
.bg-gradient-to-br a:hover {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
    box-shadow: none !important;
}

/* Specifically target the "Manage" text and surrounding elements */
.stats-card .flex.items-center,
.quick-action-card .flex.items-center,
.bg-gradient-to-br .flex.items-center {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
}

.stats-card .flex.items-center span,
.quick-action-card .flex.items-center span,
.bg-gradient-to-br .flex.items-center span {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
}

/* Remove any potential borders from the card containers */
.bg-gradient-to-br.from-emerald-50.to-teal-50,
.bg-gradient-to-br.from-teal-50.to-cyan-50,
.bg-gradient-to-br.from-green-50.to-emerald-50,
.bg-gradient-to-br.from-emerald-50.to-teal-50,
.bg-gradient-to-br.from-cyan-50.to-blue-50,
.bg-gradient-to-br.from-blue-50.to-indigo-50,
.bg-gradient-to-br.from-indigo-50.to-purple-50 {
    border: none !important;
    border-bottom: none !important;
    box-shadow: none !important;
}

/* Specifically target the Offices card */
a[href="{{ route('admin.offices.index') }}"] {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
}

a[href="{{ route('admin.offices.index') }}, 
a[href="{{ route('admin.offices.index') }}"]:hover {
    text-decoration: none !important;
    border: none !important;
    border-bottom: none !important;
}
</style>
</x-admin-layout>