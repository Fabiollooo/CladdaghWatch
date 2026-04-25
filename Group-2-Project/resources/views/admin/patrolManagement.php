<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claddagh | Patrol Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
    
</head>
<body class="pg-wrapper">


<!-- ************************ -->

 <!-- This page was Retired !!!!! -->

 <!-- ************************ -->





<?php @require resource_path('views/templates/admin/adminHeader.php'); ?>

<!---- Toolbar ------------------------------------>
<div class="pg-toolbar">
    <div class="pg-toolbar-title">
        <i class="bi bi-shield-check"></i>
        Patrol Management
    </div>
    <a href="/patrolCreate" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i> Create Patrol
    </a>
</div>

<div class="pg-container">

    <!---- Stat Cards ------------------------------------->
    <div class="pg-stat-grid">
        <div class="pg-stat-card">
            <div class="pg-stat-label">Total Patrols</div>
            <div class="pg-stat-value" id="totalPatrols">0</div>
        </div>
        <div class="pg-stat-card green">
            <div class="pg-stat-label">Upcoming</div>
            <div class="pg-stat-value" id="upcomingPatrols">0</div>
        </div>
        <div class="pg-stat-card yellow">
            <div class="pg-stat-label">Published</div>
            <div class="pg-stat-value" id="publishedPatrols">0</div>
        </div>
        <div class="pg-stat-card red">
            <div class="pg-stat-label">Drafts</div>
            <div class="pg-stat-value" id="draftPatrols">0</div>
        </div>
    </div>

    <!----- Patrol Table -------------------------->
    <div class="pg-card">
        <div class="pg-card-header">
            <h2><i class="bi bi-list-ul me-2"></i>All Patrols</h2>
        </div>
        <div class="table-responsive">
            <table class="pg-table">
                <thead>
                    <tr>
                        <th>Patrol #</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="patrolTable"></tbody>
            </table>
        </div>
        <div id="emptyState" class="pg-empty d-none">
            <i class="bi bi-calendar-x"></i>
            <p class="mb-3">No patrols scheduled yet.</p>
            <a href="/patrolCreate" class="btn btn-primary btn-sm">Create First Patrol</a>
        </div>
    </div>

</div>

<!----- View Modal ------------------------------------->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#1e3a5f; color:#fff;">
                <div>
                    <h5 class="modal-title fw-bold" id="viewPatrolNumber" style="color:#fff;"></h5>
                    <small id="viewStatus" class="opacity-75"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <div class="small fw-semibold text-secondary mb-1"><i class="bi bi-calendar3 me-1"></i>Date</div>
                            <div id="viewDate"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <div class="small fw-semibold text-secondary mb-1"><i class="bi bi-clock me-1"></i>Time</div>
                            <div id="viewTime">—</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-light rounded-3 p-3">
                            <div class="small fw-semibold text-secondary mb-1"><i class="bi bi-file-text me-1"></i>Description</div>
                            <div id="viewDescription"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!---- Cancel Modal ----------------->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Cancel Patrol
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Are you sure you want to cancel this patrol? This cannot be undone.</p>
                <p class="small text-secondary mb-0" id="cancelPatrolInfo"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Keep</button>
                <button type="button" class="btn btn-danger" onclick="confirmCancel()">Cancel Patrol</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Sample data – replace with real API fetch when backend is ready
    let patrols = [
        { id: 1, patrolNumber: 'PTR-2026-001', description: 'North District Morning Patrol', date: '2026-02-15', time: '08:00', status: 'published' },
        { id: 2, patrolNumber: 'PTR-2026-002', description: 'South District Evening Patrol',  date: '2026-02-20', time: '18:00', status: 'draft' }
    ];

    let selectedId = null;
    let viewModal, cancelModal;

    document.addEventListener('DOMContentLoaded', () => {
        viewModal   = new bootstrap.Modal(document.getElementById('viewModal'));
        cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
        renderTable();
    });

    function renderTable() {
        const tbody      = document.getElementById('patrolTable');
        const emptyState = document.getElementById('emptyState');
        if (!patrols.length) {
            emptyState.classList.remove('d-none');
            tbody.innerHTML = '';
            updateStats(); return;
        }
        emptyState.classList.add('d-none');
        tbody.innerHTML = patrols.map(p => `
            <tr>
                <td class="fw-semibold" style="color:#1e3a5f;">${p.patrolNumber}</td>
                <td>${p.description}</td>
                <td>${formatDate(p.date)}</td>
                <td><span class="${p.status === 'published' ? 'badge-released' : 'badge-draft'}">${p.status === 'published' ? 'Published' : 'Draft'}</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-link text-decoration-none p-0 me-2" onclick="viewPatrol(${p.id})"><i class="bi bi-eye me-1"></i>View</button>
                    <a href="/patrolEdit/${p.id}" class="btn btn-sm btn-link text-decoration-none p-0 me-2"><i class="bi bi-pencil me-1"></i>Edit</a>
                    <button class="btn btn-sm btn-link text-decoration-none text-danger p-0" onclick="openCancelModal(${p.id})"><i class="bi bi-trash me-1"></i>Cancel</button>
                </td>
            </tr>
        `).join('');
        updateStats();
    }

    function updateStats() {
        const now = new Date();
        document.getElementById('totalPatrols').textContent     = patrols.length;
        document.getElementById('upcomingPatrols').textContent  = patrols.filter(p => new Date(p.date) > now).length;
        document.getElementById('publishedPatrols').textContent = patrols.filter(p => p.status === 'published').length;
        document.getElementById('draftPatrols').textContent     = patrols.filter(p => p.status === 'draft').length;
    }

    function formatDate(str) {
        return new Date(str).toLocaleDateString('en-IE', { day:'numeric', month:'short', year:'numeric' });
    }

    function viewPatrol(id) {
        const p = patrols.find(x => x.id === id); if (!p) return;
        document.getElementById('viewPatrolNumber').textContent = p.patrolNumber;
        document.getElementById('viewStatus').textContent       = p.status === 'published' ? 'Published' : 'Draft';
        document.getElementById('viewDate').textContent         = formatDate(p.date);
        document.getElementById('viewTime').textContent         = p.time || '—';
        document.getElementById('viewDescription').textContent  = p.description;
        viewModal.show();
    }

    function openCancelModal(id) {
        selectedId = id;
        const p = patrols.find(x => x.id === id);
        document.getElementById('cancelPatrolInfo').textContent = `${p.patrolNumber} — ${p.description}`;
        cancelModal.show();
    }

    function confirmCancel() {
        patrols = patrols.filter(p => p.id !== selectedId);
        cancelModal.hide();
        renderTable();
    }
</script>

<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>