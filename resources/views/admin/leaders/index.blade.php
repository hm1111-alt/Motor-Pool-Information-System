<x-admin-layout>

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
        border:2px solid #a3d9b1;
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
    </style>


<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* Action buttons styling to match offices page */
.action-buttons .btn {
    font-size: 10px;
    padding: 2px 6px;
    line-height: 1;
    height: 25px;
    min-width: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    border-radius: 4px;
}

.action-buttons .btn i,
.action-buttons .btn svg {
    font-size: 10px;
    margin-right: 2px;
    width: 12px;
    height: 12px;
}

/* Colors for action buttons */
.action-buttons .edit-btn {
    color: #ffc107 !important;
    border: 1px solid #ffc107 !important;
    background-color: transparent !important;
}

.action-buttons .edit-btn:hover {
    background-color: #ffc107 !important;
    color: #000 !important;
    border-color: #ffc107 !important;
}

/* Remove underlines from leadership cards */
a[href*="leaders"] {
    text-decoration: none !important;
}

/* Pagination styling - Simplified version */
.pagination {
    display: flex;
    justify-content: flex-end;
    list-style: none;
}

.pagination .page-link {
    color: #1e6031 !important;
    padding: 0.15rem 0.4rem;
    font-size: 0.8125rem;
    display: block;
    text-decoration: none;
    background-color: #fff !important;
    border: 1px solid #1e6031;
    border-radius: 0.25rem;
}

.page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
    cursor: not-allowed;
}

.page-item:not(.disabled) .page-link:hover {
    background-color: #1e6031 !important;
    color: white !important;
}

.page-link.disabled-link {
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
    pointer-events: none;
}

.pagination .active .page-link {
    background-color: #1e6031 !important;
    color: white !important;
    font-weight: bold;
}

.page-item {
    margin: 0 2px;
}
</style>
    <div class="py-4">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8 text-center">
                <h3 class="text-3xl font-bold text-[#1e6031]">
                    Organizational Leadership Hierarchy
                </h3>

                <p class="text-md text-[#1e6031]">
                    Manage leadership roles across the organization structure
                </p>
            </div>


            <!-- Leadership Status Summary -->
            <div class="mb-8">

                <div class="flex items-center mb-4">

                    <svg class="h-6 w-6 text-green-600 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 11c1.657 0 3-1.343 3-3S17.657 5 16 5s-3
                            1.343-3 3 1.343 3 3 3zM8 11c1.657 0 3-1.343 3-3S9.657
                            5 8 5 5 6.343 5 8s1.343 3 3 3zM8
                            13c-2.667 0-8 1.333-8 4v3h16v-3c0-2.667-5.333-4-8-4zM16
                            13c-.29 0-.62.02-.97.05C17.16
                            14.16 18 15.55 18 17v3h6v-3c0-2.667-5.333-4-8-4z"/>
                    </svg>

                    <h4 class="text-base font-bold text-gray-800">
                        Leadership Status Summary
                    </h4>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <!-- Presidents -->
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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


                    <!-- Vice Presidents -->
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
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


                    <!-- Division Heads -->
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
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


                    <!-- Unit Heads -->
                    <div class="stats-card">

                        <div class="stats-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a5 5 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
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
<!-- University President Card (Light Blue) -->
<div class="flex flex-col items-center justify-center bg-blue-50 rounded-xl border-2 border-blue-200 p-6 transition-all duration-300 text-center hover:shadow-md">

    <!-- Icon -->
    <div class="rounded-lg bg-blue-100 p-3 flex-shrink-0 shadow-sm mb-3 border border-blue-100">
        <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </div>

    <!-- Header -->
    <h3 class="text-lg font-bold text-blue-600 leading-tight m-0">
        University President
    </h3>
    <p class="text-blue-500 text-xs leading-tight m-0">
        Top-level executive leadership
    </p>

    <?php
        $president = \App\Models\Officer::with('employee')->where('president', true)->first();
    ?>

    @if($president && $president->employee)
        <!-- Name + Title + Button -->
        <div class="mt-3 flex flex-col items-center">
            <p class="font-semibold text-blue-800 text-base leading-tight m-0">
                {{ $president->employee->first_name }} {{ $president->employee->last_name }}
            </p>
            <p class="text-blue-600 text-sm leading-tight m-0">Current President</p>

            <!-- Small spacing above button -->
            <a href="{{ route('admin.leaders.edit',['type'=>'president']) }}"
               class="mt-3 inline-block px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm border-2 border-blue-700 hover:bg-blue-700 hover:border-blue-800 transition-all">
                CHANGE PRESIDENT
            </a>
        </div>
    @else
        <a href="{{ route('admin.leaders.edit',['type'=>'president']) }}"
           class="mt-3 inline-block px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-sm border-2 border-blue-700 hover:bg-blue-700 hover:border-blue-800 transition-all">
            ASSIGN PRESIDENT
        </a>
    @endif

</div>

<!-- Offices Card (Soft Violet/Purple) -->
<div class="flex flex-col items-center justify-center bg-purple-50 rounded-xl border-2 border-purple-200 p-6 transition-all duration-300 text-center hover:shadow-md">

    <div class="rounded-lg bg-purple-100 p-3 flex-shrink-0 shadow-sm mb-3 border border-purple-100">
        <svg class="h-6 w-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
    </div>

    <h3 class="text-lg font-bold text-purple-600 mb-1">
        Offices
    </h3>

    <p class="text-purple-500 text-xs mb-3">
        Manage office leadership
    </p>

    <a href="{{ route('admin.leaders.offices') }}"
       class="inline-block px-3 py-1.5 bg-purple-600 text-white text-xs font-bold rounded-lg shadow-sm border-2 border-purple-700 hover:bg-purple-700 hover:border-purple-800 transition-all">
        MANAGE OFFICES
    </a>

</div>

            </div>

        </div>

    </div>

</x-admin-layout>