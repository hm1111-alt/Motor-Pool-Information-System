@extends('layouts.motorpool-admin')

@section('header')
    <div class="main">
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <span style="color:#004d00; font-weight:700; font-size:2rem;">
                Admin Dashboard
            </span>
        </div>
    </div>

                    <!-- Approved Travel Orders Section -->
                    <div style="margin-top: 30px;">
                        <h3 style="color:#004d00; font-weight:900; font-size:1.8rem; margin-bottom:20px; text-transform:uppercase; letter-spacing:1px; text-align:center;">
                            APPROVED TRAVEL ORDERS
                        </h3>
                        
                        <!-- Search Bar -->
                        <div style="margin-bottom: 20px; display: flex; justify-content: center;">
                            <div style="position: relative; width: 100%; max-width: 500px;">
                                <input type="text" 
                                       id="travelOrderSearch" 
                                       placeholder="Search travel orders..." 
                                       style="
                                           width: 100%;
                                           padding: 12px 20px;
                                           border: 2px solid #004d00;
                                           border-radius: 8px;
                                           font-size: 16px;
                                           font-weight: 500;
                                           color: #004d00;
                                           background-color: #f2f9f2;
                                           box-sizing: border-box;
                                           transition: all 0.3s ease;
                                       "
                                       onkeyup="searchTravelOrders()">
                                <i class="fas fa-search" style="
                                    position: absolute;
                                    right: 15px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    color: #004d00;
                                    font-size: 18px;
                                "></i>
                            </div>
                        </div>
                        
                        <!-- Travel Orders Table -->
                        <div style="
                            background: #fff;
                            border: 2px solid #004d00;
                            border-radius: 12px;
                            box-shadow: 0 4px 8px rgba(0,77,0,0.1);
                            overflow: hidden;
                            margin-bottom: 20px;
                        ">
                            <div style="overflow-x: auto;">
                                <table style="
                                    width: 100%;
                                    border-collapse: collapse;
                                    font-family: Arial, sans-serif;
                                ">
                                    <thead>
                                        <tr style="background-color: #004d00; color: white;">
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">No.</th>
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">Employee Name</th>
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">Purpose</th>
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">Destination</th>
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">Date</th>
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">Time</th>
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">Vehicle</th>
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">Driver</th>
                                            <th style="padding: 15px 12px; text-align: left; font-weight: 700; font-size: 14px; border-right: 1px solid rgba(255,255,255,0.2);">Remarks</th>
                                            <th style="padding: 15px 12px; text-align: center; font-weight: 700; font-size: 14px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="travelOrdersTableBody">
                                        @if(isset($travelOrders) && $travelOrders->count() > 0)
                                            @foreach($travelOrders as $index => $order)
                                                <tr style="border-bottom: 1px solid #e0e0e0; {{ $loop->even ? 'background-color: #f8f9fa;' : '' }}" class="travel-order-row">
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $index + 1 }}</td>
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $order->employee->full_name ?? 'N/A' }}</td>
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $order->purpose ?? 'N/A' }}</td>
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $order->destination ?? 'N/A' }}</td>
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $order->date_needed ? date('M d, Y', strtotime($order->date_needed)) : 'N/A' }}</td>
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $order->time_needed ?? 'N/A' }}</td>
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $order->vehicle_assigned ?? 'Not assigned' }}</td>
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $order->driver_assigned ?? 'Not assigned' }}</td>
                                                    <td style="padding: 12px; font-size: 14px; color: #333;">{{ $order->remarks ?? 'None' }}</td>
                                                    <td style="padding: 12px; text-align: center;">
                                                        <button onclick="viewTravelOrder({{ $order->id }})" style="
                                                            background: #004d00;
                                                            color: white;
                                                            border: none;
                                                            padding: 8px 12px;
                                                            border-radius: 4px;
                                                            cursor: pointer;
                                                            font-size: 12px;
                                                            font-weight: 600;
                                                            transition: background 0.2s;
                                                        " onmouseover="this.style.background='#003300'" onmouseout="this.style.background='#004d00'">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" style="padding: 30px; text-align: center; font-size: 16px; color: #666; font-style: italic;">
                                                    No approved travel orders found.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        @if(isset($travelOrders) && $travelOrders->hasPages())
                            <div style="
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                padding: 15px 20px;
                                background: #f8f9fa;
                                border: 1px solid #dee2e6;
                                border-radius: 8px;
                                font-size: 14px;
                                color: #495057;
                            ">
                                <div>
                                    Showing {{ $travelOrders->firstItem() }} to {{ $travelOrders->lastItem() }} of {{ $travelOrders->total() }} entries
                                </div>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    {{-- Previous Button --}}
                                    @if($travelOrders->onFirstPage())
                                        <button disabled style="
                                            padding: 8px 12px;
                                            background: #e9ecef;
                                            color: #6c757d;
                                            border: 1px solid #dee2e6;
                                            border-radius: 4px;
                                            cursor: not-allowed;
                                            font-size: 13px;
                                        ">
                                            <i class="fas fa-chevron-left"></i> Prev
                                        </button>
                                    @else
                                        <a href="{{ $travelOrders->previousPageUrl() }}" style="
                                            padding: 8px 12px;
                                            background: #004d00;
                                            color: white;
                                            text-decoration: none;
                                            border: 1px solid #004d00;
                                            border-radius: 4px;
                                            font-size: 13px;
                                            transition: background 0.2s;
                                        " onmouseover="this.style.background='#003300'" onmouseout="this.style.background='#004d00'">
                                            <i class="fas fa-chevron-left"></i> Prev
                                        </a>
                                    @endif
                                    
                                    {{-- Page Numbers --}}
                                    @for($i = max(1, $travelOrders->currentPage() - 2); $i <= min($travelOrders->lastPage(), $travelOrders->currentPage() + 2); $i++)
                                        @if($i == $travelOrders->currentPage())
                                            <span style="
                                                padding: 8px 12px;
                                                background: #004d00;
                                                color: white;
                                                border: 1px solid #004d00;
                                                border-radius: 4px;
                                                font-size: 13px;
                                                font-weight: 600;
                                            ">{{ $i }}</span>
                                        @else
                                            <a href="{{ $travelOrders->url($i) }}" style="
                                                padding: 8px 12px;
                                                background: white;
                                                color: #004d00;
                                                text-decoration: none;
                                                border: 1px solid #004d00;
                                                border-radius: 4px;
                                                font-size: 13px;
                                                transition: all 0.2s;
                                            " onmouseover="this.style.background='#f2f9f2'" onmouseout="this.style.background='white'">{{ $i }}</a>
                                        @endif
                                    @endfor
                                    
                                    {{-- Next Button --}}
                                    @if($travelOrders->hasMorePages())
                                        <a href="{{ $travelOrders->nextPageUrl() }}" style="
                                            padding: 8px 12px;
                                            background: #004d00;
                                            color: white;
                                            text-decoration: none;
                                            border: 1px solid #004d00;
                                            border-radius: 4px;
                                            font-size: 13px;
                                            transition: background 0.2s;
                                        " onmouseover="this.style.background='#003300'" onmouseout="this.style.background='#004d00'">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @else
                                        <button disabled style="
                                            padding: 8px 12px;
                                            background: #e9ecef;
                                            color: #6c757d;
                                            border: 1px solid #dee2e6;
                                            border-radius: 4px;
                                            cursor: not-allowed;
                                            font-size: 13px;
                                        ">
                                            Next <i class="fas fa-chevron-right"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Script -->
    <script>
        let searchTimeout;
        
        function searchTravelOrders() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = document.getElementById('travelOrderSearch').value.trim();
                
                if (searchTerm.length >= 2) {
                    // Show loading indicator
                    const tbody = document.getElementById('travelOrdersTableBody');
                    tbody.innerHTML = `<tr><td colspan="10" style="padding: 30px; text-align: center; font-size: 16px; color: #666;">Searching...</td></tr>`;
                    
                    // Make AJAX request
                    fetch(`/api/travel-orders/search?q=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                            updateTable(data.data);
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            tbody.innerHTML = `<tr><td colspan="10" style="padding: 30px; text-align: center; font-size: 16px; color: #666;">Error searching travel orders.</td></tr>`;
                        });
                } else if (searchTerm.length === 0) {
                    // Reload original data
                    location.reload();
                }
            }, 300); // Debounce for 300ms
        }
        
        function updateTable(orders) {
            const tbody = document.getElementById('travelOrdersTableBody');
            
            if (orders.length === 0) {
                tbody.innerHTML = `<tr><td colspan="10" style="padding: 30px; text-align: center; font-size: 16px; color: #666; font-style: italic;">No travel orders found matching your search.</td></tr>`;
                return;
            }
            
            let html = '';
            orders.forEach((order, index) => {
                const rowClass = index % 2 === 0 ? '' : 'background-color: #f8f9fa;';
                const employeeName = order.employee ? `${order.employee.first_name} ${order.employee.last_name}` : 'N/A';
                const dateNeeded = order.date_needed ? new Date(order.date_needed).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                }) : 'N/A';
                
                html += `
                    <tr style="border-bottom: 1px solid #e0e0e0; ${rowClass}" class="travel-order-row">
                        <td style="padding: 12px; font-size: 14px; color: #333;">${index + 1}</td>
                        <td style="padding: 12px; font-size: 14px; color: #333;">${employeeName}</td>
                        <td style="padding: 12px; font-size: 14px; color: #333;">${order.purpose || 'N/A'}</td>
                        <td style="padding: 12px; font-size: 14px; color: #333;">${order.destination || 'N/A'}</td>
                        <td style="padding: 12px; font-size: 14px; color: #333;">${dateNeeded}</td>
                        <td style="padding: 12px; font-size: 14px; color: #333;">${order.time_needed || 'N/A'}</td>
                        <td style="padding: 12px; font-size: 14px; color: #333;">${order.vehicle_assigned || 'Not assigned'}</td>
                        <td style="padding: 12px; font-size: 14px; color: #333;">${order.driver_assigned || 'Not assigned'}</td>
                        <td style="padding: 12px; font-size: 14px; color: #333;">${order.remarks || 'None'}</td>
                        <td style="padding: 12px; text-align: center;">
                            <button onclick="viewTravelOrder(${order.id})" style="
                                background: #004d00;
                                color: white;
                                border: none;
                                padding: 8px 12px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-size: 12px;
                                font-weight: 600;
                                transition: background 0.2s;
                            " onmouseover="this.style.background='#003300'" onmouseout="this.style.background='#004d00'">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        }
        
        function viewTravelOrder(id) {
            // Open PDF directly in new tab
            window.open('/motorpool/travel-orders/' + id + '/pdf', '_blank');
        }
    </script>
@endsection
<style>
.dashboard-card{
    flex: 1;
    min-width: 220px;
    height: 120px;
    display: flex;
    align-items: center;
    padding: 20px;

    background: #DFF0D8;
    border-radius: 12px;

    /* ✅ GREEN OUTER BORDER */
    border: 2px solid #00703C;

    box-shadow: 0 6px 12px rgba(0,112,60,0.15);

    cursor: pointer;

    transition: all 0.25s ease;
}

.dashboard-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 12px 20px rgba(0,112,60,0.25);
}
</style>
@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Dashboard Cards -->
                    <div class="dashboard-cards" style="
                        display: flex;
                        gap: 20px;
                        flex-wrap: wrap;
                        margin-top: 20px;
                    ">
                    
                    <!-- Click handlers for cards -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Vehicles card click handler
                            const vehiclesCard = document.getElementById('vehicles-card');
                            if (vehiclesCard) {
                                vehiclesCard.addEventListener('click', function() {
                                    window.location.href = '{{ route("vehicles.index") }}';
                                });
                            }
                            
                            // Drivers card click handler
                            const driversCard = document.getElementById('drivers-card');
                            if (driversCard) {
                                driversCard.addEventListener('click', function() {
                                    window.location.href = '{{ route("drivers.index") }}';
                                });
                            }
                            
                            // Itineraries card click handler
                            const itinerariesCard = document.getElementById('itineraries-card');
                            if (itinerariesCard) {
                                itinerariesCard.addEventListener('click', function() {
                                    window.location.href = '{{ route("itinerary.index") }}';
                                });
                            }
                            
                            // Trip Tickets card click handler
                            const tripTicketsCard = document.getElementById('tripTickets-card');
                            if (tripTicketsCard) {
                                tripTicketsCard.addEventListener('click', function() {
                                    window.location.href = '{{ route("trip-tickets.index") }}';
                                });
                            }
                        });
                    </script>
                    
                        <!-- ITINERARIES -->
                        <div id="itineraries-card" class="dashboard-card">
                            <div style="
                                width: 60px;
                                height: 60px;
                                border-radius: 50%;
                                background-color: #A8DFAA; /* darker green circle */
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 15px;
                            ">
                                <i class="fas fa-file-alt" style="font-size:28px; color:#00703C;"></i>
                            </div>
                            <div style="flex:1; text-align:right;">
                                <p style="font-size:22px; font-weight:700; margin:0; color:#00703C;">{{ $itinerariesCount ?? 0 }}</p>
                                <span style="font-size:14px; font-weight:600; color:#00703C;">ITINERARIES</span>
                            </div>
                        </div>
                    
                        <!-- TRIP TICKETS -->
                        <div id="tripTickets-card" class="dashboard-card">
                            <div style="
                                width: 60px;
                                height: 60px;
                                border-radius: 50%;
                                background-color: #A8DFAA; /* darker green circle */
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 15px;
                            ">
                                <i class="fas fa-file-signature" style="font-size:28px; color:#00703C;"></i>
                            </div>
                            <div style="flex:1; text-align:right;">
                                <p style="font-size:22px; font-weight:700; margin:0; color:#00703C;">{{ $tripTicketsCount ?? 0 }}</p>
                                <span style="font-size:14px; font-weight:600; color:#00703C;">TRIP TICKETS</span>
                            </div>
                        </div>
                    
                        <!-- VEHICLES -->
                      <div id="vehicles-card" class="dashboard-card">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background-color: #A8DFAA; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                <i class="fas fa-car" style="font-size:28px; color:#00703C;"></i>
                            </div>
                            <div style="flex:1; text-align:right;">
                                <p style="font-size:22px; font-weight:700; margin:0; color:#00703C;">{{ $vehiclesCount ?? 0 }}</p>
                                <span style="font-size:14px; font-weight:600; color:#00703C;">VEHICLES</span>
                            </div>
                        </div>
                         
                        <!-- DRIVERS CARD -->
                        <div id="drivers-card" class="dashboard-card">
                         <div style="width: 60px; height: 60px; border-radius: 50%; background-color: #A8DFAA; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                <i class="fas fa-id-badge" style="font-size:28px; color:#00703C;"></i>
                            </div>

                            <div style="flex:1; text-align:right;">
                                <p style="font-size:22px; font-weight:700; margin:0; color:#00703C;">{{ $driversCount ?? 0 }}</p>
                                <span style="font-size:14px; font-weight:600; color:#00703C;">DRIVERS</span>
                            </div>
                        </div>
                    </div>
                                        
                    <!-- Schedule of Trips -->
                    <div style="text-align:center; margin: 30px 0 20px 0;">
                 <h3 style="color:#004d00; font-weight:900; font-size:1.8rem; margin-bottom:5px; text-transform:uppercase; letter-spacing:1px;">
    SCHEDULE OF TRIPS
  </h3>
                        <div style="display:flex; justify-content:center; gap:8px; align-items:center;">
                            <label for="monthYear" style="font-weight:600; color:#004d00; font-size:1rem;">MONTH OF:</label>
                            <input type="month" id="monthYear" style="
                                padding:6px 12px;
                                border:2px solid #004d00;
                                border-radius:6px;
                                font-weight:600;
                                color:#004d00;
                                background-color:#f2f9f2;
                                font-size:1rem;
                                cursor: pointer;
                            " value="{{ date('Y-m') }}" />
                        </div>
                    </div>
                                        
                    <div id="tripCalendar" style="
                        background: #fff;
                        border: 2px solid #004d00;
                        border-radius: 12px;
                        padding: 20px;
                        box-shadow: 0 4px 8px rgba(0,77,0,0.1);
                        margin-bottom: 30px;
                    "></div>
                    
                    <!-- FullCalendar Script -->
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                      const monthYearInput = document.getElementById('monthYear');
                      const calendarEl = document.getElementById('tripCalendar');
                      
                      // Helper functions
                      function toYMD(d){ return d.toISOString().slice(0,10); }
                      
                      function formatDate(dateStr) {
                        const options = { year: 'numeric', month: 'long', day: 'numeric' };
                        const d = new Date(dateStr);
                        return d.toLocaleDateString('en-US', options);
                      }
                      
                      function formatTime(timeStr) {
                        if (!timeStr) return '';
                        const [hour, minute] = timeStr.split(':').map(Number);
                        const date = new Date();
                        date.setHours(hour, minute);
                        return date.toLocaleTimeString('en-US', { hour:'numeric', minute:'2-digit' });
                      }
                      
                      // Set initial month value
                      if (!monthYearInput.value) {
                        const now = new Date();
                        monthYearInput.value = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}`;
                      }
                      
                      const [initYear, initMonth] = monthYearInput.value.split('-').map(Number);
                      const initialDate = new Date(initYear, initMonth - 1, 1);
                      
                      const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        initialDate: initialDate,
                        height: 600,
                        dayHeaderFormat: { weekday: 'short' },
                        nowIndicator: true,
                        editable: false,
                        selectable: false,
                        navLinks: false,
                        headerToolbar: { 
                          left: '', 
                          center: 'title', 
                          right: '' 
                        },
                        displayEventTime: false,
                        dayMaxEvents: false,
                        
                        // Custom CLSU theme colors
                        eventBackgroundColor: '#004d00',
                        eventBorderColor: '#004d00',
                        eventTextColor: '#ffffff',
                        
                        events: function(fetchInfo, successCallback, failureCallback) {
                          const mv = monthYearInput.value || (new Date()).toISOString().slice(0,7);
                          const startStr = toYMD(fetchInfo.start);
                          const endStr = toYMD(fetchInfo.end);
                      
                          // Fetch trip tickets for the selected month
                          fetch(`/api/trip-tickets/calendar-events?start=${startStr}&end=${endStr}`, {
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                          })
                          .then(res => res.json())
                          .then(data => {
                            // Transform data to FullCalendar format
                            const events = data.map(ticket => ({
                              id: ticket.id,
                              title: ticket.driver_name || 'No Driver Assigned',
                              start: ticket.itinerary?.date_from || ticket.created_at,
                              end: ticket.itinerary?.date_to || ticket.created_at,
                              extendedProps: {
                                driver_name: ticket.driver_name,
                                vehicle: ticket.itinerary?.vehicle?.make + ' ' + ticket.itinerary?.vehicle?.model || 'No Vehicle',
                                destination: ticket.itinerary?.destination || 'No Destination',
                                status: ticket.status
                              }
                            }));
                            successCallback(events);
                          })
                          .catch(err => failureCallback(err));
                        },
                      
                        eventContent: function(arg) {
                          const driverName = arg.event.title || 'No Driver';
                          return { 
                            html: `<div style="padding: 2px 4px; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${driverName}</div>` 
                          };
                        },
                      
                        eventClick: function(info) {
                          // Calendar event clicked - show alert with trip info
                          const event = info.event;
                          const props = event.extendedProps;
                          
                          alert(`Driver: ${event.title}\nVehicle: ${props.vehicle}\nDestination: ${props.destination}\nStatus: ${props.status}`);
                        }
                      });
                      
                      calendar.render();
                      
                      // Apply scrollable events
                      function applyScrollable() {
                        document.querySelectorAll('.fc-daygrid-day-frame').forEach(dayFrame => {
                          const eventsContainer = dayFrame.querySelector('.fc-daygrid-day-events');
                          if (eventsContainer) {
                            eventsContainer.style.maxHeight = '80px';
                            eventsContainer.style.overflowY = 'auto';
                          }
                        });
                      }
                      
                      setTimeout(applyScrollable, 500);
                      
                      // Handle month change - update both calendar and status cards
                      monthYearInput.addEventListener('change', function() {
                        const [y,m] = this.value.split('-').map(Number);
                        calendar.gotoDate(new Date(y, m - 1, 1));
                        calendar.refetchEvents();
                        setTimeout(applyScrollable, 500);
                        
                        // Update status cards for selected month
                        updateStatusCards(this.value);
                      });
                      
                      // Function to update status cards based on selected month
                      function updateStatusCards(monthYear) {
                        fetch(`/api/trip-tickets/status-counts?month=${monthYear}`)
                          .then(res => res.json())
                          .then(data => {
                            document.getElementById('pendingCount').innerText = data.pending || 0;
                            document.getElementById('ongoingCount').innerText = data.ongoing || 0;
                            document.getElementById('completedCount').innerText = data.completed || 0;
                          })
                          .catch(err => console.error('Error updating status counts:', err));
                      }
                      
                      // Initial status card update
                      updateStatusCards(monthYearInput.value);
                    });
                    </script>
                                        
                    <!-- Status Cards -->
                    <div class="dashboard-cards" style="
                        display:flex;
                        flex-wrap:wrap;
                        gap:15px;
                        justify-content:space-between;
                        width:100%;
                        margin-top:10px;
                    ">
                                        
                        <!-- PENDING -->
                        <div class="dashboard-card" style="
                            flex:1;
                            min-width:250px;
                            height:100px;
                            display:flex;
                            align-items:center;
                            justify-content:space-between;
                            padding:20px;
                            background:#FFF4E0; /* lighter amber for bg */
                            border:2px solid #F0AD4E;
                            border-radius:8px;
                            box-shadow:0 4px 8px rgba(240,173,78,0.15);
                        ">
                            <i class="fas fa-file-alt" style="font-size:40px;color:#F0AD4E;"></i>
                            <div style="text-align:right;">
                                <p id="pendingCount" style="font-size:22px;font-weight:700;margin:0;color:#F0AD4E;">{{ $pendingCount ?? 0 }}</p>
                                <span style="font-size:16px;font-weight:600;color:#F0AD4E;">PENDING</span>
                            </div>
                        </div>
                                        
                        <!-- ON-GOING -->
                        <div class="dashboard-card" style="
                            flex:1;
                            min-width:250px;
                            height:100px;
                            display:flex;
                            align-items:center;
                            justify-content:space-between;
                            padding:20px;
                            background:#E3F0F7; /* lighter blue for bg */
                            border:2px solid #056295;
                            border-radius:8px;
                            box-shadow:0 4px 8px rgba(5,98,149,0.15);
                        ">
                            <i class="fas fa-spinner" style="font-size:40px;color:#056295;"></i>
                            <div style="text-align:right;">
                                <p id="ongoingCount" style="font-size:22px;font-weight:700;margin:0;color:#056295;">{{ $ongoingCount ?? 0 }}</p>
                                <span style="font-size:16px;font-weight:600;color:#056295;">ON-GOING</span>
                            </div>
                        </div>
                                        
                        <!-- COMPLETED -->
                        <div class="dashboard-card" style="
                            flex:1;
                            min-width:250px;
                            height:100px;
                            display:flex;
                            align-items:center;
                            justify-content:space-between;
                            padding:20px;
                            background:#DFF0D8; /* lighter green for bg */
                            border:2px solid #00703C;
                            border-radius:8px;
                            box-shadow:0 4px 8px rgba(0,112,60,0.15);
                        ">
                            <i class="fas fa-check-circle" style="font-size:40px;color:#00703C;"></i>
                            <div style="text-align:right;">
                                <p id="completedCount" style="font-size:22px;font-weight:700;margin:0;color:#00703C;">{{ $completedCount ?? 0 }}</p>
                                <span style="font-size:16px;font-weight:600;color:#00703C;">COMPLETED</span>
                            </div>
                        </div>
                    </div>
                                        

                                        
                    </div>
<!-- ===============================
VEHICLES & DRIVERS ROW
================================ -->

<div style="display:flex; gap:20px; margin-top:20px; flex-wrap:nowrap; width:100vw; max-width:100%; overflow-x:auto;">

  <!-- ===== LEFT COLUMN: MOST USED VEHICLES (PIE CHART) ===== -->
  <div style="flex:1; min-width:400px; max-width:50vw;">
    <div class="analytics-card p-3 mb-4" style="background:#f0f8ff;border-radius:8px;">

      <div class="analytics-header" style="padding:6px 8px; margin-bottom:1px;">
        <h4 class="mb-0" style="font-size:.9rem; color:#00703C;">
          Most Used Vehicles (<span id="vehiclesDateRange">Feb 01, 2026 - Feb 28, 2026</span>)
        </h4>
        <p class="text-success small mb-0" style="font-size:.8rem;">
          Vehicles with the highest number of completed trips.
        </p>
      </div>

      <div class="d-flex gap-1 align-items-center flex-wrap" style="padding:2px 4px 6px; margin-bottom:2px;">
        <select id="vehiclePeriod" class="form-select form-select-sm" style="max-width:100px; font-size:.8rem;">
          <option value="monthly" selected>Monthly</option>
          <option value="weekly">Weekly</option>
          <option value="yearly">Yearly</option>
        </select>
        <input type="week" id="vehicleFilterWeek" class="form-control form-control-sm" style="max-width:135px; display:none; font-size:.8rem;">
        <input type="month" id="vehicleFilterMonth" class="form-control form-control-sm" style="max-width:135px; display:none; font-size:.8rem;">
        <input type="number" id="vehicleFilterYear" class="form-control form-control-sm" style="max-width:100px; display:none; font-size:.8rem;" placeholder="Year" min="2000" max="2100">
      </div>

      <div style="height:300px; display:flex; align-items:center; justify-content:center;">
        <canvas id="vehiclePieChart"></canvas>
      </div>

    </div>
  </div>

  <!-- ===== RIGHT COLUMN: MOST ASSIGNED DRIVERS (BAR GRAPH) ===== -->
  <div style="flex:1; min-width:400px; max-width:50vw;">
    <div class="analytics-card p-3 mb-4" style="background:#f0f8ff;border-radius:8px;">

      <div class="analytics-header" style="padding:6px 8px; margin-bottom:1px;">
        <h4 class="mb-0" style="font-size:.9rem; color:#00703C;">
          Most Assigned Drivers (<span id="driversDateRange">Feb 01, 2026 - Feb 28, 2026</span>)
        </h4>
        <p class="text-success small mb-0" style="font-size:.8rem;">
          Drivers with the highest number of completed trips.
        </p>
      </div>

      <div class="d-flex gap-1 align-items-center flex-wrap" style="padding:2px 4px 6px; margin-bottom:2px;">
        <select id="driverPeriod" class="form-select form-select-sm" style="max-width:100px; font-size:.8rem;">
          <option value="monthly" selected>Monthly</option>
          <option value="weekly">Weekly</option>
          <option value="yearly">Yearly</option>
        </select>
        <input type="week" id="driverFilterWeek" class="form-control form-control-sm" style="max-width:135px; display:none; font-size:.8rem;">
        <input type="month" id="driverFilterMonth" class="form-control form-control-sm" style="max-width:135px; display:none; font-size:.8rem;">
        <input type="number" id="driverFilterYear" class="form-control form-control-sm" style="max-width:100px; display:none; font-size:.8rem;" placeholder="Year" min="2000" max="2100">
      </div>

      <div style="height:300px;">
        <canvas id="driverBarChart"></canvas>
      </div>

    </div>
  </div>

</div>

<!-- ===============================
COMPLETED TRIPS & DESTINATIONS ROW
================================ -->

<div style="display:flex; gap:20px; margin-top:20px; flex-wrap:nowrap; width:100vw; max-width:100%; overflow-x:auto;">

  <!-- ===== LEFT COLUMN: COMPLETED TRIPS ===== -->
  <div style="flex:1; min-width:400px; max-width:50vw;">
    <div class="analytics-card p-3 mb-4" style="background:#f0f8ff;border-radius:8px;">

      <div class="analytics-header" style="padding:6px 8px; margin-bottom:1px;">
        <h4 class="mb-0" style="font-size:.9rem; color:#00703C;">
          Completed Trips (<span id="completedTripsDateRange">Feb 01, 2026 - Feb 28, 2026</span>)
        </h4>
        <p class="text-success small mb-0" style="font-size:.8rem;">
          Overview of completed trips for the selected period.
        </p>
      </div>

      <div class="d-flex gap-1 align-items-center flex-wrap" style="padding:2px 4px 6px; margin-bottom:2px;">
        <select id="completedTripsPeriod" class="form-select form-select-sm" style="max-width:100px; font-size:.8rem;">
          <option value="monthly" selected>Monthly</option>
          <option value="weekly">Weekly</option>
          <option value="yearly">Yearly</option>
        </select>
        <input type="week" id="completedTripsFilterWeek" class="form-control form-control-sm" style="max-width:135px; display:none; font-size:.8rem;">
        <input type="month" id="completedTripsFilterMonth" class="form-control form-control-sm" style="max-width:135px; display:none; font-size:.8rem;">
        <input type="number" id="completedTripsFilterYear" class="form-control form-control-sm" style="max-width:100px; display:none; font-size:.8rem;" placeholder="Year" min="2000" max="2100">
      </div>

      <div style="height:300px; display:flex; align-items:center; justify-content:center;">
        <canvas id="completedTripsChart"></canvas>
      </div>

    </div>
  </div>

  <!-- ===== RIGHT COLUMN: TOP 10 DESTINATIONS ===== -->
  <div style="flex:1; min-width:400px; max-width:50vw;">
    <div class="analytics-card p-3 mb-4" style="background:#f0f8ff;border-radius:8px;">

      <div class="analytics-header" style="padding:6px 8px; margin-bottom:1px;">
        <h4 class="mb-0" style="font-size:.9rem; color:#00703C;">
          Top 10 Destinations (<span id="destinationsDateRange">Feb 01, 2026 - Feb 28, 2026</span>)
        </h4>
        <p class="text-success small mb-0" style="font-size:.8rem;">
          Most visited destinations for the selected period.
        </p>
      </div>

      <div class="d-flex gap-1 align-items-center flex-wrap" style="padding:2px 4px 6px; margin-bottom:2px;">
        <select id="destinationPeriod" class="form-select form-select-sm" style="max-width:100px; font-size:.8rem;">
          <option value="monthly" selected>Monthly</option>
          <option value="weekly">Weekly</option>
          <option value="yearly">Yearly</option>
        </select>
        <input type="week" id="destinationFilterWeek" class="form-control form-control-sm" style="max-width:135px; display:none; font-size:.8rem;">
        <input type="month" id="destinationFilterMonth" class="form-control form-control-sm" style="max-width:135px; display:none; font-size:.8rem;">
        <input type="number" id="destinationFilterYear" class="form-control form-control-sm" style="max-width:100px; display:none; font-size:.8rem;" placeholder="Year" min="2000" max="2100">
      </div>

      <div style="height:300px;">
        <canvas id="topDestinationsChart"></canvas>
      </div>

    </div>
  </div>

</div>
@endsection