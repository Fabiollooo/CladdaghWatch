<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claddagh | Roster Resourcing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
</head>
<body class="pg-wrapper">

<?php @require resource_path('views/templates/admin/adminHeader.php'); ?>


<!-- ************************ -->

 <!-- This page was Retired !!!!! -->

 <!-- ************************ -->



<!---- Toolbar ----------------------------------------------------------->
<div class="pg-toolbar">
    <div class="pg-toolbar-title">
        <i class="bi bi-people"></i>
        Roster Resourcing
    </div>
    <div class="d-flex align-items-center gap-2">
        <label for="roleSelect" class="small text-secondary mb-0">View as:</label>
        <select id="roleSelect" class="form-select form-select-sm" style="width:auto;" onchange="setRole(this.value)">
            <option value="operations">Operations Manager</option>
            <option value="supervisor">Patrol Supervisor</option>
            <option value="volunteer">Volunteer</option>
        </select>
    </div>
</div>

<div class="pg-container">

    <!---- Stat Cards ----------------------------------------------------------->
    <div class="pg-stat-grid">
        <div class="pg-stat-card" style="cursor:pointer;" onclick="setActiveTab('unreleased')">
            <div class="pg-stat-label">Unreleased</div>
            <div class="pg-stat-value" id="statUnreleased">0</div>
        </div>

        <div class="pg-stat-card green" style="cursor:pointer;" onclick="setActiveTab('released')">
            <div class="pg-stat-label">Released</div>
            <div class="pg-stat-value" id="statReleased">0</div>
        </div>

        <div class="pg-stat-card yellow" style="cursor:pointer;" onclick="setActiveTab('unreleased')">
            <div class="pg-stat-label">Under-resourced</div>
            <div class="pg-stat-value" id="statUnder">0</div>
        </div>

        <div class="pg-stat-card red" style="cursor:pointer;" onclick="setActiveTab('unreleased')">
            <div class="pg-stat-label">Not Ready</div>
            <div class="pg-stat-value" id="statNotReady">0</div>
        </div>
    </div>

    <!---- Tab switcher ----------------------------------------------------------->
    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-primary" id="tabBtnUnreleased" onclick="setActiveTab('unreleased')">
            <i class="bi bi-lock me-1"></i>Unreleased
        </button>
        <button class="btn btn-outline-primary" id="tabBtnReleased" onclick="setActiveTab('released')">
            <i class="bi bi-unlock me-1"></i>Released &amp; Sign-ups
        </button>
    </div>

    <!---- Unreleased Panel ----------------------------------------------------------->
    <div id="panelUnreleased">
        <div class="pg-card">
            <div class="pg-card-header">
                <h2><i class="bi bi-grid-3x2-gap me-2"></i>Upcoming Unreleased Patrols</h2>
            </div>
            <div class="pg-card-body">
                <div id="patrolOverview"></div>
            </div>
        </div>
    </div>

    <!---- Released Panel ----------------------------------------------------------->
    <div id="panelReleased" style="display:none;">
        <div class="pg-card">
            <div class="pg-card-header">
                <h2><i class="bi bi-people me-2"></i>Released Patrols &amp; Sign-ups</h2>
            </div>
            <div class="pg-card-body">
                <div id="releasedOverview"></div>
            </div>
        </div>
    </div>

    <!---- Release Control Table (Operations Manager only) --------------------------------->
    <div class="pg-card" id="mgmtCard">
        <div class="pg-card-header">
            <h2><i class="bi bi-unlock me-2"></i>Roster Release Control</h2>
            <small class="text-secondary">Click <strong>Release</strong> to make a patrol visible to volunteers.</small>
        </div>
        <div class="table-responsive">
            <table class="pg-table">
                <thead>
                    <tr>
                        <th>Patrol</th>
                        <th>Date / Time</th>
                        <th>Volunteers</th>
                        <th>SUPER</th>
                        <th>Release Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody id="mgmtTableBody"></tbody>
            </table>
        </div>
    </div>

</div>

<!---- Confirm Release Modal ----------------------------------------------------------->
<div class="modal fade" id="releaseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#1e3a5f; color:#fff;">
                <h5 class="modal-title" style="color:#fff;" id="releaseModalTitle">Release Patrol Roster</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="releaseModalMsg" class="mb-0">Releasing this roster will make it visible to all users and lock volunteer edits.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmRelease()">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentRole = 'operations';
    let currentFilter = 'all';
    let pendingReleaseId = null;
    let releaseModal;

    // Real data from server – upcoming unreleased patrols
    const patrols = <?php echo $patrolsJson ?? '[]'; ?>;

    document.addEventListener('DOMContentLoaded', () => {
        releaseModal = new bootstrap.Modal(document.getElementById('releaseModal'));
        render();
    });

    let activeTab = 'unreleased';

    function setActiveTab(tab) {
        activeTab = tab;
        document.getElementById('panelUnreleased').style.display  = tab === 'unreleased' ? '' : 'none';
        document.getElementById('panelReleased').style.display     = tab === 'released'   ? '' : 'none';
        document.getElementById('tabBtnUnreleased').className = tab === 'unreleased' ? 'btn btn-primary'         : 'btn btn-outline-primary';
        document.getElementById('tabBtnReleased').className   = tab === 'released'   ? 'btn btn-primary'         : 'btn btn-outline-primary';
        if (tab === 'released') renderReleasedPanel();
    }

    function setRole(role) {
        currentRole = role;
        render();
    }

    function filterPatrols(status) {
        currentFilter = status;
        renderOverview();
    }

    function render() {
        renderStats();
        renderOverview();
        if (activeTab === 'released') renderReleasedPanel();
        renderMgmtTable();
        document.getElementById('mgmtCard').style.display = currentRole === 'operations' ? '' : 'none';
    }

    function renderStats() {
        const unreleased = patrols.filter(p => !p.released);
        const released   = patrols.filter(p => p.released);
        document.getElementById('statUnreleased').textContent = unreleased.length;
        document.getElementById('statReleased').textContent   = released.length;
        document.getElementById('statUnder').textContent      = unreleased.filter(p => p.status === 'under-resourced').length;
        document.getElementById('statNotReady').textContent   = unreleased.filter(p => p.status === 'not-ready').length;
    }

    function renderReleasedPanel() {
        const wrap = document.getElementById('releasedOverview');
        const released = patrols.filter(p => p.released);
        if (released.length === 0) {
            wrap.innerHTML = `<div class="text-center py-5 text-secondary">
                <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                <p class="fw-semibold mb-1">No released patrols yet</p>
                <p class="small">Go to the Unreleased tab to release a patrol.</p>
            </div>`;
            return;
        }
        wrap.innerHTML = released.map(p => {
            const dt     = new Date(p.datetime);
            const dtStr  = dt.toLocaleDateString('en-IE', {day:'numeric', month:'short', year:'numeric'});
            const signed = p.volunteers.current;
            const req    = p.volunteers.required;
            const pct    = req > 0 ? Math.round((signed / req) * 100) : 0;
            const fillCls = pct >= 100 ? 'full' : pct < 50 ? 'low' : '';
            const volChips = p.volunteers.list.length
                ? p.volunteers.list.map(v => `<span class="pg-vol-chip">${escHtml(v.name)}</span>`).join('')
                : `<span class="text-secondary small fst-italic">No sign-ups yet</span>`;
            const superChip = `<span class="pg-vol-chip supervisor"><i class="bi bi-person-badge me-1"></i>${escHtml(p.supervisor.name)}</span>`;
            return `
            <div class="pg-patrol-card" style="border-left:4px solid #16a34a;">
                <div class="pg-patrol-card-header">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="pg-patrol-number"><i class="bi bi-shield-check me-1 text-success"></i>${escHtml(p.number)}</span>
                        <small class="text-secondary">${dtStr}</small>
                        ${p.patrolDescription ? `<small class="text-secondary fst-italic">${escHtml(p.patrolDescription)}</small>` : ''}
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge-released">Released</span>
                        <button class="btn btn-sm btn-outline-warning" onclick="openReleaseModal(${p.id})">
                            <i class="bi bi-lock me-1"></i>Unrelease
                        </button>
                    </div>
                </div>
                <div class="pg-progress-wrap mt-2">
                    <div class="pg-progress-label">
                        Sign-ups: <strong>${signed} / ${req}</strong>
                        ${signed < req
                            ? `<span class="text-danger ms-1">(${req - signed} spot${req-signed===1?'':'s'} remaining)</span>`
                            : '<span class="text-success ms-1">&#10003; Full</span>'}
                    </div>
                    <div class="pg-progress-bar">
                        <div class="pg-progress-fill ${fillCls}" style="width:${Math.min(pct,100)}%"></div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="small fw-semibold text-secondary mb-1"><i class="bi bi-people me-1"></i>Signed-up volunteers</div>
                    <div>${volChips}${superChip}</div>
                </div>
            </div>`;
        }).join('');
    }

    function renderOverview() {
        const wrap = document.getElementById('patrolOverview');
        const unreleased = patrols.filter(p => !p.released);

        if (unreleased.length === 0) {
            wrap.innerHTML = `<div class="text-center py-5 text-secondary">
                <i class="bi bi-calendar-check fs-1 d-block mb-3"></i>
                <p class="fw-semibold mb-1">No upcoming unreleased patrols</p>
                <p class="small">All scheduled patrols have been released, or none are scheduled yet.</p>
                <a href="/schedules" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-plus-lg me-1"></i>Create Patrol</a>
            </div>`;
            return;
        }

        let filtered = unreleased;
        if (currentFilter !== 'all') {
            filtered = unreleased.filter(p => p.status === currentFilter);
        }
        
        wrap.innerHTML = filtered.map(p => {
            const pct       = Math.round((p.volunteers.current / p.volunteers.required) * 100);
            const fillClass = pct >= 100 ? 'full' : pct < 50 ? 'low' : '';
            const relBadge  = p.released
                ? '<span class="badge-released">Released</span>'
                : '<span class="badge-unreleased">Not Released</span>';
            const stBadge   = statusBadge(p.status);
            const dt        = new Date(p.datetime);
            const dtStr     = dt.toLocaleDateString('en-IE', {day:'numeric', month:'short', year:'numeric'})
                            + ' · ' + dt.toLocaleTimeString('en-IE', {hour:'2-digit', minute:'2-digit'});

            // Volunteer chips
            const volChips = p.volunteers.list.map(v =>
                `<span class="pg-vol-chip">${escHtml(v.name)}</span>`
            ).join('');

            const superChip = `<span class="pg-vol-chip supervisor">
                <i class="bi bi-person-badge me-1"></i>
                ${escHtml(p.supervisor.name)}
                ${p.supervisor.available ? '' : ' <span class="badge bg-danger ms-1 small">Unavailable</span>'}
            </span>`;

            return `
            <div class="pg-patrol-card">
                <div class="pg-patrol-card-header">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="pg-patrol-number"><i class="bi bi-shield-check me-1 text-primary"></i>${p.number}</span>
                        <small class="text-secondary">${dtStr}</small>
                    </div>
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        ${relBadge}
                        ${stBadge}
                    </div>
                </div>

                <div class="pg-progress-wrap">
                    <div class="pg-progress-label">
                        Volunteers: <strong>${p.volunteers.current} / ${p.volunteers.required}</strong>
                        ${p.volunteers.current < p.volunteers.required
                            ? `<span class="text-danger ms-1">(${p.volunteers.required - p.volunteers.current} more needed)</span>`
                            : '<span class="text-success ms-1"> Sufficient</span>'}
                    </div>
                    <div class="pg-progress-bar">
                        <div class="pg-progress-fill ${fillClass}" style="width:${Math.min(pct,100)}%"></div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="small fw-semibold text-secondary mb-1"><i class="bi bi-people me-1"></i>Team</div>
                    <div>${volChips}${superChip}</div>
                </div>
            </div>`;
        }).join('');
    }

    function renderMgmtTable() {
        const tbody = document.getElementById('mgmtTableBody');
        if (!tbody) return;
        const unreleased = patrols.filter(p => !p.released);
        if (unreleased.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center text-secondary py-4">
                <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                All patrols have been released.
            </td></tr>`;
            return;
        }
        tbody.innerHTML = unreleased.map(p => {
            const dt    = new Date(p.datetime);
            const dtStr = dt.toLocaleDateString('en-IE', {day:'numeric',month:'short'}) + ' ' + dt.toLocaleTimeString('en-IE',{hour:'2-digit',minute:'2-digit'});
            return `<tr>
                <td class="fw-semibold" style="color:#1e3a5f;">${p.number}</td>
                <td>${dtStr}</td>
                <td>${p.volunteers.current} / ${p.volunteers.required}</td>
                <td>${escHtml(p.supervisor.name)}
                    ${p.supervisor.available ? '<i class="bi bi-check-circle text-success ms-1"></i>' : '<i class="bi bi-x-circle text-danger ms-1"></i>'}
                </td>
                <td><span class="badge-unreleased">Not Released</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-success" onclick="openReleaseModal(${p.id})">
                        <i class="bi bi-unlock me-1"></i>Release
                    </button>
                </td>
            </tr>`;
        }).join('');
    }

    function openReleaseModal(id) {
        pendingReleaseId = id;
        const p = patrols.find(x => x.id === id);
        const action = p.released ? 'Unrelease' : 'Release';
        document.getElementById('releaseModalTitle').textContent = `${action} Patrol ${p.number}`;
        document.getElementById('releaseModalMsg').textContent = p.released
            ? `This will mark Patrol ${p.number} as Not Released, hiding it from volunteers.`
            : `Releasing Patrol ${p.number} will make it visible to all users and lock volunteer edits (Operations Manager can still edit).`;
        releaseModal.show();
    }

    function confirmRelease() {
        const p = patrols.find(x => x.id === pendingReleaseId);
        if (!p) { releaseModal.hide(); return; }
        const newStatus = p.released ? 0 : 1;
        const btn = document.querySelector('#releaseModal .btn-primary');
        btn.disabled = true;
        btn.textContent = 'Saving…';
        fetch(`/api/schedules/${p.id}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ patrol_status: newStatus })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                p.released = !p.released;
                p.patrol_status = newStatus;
                // Switch tab to show where the patrol just moved
                setActiveTab(p.released ? 'released' : 'unreleased');
            } else {
                alert('Error: ' + (data.message || 'Could not update patrol status.'));
            }
        })
        .catch(e => alert('Network error: ' + e.message))
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Confirm';
            releaseModal.hide();
            render();
        });
    }

    function statusBadge(status) {
        const map = {
            'sufficient':     ['badge-released',   'Sufficient'],
            'under-resourced':['badge-unreleased',  'Under-resourced'],
            'not-ready':      ['badge-cancelled',   'Not Ready']
        };
        const [cls, label] = map[status] || ['badge-draft', status];
        return `<span class="${cls}">${label}</span>`;
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>
