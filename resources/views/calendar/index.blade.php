<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vehicle Calendar') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                        <h2 class="text-2xl font-semibold text-gray-800">Vehicle Calendar</h2>
                        
                        <!-- Month and Year Selection -->
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <label for="month-select" class="text-gray-700 font-medium">Month:</label>
                                <select id="month-select" class="border border-gray-200 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#009639] focus:border-transparent bg-white shadow-sm">
                                    <option value="0">January</option>
                                    <option value="1">February</option>
                                    <option value="2">March</option>
                                    <option value="3">April</option>
                                    <option value="4">May</option>
                                    <option value="5">June</option>
                                    <option value="6">July</option>
                                    <option value="7">August</option>
                                    <option value="8">September</option>
                                    <option value="9">October</option>
                                    <option value="10">November</option>
                                    <option value="11">December</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <label for="year-select" class="text-gray-700 font-medium">Year:</label>
                                <select id="year-select" class="border border-gray-200 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#009639] focus:border-transparent bg-white shadow-sm">
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FullCalendar Container -->
                    <div id='calendar' class="bg-white rounded-lg"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for showing travel order details -->
    <div id="event-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-xl">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                <h3 id="modal-title" class="text-xl font-semibold text-gray-800"></h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700 rounded-full p-1 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modal-content" class="max-h-96 overflow-y-auto">
                <p id="modal-destination" class="text-gray-600 mb-2"></p>
                <p id="modal-date" class="text-gray-600 mb-4"></p>
                <div id="modal-description" class="text-gray-700"></div>
            </div>
            <div class="mt-6 flex justify-end">
                <button id="modal-close-btn" class="px-4 py-2 bg-[#009639] text-white rounded-md hover:bg-[#007d31] focus:outline-none focus:ring-2 focus:ring-[#009639] focus:ring-offset-2">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Include FullCalendar CSS and JS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    
    <style>
        /* Custom styling for FullCalendar */
        .fc {
            --fc-border-color: #f0f0f0;
            --fc-button-bg-color: #f8f8f8;
            --fc-button-border-color: #e5e5e5;
            --fc-button-text-color: #333;
            --fc-button-hover-bg-color: #ffffff;
            --fc-button-hover-border-color: #009639;
            --fc-button-active-bg-color: #e6f4ea;
            --fc-button-active-border-color: #009639;
            --fc-event-bg-color: #009639;
            --fc-event-border-color: #009639;
            --fc-today-bg-color: #f0f9f2;
        }
        
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }
        
        .fc .fc-button {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .fc .fc-event {
            border-radius: 0.25rem;
            padding: 2px 4px;
            font-size: 0.85rem;
        }
        
        .fc .fc-daygrid-event {
            margin-bottom: 1px;
        }
        
        .fc .fc-daygrid-day-frame {
            min-height: 80px;
        }
        
        .fc .fc-col-header-cell {
            padding: 0.75rem 0;
            background-color: #f9f9f9;
        }
        
        .fc .fc-col-header-cell-cushion {
            font-weight: 600;
            color: #333;
        }
        
        .fc .fc-daygrid-day-number {
            font-weight: 500;
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
                    document.getElementById('modal-destination').textContent = 'Destination: ' + info.event.extendedProps.destination;
                    document.getElementById('modal-date').textContent = 'Date: ' + info.event.start.toLocaleDateString();
                    document.getElementById('modal-description').textContent = info.event.title;
                    modal.classList.remove('hidden');
                },
                eventDisplay: 'block',
                themeSystem: 'bootstrap',
                height: 'auto',
                aspectRatio: 1.8
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
            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
            
            modalCloseBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>