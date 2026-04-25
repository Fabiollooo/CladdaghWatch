<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f4f4f4; color: #333; }
        
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <img src="https://via.placeholder.com/80/ff9f1c/0f6b6b?text=PS" alt="Logo">
            <nav>
                <a href="#" class="active">Schedule</a>
                <a href="/volunteers.php">Volunteers</a>
                <a href="#">Reports</a>
                <a href="#">Settings</a>
            </nav>
            <!-- <div class="logout" title="Logout">⏻</div> -->
            <a class="logout" href="/dashboard">⏻</a>
        </div>

        <div class="main">
            <div class="header">Admin Dashboard / Schedule</div>
            
            <div class="content">
                <!-- Left Panel -->
                <div class="left-panel">
                    <div class="add-volunteer">
                        <button id="addVolunteerBtn">+ Add Volunteer</button>
                        <div class="volunteer-list" id="volunteerList"></div>
                    </div>
                </div>

                <!-- Center Panel -->
                <div class="center-panel">
                    <div class="week-header">
                        <div class="week-title" id="weekTitle">Loading...</div>
                        <div class="week-nav">
                            <button id="prevWeekBtn">‹</button>
                            <button id="todayBtn">Today</button>
                            <button id="nextWeekBtn">›</button>
                        </div>
                    </div>
                    
                    <div class="calendar-container">
                        <div class="calendar-grid" id="calendarGrid"></div>
                    </div>
                </div>

                <!-- Right Panel -->
                <div class="right-panel">
                    <div class="create-schedule">
                        <button id="createScheduleBtn">Create Schedule</button>
                    </div>
                    
                    <div class="mini-calendar">
                        <div class="calendar-month" id="currentMonth"></div>
                        <div class="calendar-days" id="miniCalendarDays"></div>
                    </div>
                    
                    <div class="upcoming-events">
                        <h4>Upcoming Events</h4>
                        <div class="event-list" id="eventList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentWeek = 52;
        let currentDate = new Date(); 
        const events = ['Event 1', 'Event 2', 'Event 3', 'Event 4', 'Event 5'];
        const volunteers = ['Volunteer A', 'Volunteer B', 'Volunteer C', 'Volunteer D', 'Volunteer E'];

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            initVolunteers();
            initEvents();
            initCalendar();
            initMiniCalendar();
            updateWeekTitle(); 
            setupEventListeners();
        });

        // Get real week number function
        function getWeekNumber(date) {
            // Copy date so don't modify original
            const d = new Date(date);
            d.setHours(0, 0, 0, 0);
            d.setDate(d.getDate() + 3 - (d.getDay() + 6) % 7);
            // January 4 is always in week 1
            const week1 = new Date(d.getFullYear(), 0, 4);
            return 1 + Math.round(((d.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
        }

        // Get real dates for a week (Monday to Friday)
        function getWeekDates(weekOffset = 0) {
            const now = new Date();
            const currentWeek = getWeekNumber(now);
            const targetWeek = currentWeek + weekOffset;
            
            // Calculate Monday of current week
            const monday = new Date(now);
            const day = monday.getDay();
            const diff = monday.getDate() - day + (day === 0 ? -6 : 1); 
            
            monday.setDate(diff + (weekOffset * 7));
            
            // Generate Monday to Friday dates
            const weekDays = [];
            const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            
            for (let i = 0; i < 5; i++) {
                const date = new Date(monday);
                date.setDate(monday.getDate() + i);
                const dayNum = date.getDate().toString().padStart(2, '0');
                const monthName = date.toLocaleDateString('en-US', { month: 'short' });
                weekDays.push({
                    short: `${dayNames[i]} ${dayNum}`,
                    full: `${dayNames[i]} ${dayNum} ${monthName}`,
                    date: new Date(date) 
                });
            }
            
            return {
                weekDays,
                weekNumber: targetWeek,
                month: monday.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
            };
        }

        // Update week title with real date
        function updateWeekTitle(weekOffset = 0) {
            const weekData = getWeekDates(weekOffset);
            document.getElementById('weekTitle').textContent = 
                `Week ${weekData.weekNumber} - ${weekData.month}`;
            return weekData;
        }

        // Volunteers
        function initVolunteers() {
            const container = document.getElementById('volunteerList');
            volunteers.forEach(volunteer => {
                const div = document.createElement('div');
                div.className = 'volunteer-item';
                div.innerHTML = `<div class="volunteer-name">${volunteer}</div>`;
                container.appendChild(div);
            });
        }

        // Events
        function initEvents() {
            const container = document.getElementById('eventList');
            const now = new Date();
            
            events.forEach((event, i) => {
                const eventDate = new Date(now);
                eventDate.setDate(now.getDate() + i + 7); 
                
                const div = document.createElement('div');
                div.className = 'event-item';
                div.innerHTML = `${event}<br><small>${eventDate.toLocaleDateString('en-US', { 
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric' 
                })}</small>`;
                container.appendChild(div);
            });
        }

        // Calendar
        function initCalendar() {
            const grid = document.getElementById('calendarGrid');
            
            // Time column
            const timeCol = document.createElement('div');
            timeCol.className = 'time-column';
            timeCol.innerHTML = '<div></div>' + Array.from({length: 9}, (_, i) => 
                `<div class="time-slot">${i + 9}:00</div>`
            ).join('');
            grid.appendChild(timeCol);
            
            // Get current week dates
            const weekData = getWeekDates();
            
            // Day columns
            weekData.weekDays.forEach(day => {
                const col = document.createElement('div');
                col.className = 'day-column';
                col.innerHTML = `<div class="day-header">${day.short}</div>` + 
                    Array.from({length: 9}, (_, i) => 
                        `<div class="calendar-cell" data-day="${day.short}" data-date="${day.date.toISOString().split('T')[0]}" data-hour="${i + 9}"></div>`
                    ).join('');
                grid.appendChild(col);
            });
            
            // Add some sample bookings
            addSampleBookings(weekData);
        }

        function addSampleBookings(weekData) {
            // Add a few sample bookings on different days
            const days = weekData.weekDays;
            if (days.length > 0) {
                
                bookSlot(days[0].short, 9, 'Volunteer A', '#ff9f1c');
                
                if (days.length > 1) bookSlot(days[1].short, 11, 'Volunteer B', '#3498db');
                
                if (days.length > 2) bookSlot(days[2].short, 14, 'Volunteer C', '#2ecc71');
                
                if (days.length > 3) bookSlot(days[3].short, 10, 'Volunteer D', '#9b59b6');
                
                if (days.length > 4) bookSlot(days[4].short, 16, 'Volunteer E', '#e74c3c');
            }
        }

        function bookSlot(day, hour, volunteer, color) {
            const cell = document.querySelector(`.calendar-cell[data-day="${day}"][data-hour="${hour}"]`);
            if (cell) {
                cell.classList.add('booked');
                cell.innerHTML = `<div class="event-info">${hour}:00<br>${volunteer}</div>`;
                cell.style.backgroundColor = color;
            }
        }

        // Mini Calendar - Show current month
        function initMiniCalendar() {
            const container = document.getElementById('miniCalendarDays');
            const monthTitle = document.getElementById('currentMonth');
            
            const now = new Date();
            const year = now.getFullYear();
            const month = now.getMonth();
            
            // Set month title
            monthTitle.textContent = now.toLocaleDateString('en-US', { 
                month: 'long', 
                year: 'numeric' 
            });
            
            // Day headers
            ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'].forEach(day => {
                const div = document.createElement('div');
                div.textContent = day;
                div.style.fontSize = '11px';
                div.style.color = 'rgba(255,255,255,0.7)';
                container.appendChild(div);
            });
            
            // Get first day of month
            const firstDay = new Date(year, month, 1).getDay();
            const firstMonday = firstDay === 0 ? 6 : firstDay - 1;
            
            // Days in month
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            // Empty cells before first day
            for (let i = 0; i < firstMonday; i++) {
                container.appendChild(document.createElement('div'));
            }
            
            // Days of the month - simple display only
            for (let day = 1; day <= daysInMonth; day++) {
                const div = document.createElement('div');
                div.className = 'day-number';
                div.textContent = day;
                container.appendChild(div);
            }
        }

        // Event Listeners
        function setupEventListeners() {
            let weekOffset = 0;
            
            // Week navigation
            document.getElementById('prevWeekBtn').onclick = () => {
                weekOffset--;
                updateWeekTitle(weekOffset);
                updateCalendarDays(weekOffset);
            };
            
            document.getElementById('nextWeekBtn').onclick = () => {
                weekOffset++;
                updateWeekTitle(weekOffset);
                updateCalendarDays(weekOffset);
            };
            
            document.getElementById('todayBtn').onclick = () => {
                weekOffset = 0;
                updateWeekTitle(weekOffset);
                updateCalendarDays(weekOffset);
                alert('Navigated to current week');
            };
            
            
            // Create schedule
            document.getElementById('createScheduleBtn').onclick = () => {
                alert('Schedule creation would open here');
            };
            
        }

        function updateCalendarDays(weekOffset) {
            const weekData = getWeekDates(weekOffset);
            const dayHeaders = document.querySelectorAll('.day-header');
            
            // Update day headers with real dates
            weekData.weekDays.forEach((day, i) => {
                if (dayHeaders[i]) {
                    dayHeaders[i].textContent = day.short;
                    
                    // Update all cells for this day
                    const cells = document.querySelectorAll(`.calendar-cell:nth-child(${i + 2})`);
                    cells.forEach(cell => {
                        cell.dataset.day = day.short;
                        cell.dataset.date = day.date.toISOString().split('T')[0];
                    });
                }
            });
            
            // Clear and re-add bookings
            document.querySelectorAll('.calendar-cell').forEach(cell => {
                cell.classList.remove('booked');
                cell.innerHTML = '';
                cell.style.backgroundColor = '';
            });
            
            // Add fresh sample bookings for the new week
            addSampleBookings(weekData);
        }
    </script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>