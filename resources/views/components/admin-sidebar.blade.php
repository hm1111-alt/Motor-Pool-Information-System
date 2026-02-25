<div class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 shadow-lg z-50 transition-all duration-300" id="sidebar">
    <div class="flex flex-col h-full">
        <!-- Logo/Header Section -->
        <div class="flex items-center justify-between h-12 bg-[#1e6031] text-white px-4">
            <h1 class="text-lg font-bold" id="sidebar-title"><span>Admin</span></h1>
            <button id="toggleSidebar" class="text-sm focus:outline-none">
                <svg id="collapseIcon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- User Profile Section -->
        <div class="border-b border-gray-200 py-3 px-3">
            <div class="flex items-start">
                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-8 h-8 flex items-center justify-center">
                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="ml-2">
                    <p class="text-xs font-medium text-gray-700 mb-0.5"><span>Admin</span></p>
                    <a href="{{ route('profile.edit') }}" class="text-xs font-medium text-[#1e6031] hover:text-[#007d31] truncate block">
                        <span>Manage Profile</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <nav class="flex-1 px-2 py-2 overflow-y-auto">
                <!-- Dashboard section -->
                <div class="mb-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                        <svg class="h-5 w-5 text-[#004d26] mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-sm font-bold text-[#004d26]">Dashboard</span>
                    </a>
                </div>
                
                <!-- Organization section -->
                <div class="mb-1">
                    <button type="button" class="w-full flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1 organization-toggle">
                        <svg class="h-5 w-5 text-[#004d26] mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-sm font-bold text-[#004d26] flex-1 text-left">Organization</span>
                        <svg class="h-4 w-4 text-[#004d26] transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <!-- Sub-items for Organization -->
                    <div class="ml-4 mt-1 space-y-1 organization-submenu hidden">
                        <a href="{{ route('admin.offices.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <svg class="h-5 w-5 text-[#1e6031] mr-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="text-sm font-medium">Offices</span>
                        </a>
                        <a href="{{ route('admin.divisions.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <svg class="h-5 w-5 text-[#1e6031] mr-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="text-sm font-medium">Divisions</span>
                        </a>
                        <a href="{{ route('admin.units.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <svg class="h-5 w-5 text-[#1e6031] mr-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="text-sm font-medium">Units</span>
                        </a>
                        <a href="{{ route('admin.subunits.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <svg class="h-5 w-5 text-[#1e6031] mr-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="text-sm font-medium">Subunits</span>
                        </a>
                        <a href="{{ route('admin.classes.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <svg class="h-5 w-5 text-[#1e6031] mr-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="text-sm font-medium">Classes</span>
                        </a>
                        <a href="{{ route('admin.employees.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <svg class="h-5 w-5 text-[#1e6031] mr-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm font-medium">Employees</span>
                        </a>
                    </div>
                </div>

                <!-- Personnel section -->
                <div class="mb-1">
                    <button type="button" class="w-full flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1 personnel-toggle">
                        <svg class="h-5 w-5 text-[#004d26] mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-sm font-bold text-[#004d26] flex-1 text-left">Personnel</span>
                        <svg class="h-4 w-4 text-[#004d26] transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <!-- Sub-items for Personnel -->
                    <div class="ml-4 mt-1 space-y-1 personnel-submenu hidden">
                        <a href="{{ route('admin.leaders.index') }}" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200 mb-1">
                            <svg class="h-5 w-5 text-[#1e6031] mr-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm font-medium">Leadership Roles</span>
                        </a>
                    </div>
                </div>
            </nav>
            
            <!-- Logout Button - Fixed at the bottom -->
            <div class="px-3 py-2 border-t border-gray-200 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        <svg class="h-5 w-5 text-[#1e6031] mr-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-medium text-sm truncate">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const collapseIcon = document.getElementById('collapseIcon');
            const mainContent = document.getElementById('main-content');
            const sidebarTitle = document.getElementById('sidebar-title');
            
            // Initialize icon click events for collapsed state
            function initCollapsedIconEvents() {
                if (sidebar.classList.contains('w-20')) {
                    const navIcons = document.querySelectorAll('#sidebar a.flex svg, #sidebar .organization-toggle svg:first-child, #sidebar .personnel-toggle svg:first-child');
                    navIcons.forEach(icon => {
                        icon.addEventListener('click', function(e) {
                            // Only expand if sidebar is collapsed
                            if (sidebar.classList.contains('w-20')) {
                                e.preventDefault();
                                expandSidebar();
                            }
                        });
                    });
                }
            }
            
            // Call on initial load
            initCollapsedIconEvents();
            
            // Function to collapse the sidebar
            function collapseSidebar() {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                
                // Change icon to expand (right arrow)
                collapseIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
                
                // Hide all text spans including the sidebar title
                const textSpans = document.querySelectorAll('#sidebar span');
                textSpans.forEach(span => {
                    span.style.display = 'none';
                });
                
                // Center the icons
                const links = document.querySelectorAll('#sidebar a.flex, #sidebar button');
                links.forEach(link => {
                    link.classList.add('justify-center', 'px-0');
                    link.classList.remove('px-3');
                });
                
                // Hide dropdown arrows
                const dropdownArrows = document.querySelectorAll('#sidebar .organization-toggle svg:last-child, #sidebar .personnel-toggle svg:last-child');
                dropdownArrows.forEach(arrow => {
                    arrow.style.display = 'none';
                });
                
                // Hide sub-menus completely
                const subMenus = document.querySelectorAll('#sidebar .organization-submenu, #sidebar .personnel-submenu');
                subMenus.forEach(menu => {
                    menu.classList.add('hidden');
                    menu.classList.remove('block');
                });
                
                // Add click event to navigation icons to expand sidebar (excluding toggle button)
                const navIcons = document.querySelectorAll('#sidebar a.flex svg, #sidebar .organization-toggle svg:first-child, #sidebar .personnel-toggle svg:first-child');
                navIcons.forEach(icon => {
                    icon.addEventListener('click', function(e) {
                        // Only expand if sidebar is collapsed
                        if (sidebar.classList.contains('w-20')) {
                            e.preventDefault();
                            expandSidebar();
                        }
                    });
                });
                
                // Update main content margin
                if (mainContent) {
                    mainContent.style.marginLeft = '5rem';
                }
            }
            
            // Function to expand the sidebar
            function expandSidebar() {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                
                // Change icon to collapse (left arrow)
                collapseIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />';
                
                // Show all text spans
                const textSpans = document.querySelectorAll('#sidebar span');
                textSpans.forEach(span => {
                    span.style.display = 'inline';
                });
                
                // Reset link styles
                const links = document.querySelectorAll('#sidebar a.flex, #sidebar button');
                links.forEach(link => {
                    link.classList.remove('justify-center', 'px-0');
                    link.classList.add('px-3');
                });
                
                // Show dropdown arrows
                const dropdownArrows = document.querySelectorAll('#sidebar .organization-toggle svg:last-child, #sidebar .personnel-toggle svg:last-child');
                dropdownArrows.forEach(arrow => {
                    arrow.style.display = 'block';
                });
                
                // Show sub-menus (but keep their hidden/show state based on previous toggle)
                const subMenus = document.querySelectorAll('#sidebar .organization-submenu, #sidebar .personnel-submenu');
                subMenus.forEach(menu => {
                    // Don't automatically show them - let the toggle state control visibility
                    // Just remove the forced hidden style from collapse
                });
                
                // Remove click event from navigation icons to expand sidebar
                const navIcons = document.querySelectorAll('#sidebar a.flex svg, #sidebar .organization-toggle svg:first-child, #sidebar .personnel-toggle svg:first-child');
                navIcons.forEach(icon => {
                    icon.removeEventListener('click', expandSidebar);
                });
                
                // Update main content margin
                if (mainContent) {
                    mainContent.style.marginLeft = '16rem';
                }
            }
            
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (sidebar.classList.contains('w-20')) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            });
            
            // Function to save submenu state
            function saveSubmenuState() {
                const orgSubmenu = document.querySelector('.organization-submenu');
                const persSubmenu = document.querySelector('.personnel-submenu');
                
                if (orgSubmenu) {
                    localStorage.setItem('orgSubmenuVisible', !orgSubmenu.classList.contains('hidden'));
                }
                
                if (persSubmenu) {
                    localStorage.setItem('persSubmenuVisible', !persSubmenu.classList.contains('hidden'));
                }
            }
            
            // Function to restore submenu state
            function restoreSubmenuState() {
                const orgSubmenu = document.querySelector('.organization-submenu');
                const persSubmenu = document.querySelector('.personnel-submenu');
                
                if (orgSubmenu) {
                    const orgVisible = localStorage.getItem('orgSubmenuVisible');
                    if (orgVisible === 'true') {
                        orgSubmenu.classList.remove('hidden');
                        orgSubmenu.classList.add('block');
                        
                        // Update the arrow icon rotation
                        const orgArrow = document.querySelector('.organization-toggle svg:last-child');
                        if (orgArrow) {
                            orgArrow.style.transform = 'rotate(180deg)';
                        }
                    }
                }
                
                if (persSubmenu) {
                    const persVisible = localStorage.getItem('persSubmenuVisible');
                    if (persVisible === 'true') {
                        persSubmenu.classList.remove('hidden');
                        persSubmenu.classList.add('block');
                        
                        // Update the arrow icon rotation
                        const persArrow = document.querySelector('.personnel-toggle svg:last-child');
                        if (persArrow) {
                            persArrow.style.transform = 'rotate(180deg)';
                        }
                    }
                }
            }
            
            // Restore submenu state on page load
            restoreSubmenuState();
            
            // Personnel submenu toggle functionality
            const personnelToggle = document.querySelector('.personnel-toggle');
            const personnelSubmenu = document.querySelector('.personnel-submenu');
            
            if (personnelToggle && personnelSubmenu) {
                personnelToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Only toggle if sidebar is expanded
                    if (!sidebar.classList.contains('w-20')) {
                        // Toggle submenu visibility
                        if (personnelSubmenu.classList.contains('hidden')) {
                            personnelSubmenu.classList.remove('hidden');
                            personnelSubmenu.classList.add('block');
                            
                            // Rotate the arrow icon
                            const arrowIcon = this.querySelector('svg:last-child');
                            arrowIcon.style.transform = 'rotate(180deg)';
                        } else {
                            personnelSubmenu.classList.add('hidden');
                            personnelSubmenu.classList.remove('block');
                            
                            // Rotate the arrow icon back
                            const arrowIcon = this.querySelector('svg:last-child');
                            arrowIcon.style.transform = 'rotate(0deg)';
                        }
                        
                        // Save the state
                        saveSubmenuState();
                    }
                });
            }
            
            // Organization submenu toggle functionality
            const organizationToggle = document.querySelector('.organization-toggle');
            const organizationSubmenu = document.querySelector('.organization-submenu');
            
            if (organizationToggle && organizationSubmenu) {
                organizationToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Only toggle if sidebar is expanded
                    if (!sidebar.classList.contains('w-20')) {
                        // Toggle submenu visibility
                        if (organizationSubmenu.classList.contains('hidden')) {
                            organizationSubmenu.classList.remove('hidden');
                            organizationSubmenu.classList.add('block');
                            
                            // Rotate the arrow icon
                            const arrowIcon = this.querySelector('svg:last-child');
                            arrowIcon.style.transform = 'rotate(180deg)';
                        } else {
                            organizationSubmenu.classList.add('hidden');
                            organizationSubmenu.classList.remove('block');
                            
                            // Rotate the arrow icon back
                            const arrowIcon = this.querySelector('svg:last-child');
                            arrowIcon.style.transform = 'rotate(0deg)';
                        }
                        
                        // Save the state
                        saveSubmenuState();
                    }
                });
            }
        });
    </script>
</div>