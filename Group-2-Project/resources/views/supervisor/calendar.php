<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claddagh | Supervisor Patrol Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
    <style>
        /* -- PAGE ----------------------------------------- */
        .cal-page-wrapper { min-height:100vh; background:#f0f4f8; padding-bottom:0; }

        /* -- TOOLBAR ----------------------------------------- */
        .cal-toolbar { display:flex; align-items:center; justify-content:flex-start; padding:16px 24px; background:#fff; border-bottom:1px solid #e2e8f0; flex-wrap:wrap; gap:12px; position:relative; }
        .cal-toolbar-title { font-size:1.25rem; font-weight:700; color:#1e3a5f; display:flex; align-items:center; gap:10px; }
        .cal-toolbar-title i { color:#2563eb; }
        .cal-month-nav { display:flex; align-items:center; gap:8px; margin-left:auto; }
        .cal-month-nav .month-label { font-size:1rem; font-weight:600; min-width:140px; text-align:center; color:#1e3a5f; }
        .cal-nav-btn { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#475569; transition:all .15s; }
        .cal-nav-btn:hover { background:#2563eb; color:#fff; border-color:#2563eb; }
        .btn-close { opacity:1 !important; filter:var(--bs-btn-close-white-filter) !important; }
        .btn-close:hover { opacity:1 !important; }

        @media (min-width: 992px) {
            .cal-month-nav {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                margin-left: 0;
            }

            .cal-toolbar > #managerToolbar,
            .cal-toolbar > .small.text-secondary {
                margin-left: auto;
            }
        }

        /* -- TWO-COLUMN LAYOUT ----------------------------------------- */
        .vol-layout-wrapper { display:flex; gap:20px; max-width:1400px; margin:20px auto 0; padding:0 24px; align-items:flex-start; }
        .vol-cal-col  { flex:0 0 63%; width:63%; min-width:0; }
        .vol-sidebar-col { flex:0 0 calc(37% - 20px); width:calc(37% - 20px); min-width:0; position:sticky; top:16px; }
        @media (max-width: 991px) {
            .vol-layout-wrapper { flex-direction:column; padding:0 12px; }
            .vol-cal-col, .vol-sidebar-col { flex:none; width:100%; }
            .vol-sidebar-col { position:static; order:-1; }
        }

        /* -- MY UPCOMING PATROLS ----------------------------------------- */
        .upcoming-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
        .upcoming-card-header { display:flex; align-items:center; justify-content:space-between; padding:14px 16px; border-bottom:1px solid #f1f5f9; flex-wrap:wrap; gap:8px; }
        .upcoming-card-title { font-size:.95rem; font-weight:700; color:#1e3a5f; display:flex; align-items:center; gap:8px; }
        .upcoming-collapse-toggle { width:100%; display:flex; align-items:center; justify-content:space-between; background:transparent; border:none; padding:0; color:inherit; cursor:pointer; }
        .upcoming-collapse-toggle i { color:#64748b; transition:transform .2s ease; }
        .upcoming-collapse-toggle[aria-expanded="true"] i { transform:rotate(180deg); }
        .upcoming-body { border-top:1px solid #f1f5f9; }
        .filter-btns { display:flex; gap:5px; flex-wrap:wrap; }
        .filter-btn { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:20px; padding:3px 11px; font-size:.75rem; font-weight:600; color:#475569; cursor:pointer; transition:all .15s; white-space:nowrap; }
        .filter-btn.active, .filter-btn:hover { background:#2563eb; color:#fff; border-color:#2563eb; }
        .upcoming-list { padding:10px 16px 14px; }
        .upcoming-patrol-item { display:flex; align-items:flex-start; gap:12px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
        .upcoming-patrol-item:last-child { border-bottom:none; }
        .upcoming-date-badge { flex-shrink:0; background:#eff6ff; border-radius:10px; width:46px; text-align:center; padding:5px 4px; }
        .upcoming-date-badge .day-num { font-size:1.15rem; font-weight:800; color:#1e3a5f; line-height:1; }
        .upcoming-date-badge .day-name { font-size:.6rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.04em; }
        .upcoming-patrol-info { flex:1; min-width:0; }
        .upcoming-patrol-title { font-size:.85rem; font-weight:600; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .upcoming-patrol-meta { font-size:.74rem; color:#64748b; margin-top:2px; }
        .status-pill { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:.68rem; font-weight:600; margin-left:4px; }
        .status-pill.released   { background:#fef9c3; color:#92400e; }
        .status-pill.finalized  { background:#dcfce7; color:#166534; }
        .status-pill.postponed  { background:#e5e7eb; color:#374151; }
        .status-pill.draft      { background:#f1f5f9; color:#475569; }
        .status-pill.cancelled  { background:#fee2e2; color:#991b1b; }
        .upcoming-empty { text-align:center; padding:24px 12px; color:#94a3b8; font-size:.84rem; }

        /* -- CALENDAR (DESKTOP) ----------------------------------------- */
        .cal-grid-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
        .cal-day-headers { display:grid; grid-template-columns:repeat(7,1fr); background:#1e3a5f; position:sticky; top:0; z-index:10; }
        .cal-day-header { padding:10px 0; text-align:center; font-size:.78rem; font-weight:700; color:rgba(255,255,255,.85); letter-spacing:.05em; }
        .cal-days { display:grid; grid-template-columns:repeat(7,1fr); border-top:1px solid #e2e8f0; }
        .cal-day { min-height:100px; padding:8px; border-right:1px solid #f1f5f9; border-bottom:1px solid #f1f5f9; cursor:pointer; transition:background .12s; position:relative; }
        .cal-day:hover:not(.empty) { background:#f0f7ff; }
        .cal-day.empty { background:#fafafa; cursor:default; }
        .cal-day.today .cal-day-num { background:#2563eb; color:#fff; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; }
        .cal-day.selected { background:#eff6ff; outline:2px solid #2563eb; outline-offset:-2px; }
        .cal-day.has-postponed.selected { background:#d1d5db; outline:2px solid #6b7280; }
        .cal-day-num { font-size:.85rem; font-weight:600; color:#374151; margin-bottom:4px; width:28px; height:28px; display:flex; align-items:center; justify-content:center; }
        .cal-patrol-chip { display:block; font-size:.7rem; padding:2px 6px; border-radius:5px; margin-top:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-weight:500; }
        .cal-more-badge { font-size:.68rem; color:#64748b; padding:1px 4px; display:block; }

        /* -- MOBILE AGENDA VIEW ----------------------------------------- */
        .vol-agenda { margin:0; padding:0; }
        .agenda-day-row { margin-bottom:14px; }
        .agenda-day-header { display:flex; align-items:center; gap:10px; padding:8px 0 6px; border-bottom:2px solid #e2e8f0; margin-bottom:6px; }
        .agenda-day-label { font-size:.88rem; font-weight:700; color:#1e3a5f; }
        .agenda-day-label.today-label { color:#2563eb; }
        .agenda-patrol-card { background:#fff; border-radius:12px; padding:14px 16px; margin-bottom:8px; box-shadow:0 1px 6px rgba(0,0,0,.06); display:flex; align-items:flex-start; gap:12px; cursor:pointer; border:1px solid #e2e8f0; min-height:44px; }
        .agenda-patrol-card:hover, .agenda-patrol-card:focus { border-color:#2563eb; outline:none; background:#f8fafc; }
        .agenda-time { font-size:.78rem; font-weight:700; color:#2563eb; width:44px; flex-shrink:0; padding-top:2px; }
        .agenda-patrol-info { flex:1; min-width:0; }
        .agenda-patrol-title { font-size:.9rem; font-weight:600; color:#1e293b; }
        .agenda-patrol-meta { font-size:.75rem; color:#64748b; margin-top:3px; }
        .agenda-empty { text-align:center; padding:20px; color:#94a3b8; font-size:.85rem; }
        .agenda-no-days { text-align:center; padding:40px 16px; color:#94a3b8; }
        .agenda-empty-icon { font-size:2.5rem; display:block; margin-bottom:10px; }

        /* -- DAY DETAIL PANEL ----------------------------------------- */
        #dayDetailPanel { display:none; max-width:1400px; margin:18px auto 0; padding:0 24px; }
        .detail-card { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.08); overflow:hidden; }
        .detail-header { display:flex; align-items:center; justify-content:space-between; padding:16px 22px; background:#1e3a5f; }
        .detail-header h2 { margin:0; font-size:1.05rem; font-weight:700; color:#fff; }
        .detail-body { padding:20px; }
        .patrol-open-card { border:1px solid #e2e8f0; border-radius:12px; padding:16px; margin-bottom:14px; }
        .patrol-open-card:hover { border-color:#2563eb; background:#f8fafc; }
        .signup-btn { min-width:44px; min-height:44px; }
        .signed-up-badge { display:inline-flex; align-items:center; gap:6px; background:#dbeafe; color:#1e40af; padding:6px 14px; border-radius:20px; font-size:.82rem; font-weight:600; }
        .member-count-bar { display:flex; align-items:center; gap:8px; margin-bottom:10px; flex-wrap:wrap; }
        .member-count-text { font-size:.78rem; font-weight:600; color:#64748b; line-height:1.25; min-width:0; overflow-wrap:anywhere; }
        .member-avatars { display:flex; gap:-4px; }
        .member-avatar-sm { width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; font-size:.72rem; font-weight:700; display:flex; align-items:center; justify-content:center; border:2px solid #fff; margin-left:-6px; flex-shrink:0; }
        .member-avatar-sm:first-child { margin-left:0; }
        .finalized-mini-panel { margin-top:10px; border:1px solid #d1fae5; background:#f0fdf4; border-radius:10px; padding:8px 10px; }
        .finalized-mini-title { font-size:.74rem; font-weight:700; color:#166534; margin-bottom:6px; display:flex; align-items:center; gap:6px; }
        .finalized-mini-list { margin:0; padding-left:16px; font-size:.78rem; color:#1f2937; }
        .finalized-mini-list li { margin-bottom:2px; }
        .finalized-mini-empty { font-size:.76rem; color:#6b7280; }
        .finalized-super-tag { display:inline-block; margin-left:6px; padding:1px 6px; border-radius:999px; font-size:.62rem; font-weight:700; background:#dcfce7; color:#166534; }
        .finalized-mini-toggle { font-size:.72rem; padding:4px 9px; border-radius:999px; }

        /* -- STATUS CELL COLOURS (full block) ----------------------------------------- */
        .cal-day.has-released  { background: #fef9c3; }
        .cal-day.has-finalized { background: #dcfce7; }
        .cal-day.has-postponed { background: #d1d5db; }
        .cal-day.has-draft     { background: #f1f5f9; }
        .cal-day.has-released:hover:not(.empty)  { background: #fef08a; }
        .cal-day.has-finalized:hover:not(.empty) { background: #bbf7d0; }
        .cal-day.has-postponed:hover:not(.empty) { background: #b4b8c2; }
        .cal-day.has-draft:hover:not(.empty)     { background: #e2e8f0; }

        /* -- SIGNED-UP SMILEY INDICATOR ----------------------------------------- */
        .cal-smiley {
            position: absolute;
            top: 5px;
            right: 6px;
            font-size: 1.15rem;
            line-height: 1;
            filter: drop-shadow(0 1px 2px rgba(0,0,0,.18));
            animation: smiley-pop .3s cubic-bezier(.22,.68,0,1.4) both;
            pointer-events: none;
        }
        @keyframes smiley-pop {
            from { transform: scale(0) rotate(-20deg); opacity: 0; }
            to   { transform: scale(1) rotate(0deg);   opacity: 1; }
        }

        /* -- MANAGER MULTI-SELECT ----------------------------------------- */
        .cal-day.manager-selected { outline: 3px solid #2563eb; outline-offset: -3px; background: #dbeafe !important; }

        /* -- STATUS CHIPS (updated colours) ----------------------------------------- */
        .cal-patrol-chip.draft     { background: #e2e8f0; color: #475569; display:block; }
        .cal-patrol-chip.released  { background: #fef9c3; color: #92400e; border: 1px solid #fbbf24; display:block; }
        .cal-patrol-chip.finalized { background: #dcfce7; color: #166534; border: 1px solid #4ade80; display:block; }
        .cal-patrol-chip.postponed { background: #d1d5db; color: #374151; border: 1px solid #9ca3af; display:block; }

        /* -- RESPONSIVE ----------------------------------------- */
        .count-abbr { display: none; }
        @media (max-width: 767px) {
            .cal-toolbar { padding:10px 12px; gap:8px; justify-content:center; }
            .cal-toolbar-title { font-size:1rem; width:100%; justify-content:center; }
            .cal-month-nav { margin-left:0; justify-content:center; }
            .vol-layout-wrapper { padding:0 6px; gap:12px; }
            #dayDetailPanel { padding:0 8px; }
            .cal-grid-card { overflow-x: hidden; }
            .cal-days { overflow-x: hidden; }
            .cal-day-header { padding:7px 0; font-size:.68rem; }
            .cal-day { min-height:56px; padding:4px 3px; }
            .cal-day-num { font-size:.72rem; width:20px; height:20px; }
            .cal-patrol-chip { font-size:.55rem; padding:1px 3px; margin-top:2px; }
            .cal-more-badge { font-size:.55rem; padding:0; }
            .cal-count-chip { font-size:.54rem; padding:1px 3px; }
            .cal-counts { gap:2px; margin-top:2px; }
            .cal-smiley { font-size:.9rem; top:3px; right:3px; }
            /* Compact upcoming patrols */
            .upcoming-card-header { padding:9px 12px; }
            .upcoming-card-title { font-size:.88rem; }
            .filter-btn { padding:2px 9px; font-size:.7rem; }
            .upcoming-list { padding:5px 12px 8px; }
            .upcoming-patrol-item { padding:6px 0; gap:8px; }
            .upcoming-date-badge { width:36px; padding:3px 2px; border-radius:8px; }
            .upcoming-date-badge .day-num { font-size:.95rem; }
            .upcoming-date-badge .day-name { font-size:.55rem; }
            .upcoming-patrol-title { font-size:.78rem; }
            .upcoming-patrol-meta { font-size:.67rem; margin-top:1px; }
            .status-pill { font-size:.6rem; padding:1px 5px; }
            .upcoming-patrol-item .btn { font-size:.72rem; padding:3px 8px; min-height:30px !important; }
            /* Compact legend – horizontal row */
            .legend-panel-header { padding:9px 12px; font-size:.88rem; }
            .legend-panel-body { flex-direction:row; flex-wrap:wrap; gap:6px 14px; padding:10px 12px; }
            .legend-item { font-size:.78rem; gap:6px; }
            .legend-swatch { width:14px; height:14px; border-radius:4px !important; }
        }
        @media (max-width: 480px) {
            .upcoming-card-header { flex-direction:column; align-items:flex-start; }
            .cal-day-header { font-size:.6rem; padding:6px 0; letter-spacing:0; }
            .cal-day { min-height:48px; padding:3px 2px; }
            .cal-day-num { font-size:.67rem; width:17px; height:17px; }
            .cal-patrol-chip { font-size:.5rem; padding:1px 2px; }
            .cal-count-chip { font-size:.5rem; padding:1px 2px; }
            .cal-counts { gap:1px; margin-top:1px; }
            .count-lbl { display: none; }
            .count-abbr { display: inline; }
            .cal-smiley { font-size:.8rem; top:2px; right:2px; }
            /* xs: icon-only cancel button */
            .upcoming-patrol-item .cancel-text { display:none; }
            .upcoming-patrol-item .btn { padding:3px 6px; }
            .upcoming-patrol-item { gap:5px; }
            .upcoming-date-badge { width:32px; }
            .upcoming-date-badge .day-num { font-size:.88rem; }
            .filter-btn { padding:2px 7px; font-size:.67rem; }
        }

        /* -- CAL COUNTS (Super / Vol in cells) ----------------------------------------- */
        .cal-counts { display:flex; gap:4px; margin-top:5px; flex-wrap:wrap; }
        .cal-count-chip { font-size:.6rem; font-weight:700; color:#475569; background:#e2e8f0; border-radius:4px; padding:1px 5px; line-height:1.7; white-space:nowrap; }
        .cal-day.has-released  .cal-count-chip { background:rgba(251,191,36,.3);  color:#78350f; }
        .cal-day.has-finalized .cal-count-chip { background:rgba(74,222,128,.3);  color:#14532d; }
        .cal-day.manager-selected .cal-count-chip { background:rgba(37,99,235,.2); color:#1e40af; }

        /* -- SELECTION ACTION PANEL ----------------------------------------- */
        .sel-panel { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; margin-bottom:14px; }
        .sel-panel-header { padding:14px 16px; border-bottom:1px solid #f1f5f9; font-size:.95rem; font-weight:700; color:#1e3a5f; display:flex; align-items:center; gap:8px; }
        .sel-count-badge { background:#2563eb; color:#fff; border-radius:20px; font-size:.72rem; font-weight:700; padding:2px 9px; margin-left:auto; }
        .sel-panel-body { padding:14px 16px; }
        .sel-panel-empty { text-align:center; padding:18px 0 10px; color:#94a3b8; font-size:.83rem; }
        .sel-action-btn { display:flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:.6rem 1rem; border-radius:10px; font-size:.88rem; font-weight:600; cursor:pointer; transition:all .15s; border:1px solid transparent; margin-bottom:8px; }
        .sel-action-btn:disabled { opacity:.5; cursor:not-allowed; }
        .sel-action-btn.s-draft    { background:#f1f5f9; color:#475569; border-color:#e2e8f0; }
        .sel-action-btn.s-draft:hover:not(:disabled)    { background:#e2e8f0; }
        .sel-action-btn.s-release  { background:#fef9c3; color:#92400e; border-color:#fbbf24; }
        .sel-action-btn.s-release:hover:not(:disabled)  { background:#fef08a; }
        .sel-action-btn.s-finalize { background:#dcfce7; color:#166534; border-color:#4ade80; }
        .sel-action-btn.s-finalize:hover:not(:disabled) { background:#bbf7d0; }
        .sel-action-btn.s-delete   { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }
        .sel-action-btn.s-delete:hover:not(:disabled)   { background:#fecaca; }
        .sel-action-btn.s-create   { background:#eff6ff; color:#1e40af; border-color:#93c5fd; }
        .sel-action-btn.s-create:hover:not(:disabled)   { background:#dbeafe; }
        .sel-action-btn.s-edit     { background:#f0fdf4; color:#065f46; border-color:#6ee7b7; }
        .sel-action-btn.s-edit:hover:not(:disabled)     { background:#d1fae5; }
        .sel-clear-link { background:none; border:none; color:#94a3b8; font-size:.78rem; cursor:pointer; padding:6px 0 0; width:100%; text-align:center; display:block; }
        .sel-clear-link:hover { color:#475569; }

        /* -- LEGEND PANEL (sidebar) ----------------------------------------- */
        .legend-panel { background:#fff; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,.07); overflow:hidden; }
        .legend-panel-header { padding:14px 16px; border-bottom:1px solid #f1f5f9; font-size:.95rem; font-weight:700; color:#1e3a5f; display:flex; align-items:center; gap:8px; }
        .legend-panel-body { padding:14px 16px; display:flex; flex-direction:column; gap:10px; }
        .legend-item { display:flex; align-items:center; gap:10px; font-size:.88rem; color:#475569; font-weight:500; }
        .legend-swatch { width:20px; height:20px; border-radius:5px; flex-shrink:0; }
    </style>
</head>



<body class="cal-page-wrapper">
<?php
    // -- Auth: redirect if not logged in --------------------
    // JwtHelper is PSR-4 autoloaded under App\Helpers
    $__payload   = \App\Helpers\JwtHelper::fromCookie();
    if (!$__payload) {
        // Clear stale/invalid JWT to prevent redirect loops
        setcookie('cw_jwt',  '', time() - 3600, '/');
        setcookie('cw_role', '', time() - 3600, '/');
        header('Location: ' . url('/login')); exit;
    }
    $__volId     = (int)$__payload['sub'];
    $__roleName  = $__payload['roleName'] ?? 'SUPER';
    $__role      = (int)($__payload['role'] ?? 99);
    $__isManager = false;
    $__isSuper   = ($__role === 4);

    if (!$__isSuper) {
        header('Location: ' . url('/volunteer')); exit;
    }

    // -- Supervisors: released + finalized + postponed patrols from current month onward ---
    $schedules = \App\Models\Schedule::with(['volunteers', 'supervisor'])
                  ->whereIn('patrol_status', [1, 2, 3])
                  ->where('patrolDate', '>=', now()->startOfMonth()->startOfDay())
                  ->orderBy('patrolDate')
                  ->get();

    $schedulesByDate = [];
    foreach ($schedules as $s) {
        $d = is_string($s->patrolDate)
                ? substr($s->patrolDate, 0, 10)
                : $s->patrolDate->format('Y-m-d');
        $schedulesByDate[$d][] = $s;
    }

    $baseDate    = new DateTime();
    // Allow month navigation via URL params
    $nowYear  = (int)$baseDate->format('Y');
    $nowMonth = (int)$baseDate->format('n');
    $qYear  = isset($_GET['year'])  ? (int)$_GET['year']  : $nowYear;
    $qMonth = isset($_GET['month']) ? (int)$_GET['month'] : $nowMonth;
    if ($qYear < $nowYear || ($qYear === $nowYear && $qMonth < $nowMonth)) {
        $qYear = $nowYear;
        $qMonth = $nowMonth;
    }
    $isFirstMonth = ($qYear === $nowYear && $qMonth === $nowMonth);
    $isLastMonth  = false;
    $calYear     = $qYear;
    $calMonth    = $qMonth;
    $firstDay    = new DateTime("$calYear-$calMonth-01");
    $daysInMonth = (int)$firstDay->format('t');
    $startDow    = (int)$firstDay->format('N');
    $todayStr    = $baseDate->format('Y-m-d');

    if (!function_exists('statusLabel')) {
        function statusLabel($v) {
            if ($v == 1) return ['released',  'Released'];
            if ($v == 2) return ['finalized', 'Finalized'];
            if ($v == 3) return ['postponed', 'Postponed'];
            return              ['draft',     'Draft'];
        }
    }

    // Supervisors for edit modal
    $supervisorsJson = json_encode(
        \App\Models\User::where('userTypeNr', 4)
            ->where('userEnabled', 1)
            ->orderBy('FirstName')
            ->get(['UserNr','FirstName','LastName'])
            ->map(fn($u) => ['id' => $u->UserNr, 'name' => trim(($u->FirstName ?? '').' '.($u->LastName ?? ''))])
            ->values()->all(),
        JSON_HEX_TAG | JSON_HEX_QUOT
    );

    $schedulesJson = json_encode($schedules->values()->map(fn($s) => [
        'patrolNr'          => $s->patrolNr,
        'patrolDate'        => substr((string)$s->patrolDate, 0, 10),
        'start_time'        => $s->start_time ? substr((string)$s->start_time, 0, 8) : null,
        'end_time'          => $s->end_time ? substr((string)$s->end_time, 0, 8) : null,
        'patrolDescription' => $s->patrolDescription,
        'patrol_status'     => $s->patrol_status,
        'SuperUserNr'       => $s->SuperUserNr,
        'volunteer_count'   => $s->volunteers->count(),
        'volunteers'        => $s->volunteers->map(fn($v) => [
                                'id'   => $v->UserNr,
                                'name' => trim(($v->FirstName ?? '') . ' ' . ($v->LastName ?? '')),
                            ])->values()->all(),
        'supervisor'        => $s->supervisor ? [
                                'id'   => $s->supervisor->UserNr,
                                'name' => trim(($s->supervisor->FirstName ?? '') . ' ' . ($s->supervisor->LastName ?? '')),
                            ] : null,
    ])->values()->all(), JSON_HEX_TAG | JSON_HEX_QUOT);
?>

<?php @require resource_path('views/templates/header.php'); ?>

<!-- -- Toolbar ----------------------------------------- -->
<div class="cal-toolbar">
    <div class="cal-toolbar-title">
        <i class="bi bi-calendar3"></i>
        Supervisor Patrol Calendar
    </div>

    <div class="cal-month-nav">
        <button class="cal-nav-btn" onclick="changeMonth(-1)" title="Previous month" <?php if ($isFirstMonth) echo 'disabled style="opacity:.35;cursor:default;pointer-events:none;"'; ?>>
            <i class="bi bi-chevron-left"></i>
        </button>
        <span class="month-label" id="monthLabel">
            <?php echo $firstDay->format('F Y'); ?>
        </span>
        <button class="cal-nav-btn" onclick="changeMonth(1)" title="Next month" <?php if ($isLastMonth) echo 'disabled style="opacity:.35;cursor:default;pointer-events:none;"'; ?>>
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>

    <?php if ($__isManager): ?>
    <div class="d-flex align-items-center gap-2" id="managerToolbar">
        <span id="selectionCount" class="small text-muted d-none">
            <i class="bi bi-check2-square me-1 text-primary"></i>
            <span id="selCountNum">0</span> date(s) selected
        </span>
    </div>
    <?php else: ?>
    <div class="small text-secondary">
        <i class="bi bi-person-badge me-1"></i>
        Signed in as <strong><?php echo htmlspecialchars($__payload['email'] ?? ''); ?></strong>
        <span class="badge bg-primary ms-1"><?php echo htmlspecialchars($__roleName); ?></span>
    </div>
    <?php endif; ?>
</div>

<!---- Main Two-Column Layout ----------------------------------------- -->
<div class="vol-layout-wrapper">

    <!-- LEFT: Calendar (75%) -->
    <div class="vol-cal-col">

        <!-- Calendar (all screen sizes) -->
        <div class="cal-container">
        <div class="cal-grid-card">
            <div class="cal-day-headers">
                <?php foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $h): ?>
                    <div class="cal-day-header"><?php echo $h; ?></div>
                <?php endforeach; ?>
            </div>

            <div class="cal-days" id="calDays">
                <?php for ($e = 1; $e < $startDow; $e++): ?>
                    <div class="cal-day empty"><div class="cal-day-num"></div></div>
                <?php endfor; ?>

                <?php for ($day = 1; $day <= $daysInMonth; $day++):
                    $dayDate    = new DateTime("$calYear-$calMonth-$day");
                    $dayStr     = $dayDate->format('Y-m-d');
                    $isToday    = ($dayStr === $todayStr);
                    $hasPatrols = !empty($schedulesByDate[$dayStr]);

                    $classes  = 'cal-day';
                    if ($isToday) $classes .= ' today';

                    // Determine cell background class from highest-priority status
                    if ($hasPatrols) {
                        $statuses = array_map(fn($s) => (int)$s->patrol_status, $schedulesByDate[$dayStr]);
                        if (in_array(2, $statuses)) $classes .= ' has-finalized';
                        elseif (in_array(1, $statuses)) $classes .= ' has-released';
                        elseif (in_array(3, $statuses)) $classes .= ' has-postponed';
                        elseif ($__isManager) $classes .= ' has-draft';
                    }
                ?>
                    <div class="<?php echo $classes; ?>"
                         data-date="<?php echo $dayStr; ?>"
                         onclick="selectDay('<?php echo $dayStr; ?>')">
                        <div class="cal-day-num"><?php echo $day; ?></div>

                        <?php if ($hasPatrols):
                            [$stClass, $stLabel] = statusLabel($schedulesByDate[$dayStr][0]->patrol_status);
                            echo "<span class='cal-patrol-chip $stClass'>$stLabel</span>";
                            $extra = count($schedulesByDate[$dayStr]) - 1;
                            if ($extra > 0) echo "<span class='cal-more-badge'>+$extra more</span>";
                            if ($__isManager) {
                                $totalVols = 0; $totalSups = 0;
                                foreach ($schedulesByDate[$dayStr] as $_ps) {
                                    if ($_ps->relationLoaded('volunteers')) $totalVols += $_ps->volunteers->count();
                                    if ($_ps->SuperUserNr) $totalSups++;
                                }
                                echo "<div class='cal-counts'><span class='cal-count-chip'><span class='count-lbl'>Super: </span><span class='count-abbr'>S:</span>$totalSups</span><span class='cal-count-chip'><span class='count-lbl'>Vol: </span><span class='count-abbr'>V:</span>$totalVols</span></div>";
                            }
                        endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        </div>

    </div>

    <!-- RIGHT: Sidebar -->
    <div class="vol-sidebar-col">

        <?php if ($__isManager): ?>
        <!-- Manager: Selection Action Panel -->
        <div class="sel-panel" id="selPanel">
            <div class="sel-panel-header">
                <i class="bi bi-ui-checks text-primary"></i>
                Actions
                <span class="sel-count-badge d-none" id="selCountBadge">0</span>
            </div>
            <div class="sel-panel-body" id="selPanelBody">
                <div class="sel-panel-empty">
                    <i class="bi bi-hand-index-thumb d-block mb-2 fs-2 text-muted"></i>
                    Click dates on the calendar to select them
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Supervisor: Released Patrols -->
        <div class="upcoming-card mb-3">
            <div class="upcoming-card-header">
                <button type="button" class="upcoming-collapse-toggle" id="upcomingToggleBtn" aria-expanded="false" onclick="toggleUpcomingMenu()">
                    <span class="upcoming-card-title">
                        <i class="bi bi-calendar-check text-primary"></i>
                        Released Patrols
                    </span>
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
            <div class="upcoming-body d-none" id="upcomingBody">
                <div class="upcoming-list" style="padding-bottom:8px;">
                    <div class="filter-btns" id="filterBtns">
                        <button class="filter-btn active" onclick="setFilter('all', this)">All</button>
                        <button class="filter-btn" onclick="setFilter('this', this)">This Month</button>
                        <button class="filter-btn" onclick="setFilter('future', this)">Future Months</button>
                    </div>
                </div>
                <div class="upcoming-list" id="upcomingList">
                    <div class="upcoming-empty">
                        <i class="bi bi-hourglass-split d-block mb-2 fs-3"></i>
                        Loading released patrols...
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Legend (all roles) -->
        <div class="legend-panel">
            <div class="legend-panel-header">
                <i class="bi bi-info-circle text-primary"></i>
                Legend
            </div>
            <div class="legend-panel-body">
                <div class="legend-item">
                    <span class="legend-swatch" style="background:#fef9c3;border:1px solid #fbbf24;"></span>
                    <span>Released</span>
                </div>
                <?php if ($__isManager): ?>
                <div class="legend-item">
                    <span class="legend-swatch" style="background:#f1f5f9;border:1px solid #e2e8f0;"></span>
                    <span>Draft</span>
                </div>
                <div class="legend-item">
                    <span class="legend-swatch" style="background:#dbeafe;border:2px solid #2563eb;"></span>
                    <span>Selected</span>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<!---- Day Detail Panel ----------------------------------------- -->
<div id="dayDetailPanel">
    <div class="detail-card" style="max-width:100%;margin:0 auto;">
        <div class="detail-header">
            <h2 id="detailHeading"><i class="bi bi-calendar3 me-2"></i><span id="detailDateLabel"></span></h2>
            <button class="btn btn-sm btn-outline-light" onclick="closeDetail()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="detail-body" id="detailBody"></div>
    </div>
</div>

<!---- Edit Patrol Modal ----------------------------------------- -->
<div class="modal fade" id="editPatrolModal" tabindex="-1" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content" style="border-radius:14px;">
            <div class="modal-header" style="background:#1e3a5f;color:#fff;border-radius:14px 14px 0 0;">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Patrol <span id="editModalPatrolLabel" class="fw-normal opacity-75"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <input type="text" class="form-control" id="editPatrolDesc" placeholder="Regular Scheduled Patrol">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Supervisor</label>
                    <select class="form-select" id="editPatrolSupervisor">
                        <option value="">— None —</option>
                        <?php foreach (json_decode($supervisorsJson, true) as $sup): ?>
                            <option value="<?php echo $sup['id']; ?>"><?php echo htmlspecialchars($sup['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-1">
                    <label class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="editPatrolStatusSel">
                        <option value="0">Draft</option>
                        <option value="1">Released</option>
                        <option value="2">Finalized</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="background:#f8fafc;border-radius:0 0 14px 14px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitEditPatrol()">
                    <i class="bi bi-check-lg me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!---- Toast notification ----------------------------------------- -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
    <div id="signupToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMsg">Signed up!</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>



<script>
    const allSchedules  = <?php echo $schedulesJson; ?>;
    const allSupervisors = <?php echo $supervisorsJson; ?>;
    const volunteerId  = <?php echo $__volId; ?>;
    const isManager    = <?php echo $__isManager ? 'true' : 'false'; ?>;
    const isSupervisor = <?php echo $__isSuper ? 'true' : 'false'; ?>;
    const baseUrl = '<?php echo url('/supervisor/calendar'); ?>';
    const calYear  = <?php echo $calYear; ?>;
    const calMonth = <?php echo $calMonth; ?>;

    // Track which patrols this volunteer is signed up for
    let signedUpPatrols = new Set();
    let activeFilter    = 'all';
    const selectedDates = new Set(); // manager multi-select
    let upcomingCollapsed = true;

    function toggleUpcomingMenu(forceState = null) {
        const body = document.getElementById('upcomingBody');
        const btn = document.getElementById('upcomingToggleBtn');
        if (!body || !btn) return;

        upcomingCollapsed = forceState === null ? !upcomingCollapsed : !!forceState;
        body.classList.toggle('d-none', upcomingCollapsed);
        btn.setAttribute('aria-expanded', upcomingCollapsed ? 'false' : 'true');
    }

    // -- Utility helpers ---------------------------
    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function formatDateLabel(dateStr) {
        const d = new Date(`${dateStr}T00:00:00`);
        return d.toLocaleDateString('en-IE', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    }

    function statusPill(val) {
        if (val == 1) return '<span class="status-pill released">Released</span>';
        if (val == 2) return '<span class="status-pill finalized">Finalized</span>';
        if (val == 3) return '<span class="status-pill postponed">Postponed</span>';
        return '<span class="status-pill draft">Draft</span>';
    }

    function buildFinalizedMembersPanel(patrol, patrolNr) {
        if (patrol.patrol_status != 2) return '';
        const volunteers = Array.isArray(patrol.volunteers) ? patrol.volunteers : [];
        const supervisor = patrol.supervisor || null;

        let listHtml = '';
        if (volunteers.length > 0) {
            listHtml += volunteers.map(v => `<li>${escHtml(v.name || `Volunteer #${v.id}`)}</li>`).join('');
        } else {
            listHtml += `<li class="finalized-mini-empty">No volunteers assigned</li>`;
        }

        if (supervisor) {
            listHtml += `<li>${escHtml(supervisor.name || `User #${supervisor.id}`)} <span class="finalized-super-tag">SUPER</span></li>`;
        } else {
            listHtml += `<li class="finalized-mini-empty">No supervisor assigned</li>`;
        }

        return `
        <div class="finalized-mini-panel d-none" id="finalizedPanel_${patrolNr}">
            <div class="finalized-mini-title"><i class="bi bi-people-fill"></i> Finalized Patrol Team</div>
            <ul class="finalized-mini-list">${listHtml}</ul>
        </div>`;
    }

    function toggleFinalizedPanel(patrolNr) {
        const panel = document.getElementById(`finalizedPanel_${patrolNr}`);
        const btn = document.getElementById(`finalizedToggleBtn_${patrolNr}`);
        if (!panel || !btn) return;

        panel.classList.toggle('d-none');
        const expanded = !panel.classList.contains('d-none');
        btn.innerHTML = expanded
            ? '<i class="bi bi-chevron-up me-1"></i>Team'
            : '<i class="bi bi-chevron-down me-1"></i>Team';
    }

    // -- Filter logic ---------------------------------
    function setFilter(filter, btn) {
        activeFilter = filter;
        document.querySelectorAll('#filterBtns .filter-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        renderUpcomingPatrols();
    }

    function filterPatrols(patrols) {
        const now = new Date();
        const thisYear  = now.getFullYear();
        const thisMonth = now.getMonth();

        if (activeFilter === 'all') {

            return patrols;
        }

        if (activeFilter === 'this') {
            return patrols.filter(p => {
                const d = new Date(`${(p.patrolDate||'').slice(0,10)}T00:00:00`);
                return d.getFullYear() === thisYear && d.getMonth() === thisMonth;
            });
        }

        if (activeFilter === 'next') {
            const nextMonth = (thisMonth + 1) % 12;
            const nextYear  = thisMonth === 11 ? thisYear + 1 : thisYear;
            return patrols.filter(p => {
                const d = new Date(`${(p.patrolDate||'').slice(0,10)}T00:00:00`);
                return d.getFullYear() === nextYear && d.getMonth() === nextMonth;
            });
        }
        if (activeFilter === 'future') {
            return patrols.filter(p => {
                const d = new Date(`${(p.patrolDate||'').slice(0,10)}T00:00:00`);
                return d.getFullYear() > thisYear || (d.getFullYear() === thisYear && d.getMonth() > thisMonth);
            });
        }
        return patrols; // 'all'
    }

    // -- My Upcoming Patrols section --------------------------
    function renderUpcomingPatrols() {
        const container = document.getElementById('upcomingList');
        if (!container) return;

        // Keep signed-up patrols that have not ended yet (includes time on current day)
        const now = new Date();

        let myPatrols = allSchedules.filter(p => {
            if (!signedUpPatrols.has(p.patrolNr)) return false;
            if (Number(p.patrol_status) === 3) return false;

            const dateStr = (p.patrolDate || '').slice(0, 10);
            if (!dateStr) return false;

            const endTime = (p.end_time || p.start_time || '23:59:59').slice(0, 8);
            const patrolEnd = new Date(`${dateStr}T${endTime}`);
            return patrolEnd >= now;
        });

        myPatrols = filterPatrols(myPatrols);
        myPatrols.sort((a, b) => (a.patrolDate||'').localeCompare(b.patrolDate||''));

        if (myPatrols.length === 0) {
            container.innerHTML = `<div class="upcoming-empty">
                <i class="bi bi-calendar-x d-block mb-2 fs-2"></i>
                No released patrols found for this filter.
            </div>`;
            return;
        }

        container.innerHTML = myPatrols.map(p => {
            const dateStr = (p.patrolDate || '').slice(0, 10);
            const d  = new Date(`${dateStr}T00:00:00`);
            const dayNum  = d.toLocaleDateString('en-IE', { day: 'numeric' });
            const dayName = d.toLocaleDateString('en-IE', { weekday: 'short' });
            const monthName = d.toLocaleDateString('en-IE', { month: 'short', year: 'numeric' });
            const desc = escHtml(p.patrolDescription || 'Patrol');
            const superTxt = p.SuperUserNr ? `Supervisor #${p.SuperUserNr}` : 'No supervisor assigned';

            return `
            <div class="upcoming-patrol-item">
                <div class="upcoming-date-badge">
                    <div class="day-num">${dayNum}</div>
                    <div class="day-name">${dayName}</div>
                </div>
                <div class="upcoming-patrol-info">
                    <div class="upcoming-patrol-title">
                        <i class="bi bi-shield-check me-1 text-primary"></i>${desc}
                        ${statusPill(p.patrol_status)}
                    </div>
                    <div class="upcoming-patrol-meta">
                        <i class="bi bi-calendar3 me-1"></i>${monthName}
                        &nbsp;·&nbsp;<i class="bi bi-person-badge me-1"></i>${superTxt}
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="badge" style="background:#fef9c3;color:#92400e;font-size:.68rem;padding:6px 10px;">
                        <i class="bi bi-broadcast me-1"></i>Released
                    </span>
                </div>
            </div>`;
        }).join('');
    }

    // -- Mobile Agenda View ------------------------------
    function renderAgendaView() {
        const container = document.getElementById('agendaView');
        if (!container) return;

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Show patrols for this calendar month
        const firstOfMonth = new Date(calYear, calMonth - 1, 1);
        const lastOfMonth  = new Date(calYear, calMonth, 0);

        // Gather days that have patrols
        const daysWithPatrols = [];
        const seen = new Set();
        allSchedules.forEach(p => {
            const dateStr = (p.patrolDate || '').slice(0, 10);
            if (!seen.has(dateStr)) {
                const d = new Date(`${dateStr}T00:00:00`);
                if (d >= firstOfMonth && d <= lastOfMonth) {
                    seen.add(dateStr);
                    daysWithPatrols.push(dateStr);
                }
            }
        });
        daysWithPatrols.sort();

        if (daysWithPatrols.length === 0) {
            container.innerHTML = `<div class="agenda-no-days">
                <span class="agenda-empty-icon">📅</span>
                <p class="text-muted mb-0">No patrols scheduled this month.</p>
            </div>`;
            return;
        }

        const todayStr = today.toLocaleDateString('en-CA');
        let html = '';
        daysWithPatrols.forEach(dateStr => {
            const d = new Date(`${dateStr}T00:00:00`);
            const isToday = dateStr === todayStr;
            const dayLabel = d.toLocaleDateString('en-IE', { weekday: 'long', day: 'numeric', month: 'short' });
            const dayPatrols = allSchedules.filter(p => (p.patrolDate||'').slice(0,10) === dateStr);

            html += `<div class="agenda-day-row">
                <div class="agenda-day-header">
                    <div class="agenda-day-label${isToday ? ' today-label' : ''}">${dayLabel}${isToday ? ' <span class="badge bg-primary ms-1" style="font-size:.65rem;">Today</span>' : ''}</div>
                </div>`;

            dayPatrols.forEach(p => {
                const desc = escHtml(p.patrolDescription || 'Open Patrol');
                const isSignedUp = signedUpPatrols.has(p.patrolNr);
                let signupLabel;
                if (isSupervisor) {
                    signupLabel = '<span class="badge ms-1" style="background:#fef9c3;color:#92400e;font-size:.68rem;"><i class="bi bi-broadcast me-1"></i>Released</span>';
                } else if (isSignedUp) {
                    signupLabel = '<span class="badge bg-success ms-1" style="font-size:.68rem;"><i class="bi bi-check2"></i> Signed Up</span>';
                } else {
                    signupLabel = '<span class="badge bg-light text-secondary ms-1" style="font-size:.68rem;">Tap to sign up</span>';
                }

                html += `<div class="agenda-patrol-card" role="button" tabindex="0"
                             onclick="selectDay('${dateStr}')"
                             onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();selectDay('${dateStr}');}">
                    <div class="agenda-patrol-info">
                        <div class="agenda-patrol-title">${desc} ${signupLabel}</div>
                    </div>
                </div>`;
            });

            html += `</div>`;
        });

        container.innerHTML = html;
    }

    // -- calendar nav ----------------------------------
    function changeMonth(dir) {
        const url = new URL(window.location.href);
        let y = calYear, m = calMonth;
        m += dir;
        if (m < 1) { m = 12; y--; }
        if (m > 12) { m = 1;  y++; }

        if (!isManager && !isSupervisor) {
            // Volunteers: restrict to current month and next month only
            const now   = new Date();
            const nowY  = now.getFullYear();
            const nowM  = now.getMonth() + 1;
            let nextM   = nowM + 1, nextY = nowY;
            if (nextM > 12) { nextM = 1; nextY++; }

            if (y < nowY || (y === nowY && m < nowM)) return;
            if (y > nextY || (y === nextY && m > nextM)) return;
        }

        url.searchParams.set('month', m);
        url.searchParams.set('year',  y);
        window.location.href = url.toString();
    }

    // -- Select a day --------------------------------
    function selectDay(dateStr) {
        if (isManager) {
            // Manager: always toggle multi-select (patrol or empty day)
            toggleManagerSelect(dateStr);
        } else {
            // Volunteer: highlight and show sign-up detail panel
            document.querySelectorAll('.cal-day.selected').forEach(el => el.classList.remove('selected'));
            const cell = document.querySelector(`.cal-day[data-date="${dateStr}"]`);
            if (cell) cell.classList.add('selected');
            renderDetailPanel(dateStr);
        }
    }

    // -- Manager multi-select ---------------------------------
    function toggleManagerSelect(dateStr) {
        const cell = document.querySelector(`.cal-day[data-date="${dateStr}"]`);
        if (!cell) return;

        if (selectedDates.has(dateStr)) {
            selectedDates.delete(dateStr);
            cell.classList.remove('manager-selected');
        } else {
            selectedDates.add(dateStr);
            cell.classList.add('manager-selected');
        }
        updateManagerToolbar();
    }

    function updateManagerToolbar() {
        const count   = selectedDates.size;
        const countEl = document.getElementById('selCountNum');
        const selEl   = document.getElementById('selectionCount');
        if (countEl) countEl.textContent = count;
        if (selEl)   selEl.classList.toggle('d-none', count === 0);
        updateSelectionPanel();
    }

    function clearSelection() {
        selectedDates.clear();
        document.querySelectorAll('.cal-day.manager-selected').forEach(el => el.classList.remove('manager-selected'));
        updateManagerToolbar();
    }

    // -- Create schedules (empty dates only) ------------------
    async function createSelectedSchedules() {
        const today = new Date().toISOString().split('T')[0];
        const emptyDates = [...selectedDates].filter(d =>
            d >= today &&
            !allSchedules.some(s => (s.patrolDate||'').slice(0,10) === d)
        );
        const skipped = [...selectedDates].filter(d => d < today).length;
        if (skipped > 0) showToast(`${skipped} past date(s) skipped.`, 'warning');
        if (emptyDates.length === 0) return;

        document.querySelectorAll('#selPanelBody .sel-action-btn').forEach(b => b.disabled = true);

        const results = await Promise.allSettled(emptyDates.map(async (date) => {
            const res = await fetch('/api/schedules', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    patrolDate: date + ' 00:00:00',
                    patrol_status: 0,
                    patrolDescription: 'Regular Patrol'
                })
            });
            if (!res.ok) throw new Error(`Failed for ${date}`);
        }));

        const failed = results.filter(r => r.status === 'rejected').length;
        if (failed > 0) {
            showToast(`${emptyDates.length - failed} created, ${failed} failed.`, 'danger');
            document.querySelectorAll('#selPanelBody .sel-action-btn').forEach(b => b.disabled = false);
        } else {
            showToast(`${emptyDates.length} patrol(s) created!`, 'success');
            setTimeout(() => window.location.reload(), 800);
        }
    }

    // -- Selection panel helpers -----------------------
    function getSelectedPatrolIds() {
        const ids = [];
        selectedDates.forEach(dateStr => {
            allSchedules.filter(s => (s.patrolDate||'').slice(0,10) === dateStr)
                         .forEach(p => ids.push(p.patrolNr));
        });
        return ids;
    }

    function updateSelectionPanel() {
        const body  = document.getElementById('selPanelBody');
        const badge = document.getElementById('selCountBadge');
        if (!body) return;

        const count = selectedDates.size;
        if (badge) {
            badge.textContent = count;
            badge.classList.toggle('d-none', count === 0);
        }

        if (count === 0) {
            body.innerHTML = `<div class="sel-panel-empty">
                <i class="bi bi-hand-index-thumb d-block mb-2 fs-2 text-muted"></i>
                Click dates on the calendar to select them
            </div>`;
            return;
        }

        const patrolIds  = getSelectedPatrolIds();
        const emptyDates = [...selectedDates].filter(d =>
            !allSchedules.some(s => (s.patrolDate||'').slice(0,10) === d)
        );
        const hasPatrols = patrolIds.length > 0;
        const hasEmpty   = emptyDates.length > 0;

        let html = `<p class="small text-muted mb-3"><strong>${count}</strong> date${count !== 1 ? 's' : ''} selected`;
        if (patrolIds.length > 0) html += ` &middot; <strong>${patrolIds.length}</strong> patrol${patrolIds.length !== 1 ? 's' : ''}`;
        html += `</p>`;

        if (hasPatrols) {
            if (patrolIds.length === 1) {
                html += `<button class="sel-action-btn s-edit" onclick="openEditSelected()"><i class="bi bi-pencil-square"></i> Edit Patrol</button>`;
            }
            html += `
            <button class="sel-action-btn s-draft"    onclick="bulkSetStatus(0)"><i class="bi bi-pencil"></i> Set Draft</button>
            <button class="sel-action-btn s-release"  onclick="bulkSetStatus(1)"><i class="bi bi-unlock"></i> Release</button>
            <button class="sel-action-btn s-finalize" onclick="bulkSetStatus(2)"><i class="bi bi-check-circle"></i> Finalize</button>
            <button class="sel-action-btn s-delete"   onclick="bulkDelete()"><i class="bi bi-trash"></i> Delete${patrolIds.length > 1 ? ' (' + patrolIds.length + ')' : ''}</button>`;
        }
        if (hasEmpty) {
            html += `
            <button class="sel-action-btn s-create" onclick="createSelectedSchedules()"><i class="bi bi-plus-circle"></i> Create ${emptyDates.length} schedule${emptyDates.length !== 1 ? 's' : ''}</button>`;
        }
        html += `<button class="sel-clear-link" onclick="clearSelection()"><i class="bi bi-x me-1"></i>Clear selection</button>`;
        body.innerHTML = html;
    }

    // -- Bulk status change ---------------------------------
    async function bulkSetStatus(newStatus) {
        const patrolIds = getSelectedPatrolIds();
        if (patrolIds.length === 0) return;
        const labels = { 0: 'Draft', 1: 'Released', 2: 'Finalized' };
        document.querySelectorAll('#selPanelBody .sel-action-btn').forEach(b => b.disabled = true);
        const results = await Promise.allSettled(patrolIds.map(id =>
            fetch(`/api/schedules/${id}/status`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ patrol_status: newStatus })
            }).then(r => { if (!r.ok) throw new Error(); })
        ));
        const failed = results.filter(r => r.status === 'rejected').length;
        if (failed > 0) {
            showToast(`${patrolIds.length - failed} updated, ${failed} failed.`, 'danger');
            document.querySelectorAll('#selPanelBody .sel-action-btn').forEach(b => b.disabled = false);
        } else {
            showToast(`${patrolIds.length} patrol${patrolIds.length !== 1 ? 's' : ''} set to ${labels[newStatus]}.`, 'success');
            setTimeout(() => window.location.reload(), 700);
        }
    }

    // -- Bulk delete ---------------------------------
    async function bulkDelete() {
        const patrolIds = getSelectedPatrolIds();
        if (patrolIds.length === 0) return;
        const msg = patrolIds.length === 1
            ? 'Delete this patrol? This cannot be undone.'
            : `Delete ${patrolIds.length} patrols? This cannot be undone.`;
        if (!confirm(msg)) return;
        document.querySelectorAll('#selPanelBody .sel-action-btn').forEach(b => b.disabled = true);
        const results = await Promise.allSettled(patrolIds.map(id =>
            fetch(`/api/schedules/${id}`, { method: 'DELETE' })
                .then(r => { if (!r.ok) throw new Error(); })
        ));
        const failed = results.filter(r => r.status === 'rejected').length;
        if (failed > 0) {
            showToast(`${patrolIds.length - failed} deleted, ${failed} failed.`, 'danger');
            document.querySelectorAll('#selPanelBody .sel-action-btn').forEach(b => b.disabled = false);
        } else {
            showToast(`${patrolIds.length} patrol${patrolIds.length !== 1 ? 's' : ''} deleted.`, 'secondary');
            setTimeout(() => window.location.reload(), 700);
        }
    }

    // -- Render detail panel -----------------------------
    function renderDetailPanel(dateStr) {
        const panel   = document.getElementById('dayDetailPanel');
        const heading = document.getElementById('detailDateLabel');
        const body    = document.getElementById('detailBody');

        heading.textContent = formatDateLabel(dateStr);

        const dayPatrols = allSchedules.filter(s => (s.patrolDate || '').slice(0,10) === dateStr);

        if (dayPatrols.length === 0) {
            body.innerHTML = `<div class="text-center text-muted py-4">
                <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                No open patrols on this date.
            </div>`;
        } else {
            body.innerHTML = dayPatrols.map(p => renderPatrolCard(p)).join('');
        }

        panel.style.display = 'block';
        setTimeout(() => panel.scrollIntoView({behavior:'smooth', block:'start'}), 80);
    }

    // Map of patrolNr → signed-up count (loaded from API)
    const patrolSignupCounts = {};

    function getTeamCount(patrol) {
        const nr = patrol.patrolNr;
        const rosterVolunteerCount = Array.isArray(patrol.volunteers) ? patrol.volunteers.length : 0;
        const countedVolunteerCount = patrolSignupCounts[nr] || 0;
        const volunteersTotal = Math.max(rosterVolunteerCount, countedVolunteerCount);
        return volunteersTotal + (patrol.SuperUserNr ? 1 : 0);
    }

    function renderPatrolCard(patrol) {
        const nr  = patrol.patrolNr;
        const desc = patrol.patrolDescription || 'Regular Scheduled Patrol';
        const isSignedUp = signedUpPatrols.has(nr);
        const count      = getTeamCount(patrol);
        const superTxt   = patrol.SuperUserNr ? `Supervisor on duty` : 'No supervisor assigned';
        const finalizedPanel = buildFinalizedMembersPanel(patrol, nr);
        const isFinalized = patrol.patrol_status == 2;
        const isPostponed = patrol.patrol_status == 3;
        let actionBtn;
        if (isFinalized) {
            actionBtn = `<span class="badge" style="background:#dcfce7;color:#166534;padding:8px 14px;font-size:.82rem;"><i class="bi bi-lock me-1"></i>Finalized</span>`;
        } else if (isPostponed) {
            actionBtn = `<span class="badge" style="background:#e5e7eb;color:#374151;padding:8px 14px;font-size:.82rem;"><i class="bi bi-clock-history me-1"></i>Postponed</span>`;
        } else if (isSignedUp) {
            actionBtn = `<button class="btn btn-sm btn-outline-danger signup-btn" onclick="cancelSignup(${nr})">
                   <i class="bi bi-x-circle me-1"></i>Cancel Sign-up
               </button>`;
        } else {
            actionBtn = `<button class="btn btn-sm btn-primary signup-btn" onclick="doSignup(${nr})">
                   <i class="bi bi-person-plus me-1"></i>Sign Up
               </button>`;
        }

        // Build small member avatars placeholder
        const avatarColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6'];
        let avatarHtml = '';
        for (let i = 0; i < Math.min(count, 5); i++) {
            avatarHtml += `<span class="member-avatar-sm" style="background:${avatarColors[i % avatarColors.length]};">
                <i class="bi bi-person-fill" style="font-size:.65rem;"></i></span>`;
        }
        if (count > 5) {
            avatarHtml += `<span class="member-avatar-sm" style="background:#94a3b8;font-size:.6rem;">+${count-5}</span>`;
        }

        return `
        <div class="patrol-open-card" id="patrolCard_${nr}">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-2">
                <div>
                    <div class="fw-bold fs-6 mb-1">
                        <i class="bi bi-shield-check me-1 text-primary"></i>Patrol #${nr}
                        <span class="badge bg-success ms-1" style="font-size:.7rem;">Open</span>
                    </div>
                    <div class="small text-secondary mb-1"><i class="bi bi-card-text me-1"></i>${escHtml(desc)}</div>
                    <div class="small text-muted"><i class="bi bi-person-badge me-1"></i>${superTxt}</div>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    <div id="btnArea_${nr}">${actionBtn}</div>
                    ${isFinalized ? `<button type="button" class="btn btn-outline-success finalized-mini-toggle" id="finalizedToggleBtn_${nr}" onclick="toggleFinalizedPanel(${nr})"><i class="bi bi-chevron-down me-1"></i>Team</button>` : ''}
                </div>
            </div>
            <div class="member-count-bar">
                <div class="member-avatars">${avatarHtml}</div>
                <span class="member-count-text">
                    <strong>${count}</strong> ${count === 1 ? 'person' : 'people'} signed up
                </span>
            </div>
            ${finalizedPanel}
        </div>`;
    }

    function closeDetail() {
        document.getElementById('dayDetailPanel').style.display = 'none';
        document.querySelectorAll('.cal-day.selected').forEach(el => el.classList.remove('selected'));
    }

    // -- Sign-up / Cancel ------------------------------
    async function doSignup(patrolNr) {
        const btn = document.querySelector(`#btnArea_${patrolNr} button`);
        if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }

        try {
            const res  = await fetch('/api/roster', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ patrolNr })
            });
            const data = await res.json();
            if (res.ok || data.alreadySignedUp) {
                signedUpPatrols.add(patrolNr);
                refreshSmileyIndicators();
                refreshCard(patrolNr);
                renderUpcomingPatrols();
                showToast(data.message || 'Signed up successfully!', 'success');
            } else {
                showToast(data.message || 'Could not sign up.', 'danger');
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-person-plus me-1"></i>Sign Up'; }
            }
        } catch {
            showToast('Network error — please try again.', 'danger');
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-person-plus me-1"></i>Sign Up'; }
        }
    }

    async function cancelSignup(patrolNr) {
        if (!confirm('Remove yourself from Patrol #' + patrolNr + '?')) return;
        const btn = document.querySelector(`#btnArea_${patrolNr} button`);
        if (btn) { btn.disabled = true; }

        try {
            const res  = await fetch(`/api/roster/${patrolNr}`, { method: 'DELETE' });
            const data = await res.json();
            if (res.ok) {
                signedUpPatrols.delete(patrolNr);
                refreshSmileyIndicators();
                refreshCard(patrolNr);
                renderUpcomingPatrols();
                showToast('Removed from Patrol #' + patrolNr, 'secondary');
            } else {
                showToast(data.message || 'Could not remove.', 'danger');
                if (btn) btn.disabled = false;
            }
        } catch {
            showToast('Network error.', 'danger');
            if (btn) btn.disabled = false;
        }
    }

    function refreshCard(patrolNr) {
        const patrol = allSchedules.find(s => s.patrolNr === patrolNr);
        if (!patrol) return;
        // Refresh count after signup/cancel
        if (signedUpPatrols.has(patrolNr)) {
            patrolSignupCounts[patrolNr] = (patrolSignupCounts[patrolNr] || 0) + 1;
        } else {
            patrolSignupCounts[patrolNr] = Math.max(0, (patrolSignupCounts[patrolNr] || 1) - 1);
        }
        const container = document.getElementById(`patrolCard_${patrolNr}`);
        if (!container) return;
        const tmp = document.createElement('div');
        tmp.innerHTML = renderPatrolCard(patrol);
        container.replaceWith(tmp.firstElementChild);
    }

    // -- Edit single patrol -------------------------------
    let _editingPatrol = null;

    function openEditSelected() {
        const ids = getSelectedPatrolIds();
        if (ids.length !== 1) return;
        _editingPatrol = allSchedules.find(s => s.patrolNr === ids[0]);
        if (!_editingPatrol) return;

        document.getElementById('editModalPatrolLabel').textContent = '— Patrol #' + _editingPatrol.patrolNr;
        document.getElementById('editPatrolDesc').value = _editingPatrol.patrolDescription || '';
        document.getElementById('editPatrolStatusSel').value = String(_editingPatrol.patrol_status ?? 0);
        const supSel = document.getElementById('editPatrolSupervisor');
        supSel.value = _editingPatrol.SuperUserNr ? String(_editingPatrol.SuperUserNr) : '';

        bootstrap.Modal.getOrCreateInstance(document.getElementById('editPatrolModal')).show();
    }

    async function submitEditPatrol() {
        if (!_editingPatrol) return;
        const btn = document.querySelector('#editPatrolModal .btn-primary');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

        const body = {
            patrolDate:        (_editingPatrol.patrolDate || '').slice(0,10) + ' 00:00:00',
            patrolDescription: document.getElementById('editPatrolDesc').value || 'Regular Scheduled Patrol',
            SuperUserNr:       document.getElementById('editPatrolSupervisor').value || null,
            patrol_status:     parseInt(document.getElementById('editPatrolStatusSel').value)
        };

        try {
            const res = await fetch(`/api/schedules/${_editingPatrol.patrolNr}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body)
            });
            const data = await res.json();
            if (res.ok && data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editPatrolModal')).hide();
                showToast('Patrol updated!', 'success');
                setTimeout(() => window.location.reload(), 700);
            } else {
                showToast(data.message || 'Update failed.', 'danger');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save Changes';
            }
        } catch {
            showToast('Network error.', 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save Changes';
        }
    }

    // -- Toast helper -------------------------
    // -- Smiley indicators on signed-up released dates -----------
    function refreshSmileyIndicators() {
        if (isManager) return; // managers don't sign up — no smiley
        document.querySelectorAll('.cal-day:not(.empty)').forEach(cell => {
            const dateStr = cell.dataset.date;
            if (!dateStr) return;
            // Remove existing smiley
            const existing = cell.querySelector('.cal-smiley');
            if (existing) existing.remove();
            // Check: any patrol on this date is Released (status=1) AND signed up
            const hasSignedUpReleased = allSchedules.some(p =>
                (p.patrolDate || '').slice(0, 10) === dateStr &&
                p.patrol_status == 1 &&
                signedUpPatrols.has(p.patrolNr)
            );
            if (hasSignedUpReleased) {
                const span = document.createElement('span');
                span.className = 'cal-smiley';
                span.textContent = '😊';
                span.title = 'You\'re signed up!';
                cell.appendChild(span);
            }
        });
    }

    function showToast(msg, type = 'success') {
        const el = document.getElementById('signupToast');
        const colorMap = { success:'bg-success', danger:'bg-danger', secondary:'bg-secondary' };
        el.className = `toast align-items-center text-white border-0 ${colorMap[type] || 'bg-primary'}`;
        document.getElementById('toastMsg').textContent = msg;
        bootstrap.Toast.getOrCreateInstance(el, { delay: 3000 }).show();
    }

    // -- On load ----------------------------------------------
    document.addEventListener('DOMContentLoaded', async () => {
        // Load current user's signed-up patrols
        try {
            const res  = await fetch('/api/roster/my');
            if (res.ok) {
                const data = await res.json();
                signedUpPatrols = new Set((data.patrols || []).map(Number));
            }
        } catch { /* silently ignore */ }

        // Load signup counts for all displayed patrols
        const patrolNrs = allSchedules.map(p => p.patrolNr);
        if (patrolNrs.length > 0) {
            try {
                const res = await fetch('/api/roster/counts?ids=' + patrolNrs.join(','));
                if (res.ok) {
                    const data = await res.json();
                    Object.assign(patrolSignupCounts, data.counts || {});
                }
            } catch { /* silently ignore */ }
        }

        // Render all dynamic sections
        renderUpcomingPatrols();
        refreshSmileyIndicators();

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
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>