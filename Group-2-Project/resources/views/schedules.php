<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claddagh | Schedules</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>
<body class="schedule-app-body">
    <?php 
    $scheduleCount = isset($schedules) ? count($schedules) : 0;
    $selectedDateParam = isset($_GET['date']) ? trim($_GET['date']) : '';
    $selectedDate = null;
    if ($selectedDateParam !== '') {
        $parsedDate = DateTime::createFromFormat('Y-m-d', $selectedDateParam);
        if ($parsedDate && $parsedDate->format('Y-m-d') === $selectedDateParam) {
            $selectedDate = $parsedDate;
        }
    }
    $baseDate = $selectedDate ?: new DateTime();
    $highlightDate = $baseDate->format('Y-m-d');
    ?>
    
    <div class="schedule-app-container">
        <!-- Sidebar -->
        <aside class="schedule-sidebar">
            <div class="sidebar-header">
                <img src="<?php echo asset('images/logo.png'); ?>" alt="Logo" class="sidebar-logo" role="button" tabindex="0" aria-label="Close menu" onclick="closeScheduleSidebar()">
            </div>
            
            <nav class="sidebar-nav">
                <div class="dropdown">
                    <button class="sidebar-nav-item active dropdown-toggle" type="button" id="sidebarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-calendar-check"></i> Schedule
                    </button>
                    <ul class="dropdown-menu sidebar-dropdown-menu" aria-labelledby="sidebarDropdown">
                        <li><a class="dropdown-item" href="<?php echo url('/home'); ?>">
                            <i class="bi bi-house-door"></i> Home
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/schedules'); ?>">
                            <i class="bi bi-calendar-check"></i> Schedules
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/about'); ?>">
                            <i class="bi bi-info-circle"></i> About
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo url('/logout'); ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a></li>
                    </ul>
                </div>
            </nav>

            <div class="volunteers-list">
                <div class="volunteer-item">
                    <div class="volunteer-avatar" style="background: linear-gradient(135deg, #FF9A6C, #FF6B6B);">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="volunteer-info">
                        <div class="volunteer-name">Roselle Ehrman</div>
                    </div>
                    <button class="volunteer-remove" type="button" aria-label="Remove volunteer" onclick="removeVolunteer(this)">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="volunteer-item">
                    <div class="volunteer-avatar" style="background: linear-gradient(135deg, #A78BFA, #EC4899);">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="volunteer-info">
                        <div class="volunteer-name">Leobrice Kulik</div>
                        <div class="volunteer-status">08:30 AM</div>
                        <div class="volunteer-message">Went to meeting</div>
                    </div>
                    <button class="volunteer-remove" type="button" aria-label="Remove volunteer" onclick="removeVolunteer(this)">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="volunteer-item">
                    <div class="volunteer-avatar" style="background: linear-gradient(135deg, #60A5FA, #3B82F6);">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="volunteer-info">
                        <div class="volunteer-name">James B</div>
                        <div class="volunteer-status">06:15 PM</div>
                        <div class="volunteer-message">Voice message 01:30</div>
                    </div>
                    <button class="volunteer-remove" type="button" aria-label="Remove volunteer" onclick="removeVolunteer(this)">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="schedule-main-content">
            <div class="schedule-header">
                <button class="navbar-toggler-custom schedule-burger d-lg-none" type="button" aria-label="Toggle menu" onclick="toggleScheduleSidebar()">
                    <i class="bi bi-list"></i>
                </button>

                <div class="week-navigation">
                    <button class="week-nav-btn" onclick="changeWeek(-1)">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <span class="week-label" id="currentWeek">
                        Week <?php echo $baseDate->format('W'); ?>
                    </span>
                    <button class="week-nav-btn" onclick="changeWeek(1)">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="schedule-header-actions">
                    <button class="btn btn-light-custom" data-bs-toggle="modal" data-bs-target="#addVolunteerModal">
                        <i class="bi bi-plus-circle"></i> Add Volunteer
                    </button>
                    <button class="btn btn-light-custom" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        Create Schedule
                    </button>
                </div>
            </div>
            

            <div class="schedule-content-grid">
                <?php if ($scheduleCount === 0): ?>
                    <div class="col-span-2 p-5 text-center" style="grid-column: 1 / -1;">
                        <div class="alert alert-info">
                            <h4><i class="bi bi-info-circle"></i> No Schedules Found</h4>
                            <p>There are currently no patrol schedules in the database.</p>
                            <p>Click the <strong>"Create Schedule"</strong> button above to add your first schedule!</p>
                            <p class="mt-3 mb-0">
                                <small>
                                    <strong>Note:</strong> If you expect to see schedules here, please check:
                                    <a href="<?php echo url('/test-db'); ?>" target="_blank">Database Connection Status</a>
                                </small>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Mobile List View -->
                <div class="mobile-schedule-list d-lg-none">
                    <?php 
                    $filteredSchedules = [];
                    if (!empty($schedules)) {
                        foreach ($schedules as $schedule) {
                            if (date('Y-m-d', strtotime($schedule->patrolDate)) === $highlightDate) {
                                $filteredSchedules[] = $schedule;
                            }
                        }
                    }
                    ?>
                    
                    <?php if (!empty($filteredSchedules)): ?>
                        <?php foreach ($filteredSchedules as $schedule): ?>
                            <?php
                                $dateDisplay = date('D, M d, Y', strtotime($schedule->patrolDate));
                            ?>
                            <div class="mobile-patrol-card" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($schedule), ENT_QUOTES, 'UTF-8'); ?>)">
                                <div class="mobile-patrol-date">
                                    <i class="bi bi-calendar3"></i> <?php echo $dateDisplay; ?>
                                </div>
                                <div class="mobile-patrol-description">
                                    <?php echo htmlspecialchars($schedule->patrolDescription ?? 'Patrol'); ?>
                                </div>
                                <div class="mobile-patrol-status">
                                    Status: <?php echo htmlspecialchars($schedule->patrol_status); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center p-4">
                            <p class="text-muted mb-1">No patrols scheduled for</p>
                            <strong><?php echo date('l, F j, Y', strtotime($highlightDate)); ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="calendar-section d-none d-lg-block">
                    <!-- Day Headers -->
                    <div class="day-headers">
                        <?php
                        $weekStart = (clone $baseDate)->modify('monday this week');
                        $dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                        
                        for ($i = 0; $i < 5; $i++):
                            $date = (clone $weekStart)->modify("+{$i} days");
                        ?>
                        <div class="day-header">
                            <div class="day-name"><?php echo $dayNames[$i] . ' ' . $date->format('d'); ?></div>
                        </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Time Slots -->
                    <div class="time-grid">
                        <div class="time-column">
                            <div class="time-slot">9:00</div>
                            <div class="time-slot">10:00</div>
                            <div class="time-slot">11:00</div>
                            <div class="time-slot">12:00</div>
                            <div class="time-slot">01:00</div>
                            <div class="time-slot">02:00</div>
                            <div class="time-slot">03:00</div> 
                            <div class="time-slot">04:00</div> 
                            <div class="time-slot">05:00</div> 
                            <div class="time-slot">06:00</div> 
                            <div class="time-slot">07:00</div>
                             <div class="time-slot">08:00</div>
                             <div class="time-slot">09:00</div>
                             <div class="time-slot">10:00</div> 
                             <div class="time-slot">11:00</div> 

                        </div>

                        <div class="days-grid">
                            <?php 
                            // Get current week's dates
                            $weekStart = (clone $baseDate)->modify('monday this week');
                            
                            // Function to calculate position based on time (9:00 AM = 0px, each hour = 60px)
                            function getEventPosition($timeStr) {
                                $time = new DateTime($timeStr);
                                $hour = (int)$time->format('H');
                                $minute = (int)$time->format('i');
                                $startHour = 9; // Calendar starts at 9:00 AM
                                
                                $hoursDiff = $hour - $startHour;
                                $position = ($hoursDiff * 60) + ($minute);
                                
                                return max(0, $position); // Don't allow negative positions
                            }
                            
                            for ($day = 0; $day < 5; $day++): 
                                $currentDate = (clone $weekStart)->modify("+{$day} days");
                                $dateStr = $currentDate->format('Y-m-d');
                            ?>
                                <div class="day-column" data-date="<?php echo $dateStr; ?>" onclick="onDayColumnClick(event, '<?php echo $dateStr; ?>')"
                                     style="cursor: crosshair;">
                                    <?php if (!empty($schedules)): ?>
                                        <?php foreach ($schedules as $schedule): ?>
                                            <?php if (date('Y-m-d', strtotime($schedule->patrolDate)) === $dateStr): ?>
                                                <?php
                                                    $positionTime = $schedule->patrolDate;
                                                    $eventTop = getEventPosition($positionTime);
                                                    
                                                    // Calculate height based on duration (default 2 hours = 120px)
                                                    $eventHeight = 120;
                                                ?>
                                                <div class="schedule-event" 
                                                     style="top: <?php echo $eventTop; ?>px; height: <?php echo $eventHeight; ?>px; cursor: pointer;" 
                                                     onclick="openEditModal(<?php echo htmlspecialchars(json_encode($schedule), ENT_QUOTES, 'UTF-8'); ?>)">
                                                    <div class="event-title"><?php echo htmlspecialchars($schedule->patrolDescription ?? 'Patrol'); ?></div>
                                                    <div class="event-status">Status: <?php echo htmlspecialchars($schedule->patrol_status); ?></div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <aside class="schedule-right-sidebar">
                    <div class="mini-calendar">
                        <div class="mini-calendar-header">
                            <button class="calendar-nav-btn" onclick="changeMonth(-1)">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <span id="currentMonth">
                                <?php 
                                $now = clone $baseDate;
                                echo $now->format('F Y'); 
                                ?>
                            </span>
                            <button class="calendar-nav-btn" onclick="changeMonth(1)">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                        <div class="mini-calendar-grid">
                            <div class="calendar-day-header">Mon</div>
                            <div class="calendar-day-header">Tue</div>
                            <div class="calendar-day-header">Wed</div>
                            <div class="calendar-day-header">Thu</div>
                            <div class="calendar-day-header">Fri</div>
                            <div class="calendar-day-header">Sat</div>
                            <div class="calendar-day-header">Sun</div>
                            
                            <?php
                            $now = clone $baseDate;
                            $currentDay = (int)$now->format('j');
                            $currentMonth = (int)$now->format('n');
                            $currentYear = (int)$now->format('Y');
                            
                            // Get first day of the month (1 = Monday, 7 = Sunday)
                            $firstDay = new DateTime("$currentYear-$currentMonth-01");
                            $startDayNum = (int)$firstDay->format('N'); // 1 (Mon) to 7 (Sun)
                            
                            // Get number of days in the month
                            $daysInMonth = (int)$firstDay->format('t');
                            
                            // Add empty cells before the first day
                            for ($i = 1; $i < $startDayNum; $i++) {
                                echo '<div class="calendar-day empty"></div>';
                            }
                            
                            // Display the days
                            for ($day = 1; $day <= $daysInMonth; $day++) {
                                $dayDate = new DateTime("$currentYear-$currentMonth-$day");
                                $dayDateStr = $dayDate->format('Y-m-d');
                                $class = $dayDateStr === $highlightDate ? 'calendar-day active' : 'calendar-day';
                                $dayUrl = url('/schedules') . '?date=' . $dayDateStr;
                                echo "<a class='$class' href='$dayUrl'>$day</a>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="upcoming-events">
                        <h3>Upcoming Events</h3>
                        <div class="event-list">
                            <?php if (!empty($schedules) && count($schedules) > 0): ?>
                                <?php $count = 0; ?>
                                <?php foreach ($schedules as $schedule): ?>
                                    <?php if ($count >= 5) break; ?>
                                    <div class="event-item">
                                        <div class="event-icon" style="background: linear-gradient(135deg, #10B981, #059669);">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div class="event-details">
                                            <div class="event-category">Transfer</div>
                                            <div class="event-name"><?php echo htmlspecialchars($schedule->patrolDescription ?? 'Patrol'); ?></div>
                                        </div>
                                        <div class="event-date"><?php echo date('d M, Y', strtotime($schedule->patrolDate)); ?></div>
                                    </div>
                                    <?php $count++; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No upcoming events</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>

    <!-- Add Volunteer Modal -->
    <div class="modal fade" id="addVolunteerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Volunteer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addVolunteerForm">
                        <div class="mb-3">
                            <label for="volunteerName" class="form-label">Volunteer Name</label>
                            <input type="text" class="form-control" id="volunteerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="volunteerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="volunteerEmail" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="addVolunteerSubmit">Add Volunteer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Patrol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">A default <strong>Regular Patrol</strong> will be created for the selected date.</p>
                    <div class="mb-3">
                        <label for="patrolDate" class="form-label fw-semibold">Patrol Date</label>
                        <input type="date" class="form-control" id="patrolDate" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSchedule()">
                        <i class="bi bi-plus-circle"></i> Create Patrol
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Patrol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editScheduleForm">
                        <div class="mb-3">
                            <label for="editPatrolDate" class="form-label">Patrol Date</label>
                            <input type="date" class="form-control" id="editPatrolDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPatrolDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" id="editPatrolDescription" placeholder="Regular Patrol">
                        </div>
                        <div class="mb-3">
                            <label for="editPatrolStatus" class="form-label">Status</label>
                            <select class="form-select" id="editPatrolStatus">
                                <option value="0">Unreleased</option>
                                <option value="1">Released</option>
                                <option value="2">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editSupervisor" class="form-label">Supervisor</label>
                            <select class="form-select" id="editSupervisor">
                                <option value="">— None —</option>
                                <?php foreach ($supervisors ?? [] as $sup): ?>
                                    <option value="<?php echo $sup->UserNr; ?>">
                                        <?php echo htmlspecialchars($sup->FirstName . ' ' . $sup->LastName); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="deleteCurrentSchedule()">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="updateSchedule()">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('patrolDate').value = today;

            const addVolunteerBtn = document.getElementById('addVolunteerSubmit');
            if (addVolunteerBtn) {
                addVolunteerBtn.addEventListener('click', addVolunteerToSidebar);
            }
        });

        function addVolunteerToSidebar() {
            const nameInput = document.getElementById('volunteerName');
            const emailInput = document.getElementById('volunteerEmail');
            const name = nameInput ? nameInput.value.trim() : '';
            const email = emailInput ? emailInput.value.trim() : '';

            if (!name || !email) {
                alert('Please enter a volunteer name and email.');
                return;
            }

            const list = document.querySelector('.volunteers-list');
            if (!list) {
                return;
            }

            const item = document.createElement('div');
            item.className = 'volunteer-item';

            const avatar = document.createElement('div');
            avatar.className = 'volunteer-avatar';
            avatar.style.background = 'linear-gradient(135deg, #60A5FA, #3B82F6)';
            avatar.innerHTML = '<i class="bi bi-person-fill"></i>';

            const info = document.createElement('div');
            info.className = 'volunteer-info';

            const nameEl = document.createElement('div');
            nameEl.className = 'volunteer-name';
            nameEl.textContent = name;

            const statusEl = document.createElement('div');
            statusEl.className = 'volunteer-status';
            statusEl.textContent = email;

            info.appendChild(nameEl);
            info.appendChild(statusEl);
            item.appendChild(avatar);
            item.appendChild(info);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'volunteer-remove';
            removeBtn.setAttribute('aria-label', 'Remove volunteer');
            removeBtn.innerHTML = '<i class="bi bi-x"></i>';
            removeBtn.addEventListener('click', function() {
                removeVolunteer(removeBtn);
            });

            item.appendChild(removeBtn);
            list.appendChild(item);

            nameInput.value = '';
            emailInput.value = '';

            const modalEl = document.getElementById('addVolunteerModal');
            if (modalEl) {
                const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modalInstance.hide();
            }
        }

        function removeVolunteer(button) {
            const item = button ? button.closest('.volunteer-item') : null;
            if (!item) {
                return;
            }
            const nameEl = item.querySelector('.volunteer-name');
            const name = nameEl ? nameEl.textContent.trim() : 'this volunteer';
            if (!confirm(`Remove ${name}?`)) {
                return;
            }
            item.remove();
        }

        function parseJsonResponse(response) {
            return response.text().then(text => {
                if (!text) {
                    return { ok: response.ok, data: null, text: '' };
                }

                try {
                    return { ok: response.ok, data: JSON.parse(text), text: '' };
                } catch (error) {
                    return { ok: false, data: null, text: text };
                }
            });
        }

        function saveSchedule() {
            const patrolDate = document.getElementById('patrolDate').value;
            if (!patrolDate) { alert('Please select a date'); return; }

            const today = new Date().toISOString().split('T')[0];
            if (patrolDate < today) {
                alert('Cannot create a patrol on a past date.'); return;
            }

            const allDates = <?php echo json_encode(array_map(fn($s) => substr((string)$s->patrolDate, 0, 10), $schedules->all())); ?>;
            if (allDates.includes(patrolDate)) {
                alert('A patrol already exists on this date.'); return;
            }

            createDefaultPatrol(patrolDate, function() {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addScheduleModal'));
                modal.hide();
            });
        }

        function createDefaultPatrol(dateStr, onSuccess) {
            fetch('/api/schedules', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    patrolDate: dateStr + ' 00:00:00',
                    patrolDescription: 'Regular Patrol',
                    SuperUserNr: null,
                    patrol_status: 0
                })
            })
            .then(res => parseJsonResponse(res))
            .then(result => {
                if (result.data && result.data.success) {
                    if (onSuccess) onSuccess();
                    location.reload();
                } else {
                    const message = result.data && result.data.message
                        ? result.data.message
                        : (result.text ? result.text : 'Unknown error');
                    alert('Error creating patrol: ' + message);
                }
            })
            .catch(err => alert('Error: ' + err.message));
        }

        // Store the currently editing schedule
        let currentEditSchedule = null;

        // Open edit modal with schedule data
        function openEditModal(schedule) {
            currentEditSchedule = schedule;

            const dateStr = (schedule.patrolDate || '').split(' ')[0];
            document.getElementById('editPatrolDate').value = dateStr;
            document.getElementById('editPatrolDescription').value = schedule.patrolDescription || '';
            document.getElementById('editPatrolStatus').value = String(schedule.patrol_status ?? 0);
            document.getElementById('editSupervisor').value = schedule.SuperUserNr ? String(schedule.SuperUserNr) : '';

            const modal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
            modal.show();
        }

        function updateSchedule() {
            if (!currentEditSchedule) { alert('No schedule selected'); return; }

            const patrolDate = document.getElementById('editPatrolDate').value;
            const patrolDescription = document.getElementById('editPatrolDescription').value;
            const patrolStatus = document.getElementById('editPatrolStatus').value;

            if (!patrolDate) { alert('Please select a date'); return; }

            const supervisorVal = document.getElementById('editSupervisor').value;

            fetch(`/api/schedules/${currentEditSchedule.patrolNr}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    patrolDate: `${patrolDate} 00:00:00`,
                    patrolDescription: patrolDescription || 'Regular Patrol',
                    SuperUserNr: supervisorVal ? parseInt(supervisorVal) : null,
                    patrol_status: parseInt(patrolStatus)
                })
            })
            .then(res => parseJsonResponse(res))
            .then(result => {
                if (result.data && result.data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editScheduleModal'));
                    modal.hide();
                    location.reload();
                } else {
                    const message = result.data && result.data.message
                        ? result.data.message
                        : (result.text ? result.text : 'Unknown error');
                    alert('Error updating patrol: ' + message);
                }
            })
            .catch(err => alert('Error: ' + err.message));
        }

        function deleteSchedule(scheduleId) {
            if (confirm('Are you sure you want to delete this schedule?')) {
                fetch(`/api/schedules/${scheduleId}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(res => parseJsonResponse(res))
                .then(result => {
                    if (result.data && result.data.success) {
                        alert('Schedule deleted successfully!');
                        location.reload();
                    } else {
                        const message = result.data && result.data.message
                            ? result.data.message
                            : (result.text ? result.text : 'Unknown error');
                        alert('Error deleting schedule: ' + message);
                    }
                })
                .catch(err => alert('Error: ' + err.message));
            }
        }

        function deleteCurrentSchedule() {
            if (!currentEditSchedule) {
                alert('No schedule selected');
                return;
            }

            if (confirm('Are you sure you want to delete this schedule?')) {
                fetch(`/api/schedules/${currentEditSchedule.patrolNr}`, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(res => parseJsonResponse(res))
                .then(result => {
                    if (result.data && result.data.success) {
                        alert('Schedule deleted successfully!');
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editScheduleModal'));
                        modal.hide();
                        location.reload();
                    } else {
                        const message = result.data && result.data.message
                            ? result.data.message
                            : (result.text ? result.text : 'Unknown error');
                        alert('Error deleting schedule: ' + message);
                    }
                })
                .catch(err => alert('Error: ' + err.message));
            }
        }

        function onDayColumnClick(e, dateStr) {
            if (e.target.closest('.schedule-event')) return;
            createDefaultPatrol(dateStr, null);
        }

        function toggleScheduleSidebar() {
            const sidebar = document.querySelector('.schedule-sidebar');
            if (!sidebar) {
                return;
            }
            sidebar.classList.toggle('active');
        }

        function closeScheduleSidebar() {
            const sidebar = document.querySelector('.schedule-sidebar');
            if (!sidebar) {
                return;
            }
            sidebar.classList.remove('active');
        }

        // Week and Month Navigation
        const baseDateValue = "<?php echo $baseDate->format('Y-m-d'); ?>";
        const today = new Date(`${baseDateValue}T00:00:00`);
        
        // Get current week number (ISO 8601)
        function getWeekNumber(date) {
            const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
            const dayNum = d.getUTCDay() || 7;
            d.setUTCDate(d.getUTCDate() + 4 - dayNum);
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
            return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        }
        
        let currentWeek = getWeekNumber(today);
        let currentMonthIndex = today.getMonth(); // Current month (0-based)
        let currentYear = today.getFullYear();
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];

        function changeWeek(direction) {
            const newDate = new Date(today);
            newDate.setDate(newDate.getDate() + (direction * 7));
            const dateParam = newDate.toISOString().slice(0, 10);
            window.location.href = `<?php echo url('/schedules'); ?>?date=${dateParam}`;
        }

        function changeMonth(direction) {
            const newDate = new Date(today);
            newDate.setMonth(newDate.getMonth() + direction);
            newDate.setDate(1);
            const dateParam = newDate.toISOString().slice(0, 10);
            window.location.href = `<?php echo url('/schedules'); ?>?date=${dateParam}`;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>

