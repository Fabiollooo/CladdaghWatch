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
    <style>

        /* ===== MAIN CALENDAR PAGE ===== */
        .cal-page-wrapper {
            min-height: 100vh;
            background: #f0f4f8;
            padding: 0 0 0;
        }

        .cal-toolbar {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 18px 28px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            flex-wrap: wrap;
            gap: 12px;
        }
        .cal-toolbar-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }
        .cal-toolbar-title:hover { color: #2563eb; }
        .cal-toolbar-title i { color: #2563eb; }
        .cal-month-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }
        .cal-month-nav .month-label {
            font-size: 1.05rem;
            font-weight: 600;
            min-width: 160px;
            text-align: center;
            color: #1e3a5f;
        }
        .cal-nav-btn {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #475569;
            transition: all 0.15s;
        }
        .cal-nav-btn:hover { background: #2563eb; color: #fff; border-color: #2563eb; }

        /* ===== FULL CALENDAR GRID ===== */
        .cal-container {
            max-width: 1100px;
            margin: 28px auto 0;
            padding: 0 16px;
        }
        .cal-grid-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            overflow: hidden;
        }
        .cal-day-headers {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background: #1e3a5f;
        }
        .cal-day-header {
            text-align: center;
            padding: 12px 4px;
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(255,255,255,0.85);
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .cal-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            border-top: 1px solid #e2e8f0;
        }
        .cal-day {
            min-height: 110px;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 8px;
            cursor: pointer;
            transition: background 0.15s;
            position: relative;
        }
        .cal-day:hover { background: #f0f7ff; }
        .cal-day.empty { background: #f8fafc; cursor: default; }
        .cal-day.today .cal-day-num {
            background: #2563eb;
            color: #fff;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cal-day.selected { background: #eff6ff; outline: 2px solid #2563eb; outline-offset: -2px; }
        .cal-day-num {
            font-size: 0.88rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cal-day.empty .cal-day-num { color: #cbd5e1; }
        .cal-patrol-chip {
            display: block;
            font-size: 0.72rem;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 5px;
            padding: 2px 6px;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 500;
            cursor: pointer;
        }
        .cal-patrol-chip.released {
            background: #dbeafe;
            color: #1e40af;
        }
        .cal-patrol-chip.unreleased {
            background: #f1f5f9;
            color: #475569;
        }
        .cal-more-badge {
            font-size: 0.68rem;
            color: #6b7280;
            padding: 1px 4px;
        }

        /* ===== DAY DETAIL PANEL ===== */
        #dayDetailPanel {
            max-width: 1100px;
            margin: 20px auto 0;
            padding: 0 16px;
            display: none;
        }
        .detail-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.07);
            overflow: hidden;
        }
        .detail-header {
            background: #1e3a5f;
            color: #fff;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .detail-header h2 { font-size: 1.1rem; font-weight: 700; margin: 0; }
        .detail-header .detail-actions { display: flex; gap: 8px; }
        .detail-body { padding: 24px; }
        .patrol-info-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
            border-left: 4px solid #2563eb;
        }
        .patrol-info-box.no-patrol {
            border-left-color: #94a3b8;
            background: #f1f5f9;
        }
        .patrol-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }
        .patrol-info-item label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }
        .patrol-info-item span {
            font-size: 0.92rem;
            color: #1e293b;
            font-weight: 500;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .status-released { background: #dbeafe; color: #1e40af; }
        .status-unreleased { background: #f1f5f9; color: #475569; }
        .status-cancelled { background: #fde8f4; color: #9d174d; }

        /* ===== VOLUNTEERS SECTION ===== */
        .volunteers-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .volunteers-section-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e3a5f;
            margin: 0;
        }
        .vol-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }
        .vol-card {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
        }
        .vol-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #fff;
            flex-shrink: 0;
        }
        .vol-avatar.supervisor { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .vol-avatar.volunteer  { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .vol-info { flex: 1; min-width: 0; }
        .vol-name { font-size: 0.88rem; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .vol-role { font-size: 0.72rem; color: #64748b; }
        .vol-remove {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 2px 4px;
            transition: color 0.15s;
        }
        .vol-remove:hover { color: #ef4444; }
        .vol-empty {
            text-align: center;
            color: #94a3b8;
            padding: 24px;
            font-size: 0.9rem;
        }
        .vol-count-badge {
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        @media (max-width: 600px) {
            .cal-day { min-height: 68px; padding: 4px; }
            .cal-day-num { font-size: 0.78rem; }
            .cal-toolbar { padding: 12px 14px; justify-content: center; }
            .cal-toolbar-title { width: 100%; justify-content: center; }
            .cal-month-nav { margin-left: 0; justify-content: center; }
            .cal-container { padding: 0 8px; }
            #dayDetailPanel { padding: 0 8px; }
        }

        /* ===== MODALS – CENTRED BOX ===== */
        /* Ensure .modal-dialog is never position:fixed — Bootstrap centres via margin:auto */
        .modal-dialog {
            margin: 1.75rem auto;
            position: relative;    /* must NOT be fixed */
            width: 100%;
        }
        .modal-content {
            border-radius: 14px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.18);
            display: flex;
            flex-direction: column;
            max-height: 88vh;
        }
        .modal-body {
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        .modal-header { flex-shrink: 0; border-bottom: 1px solid #e2e8f0; }
        .modal-footer {
            flex-shrink: 0;
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 0 0 14px 14px;
            padding: 12px 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .modal-footer .btn {
            min-height: 40px;
            font-size: 0.92rem;
        }
        @media (max-width: 575.98px) {
            .modal-dialog { margin: 0.75rem; }
            .modal-footer .btn { min-height: 44px; font-size: 1rem; flex: 1; }
        }
        /* ===== VOLUNTEER PICKER ===== */
        .vol-picker-search {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 0.875rem;
            margin-bottom: 8px;
            outline: none;
        }
        .vol-picker-search:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.12); }
        .vol-picker-list {
            max-height: 180px;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
        }
        .vol-picker-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.1s;
        }
        .vol-picker-item:last-child { border-bottom: none; }
        .vol-picker-item:hover, .vol-picker-item:focus-within { background: #eff6ff; }
        .vol-picker-item input[type=checkbox],
        .vol-picker-item input[type=radio] { width: 16px; height: 16px; accent-color: #2563eb; flex-shrink: 0; cursor: pointer; }
        .vol-picker-item label { margin: 0; font-size: 0.88rem; color: #1e293b; cursor: pointer; font-weight: 500; }
        .vol-picker-empty { padding: 16px; text-align: center; color: #94a3b8; font-size: 0.85rem; }
        .vol-picker-count { font-size: 0.78rem; color: #64748b; margin-top: 5px; }
        /* ===== VOLUNTEER LIMIT HELPER ===== */
        .limit-caption { font-size: 0.75rem; color: #64748b; margin-top: 4px; }
        /* ===== PATROL SELECTOR ===== */
        #editPatrolSelectorRow { animation: fadeIn 0.2s ease; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
        /* ===== UNSAVED BADGE ===== */
        .badge-unsaved { background: #fef3c7; color: #92400e; font-size: 0.72rem; padding: 2px 8px; border-radius: 20px; font-weight: 600; }
        /* ===== Focus visible on interactive elements ===== */
        .cal-day:focus-visible { outline: 2px solid #2563eb; outline-offset: -2px; }

        /* ===== TOAST NOTIFICATIONS ===== */
        #cwpToastContainer {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 11000;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            pointer-events: none;
        }
        .cwp-toast {
            pointer-events: all;
            min-width: 300px;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18), 0 2px 6px rgba(0,0,0,0.10);
            overflow: hidden;
            opacity: 0;
            transform: translateX(60px);
            transition: opacity 0.28s ease, transform 0.28s cubic-bezier(.22,.68,0,1.2);
            backdrop-filter: blur(4px);
        }
        .cwp-toast.cwp-toast-show {
            opacity: 1;
            transform: translateX(0);
        }
        .cwp-toast.cwp-toast-hide {
            opacity: 0;
            transform: translateX(60px);
        }
        .cwp-toast-inner {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem 0.85rem 1rem;
        }
        .cwp-toast-icon {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        .cwp-toast-body {
            flex: 1;
        }
        .cwp-toast-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.75;
            margin-bottom: 1px;
        }
        .cwp-toast-msg {
            font-size: 0.92rem;
            font-weight: 500;
            line-height: 1.35;
        }
        .cwp-toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            cursor: pointer;
            opacity: 0.55;
            padding: 4px;
            border-radius: 6px;
            transition: opacity 0.15s, background 0.15s;
            line-height: 1;
            font-size: 1rem;
        }
        .cwp-toast-close:hover { opacity: 1; background: rgba(255,255,255,0.15); }
        /* success */
        .cwp-toast-success { background: linear-gradient(135deg, #166534 0%, #15803d 100%); color: #fff; }
        .cwp-toast-success .cwp-toast-icon { background: rgba(255,255,255,0.15); color: #bbf7d0; }
        /* danger */
        .cwp-toast-danger { background: linear-gradient(135deg, #991b1b 0%, #dc2626 100%); color: #fff; }
        .cwp-toast-danger .cwp-toast-icon { background: rgba(255,255,255,0.15); color: #fecaca; }
        /* warning */
        .cwp-toast-warning { background: linear-gradient(135deg, #78350f 0%, #d97706 100%); color: #fff; }
        .cwp-toast-warning .cwp-toast-icon { background: rgba(255,255,255,0.15); color: #fde68a; }
        /* secondary / info */
        .cwp-toast-secondary { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color: #fff; }
        .cwp-toast-secondary .cwp-toast-icon { background: rgba(255,255,255,0.15); color: #bfdbfe; }
    </style>
</head>


<body class="schedule-app-body">
<?php @require resource_path('views/templates/admin/adminHeader.php');?>
<?php
    // -- Date / schedule setup -----------------------------
    $scheduleCount = isset($schedules) ? count($schedules) : 0;
    $selectedDateParam = isset($_GET['date']) ? trim($_GET['date']) : '';
    $selectedDate = null;
    if ($selectedDateParam !== '') {
        $parsedDate = DateTime::createFromFormat('Y-m-d', $selectedDateParam);
        if ($parsedDate && $parsedDate->format('Y-m-d') === $selectedDateParam) {
            $selectedDate = $parsedDate;
        }
    }
    $baseDate    = $selectedDate ?: new DateTime();
    $highlightDate = $baseDate->format('Y-m-d');

    // Load users for the patrol form pickers
    $volunteers  = \App\Models\User::where('userTypeNr', 3)->where('userEnabled', 1)->orderBy('FirstName')->get();
    $supervisors = \App\Models\User::where('userTypeNr', 4)->where('userEnabled', 1)->orderBy('FirstName')->get();
    $volunteersJson  = json_encode($volunteers->map(fn($u) => [
        'id'   => $u->UserNr,
        'name' => trim(($u->FirstName ?? '') . ' ' . ($u->LastName ?? ''))
    ])->values()->all(), JSON_HEX_TAG | JSON_HEX_QUOT);


    $supervisorsJson = json_encode($supervisors->map(fn($u) => [
        'id'   => $u->UserNr,
        'name' => trim(($u->FirstName ?? '') . ' ' . ($u->LastName ?? ''))
    ])->values()->all(), JSON_HEX_TAG | JSON_HEX_QUOT);

    // Build a lookup: date-string => [ schedule, … ]
    $schedulesByDate = [];
    $schedulesList = $schedules ? $schedules->all() : [];

    foreach ($schedulesList as $s) {
        $d = is_string($s->patrolDate)
                ? substr($s->patrolDate, 0, 10)
                : $s->patrolDate->format('Y-m-d');
        $schedulesByDate[$d][] = $s;
    }

    // Calendar month being displayed
    $calYear  = (int)$baseDate->format('Y');
    $calMonth = (int)$baseDate->format('n');
    $firstDay = new DateTime("$calYear-$calMonth-01");
    $daysInMonth = (int)$firstDay->format('t');
    $startDow    = (int)$firstDay->format('N'); // 1=Mon … 7=Sun
    $todayStr    = (new DateTime())->format('Y-m-d');

    // Status label helper
    function statusLabel($v) {
        if ($v == 1) return ['released',   'Released'];
        if ($v == 2) return ['cancelled',  'Cancelled'];
        return        ['unreleased', 'Not Released'];
    }
?>

<div class="cal-page-wrapper">

    <!---- Toolbar ---------------------------- -->
    <div class="cal-toolbar">
        <div class="cal-toolbar-title" onclick="goToToday()" title="Go to today">
            <i class="bi bi-calendar-week"></i>
            Patrol Schedule
        </div>

        <div class="cal-month-nav">
            <button class="cal-nav-btn" onclick="changeMonth(-1)" title="Previous month">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="cal-nav-btn" onclick="goToToday()" title="Today" style="font-size:0.72rem;font-weight:700;width:auto;padding:0 10px;">Today</button>
            <span class="month-label">
                <?php echo $firstDay->format('F Y'); ?>
            </span>
            <button class="cal-nav-btn" onclick="changeMonth(1)" title="Next month">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <button class="btn btn-primary btn-sm px-3"
                data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="bi bi-plus-lg me-1"></i> Create Patrol
        </button>
    </div>

    <!----- Full Calendar Grid ------------------>
    <div class="cal-container">
        <div class="cal-grid-card">

            <!-- Day-of-week headers -->
            <div class="cal-day-headers">
                <?php foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $h): ?>
                    <div class="cal-day-header"><?php echo $h; ?></div>
                <?php endforeach; ?>
            </div>

            <!-- Day cells -->
            <div class="cal-days">
                <?php
                // Empty leading cells
                for ($e = 1; $e < $startDow; $e++):
                ?>
                    <div class="cal-day empty"><div class="cal-day-num"></div></div>
                <?php endfor; ?>

                <?php for ($day = 1; $day <= $daysInMonth; $day++):
                    $dayDate = new DateTime("$calYear-$calMonth-$day");
                    $dayStr  = $dayDate->format('Y-m-d');
                    $isToday    = ($dayStr === $todayStr);
                    $isSelected = ($dayStr === $highlightDate);
                    $hasPatrols = !empty($schedulesByDate[$dayStr]);

                    $classes = 'cal-day';
                    if ($isToday)    $classes .= ' today';
                    if ($isSelected) $classes .= ' selected';
                ?>
                    <div class="<?php echo $classes; ?>"
                         data-date="<?php echo $dayStr; ?>"
                         onclick="selectDay('<?php echo $dayStr; ?>')">
                        <div class="cal-day-num"><?php echo $day; ?></div>

                        <?php if ($hasPatrols):
                            $shown = 0;
                            foreach ($schedulesByDate[$dayStr] as $ps):
                                if ($shown >= 2) break;
                                [$stClass] = statusLabel($ps->patrol_status);
                                $label = $ps->patrolDescription ? htmlspecialchars(substr($ps->patrolDescription,0,20)) : 'Patrol';
                                echo "<span class='cal-patrol-chip $stClass' title='$label'>$label</span>";
                                $shown++;
                            endforeach;
                            $extra = count($schedulesByDate[$dayStr]) - $shown;
                            if ($extra > 0) echo "<span class='cal-more-badge'>+$extra more</span>";
                        endif; ?>
                    </div>
                <?php endfor; ?>
            </div><!-- /.cal-days -->
        </div><!-- /.cal-grid-card -->

        <!--- Legend ------------------>
        <div class="d-flex gap-3 mt-3 ps-1" style="font-size:0.8rem; color:#475569;">
            <span><span class="cal-patrol-chip released d-inline-block me-1">&#9679;</span>Released</span>
            <span><span class="cal-patrol-chip unreleased d-inline-block me-1">&#9679;</span>Not Released</span>
            <span><span class="cal-patrol-chip cancelled d-inline-block me-1" style="background:#fee2e2;color:#991b1b;">&#9679;</span>Cancelled</span>
        </div>
    </div>

    <!---- Day Detail Panel ------------------> 
    <div id="dayDetailPanel">
        <div class="detail-card">
            <div class="detail-header">
                <h2 id="detailHeading"><i class="bi bi-calendar3 me-2"></i><span id="detailDateLabel"></span></h2>
                <div class="detail-actions">
                    <button class="btn btn-sm btn-warning" id="detailEditBtn"
                            onclick="openEditForDate(selectedDateStr)"
                            title="Edit patrol on this date" style="display:none;">
                        <i class="bi bi-pencil me-1"></i> Edit Patrol
                    </button>
                    <button class="btn btn-sm btn-outline-light" id="detailCreateBtn"
                            onclick="openCreateForDate()"
                            title="Add patrol on this date">
                        <i class="bi bi-plus-lg me-1"></i> Add Patrol
                    </button>
                    <button class="btn btn-sm btn-outline-light" onclick="closeDetail()" title="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <div class="detail-body" id="detailBody">
                <!-- Injected by JS -->
            </div>
        </div>
    </div>

</div>

<?php
// Encode all schedule data for JS use
$schedulesJson = json_encode($schedules ? $schedules->values()->all() : [], JSON_HEX_TAG | JSON_HEX_QUOT);
?>


<!---- Add Patrol Modal ------------------>
<div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="addModalTitle">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:580px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalTitle">
                    <i class="bi bi-plus-circle me-2"></i>Add Patrol
                    <span class="fw-normal text-primary ms-1" id="addModalDateLabel"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <!-- Description -->
                <div class="mb-3">
                    <label for="patrolDescription" class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" id="patrolDescription" rows="2" placeholder="Optional patrol notes…"></textarea>
                </div>

                <!-- Volunteer limit -->
                <div class="mb-3">
                    <label for="patrolLimit" class="form-label fw-semibold">Volunteer Limit</label>
                    <input type="number" class="form-control" id="patrolLimit" min="0" placeholder="e.g. 8" style="max-width:140px;">
                    <div class="limit-caption"><i class="bi bi-device-hdd me-1"></i>Stored locally on this device only.</div>
                </div>

                <!-- Supervisor picker -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Supervisor</label>
                    <div class="vol-picker-list" id="supervisorPickerList" style="max-height:140px;">
                        <?php if ($supervisors->isEmpty()): ?>
                            <div class="vol-picker-empty">No supervisors found.</div>
                        <?php else: ?>
                            <div class="vol-picker-item">
                                <input type="radio" name="supervisorPick" id="superNone" value="" checked>
                                <label for="superNone" class="text-secondary fst-italic">None assigned</label>
                            </div>
                            <?php foreach ($supervisors as $sup): ?>
                                <?php $supId = htmlspecialchars($sup->UserNr); ?>
                                <?php $supName = htmlspecialchars(trim(($sup->FirstName ?? '') . ' ' . ($sup->LastName ?? ''))); ?>
                                <div class="vol-picker-item">
                                    <input type="radio" name="supervisorPick" id="sup_<?php echo $supId; ?>" value="<?php echo $supId; ?>">
                                    <label for="sup_<?php echo $supId; ?>"><?php echo $supName; ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Volunteer picker -->
                <div class="mb-2">
                    <label class="form-label fw-semibold">Volunteers</label>
                    <input type="text" class="vol-picker-search" id="volSearchInput" placeholder="Search volunteers…" oninput="filterVolPicker()" autocomplete="off">
                    <div class="vol-picker-list" id="volPickerList">
                        <?php if ($volunteers->isEmpty()): ?>
                            <div class="vol-picker-empty">No volunteers found in the database.</div>
                        <?php else: ?>
                            <?php foreach ($volunteers as $vol): ?>
                                <?php $volId = htmlspecialchars($vol->UserNr); ?>
                                <?php $volName = htmlspecialchars(trim(($vol->FirstName ?? '') . ' ' . ($vol->LastName ?? ''))); ?>
                                <div class="vol-picker-item" data-name="<?php echo strtolower($volName); ?>">
                                    <input type="checkbox" id="vol_<?php echo $volId; ?>" value="<?php echo $volId; ?>" class="vol-checkbox">
                                    <label for="vol_<?php echo $volId; ?>"><?php echo $volName; ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="vol-picker-count" id="volPickerCount">0 selected</div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="addPatrolSubmitBtn" onclick="saveSchedule()">
                    <i class="bi bi-check-lg me-1"></i> Add Patrol
                </button>
            </div>
        </div>
    </div>
</div>

<!---- Edit Patrol Modal ------------------>
<div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:580px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle"><i class="bi bi-pencil-square me-2"></i>Edit Patrol <span id="editModalDateLabel" class="fw-normal text-primary ms-1"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Patrol selector (shown only when multiple patrols on same date) -->
                <div class="mb-3" id="editPatrolSelectorRow" style="display:none;">
                    <label for="editPatrolSelector" class="form-label fw-semibold">Multiple patrols on this date — select one to edit:</label>
                    <select class="form-select" id="editPatrolSelector" onchange="switchEditPatrol()"></select>
                </div>
                
                <!-- Description -->
                <div class="mb-3">
                    <label for="editPatrolDescription" class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" id="editPatrolDescription" rows="2"></textarea>
                </div>

                <!-- Volunteer limit -->
                <div class="mb-3">
                    <label for="editPatrolLimit" class="form-label fw-semibold">Volunteer Limit</label>
                    <input type="number" class="form-control" id="editPatrolLimit" min="0" placeholder="e.g. 8" style="max-width:140px;">
                    <div class="limit-caption"><i class="bi bi-device-hdd me-1"></i>Stored locally on this device only.</div>
                </div>

                <!-- Supervisor picker -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Supervisor</label>
                    <div class="vol-picker-list" id="editSupervisorPickerList" style="max-height:140px;">
                        <div class="vol-picker-item">
                            <input type="radio" name="editSupervisorPick" id="editSuperNone" value="" checked>
                            <label for="editSuperNone" class="text-secondary fst-italic">None assigned</label>
                        </div>
                        <?php foreach ($supervisors as $sup): ?>
                            <?php $supId = htmlspecialchars($sup->UserNr); ?>
                            <?php $supName = htmlspecialchars(trim(($sup->FirstName ?? '') . ' ' . ($sup->LastName ?? ''))); ?>
                            <div class="vol-picker-item">
                                <input type="radio" name="editSupervisorPick" id="editSup_<?php echo $supId; ?>" value="<?php echo $supId; ?>">
                                <label for="editSup_<?php echo $supId; ?>"><?php echo $supName; ?></label>
                            </div>
                        <?php endforeach; ?>
                        <?php if ($supervisors->isEmpty()): ?>
                            <div class="vol-picker-empty text-muted small px-2 pb-1">No supervisors found in database.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="editPatrolStatus" class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="editPatrolStatus">
                        <option value="0">Not Released</option>
                        <option value="1">Released</option>
                        <option value="2">Cancelled</option>
                    </select>
                </div>

                <!-- Volunteer picker for edit -->
                <div class="mb-3">
                    <label class="form-label fw-semibold d-flex align-items-center gap-2">
                        Patrol Members
                        <span class="vol-count-badge" id="editVolPickerCount">0 selected</span>
                    </label>
                    <input type="text" class="vol-picker-search" id="editVolSearchInput"
                           placeholder="Search members…" oninput="filterEditVolPicker()" autocomplete="off">
                    <div class="vol-picker-list" id="editVolPickerList">
                        <?php if ($volunteers->isEmpty()): ?>
                            <div class="vol-picker-empty">No volunteers found in the database.</div>
                        <?php else: ?>
                            <?php foreach ($volunteers as $vol): ?>
                                <?php $volId   = htmlspecialchars($vol->UserNr); ?>
                                <?php $volName = htmlspecialchars(trim(($vol->FirstName ?? '') . ' ' . ($vol->LastName ?? ''))); ?>
                                <div class="vol-picker-item" data-edit-name="<?php echo strtolower($volName); ?>">
                                    <input type="checkbox" id="edit_vol_<?php echo $volId; ?>" value="<?php echo $volId; ?>" class="edit-vol-checkbox">
                                    <label for="edit_vol_<?php echo $volId; ?>"><?php echo $volName; ?></label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Delete confirmation (hidden by default) -->
                <div id="deleteConfirmSection" class="alert alert-danger mt-3" style="display:none;">
                    <strong><i class="bi bi-exclamation-triangle me-1"></i>Confirm Delete</strong>
                    <p class="mb-2 mt-1 small">This will remove the patrol. This action cannot be undone.</p>
                    <button class="btn btn-danger btn-sm me-2" onclick="confirmDeletePatrol()">Yes, Delete</button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="hideDeleteConfirm()">Cancel</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="showDeleteConfirm()">
                    <i class="bi bi-trash me-1"></i> Delete Patrol
                </button>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateSchedule()">
                        <i class="bi bi-check-lg me-1"></i> Update
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!---- Add Volunteer to Patrol Modal ------------------>
<div class="modal fade" id="addVolunteerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add Volunteer to Patrol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Patrol: <strong id="addVolPatrolLabel">—</strong>
                </p>
                <form id="addVolunteerForm">
                    <div class="mb-3">
                        <label for="volunteerName" class="form-label">Volunteer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="volunteerName" required placeholder="Full name">
                    </div>
                    <div class="mb-3">
                        <label for="volunteerRole" class="form-label">Role</label>
                        <select class="form-select" id="volunteerRole">
                            <option value="volunteer">Patrol Volunteer</option>
                            <option value="supervisor">Patrol Supervisor (SUPER)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="volunteerPhone" class="form-label">Phone / Contact</label>
                        <input type="text" class="form-control" id="volunteerPhone" placeholder="Optional">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addVolunteerToPatrol()">
                    <i class="bi bi-check-lg me-1"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // -- Data from server -----------------------------
    const allSchedules = <?php echo $schedulesJson; ?>;
    const baseDateValue = "<?php echo $baseDate->format('Y-m-d'); ?>";
    const schedulesBaseUrl = "<?php echo url('/adminSchedules'); ?>";

    // Initialize volunteer lists from database
    const volunteerLists = <?php echo json_encode($patrolVolunteers ?? [], JSON_HEX_TAG | JSON_HEX_QUOT); ?>;

    // -- User lists from PHP ----------------------------
    const allVolunteers  = <?php echo $volunteersJson; ?>;
    const allSupervisors = <?php echo $supervisorsJson; ?>;

    // -- Volunteer picker filter ----------------------------
    function filterVolPicker() {
        const q = (document.getElementById('volSearchInput')?.value || '').trim().toLowerCase();
        document.querySelectorAll('#volPickerList .vol-picker-item').forEach(item => {
            const name = item.dataset.name || '';
            item.style.display = (!q || name.includes(q)) ? '' : 'none';
        });
    }

    function filterEditVolPicker() {
        const q = (document.getElementById('editVolSearchInput')?.value || '').trim().toLowerCase();
        document.querySelectorAll('#editVolPickerList .vol-picker-item').forEach(item => {
            const name = item.dataset.editName || '';
            item.style.display = (!q || name.includes(q)) ? '' : 'none';
        });
    }

    function updateEditVolCount() {
        const checked = document.querySelectorAll('#editVolPickerList .edit-vol-checkbox:checked').length;
        const el = document.getElementById('editVolPickerCount');
        if (el) el.textContent = checked === 0 ? '0 selected' : `${checked} selected`;
    }

    function bindEditVolCheckboxes() {
        document.querySelectorAll('#editVolPickerList .edit-vol-checkbox').forEach(cb => {
            cb.addEventListener('change', updateEditVolCount);
        });
    }

    function updateVolCount() {
        const checked = document.querySelectorAll('#volPickerList .vol-checkbox:checked').length;
        const el = document.getElementById('volPickerCount');
        let text = checked + ' volunteer' + (checked === 1 ? '' : 's') + ' selected';
        if (el) el.textContent = text;
        updateCheckboxStates();
    }

    function updateCheckboxStates() {
        const limitInput = document.getElementById('patrolLimit');
        const limit = limitInput.value ? parseInt(limitInput.value) : null;
        const checkedBoxes = document.querySelectorAll('#volPickerList .vol-checkbox:checked');
        const checked = checkedBoxes.length;
        const allBoxes = document.querySelectorAll('#volPickerList .vol-checkbox');

        // Remove extra checked boxes if over limit
        if (limit !== null && checked > limit) {
            let remove = checked - limit;
            for (let i = checkedBoxes.length - 1; i >= 0 && remove > 0; i--) {
                checkedBoxes[i].checked = false;
                remove--;
            }
            updateVolCount();
            return;
        }

        // Disable boxes when limit is reached
        allBoxes.forEach(cb => {
            if (limit !== null && checked >= limit && !cb.checked) {
                cb.disabled = true;
                cb.parentElement.style.opacity = '0.5';
                cb.parentElement.style.cursor = 'not-allowed';
            } else {
                cb.disabled = false;
                cb.parentElement.style.opacity = '1';
                cb.parentElement.style.cursor = 'auto';
            }
        });
    }

    // Attach live count update to all checkboxes after DOM ready
    function bindVolCheckboxes() {
        document.querySelectorAll('#volPickerList .vol-checkbox').forEach(cb => {
            cb.addEventListener('change', updateVolCount);
        });
        
        // Listen for limit input changes
        const limitInput = document.getElementById('patrolLimit');
        if (limitInput) {
            limitInput.addEventListener('input', updateCheckboxStates);
        }
    }

    // Reset the Add Patrol form to a clean state
    function resetAddForm(dateStr) {
        document.getElementById('patrolDescription').value = '';
        document.getElementById('patrolLimit').value = '';
        // Clear volunteer selections
        document.querySelectorAll('#volPickerList .vol-checkbox').forEach(cb => cb.checked = false);
        updateVolCount();
        // Clear search
        const search = document.getElementById('volSearchInput');
        if (search) { search.value = ''; filterVolPicker(); }
        // Reset supervisor to none
        const noneRadio = document.getElementById('superNone');
        if (noneRadio) noneRadio.checked = true;
        // Load localStorage limit
        if (dateStr) {
            const lsLimit = localStorage.getItem(`patrolLimit:${dateStr}`);
            document.getElementById('patrolLimit').value = lsLimit !== null ? lsLimit : '';
        }
        // Update checkbox states after resetting
        updateCheckboxStates();
    }

    // -- Init ----------------------------------------------------
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];
        // Set default date label for toolbar "Create Patrol" button
        document.getElementById('addModalDateLabel').textContent = ' — ' + formatDate(today);
        bindVolCheckboxes();
        updateVolCount();  

        // Bind edit-modal vol checkboxes
        bindEditVolCheckboxes();

        // Make calendar day cells keyboard accessible
        document.querySelectorAll('.cal-day:not(.empty)').forEach(cell => {
            cell.setAttribute('tabindex', '0');
            cell.setAttribute('role', 'button');
            cell.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectDay(cell.dataset.date);
                }
            });
        });

        // Escape closes modals (Bootstrap handles this natively)
        // Restore scroll position hint if URL has date
        const urlDate = new URLSearchParams(window.location.search).get('date');
        if (urlDate) {
            const cell = document.querySelector(`.cal-day[data-date="${urlDate}"]`);
            if (cell) cell.classList.add('selected');
        }
    });

    // -- Format date for modal titles ---------------------------
    function formatDate(dateStr) {
        const d = new Date(`${dateStr}T00:00:00`);
        return d.toLocaleDateString('en-IE', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
    }

    // -- Calendar navigation (endless, no page reload) -----------
    const _initDate = new Date(`${baseDateValue}T00:00:00`);
    let calViewYear  = _initDate.getFullYear();
    let calViewMonth = _initDate.getMonth() + 1; // 1-based

    function changeMonth(dir) {
        calViewMonth += dir;
        if (calViewMonth > 12) { calViewMonth = 1;  calViewYear++; }
        if (calViewMonth < 1)  { calViewMonth = 12; calViewYear--; }
        renderCalendar(calViewYear, calViewMonth);
    }

    function goToToday() {
        const today = new Date();
        calViewYear  = today.getFullYear();
        calViewMonth = today.getMonth() + 1;
        renderCalendar(calViewYear, calViewMonth);
    }

    function renderCalendar(year, month) {
        const todayStr   = new Date().toISOString().slice(0, 10);
        const firstDate  = new Date(year, month - 1, 1);
        const daysInMth  = new Date(year, month, 0).getDate();

        // getDay(): 0=Sun…6=Sat  →  convert to Mon-based 1…7
        let startDow = firstDate.getDay(); // 0=Sun
        startDow = startDow === 0 ? 7 : startDow; // 7=Sun

        // Build schedule chips lookup for this month
        const monthPrefix = `${String(year)}-${String(month).padStart(2,'0')}-`;
        const byDate = {};
        allSchedules.forEach(s => {
            const d = (s.patrolDate || '').slice(0, 10);
            if (d.startsWith(monthPrefix)) {
                if (!byDate[d]) byDate[d] = [];
                byDate[d].push(s);
            }
        });

        // Status chip helper
        function chipClass(status) {
            if (status == 1) return 'released';
            if (status == 2) return 'cancelled';
            return 'unreleased';
        }

        let html = '';

        // Leading empty cells
        for (let e = 1; e < startDow; e++) {
            html += `<div class="cal-day empty"><div class="cal-day-num"></div></div>`;
        }

        for (let day = 1; day <= daysInMth; day++) {
            const dayStr = `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            const isToday    = dayStr === todayStr;
            const isSelected = dayStr === selectedDateStr;
            let cls = 'cal-day';
            if (isToday)    cls += ' today';
            if (isSelected) cls += ' selected';

            const dayPatrols = byDate[dayStr] || [];
            let chips = '';
            if (dayPatrols.length) {
                const shown = dayPatrols.slice(0, 2);
                shown.forEach(ps => {
                    const lbl = escHtml((ps.patrolDescription || 'Patrol').slice(0, 20));
                    const cc  = chipClass(ps.patrol_status);
                    chips += `<span class="cal-patrol-chip ${cc}" title="${lbl}">${lbl}</span>`;
                });
                const extra = dayPatrols.length - shown.length;
                if (extra > 0) chips += `<span class="cal-more-badge">+${extra} more</span>`;
            }

            html += `<div class="${cls}" data-date="${dayStr}" onclick="selectDay('${dayStr}')">
                <div class="cal-day-num">${day}</div>${chips}</div>`;
        }

        document.querySelector('.cal-days').innerHTML = html;

        // Update month label
        const label = firstDate.toLocaleDateString('en-IE', { month: 'long', year: 'numeric' });
        document.querySelector('.month-label').textContent = label;

        // Re-bind keyboard nav on new cells
        document.querySelectorAll('.cal-day:not(.empty)').forEach(cell => {
            cell.setAttribute('tabindex', '0');
            cell.setAttribute('role', 'button');
            cell.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectDay(cell.dataset.date);
                }
            });
        });
    }

    // -- Day selection: highlight day + show detail panel with buttons --
    let selectedDateStr = null;
    let editPatrolPool  = [];   // holds multiple patrols for same date

    function selectDay(dateStr) {
        document.querySelectorAll('.cal-day').forEach(el => el.classList.remove('selected'));
        const cell = document.querySelector(`.cal-day[data-date="${dateStr}"]`);
        if (cell) cell.classList.add('selected');
        selectedDateStr = dateStr;
        renderDetailPanel(dateStr, true);
    }

    function openEditForDate(dateStr) {
        const dayPatrols = allSchedules.filter(s => (s.patrolDate || '').slice(0, 10) === dateStr);
        if (dayPatrols.length === 0) {
            openCreateForDate();
            return;
        }
        editPatrolPool = dayPatrols;
        if (dayPatrols.length > 1) {
            const selector = document.getElementById('editPatrolSelector');
            selector.innerHTML = dayPatrols.map((p, i) =>
                `<option value="${i}">Patrol #${p.patrolNr} — ${escHtml(p.patrolDescription ? p.patrolDescription.slice(0,30) : '')}</option>`
            ).join('');
            document.getElementById('editPatrolSelectorRow').style.display = '';
        } else {
            document.getElementById('editPatrolSelectorRow').style.display = 'none';
        }
        try { fillEditModal(dayPatrols[0], dateStr); } catch(e) { console.error(e); }
        bootstrap.Modal.getOrCreateInstance(document.getElementById('editScheduleModal')).show();
    }

    function switchEditPatrol() {
        const idx = parseInt(document.getElementById('editPatrolSelector').value, 10);
        if (editPatrolPool[idx]) fillEditModal(editPatrolPool[idx], selectedDateStr);
    }

    function fillEditModal(patrol, dateStr) {
        currentEditSchedule = patrol;
        document.getElementById('editPatrolDescription').value = patrol.patrolDescription || '';

        // Set status
        const statusSel = document.getElementById('editPatrolStatus');
        if (statusSel) statusSel.value = String(patrol.patrol_status ?? 0);

        // Set supervisor radio
        const supId = patrol.SuperUserNr || '';
        const supRadio = supId ? document.getElementById('editSup_' + supId) : null;
        if (supRadio) {
            supRadio.checked = true;
        } else {
            const noneRadio = document.getElementById('editSuperNone');
            if (noneRadio) noneRadio.checked = true;
        }

        // Clear all volunteer checkboxes & reset count
        document.querySelectorAll('#editVolPickerList .edit-vol-checkbox').forEach(cb => cb.checked = false);
        updateEditVolCount();
        // Clear search
        const esearch = document.getElementById('editVolSearchInput');
        if (esearch) { esearch.value = ''; filterEditVolPicker(); }

        document.getElementById('editModalDateLabel').textContent = formatDate((patrol.patrolDate || dateStr || '').slice(0, 10));
        
        // Load localStorage limit (keyed by date only - client-side only)
        const lsLimit = localStorage.getItem(`patrolLimit:${dateStr}`);
        document.getElementById('editPatrolLimit').value = lsLimit !== null ? lsLimit : '';
        
        // Hide delete confirm
        document.getElementById('deleteConfirmSection').style.display = 'none';
        // Hide selector row if not needed
        if (editPatrolPool.length <= 1) {
            document.getElementById('editPatrolSelectorRow').style.display = 'none';
        }
        
        // Update checkbox states based on loaded limit
        setTimeout(() => updateCheckboxStates(), 50);
    }

    // -- Open create modal pre-filled with selected date -------------
    function openCreateForDate() {
        const dateStr = selectedDateStr || new Date().toISOString().split('T')[0];
        resetAddForm(dateStr);
        document.getElementById('addModalDateLabel').textContent = ' — ' + formatDate(dateStr);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('addScheduleModal')).show();
    }

    function closeDetail() {
        document.getElementById('dayDetailPanel').style.display = 'none';
        document.querySelectorAll('.cal-day').forEach(el => el.classList.remove('selected'));
        selectedDateStr = null;
    }

    // -- Delete confirmation flow -------------------------------------
    function showDeleteConfirm() {
        document.getElementById('deleteConfirmSection').style.display = '';
    }
    function hideDeleteConfirm() {
        document.getElementById('deleteConfirmSection').style.display = 'none';
    }
    function confirmDeletePatrol() {
        deleteCurrentSchedule();
    }

    // -- Render the day detail panel ------------------------------
    function renderDetailPanel(dateStr, scroll) {
        const panel = document.getElementById('dayDetailPanel');
        const heading = document.getElementById('detailDateLabel');
        const body = document.getElementById('detailBody');

        const d = new Date(`${dateStr}T00:00:00`);
        heading.textContent = d.toLocaleDateString('en-IE', {weekday:'long', year:'numeric', month:'long', day:'numeric'});

        // Find patrols for this date
        const dayPatrols = allSchedules.filter(s => {
            const pd = (s.patrolDate || '').slice(0, 10);
            return pd === dateStr;
        });

        // Show/hide Edit & Add Patrol buttons based on patrols present
        const editBtn = document.getElementById('detailEditBtn');
        const createBtn = document.getElementById('detailCreateBtn');
        if (editBtn)   editBtn.style.display   = dayPatrols.length > 0 ? '' : 'none';
        if (createBtn) createBtn.style.display = dayPatrols.length > 0 ? 'none' : '';

        let html = '';

        if (dayPatrols.length === 0) {
            html = `
                <div class="patrol-info-box no-patrol">
                    <div class="d-flex align-items-center gap-2 text-muted">
                        <i class="bi bi-calendar-x fs-4"></i>
                        <div>
                            <div class="fw-semibold">No patrol scheduled for this date.</div>
                            <div class="small">Click <strong>Add Patrol</strong> above to create one.</div>
                        </div>
                    </div>
                </div>`;
        } else {
            dayPatrols.forEach(patrol => {
                const stMap = {'0':'unreleased', '1':'released', '2':'cancelled'};
                const stLabel = {'0':'Not Released', '1':'Released', '2':'Cancelled'};
                const stKey = String(patrol.patrol_status ?? 0);
                const desc = patrol.patrolDescription || '—';
                const superNr = patrol.SuperUserNr || null;
                const patrolNr = patrol.patrolNr;

                // Volunteers for this patrol
                const vols = volunteerLists[patrolNr] || [];
                const volCount = vols.length;

                html += `
                <div class="patrol-info-box mb-4" id="patrolBox_${patrolNr}">
                    <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                        <div>
                            <span class="fw-bold fs-6"><i class="bi bi-shield-check me-1 text-primary"></i>Patrol #${patrolNr}</span>
                            <span class="status-badge status-${stMap[stKey]} ms-2">${stLabel[stKey]}</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(${JSON.stringify(patrol).replace(/"/g, '&quot;')})">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                        </div>
                    </div>

                    <div class="patrol-info-grid mt-2">
                        <div class="patrol-info-item">
                            <label><i class="bi bi-card-text me-1"></i>Description</label>
                            <span>${escHtml(desc)}</span>
                        </div>
                        <div class="patrol-info-item">
                            <label><i class="bi bi-person-badge me-1"></i>Supervisor ID</label>
                            <span>${superNr ? '#' + superNr : 'Not assigned'}</span>
                        </div>
                    </div>
                </div>

                <!-- Volunteers for this patrol -->
                <div class="mb-4">
                    <div class="volunteers-section-header">
                        <h3><i class="bi bi-people me-2 text-primary"></i>Patrol Team
                            <span class="vol-count-badge ms-2" id="volCount_${patrolNr}">${volCount} volunteer${volCount !== 1 ? 's' : ''}</span>
                        </h3>
                        <button class="btn btn-sm btn-primary" onclick="openAddVolunteer(${patrolNr}, '${escHtml(patrol.patrolDate ? patrol.patrolDate.slice(0,10) : dateStr)}')">
                            <i class="bi bi-person-plus me-1"></i> Add Volunteer
                        </button>
                    </div>
                    <div class="vol-grid" id="volGrid_${patrolNr}">
                        ${renderVolunteerCards(patrolNr, vols)}
                    </div>
                </div>`;
            });
        }

        body.innerHTML = html;
        panel.style.display = 'block';
        if (scroll) {
            setTimeout(() => panel.scrollIntoView({behavior: 'smooth', block: 'start'}), 80);
        }
    }

    function renderVolunteerCards(patrolNr, vols) {
        if (!vols.length) {
            return `<div class="vol-empty"><i class="bi bi-people fs-3 d-block mb-2"></i>No volunteers assigned yet.</div>`;
        }
        return vols.map((v, i) => `
            <div class="vol-card">
                <div class="vol-avatar ${v.role === 'supervisor' ? 'supervisor' : 'volunteer'}">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div class="vol-info">
                    <div class="vol-name">${escHtml(v.name)}</div>
                    <div class="vol-role">${v.role === 'supervisor' ? 'Supervisor' : 'Volunteer'}${v.phone ? ' · ' + escHtml(v.phone) : ''}</div>
                </div>
                <button class="vol-remove" title="Remove" onclick="removeVolunteer(${patrolNr}, ${i})">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>`).join('');
    }

    // -- Volunteer management (client-side for now) --------------------
    let currentVolPatrolNr = null;

    function openAddVolunteer(patrolNr, dateLabel) {
        currentVolPatrolNr = patrolNr;
        document.getElementById('addVolPatrolLabel').textContent = `#${patrolNr} — ${dateLabel}`;
        document.getElementById('volunteerName').value = '';
        document.getElementById('volunteerPhone').value = '';
        document.getElementById('volunteerRole').value = 'volunteer';
        const m = new bootstrap.Modal(document.getElementById('addVolunteerModal'));
        m.show();
    }

    function addVolunteerToPatrol() {
        const name  = document.getElementById('volunteerName').value.trim();
        const role  = document.getElementById('volunteerRole').value;
        const phone = document.getElementById('volunteerPhone').value.trim();
        if (!name) { alert('Please enter a volunteer name.'); return; }

        if (!volunteerLists[currentVolPatrolNr]) volunteerLists[currentVolPatrolNr] = [];
        volunteerLists[currentVolPatrolNr].push({ name, role, phone });

        // Refresh the volunteer section in the panel
        const grid = document.getElementById(`volGrid_${currentVolPatrolNr}`);
        const badge = document.getElementById(`volCount_${currentVolPatrolNr}`);
        if (grid) {
            const vols = volunteerLists[currentVolPatrolNr];
            grid.innerHTML = renderVolunteerCards(currentVolPatrolNr, vols);
            if (badge) badge.textContent = vols.length + ' volunteer' + (vols.length !== 1 ? 's' : '');
        }

        bootstrap.Modal.getInstance(document.getElementById('addVolunteerModal')).hide();
    }

    function removeVolunteer(patrolNr, idx) {
        if (!volunteerLists[patrolNr]) return;
        volunteerLists[patrolNr].splice(idx, 1);
        const grid  = document.getElementById(`volGrid_${patrolNr}`);
        const badge = document.getElementById(`volCount_${patrolNr}`);
        if (grid) {
            grid.innerHTML = renderVolunteerCards(patrolNr, volunteerLists[patrolNr]);
            if (badge) { const c = volunteerLists[patrolNr].length; badge.textContent = c + ' volunteer' + (c !== 1 ? 's' : ''); }
        }
    }

    // -- Patrol CRUD ----------------------------------------------
    function parseJsonResponse(res) {
        return res.text().then(text => {
            if (!text) return { ok: res.ok, data: null, text: '' };
            try { return { ok: res.ok, data: JSON.parse(text), text: '' }; }
            catch { return { ok: false, data: null, text }; }
        });
    }

    function saveSchedule() {
        // Use the date from the calendar click; fall back to today
        const patrolDate = selectedDateStr || new Date().toISOString().split('T')[0];
        const desc       = (document.getElementById('patrolDescription')?.value || '').trim();

        // Reject past dates
        const today = new Date().toISOString().split('T')[0];
        if (patrolDate < today) {
            showToast('Cannot create a patrol on a past date.', 'warning'); return;
        }
        // Reject duplicate dates
        if (allSchedules.some(s => (s.patrolDate || '').slice(0, 10) === patrolDate)) {
            showToast('A patrol already exists on this date.', 'warning'); return;
        }
        const limit      = (document.getElementById('patrolLimit')?.value || '').trim();

        // Collect selected supervisor
        const superRadio = document.querySelector('input[name="supervisorPick"]:checked');
        const superNr    = superRadio ? superRadio.value : '';

        // Collect selected volunteers
        const selectedVols = Array.from(
            document.querySelectorAll('#volPickerList .vol-checkbox:checked')
        ).map(cb => cb.value);

        // Validate volunteer limit
        if (limit !== '' && (isNaN(parseInt(limit, 10)) || parseInt(limit, 10) < 0)) {
            showToast('Volunteer limit must be a non-negative number.', 'warning'); return;
        }

        // Validate volunteer count doesn't exceed limit
        if (limit !== '' && selectedVols.length > parseInt(limit, 10)) {
            showToast(`Cannot add more than ${limit} volunteer(s) to this patrol.`, 'warning'); return;
        }

        // Save limit to localStorage keyed by date
        if (limit !== '') localStorage.setItem(`patrolLimit:${patrolDate}`, limit);

        const submitBtn = document.getElementById('addPatrolSubmitBtn');
        if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…'; }

        fetch('/api/schedules', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                patrolDate:        `${patrolDate} 19:00:00`,
                patrolDescription: desc || 'Regular Patrol',
                SuperUserNr:       superNr || null,
                patrol_status:     0,
                volunteerIds:      selectedVols
            })
        })
        .then(result => {
            if (result.data && result.data.success) {
                bootstrap.Modal.getInstance(document.getElementById('addScheduleModal')).hide();
                showToast('Patrol created successfully!', 'success');
                setTimeout(() => { window.location.href = `${schedulesBaseUrl}?date=${patrolDate}`; }, 1000);
            } else {
                showToast('Error: ' + (result.data?.message || result.text || 'Unknown error'), 'danger');
                if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Add Patrol'; }
            }
        })
        .catch(e => {
            showToast('Error: ' + e.message, 'danger');
            if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Add Patrol'; }
        });
    }

    let currentEditSchedule = null;

    function openEditModal(schedule) {
        // schedule may come from PHP (string) or JS object
        if (typeof schedule === 'string') schedule = JSON.parse(schedule);
        editPatrolPool = [];
        fillEditModal(schedule, (schedule.patrolDate || '').slice(0, 10));
        new bootstrap.Modal(document.getElementById('editScheduleModal')).show();
    }

    function updateSchedule() {
        if (!currentEditSchedule) { showToast('No patrol selected.', 'warning'); return; }

        const patrolDate = (currentEditSchedule.patrolDate || '').slice(0, 10);
        const desc       = document.getElementById('editPatrolDescription').value;
        const limit      = document.getElementById('editPatrolLimit').value;

        // Collect selected supervisor
        const superRadio = document.querySelector('input[name="editSupervisorPick"]:checked');
        const superNr    = superRadio ? superRadio.value : '';

        // Collect selected status
        const statusSel  = document.getElementById('editPatrolStatus');
        const newStatus  = statusSel ? parseInt(statusSel.value, 10) : (currentEditSchedule.patrol_status || 0);

        // Collect selected volunteers
        const selectedVols = Array.from(
            document.querySelectorAll('#editVolPickerList .edit-vol-checkbox:checked')
        ).map(cb => cb.value);

        if (limit !== '' && (isNaN(parseInt(limit)) || parseInt(limit) < 0)) {
            showToast('Volunteer limit must be a non-negative number.', 'warning'); return;
        }

        // Save limit to localStorage keyed by patrolNr
        if (limit !== '' && currentEditSchedule.patrolNr) {
            localStorage.setItem(`patrolLimit:patrolNr:${currentEditSchedule.patrolNr}`, limit);
        }

        fetch(`/api/schedules/${currentEditSchedule.patrolNr}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                patrolDate: `${patrolDate} 19:00:00`,
                patrolDescription: desc,
                SuperUserNr: superNr || null,
                patrol_status: newStatus,
                volunteerIds: selectedVols
            })
        })
        .then(parseJsonResponse)
        .then(result => {
            if (result.data && result.data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editScheduleModal')).hide();
                showToast('Patrol updated successfully!', 'success');
                setTimeout(() => { window.location.href = `${schedulesBaseUrl}?date=${patrolDate}`; }, 1000);
            } else {
                showToast('Error: ' + (result.data?.message || result.text || 'Unknown error'), 'danger');
            }
        })
        .catch(e => showToast('Error: ' + e.message, 'danger'));
    }

    function deleteCurrentSchedule() {
        if (!currentEditSchedule) { showToast('No patrol selected.', 'warning'); return; }
        if (!confirm('Delete this patrol? This cannot be undone.')) return;

        const dateStr = (currentEditSchedule.patrolDate || '').slice(0, 10);

        fetch(`/api/schedules/${currentEditSchedule.patrolNr}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }
        })
        .then(parseJsonResponse)
        .then(result => {
            if (result.data && result.data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editScheduleModal')).hide();
                showToast('Patrol deleted.', 'secondary');
                setTimeout(() => { window.location.href = `${schedulesBaseUrl}?date=${dateStr}`; }, 1000);
            } else {
                showToast('Error: ' + (result.data?.message || result.text || 'Unknown error'), 'danger');
            }
        })
        .catch(e => showToast('Error: ' + e.message, 'danger'));
    }

    // -- Utility -----------------------------------------
    function showToast(msg, type = 'success') {
        const icons    = { success: 'bi-check-circle-fill', danger: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill', secondary: 'bi-info-circle-fill' };
        const titles   = { success: 'Success', danger: 'Error', warning: 'Warning', secondary: 'Info' };
        const container = document.getElementById('cwpToastContainer');

        const el = document.createElement('div');
        el.className = `cwp-toast cwp-toast-${type}`;
        el.setAttribute('role', 'alert');
        el.innerHTML = `
            <div class="cwp-toast-inner">
                <div class="cwp-toast-icon"><i class="bi ${icons[type] || 'bi-bell-fill'}"></i></div>
                <div class="cwp-toast-body">
                    <div class="cwp-toast-title">${titles[type] || 'Notice'}</div>
                    <div class="cwp-toast-msg">${msg}</div>
                </div>
                <button class="cwp-toast-close" onclick="this.closest('.cwp-toast').remove()" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>`;

        container.appendChild(el);
        // Trigger enter animation
        requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('cwp-toast-show')));
        // Auto-remove after 3.5 s
        setTimeout(() => {
            el.classList.replace('cwp-toast-show', 'cwp-toast-hide');
            setTimeout(() => el.remove(), 300);
        }, 3500);
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toast notification container -->
<div id="cwpToastContainer"></div>

<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>

