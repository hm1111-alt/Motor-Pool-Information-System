@extends('layouts.employee')

@section('header')
    <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        {{ __('Calendar') }}
    </h2>
@endsection

@section('content')
    <div class="py-2">
        <div class="max-w-lg mx-auto px-3 sm:px-4">
            <div class="bg-white overflow-hidden shadow rounded border border-gray-100">
                <div class="p-2">
                    <!-- Page Title -->
                    <div class="mb-2 pb-1.5 border-b border-gray-200">
                        <h1 class="text-base font-bold text-gray-800">Calendar</h1>
                        <p class="text-gray-600 text-xs mt-0.5">View your travel schedule</p>
                    </div>
                    
                    <!-- Month and Year Selection -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-2 gap-2 bg-green-50 p-2 rounded-lg">
                        <div class="flex items-center gap-1">
                            <svg class="h-3 w-3 text-[#1e6031]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h2 class="text-sm font-semibold text-gray-800">Calendar</h2>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 w-full md:w-auto">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 w-full">
                                <label for="month-select" class="text-gray-700 font-medium text-xs whitespace-nowrap">Month:</label>
                                <div class="relative w-full sm:w-auto min-w-[80px]">
                                    <select id="month-select" class="border border-green-200 rounded pl-1.5 pr-4 py-0.5 text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1e6031] focus:border-transparent bg-white shadow-sm w-full transition duration-200 text-xs">
                                        <option value="0">Jan</option>
                                        <option value="1">Feb</option>
                                        <option value="2">Mar</option>
                                        <option value="3">Apr</option>
                                        <option value="4">May</option>
                                        <option value="5">Jun</option>
                                        <option value="6">Jul</option>
                                        <option value="7">Aug</option>
                                        <option value="8">Sep</option>
                                        <option value="9">Oct</option>
                                        <option value="10">Nov</option>
                                        <option value="11">Dec</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-1 w-full">
                                <label for="year-select" class="text-gray-700 font-medium text-xs whitespace-nowrap">Year:</label>
                                <div class="relative w-full sm:w-auto min-w-[70px]">
                                    <select id="year-select" class="border border-green-200 rounded pl-1.5 pr-4 py-0.5 text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#1e6031] focus:border-transparent bg-white shadow-sm w-full transition duration-200 text-xs">
                                        <!-- Options will be populated by JavaScript -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FullCalendar Container -->
                    <div id='calendar' class="bg-white rounded-lg shadow-sm border border-gray-100 p-0.5 overflow-hidden"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for showing travel order details -->
    <div id="event-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-1.5 overflow-y-auto">
        <div class="bg-white rounded-lg p-2 max-w-xs w-full my-4 shadow-xl transition duration-300 transform scale-95 opacity-0 modal-show">
            <div class="flex justify-between items-center mb-2 pb-1 border-b border-gray-200">
                <h3 id="modal-title" class="text-sm font-semibold text-gray-800"></h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700 rounded-full p-0.5 hover:bg-gray-100 transition duration-200">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modal-content" class="max-h-32 overflow-y-auto py-0.5">
                <div class="flex items-start mb-1">
                    <svg class="h-3 w-3 text-[#1e6031] mr-1 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p id="modal-destination" class="text-gray-700 font-medium text-xs"></p>
                </div>
                <div class="flex items-start mb-1.5">
                    <svg class="h-3 w-3 text-[#1e6031] mr-1 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p id="modal-date" class="text-gray-700 text-xs"></p>
                </div>
                <div class="mb-1.5">
                    <h4 class="text-xs font-semibold text-gray-700 mb-0.5">Purpose:</h4>
                    <div id="modal-description" class="text-gray-600 text-xs"></div>
                </div>
            </div>
            <div class="mt-2 flex justify-end">
                <button id="modal-close-btn" class="px-2 py-0.5 bg-[#1e6031] hover:bg-[#164f2a] text-white rounded text-xs focus:outline-none focus:ring-1 focus:ring-[#1e6031] focus:ring-offset-1 transition duration-300 shadow-sm hover:shadow">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Include FullCalendar CSS and JS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    
    <style>
        /* Custom styling for FullCalendar */
        .fc {
            --fc-border-color: #e2e8f0;
            --fc-button-bg-color: #f0f9f2;
            --fc-button-border-color: #c1e4c7;
            --fc-button-text-color: #1e6031;
            --fc-button-hover-bg-color: #c1e4c7;
            --fc-button-hover-border-color: #1e6031;
            --fc-button-active-bg-color: #1e6031;
            --fc-button-active-border-color: #1e6031;
            --fc-button-active-text-color: #ffffff;
            --fc-event-bg-color: #1e6031;
            --fc-event-border-color: #1e6031;
            --fc-today-bg-color: #f0f9f2;
            --fc-highlight-color: #c1e4c7;
        }
        
        .fc .fc-toolbar-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #1e6031;
        }
        
        @media (min-width: 768px) {
            .fc .fc-toolbar-title {
                font-size: 0.875rem;
            }
        }
        
        .fc .fc-button {
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-weight: 500;
            transition: all 0.2s;
            font-size: 0.65rem;
            border: 1px solid var(--fc-button-border-color);
        }
        
        @media (min-width: 768px) {
            .fc .fc-button {
                font-size: 0.7rem;
            }
        }
        
        .fc .fc-event {
            border-radius: 0.25rem;
            padding: 1px 2px;
            font-size: 0.55rem;
            line-height: 1.2;
            font-weight: 500;
        }
        
        @media (min-width: 768px) {
            .fc .fc-event {
                font-size: 0.6rem;
            }
        }
        
        .fc .fc-daygrid-event {
            margin-bottom: 1px;
        }
        
        .fc .fc-daygrid-day-frame {
            min-height: 25px;
        }
        
        @media (min-width: 768px) {
            .fc .fc-daygrid-day-frame {
                min-height: 35px;
            }
        }
        
        .fc .fc-col-header-cell {
            padding: 0.25rem 0;
            background-color: #f0f9f2;
        }
        
        .fc .fc-col-header-cell-cushion {
            font-weight: 600;
            color: #1e6031;
            font-size: 0.65rem;
        }
        
        @media (min-width: 768px) {
            .fc .fc-col-header-cell-cushion {
                font-size: 0.7rem;
            }
        }
        
        .fc .fc-daygrid-day-number {
            font-weight: 500;
            font-size: 0.65rem;
            padding: 2px;
            color: #334155;
        }
        
        .fc .fc-daygrid-day.fc-day-today {
            background-color: #f0f9f2 !important;
        }
        
        .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
            background-color: #1e6031;
            color: white;
            border-radius: 9999px;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1px;
        }
        
        @media (min-width: 768px) {
            .fc .fc-daygrid-day-number {
                font-size: 0.7rem;
            }
        }
        
        /* Responsive adjustments */
        .fc .fc-toolbar.fc-header-toolbar {
            flex-direction: column;
            gap: 0.2rem;
        }
        
        @media (min-width: 768px) {
            .fc .fc-toolbar.fc-header-toolbar {
                flex-direction: row;
                gap: 0.4rem;
            }
        }
        
        /* Modal transition styles */
        .modal-show {
            transition: all 0.3s ease-out;
        }
        
        .modal-show:not(.hidden) {
            transform: scale(1);
            opacity: 1;
        }
        
        /* Custom dropdown styles to hide default arrows */
        #month-select, #year-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231e6031' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M6 9l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.25rem center;
            background-size: 0.6rem;
        }
        
        /* Responsive adjustments for small screens */
        @media (max-width: 480px) {
            .fc .fc-toolbar-title {
                font-size: 0.7rem;
            }
            
            .fc .fc-button {
                padding: 0.15rem 0.3rem;
                font-size: 0.55rem;
            }
            
            .fc .fc-daygrid-day-number {
                font-size: 0.55rem;
            }
            
            .fc .fc-col-header-cell-cushion {
                font-size: 0.55rem;
            }
            
            .fc .fc-event {
                font-size: 0.45rem;
                padding: 0.5px 0.5px;
            }
        }
        
        /* Desktop-specific adjustments to make calendar compact */
        @media (min-width: 1024px) {
            .fc {
                max-width: 100%;
            }
            
            .fc .fc-toolbar-title {
                font-size: 0.75rem;
            }
            
            .fc .fc-daygrid-day-frame {
                min-height: 30px;
            }
            
            .fc .fc-event {
                font-size: 0.55rem;
                padding: 0.5px 0.5px;
            }
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var modal = document.getElementById('event-modal');
            var closeModal = document.getElementById('close-modal');
            var modalCloseBtn = document.getElementById('modal-close-btn');
            var monthSelect = document.getElementById('month-select');
            var yearSelect = document.getElementById('year-select');
            
            // Populate year select with a range of years
            const currentYear = new Date().getFullYear();
            for (let year = currentYear - 5; year <= currentYear + 5; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }
            
            // Set current month and year as default
            const now = new Date();
            monthSelect.value = now.getMonth();
            yearSelect.value = now.getFullYear();
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: ''
                },
                initialView: 'dayGridMonth',
                events: '{{ route('vehicle-calendar.events') }}',
                eventClick: function(info) {
                    // Show event details in modal
                    document.getElementById('modal-title').textContent = info.event.title;
                    document.getElementById('modal-destination').textContent = info.event.extendedProps.destination;
                    document.getElementById('modal-date').textContent = info.event.start.toLocaleDateString();
                    document.getElementById('modal-description').textContent = info.event.extendedProps.description || 'No description provided.';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    
                    // Trigger reflow and then add the show class for animation
                    setTimeout(() => {
                        modal.querySelector('.modal-show').classList.remove('scale-95', 'opacity-0');
                    }, 10);
                },
                eventDisplay: 'block',
                themeSystem: 'bootstrap',
                height: 'auto',
                aspectRatio: 3.5, // Make calendar even taller and narrower
                windowResize: function(arg) {
                    // Adjust calendar view based on screen size
                    if (arg.view.el.offsetWidth < 768) {
                        calendar.changeView('dayGridMonth');
                    }
                }
            });
            
            calendar.render();
            
            // Update calendar when month or year selection changes
            monthSelect.addEventListener('change', function() {
                updateCalendar();
            });
            
            yearSelect.addEventListener('change', function() {
                updateCalendar();
            });
            
            function updateCalendar() {
                const selectedMonth = parseInt(monthSelect.value);
                const selectedYear = parseInt(yearSelect.value);
                const date = new Date(selectedYear, selectedMonth, 1);
                calendar.gotoDate(date);
            }
            
            // Update select boxes when calendar navigates
            calendar.on('datesSet', function(info) {
                const currentDate = info.view.currentStart;
                monthSelect.value = currentDate.getMonth();
                yearSelect.value = currentDate.getFullYear();
            });
            
            // Close modal event
            function closeModalFunc() {
                const modalContent = modal.querySelector('.modal-show');
                modalContent.classList.add('scale-95', 'opacity-0');
                
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    // Reset classes for next open
                    modalContent.classList.remove('scale-95', 'opacity-0');
                }, 300);
            }
            
            // Function to handle calendar resize when sidebar toggles
            function handleCalendarResize() {
                if (calendar) {
                    // Longer delay to ensure sidebar animation completes
                    setTimeout(() => {
                        calendar.updateSize();
                    }, 300);
                }
            }
            
            // Listen for sidebar toggle events
            document.addEventListener('sidebarToggled', handleCalendarResize);
            
            // Also listen for window resize events
            window.addEventListener('resize', handleCalendarResize);
            
            // Additional fix: Force calendar to update size when page is fully loaded
            window.addEventListener('load', function() {
                if (calendar) {
                    setTimeout(() => {
                        calendar.updateSize();
                    }, 500);
                }
            });
            
            closeModal.addEventListener('click', closeModalFunc);
            
            modalCloseBtn.addEventListener('click', closeModalFunc);
            
            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModalFunc();
                }
            });
        });
    </script>
@endsection