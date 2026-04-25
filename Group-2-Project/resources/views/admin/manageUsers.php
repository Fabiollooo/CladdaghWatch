<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Claddagh | Manage Users</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
<link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">

<style>
    /* TABLE STYLING */

    .table {
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e6e6e6;
    }

    .table thead {
        background: #1e3a5f;
        color: #ffffff;
    }

    .table thead th {
        font-weight: 600;
        border: none;
        padding: 14px 16px;
    }

    .table tbody td {
        padding: 14px 16px;
        border-top: 1px solid #f1f1f1;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8fbfc;
        transition: 0.2s;
    }


    /* BADGES */

    .badge.bg-success {
        background: #2a9d8f !important;
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 6px;
    }


    /* BUTTONS */

    .btn-success {
        background: #2a9d8f;
        border: none;
    }

    .btn-success:hover {
        background: #21867a;
    }

    .btn-warning {
        background: #e9c46a;
        border: none;
        color: #000;
    }

    .btn-warning:hover {
        background: #d8b65f;
    }


    /* SEARCH BAR */

    .input-group-text {
        background: #1e3a5f;
        color: white;
        border: none;
    }

    .form-control:focus {
        border-color: #2a9d8f;
        box-shadow: 0 0 0 0.1rem rgba(42,157,143,0.25);
    }


    /* PAGINATION */

    .btn-light {
        border: 1px solid #e0e0e0;
    }

    .btn-light:hover {
        background: #f3f3f3;
    }

    .pagination-grid {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 8px;
        margin-top: 1rem;
    }

    .pagination-info {
        font-size: 0.9rem;
        color: #555;
        text-align: center;
        white-space: nowrap;
    }

    .schedule-header {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 30px;
        gap: 15px;
    }
    .schedule-header .schedule-header-actions { margin-left: auto; }

    /* -- Ensure navbar never overlaps page content on small screens -- */
    @media (max-width: 991px) {
        .schedule-app-body {
            padding-top: 70px !important;
        }
    }

    /* -- MOBILE USER ACCORDION --------------------- */
    .mobile-user-item {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .mobile-user-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px;
        cursor: pointer;
        user-select: none;
        gap: 10px;
    }
    .mobile-user-header:active { background: #f0f4f8; }
    .mobile-user-name { font-weight: 600; color: #1e293b; font-size: .95rem; flex: 1; }
    .mobile-chevron { color: #94a3b8; transition: transform .2s; flex-shrink: 0; font-size: 1rem; }
    .mobile-user-item.open .mobile-chevron { transform: rotate(180deg); }
    .mobile-user-details {
        padding: 12px 16px 14px;
        border-top: 1px solid #f1f5f9;
        background: #fafafa;
    }
    .mobile-user-info {
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: 5px 10px;
        margin: 0 0 12px;
        font-size: .88rem;
    }
    .mobile-user-info dt { font-weight: 600; color: #64748b; }
    .mobile-user-info dd { margin: 0; color: #1e293b; word-break: break-word; }

</style>

</head>

<body class="schedule-app-body">

<div class="schedule-app-container">


<?php @require resource_path('views/templates/header.php'); ?>

<!-- MAIN CONTENT -->
<main class="schedule-main-content">




<div class="schedule-header">
            
    <div class="pg-toolbar-title">
        <i class="bi bi-wheelchair"></i>
        Manage Users
    </div>
    <div class="schedule-header-actions">
        <button class="btn btn-light-custom" onclick="openAddModal()">
            <i class="bi bi-plus-circle"></i> Add User
        </button>
    </div>


</div>

<!-- CONTENT -->
<div class="container-fluid mt-4">

    <!-- SEARCH -->
    <form method="get" style="display:flex;flex-wrap:nowrap;align-items:center;gap:8px;" class="mb-3">
        <div style="flex:1;min-width:0;">
            <div class="input-group">
                <button class="btn btn-outline-secondary" type="submit" tabindex="-1">
                    <i class="bi bi-search"></i>
                </button>
                <input type="text" name="search" id="userSearch" class="form-control" placeholder="Search by name, email or role…"
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
                <?php if(!empty($search)): ?>
                    <button type="button" class="btn btn-light ms-1" onclick="clearSearch()" title="Clear search">
                        <i class="bi bi-x-circle"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="text-secondary small text-nowrap">
            <span id="userCount" class="fw-semibold"><?php echo $users->count(); ?></span> user(s)
        </div>
    </form>


<!-- USERS TABLE (desktop) -->
<div class="table-responsive d-none d-md-block">
<table class="table align-middle">
<thead>
<tr>
    <th></th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Mobile</th>
    <th>Role</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>
<tbody id="usersTableBody">
<?php
$roleMap = [1 => 'Admin', 2 => 'Manager', 3 => 'Volunteer', 4 => 'Supervisor', 99 => 'Unknown'];
$roleBadge = [1 => 'bg-danger', 2 => 'bg-primary', 3 => 'bg-success', 4 => 'bg-warning text-dark', 99 => 'bg-secondary'];
if (isset($users) && count($users) > 0):
    foreach ($users as $user):
        $fullName  = htmlspecialchars(trim(($user->FirstName ?? '') . ' ' . ($user->LastName ?? '')));
        $email     = htmlspecialchars($user->email ?? '');
        $mobile    = htmlspecialchars($user->mobile ?? '—');
        $typeNr    = $user->userTypeNr ?? 99;
        $roleName  = $roleMap[$typeNr]  ?? 'Unknown';
        $roleCls   = $roleBadge[$typeNr] ?? 'bg-secondary';
        $enabled   = isset($user->userEnabled) ? (bool)$user->userEnabled : true;
?>
<?php
    $initials = strtoupper(substr($user->FirstName ?? '', 0, 1) . substr($user->LastName ?? '', 0, 1));
    if (!$initials) $initials = '?';
?>
<tr data-search="<?php echo strtolower($fullName . ' ' . $email . ' ' . $roleName); ?>" data-userid="<?php echo $user->UserNr; ?>">
    <td>
        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1e3a5f,#2563eb);color:#fff;font-size:.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;letter-spacing:.03em;"><?php echo htmlspecialchars($initials); ?></div>
    </td>
    <td class="fw-semibold"><?php echo $fullName ?: '<span class="text-secondary fst-italic">No name</span>'; ?></td>
    <td><?php echo $email; ?></td>
    <td><?php echo $mobile; ?></td>
    <td><span class="badge <?php echo $roleCls; ?>"><?php echo $roleName; ?></span></td>
    <td>
        <?php if ($enabled): ?>
            <span class="badge bg-success">Active</span>
        <?php else: ?>
            <span class="badge bg-danger">Inactive</span>
        <?php endif; ?>
    </td>
    <td>
        <button class="btn btn-sm btn-success" onclick="openEditModal(<?php echo $user->UserNr; ?>, <?php echo htmlspecialchars(json_encode(['FirstName'=>$user->FirstName,'LastName'=>$user->LastName,'email'=>$user->email ?? '','mobile'=>$user->mobile ?? '','userTypeNr'=>$user->userTypeNr ?? 3]), ENT_QUOTES); ?>)">
            <i class="bi bi-pencil"></i> Edit
        </button>
        <button id="deactivateBtn_<?php echo $user->UserNr; ?>" class="btn btn-sm <?php echo $enabled ? 'btn-warning' : 'btn-outline-success'; ?>" onclick="toggleStatus(<?php echo $user->UserNr; ?>, this)">
            <i class="bi bi-person-<?php echo $enabled ? 'x' : 'check'; ?>"></i> <?php echo $enabled ? 'Deactivate' : 'Activate'; ?>
        </button>
    </td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr id="noResults">
    <td colspan="7" class="text-center text-secondary py-4"><i class="bi bi-people fs-3 d-block mb-2"></i>No users found</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>

<!-- Mobile User Accordion (< md) -->
<div class="d-md-none mt-2" id="mobileUserList">
<?php if (isset($users) && count($users) > 0): ?>
    <?php foreach ($users as $user):
        $fullName  = htmlspecialchars(trim(($user->FirstName ?? '') . ' ' . ($user->LastName ?? '')));
        $email     = htmlspecialchars($user->email ?? '');
        $mobile    = htmlspecialchars($user->mobile ?? '—');
        $typeNr    = $user->userTypeNr ?? 99;
        $roleName  = $roleMap[$typeNr]  ?? 'Unknown';
        $roleCls   = $roleBadge[$typeNr] ?? 'bg-secondary';
        $enabled   = isset($user->userEnabled) ? (bool)$user->userEnabled : true;
        $editData  = htmlspecialchars(json_encode(['FirstName'=>$user->FirstName,'LastName'=>$user->LastName,'email'=>$user->email ?? '','mobile'=>$user->mobile ?? '','userTypeNr'=>$user->userTypeNr ?? 3]), ENT_QUOTES);
    ?>
    <div class="mobile-user-item" data-userid="<?php echo $user->UserNr; ?>">
        <div class="mobile-user-header" onclick="toggleUserAccordion(<?php echo $user->UserNr; ?>)">
            <span class="mobile-user-name"><?php echo $fullName ?: '<em class="text-secondary">No name</em>'; ?></span>
            <i class="bi bi-chevron-down mobile-chevron"></i>
        </div>
        <div class="mobile-user-details" style="display:none;">
            <dl class="mobile-user-info">
                <dt>Email</dt>    <dd class="mobile-user-email"><?php echo $email; ?></dd>
                <dt>Mobile</dt>   <dd class="mobile-user-mobile"><?php echo $mobile; ?></dd>
                <dt>Role</dt>     <dd class="mobile-user-role"><span class="badge <?php echo $roleCls; ?>"><?php echo $roleName; ?></span></dd>
                <dt>Status</dt>   <dd class="mobile-user-status">
                    <?php if ($enabled): ?>
                        <span class="badge bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactive</span>
                    <?php endif; ?>
                </dd>
            </dl>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-success mobile-edit-btn flex-fill"
                        onclick="openEditModal(<?php echo $user->UserNr; ?>, <?php echo $editData; ?>)">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm <?php echo $enabled ? 'btn-warning' : 'btn-outline-success'; ?> mobile-toggle-btn flex-fill"
                        id="mobileDeactivateBtn_<?php echo $user->UserNr; ?>"
                        onclick="toggleStatus(<?php echo $user->UserNr; ?>, this)">
                    <i class="bi bi-person-<?php echo $enabled ? 'x' : 'check'; ?>"></i>
                    <?php echo $enabled ? 'Deactivate' : 'Activate'; ?>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="text-center text-secondary py-4"><i class="bi bi-people fs-3 d-block mb-2"></i>No users found</div>
<?php endif; ?>
</div>


<!-- PAGINATION -->
<div class="pagination-grid">

    <div>
        <?php if($users->onFirstPage()): ?>
        <button class="btn btn-light" disabled>
            <i class="bi bi-arrow-left"></i>
            Prev
        </button>
        <?php else: ?>
        <a href="<?php echo $users->previousPageUrl(); ?>" class="btn btn-light">
            <i class="bi bi-arrow-left"></i>
            Prev
        </a>
        <?php endif; ?>
    </div>

    <div class="pagination-info">
        <?php if(isset($search) && $search): ?>
          <strong>Search results for "<?php echo htmlspecialchars($search); ?>"</strong> - 
        <?php endif; ?>
        <?php echo $users->currentPage(); ?> / <?php echo $users->lastPage(); ?> (Total: <?php echo $users->total(); ?>)
    </div>

    <div>
        <?php if($users->hasMorePages()): ?>
        <a href="<?php echo $users->nextPageUrl(); ?>" class="btn btn-light">
            Next
            <i class="bi bi-arrow-right"></i>
        </a>
        <?php else: ?>
        <button class="btn btn-light" disabled>
            Next
            <i class="bi bi-arrow-right"></i>
        </button>
        <?php endif; ?>
    </div>

</div>


</div>

</main>

</div>


<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalTitle" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content" style="border-radius:14px;">
            <div class="modal-header" style="background:#1e3a5f;color:#fff;">
                <h5 class="modal-title" id="addUserModalTitle" style="color:#fff;"><i class="bi bi-person-plus me-2"></i>Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" class="form-control" id="addFirstName" placeholder="e.g. Jane">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" class="form-control" id="addLastName" placeholder="e.g. Smith">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="addEmail" placeholder="e.g. jane@example.com">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Mobile</label>
                        <input type="text" class="form-control" id="addMobile" placeholder="e.g. 087…">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Role</label>
                        <select class="form-select" id="addRole">
                            <option value="1">Admin</option>
                            <option value="2">Manager</option>
                            <option value="3">Volunteer</option>
                            <option value="4">Supervisor</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="addPassword" placeholder="Minimum 6 characters">
                            <button type="button" class="btn btn-outline-secondary" onclick="generateRandomPassword()">Generate</button>
                        </div>
                        <div class="form-text">Type your own password or click Generate (minimum 6 chars).</div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="addSendEmail" checked>
                            <label class="form-check-label" for="addSendEmail">Send email with password</label>
                        </div>
                    </div>
                </div>
                <div id="addUserError" class="text-danger small mt-2" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="addUserSaveBtn" onclick="saveNewUser()"><i class="bi bi-person-check me-1"></i>Add User</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalTitle" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content" style="border-radius:14px;">
            <div class="modal-header" style="background:#1e3a5f;color:#fff;">
                <h5 class="modal-title" id="editUserModalTitle" style="color:#fff;"><i class="bi bi-pencil-square me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editUserId">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">First Name</label>
                        <input type="text" class="form-control" id="editFirstName">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Last Name</label>
                        <input type="text" class="form-control" id="editLastName">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="editEmail">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Mobile</label>
                        <input type="text" class="form-control" id="editMobile">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Role</label>
                        <select class="form-select" id="editRole">
                            <option value="1">Admin</option>
                            <option value="2">Manager</option>
                            <option value="3">Volunteer</option>
                            <option value="4">Supervisor</option>
                        </select>
                    </div>
                </div>
                <div id="editUserError" class="text-danger small mt-2" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="editUserSaveBtn" onclick="saveUser()"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- SIDEBAR SCRIPT -->
<script>
function toggleSidebar() {
    document.querySelector('.schedule-sidebar').classList.toggle('active');
}
function closeSidebar() {
    document.querySelector('.schedule-sidebar').classList.remove('active');
}

// -- Add user ------------------------------------------------------------
let addModal;
document.addEventListener('DOMContentLoaded', () => {
    editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    addModal  = new bootstrap.Modal(document.getElementById('addUserModal'));
    const rows = document.querySelectorAll('#usersTableBody tr[data-search]');
    document.getElementById('userCount').textContent = rows.length;

    const addMobileInput = document.getElementById('addMobile');
    if (addMobileInput) {
        addMobileInput.addEventListener('input', () => {
            addMobileInput.value = sanitizePhoneInput(addMobileInput.value);
        });
    }

    const editMobileInput = document.getElementById('editMobile');
    if (editMobileInput) {
        editMobileInput.addEventListener('input', () => {
            editMobileInput.value = sanitizePhoneInput(editMobileInput.value);
        });
    }
});

function openAddModal() {
    ['addFirstName','addLastName','addEmail','addMobile','addPassword'].forEach(id => {
        document.getElementById(id).value = '';
    });
    document.getElementById('addRole').value = '3';
    document.getElementById('addSendEmail').checked = true;
    document.getElementById('addUserError').style.display = 'none';
    addModal.show();
}

function generateRandomPassword(length = 10) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_+-=';
    let pass = '';
    for (let i = 0; i < length; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('addPassword').value = pass;
    return pass;
}

function saveNewUser() {
    const btn = document.getElementById('addUserSaveBtn');
    const err = document.getElementById('addUserError');
    const addMobileInput = document.getElementById('addMobile');
    const sanitizedMobile = sanitizePhoneInput(addMobileInput.value);
    addMobileInput.value = sanitizedMobile;
    const body = {
        FirstName:  document.getElementById('addFirstName').value.trim(),
        LastName:   document.getElementById('addLastName').value.trim(),
        email:      document.getElementById('addEmail').value.trim(),
        mobile:     sanitizedMobile,
        userTypeNr: parseInt(document.getElementById('addRole').value),
        password:   document.getElementById('addPassword').value,
        sendEmail:  document.getElementById('addSendEmail').checked,
    };
    if (!body.FirstName || !body.LastName || !body.email || !body.password) {
        err.textContent = 'First name, last name, email and password are required.';
        err.style.display = '';
        return;
    }
    if (body.password.length < 6) {
        err.textContent = 'Password must be at least 6 characters long.';
        err.style.display = '';
        return;
    }
    if (body.mobile && countPhoneDigits(body.mobile) < 7) {
        err.textContent = 'Mobile number must contain at least 7 digits.';
        err.style.display = '';
        return;
    }
    btn.disabled = true; btn.textContent = 'Saving\u2026';
    fetch('/api/users', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            addModal.hide();
            const u = data.user;
            const roleMap = {1:'Admin', 2:'Manager', 3:'Volunteer', 4:'Supervisor'};
            const roleCls = {1:'bg-danger', 2:'bg-primary', 3:'bg-success', 4:'bg-warning text-dark'};
            const fullName = (body.FirstName + ' ' + body.LastName).trim();
            const role = roleMap[body.userTypeNr] || 'Unknown';
            const tbody = document.getElementById('usersTableBody');
            const placeholder = document.getElementById('noResults');
            if (placeholder) placeholder.remove();
            const tr = document.createElement('tr');
            tr.dataset.search = (fullName + ' ' + body.email + ' ' + role).toLowerCase();
            tr.dataset.userid = u.UserNr;
            const initials = ((body.FirstName||'').charAt(0) + (body.LastName||'').charAt(0)).toUpperCase() || '?';
            tr.innerHTML = `
                <td><div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1e3a5f,#2563eb);color:#fff;font-size:.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;letter-spacing:.03em;">${escHtml(initials)}</div></td>
                <td class="fw-semibold">${escHtml(fullName)}</td>
                <td>${escHtml(body.email)}</td>
                <td>${escHtml(body.mobile || '\u2014')}</td>
                <td><span class="badge ${roleCls[body.userTypeNr] || 'bg-secondary'}">${escHtml(role)}</span></td>
                <td><span class="badge bg-success">Active</span></td>
                <td>
                    <button class="btn btn-sm btn-success" onclick="openEditModal(${u.UserNr}, ${JSON.stringify({FirstName:body.FirstName,LastName:body.LastName,email:body.email,mobile:body.mobile,userTypeNr:body.userTypeNr}).replace(/"/g,'&quot;')})">
                        <i class="bi bi-pencil"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="toggleStatus(${u.UserNr}, this)">
                        <i class="bi bi-person-x"></i> Deactivate
                    </button>
                </td>`;
            tbody.appendChild(tr);
            // Also add to mobile accordion
            const mobileList = document.getElementById('mobileUserList');
            if (mobileList) {
                // Remove empty-state message if present
                const noMsg = mobileList.querySelector('.text-center.text-secondary');
                if (noMsg) noMsg.remove();
                const roleMap2 = {1:'Admin', 2:'Manager', 3:'Volunteer', 4:'Supervisor'};
                const roleCls2 = {1:'bg-danger', 2:'bg-primary', 3:'bg-success', 4:'bg-warning text-dark'};
                const editDataStr = JSON.stringify({FirstName:body.FirstName,LastName:body.LastName,email:body.email,mobile:body.mobile,userTypeNr:body.userTypeNr}).replace(/"/g,'&quot;');
                const mCard = document.createElement('div');
                mCard.className = 'mobile-user-item';
                mCard.dataset.userid = u.UserNr;
                mCard.innerHTML = `
                    <div class="mobile-user-header" onclick="toggleUserAccordion(${u.UserNr})">
                        <span class="mobile-user-name">${escHtml(fullName)}</span>
                        <i class="bi bi-chevron-down mobile-chevron"></i>
                    </div>
                    <div class="mobile-user-details" style="display:none;">
                        <dl class="mobile-user-info">
                            <dt>Email</dt>   <dd class="mobile-user-email">${escHtml(body.email)}</dd>
                            <dt>Mobile</dt>  <dd class="mobile-user-mobile">${escHtml(body.mobile || '—')}</dd>
                            <dt>Role</dt>    <dd class="mobile-user-role"><span class="badge ${roleCls2[body.userTypeNr] || 'bg-secondary'}">${escHtml(roleMap2[body.userTypeNr] || 'Unknown')}</span></dd>
                            <dt>Status</dt>  <dd class="mobile-user-status"><span class="badge bg-success">Active</span></dd>
                        </dl>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-success mobile-edit-btn flex-fill" onclick="openEditModal(${u.UserNr}, ${editDataStr})">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-warning mobile-toggle-btn flex-fill" id="mobileDeactivateBtn_${u.UserNr}" onclick="toggleStatus(${u.UserNr}, this)">
                                <i class="bi bi-person-x"></i> Deactivate
                            </button>
                        </div>
                    </div>`;
                mobileList.appendChild(mCard);
            }
            const countEl = document.getElementById('userCount');
            countEl.textContent = parseInt(countEl.textContent || 0) + 1;
        } else {
            err.textContent = data.message || 'Could not create user.';
            err.style.display = '';
        }
    })
    .catch(e => { err.textContent = 'Network error: ' + e.message; err.style.display = ''; })
    .finally(() => { btn.disabled = false; btn.innerHTML = '<i class="bi bi-person-check me-1"></i>Add User'; });
}

function openEditModal(id, data) {
    document.getElementById('editUserId').value    = id;
    document.getElementById('editFirstName').value = data.FirstName || '';
    document.getElementById('editLastName').value  = data.LastName  || '';
    document.getElementById('editEmail').value     = data.email     || '';
    document.getElementById('editMobile').value    = sanitizePhoneInput(data.mobile || '');
    document.getElementById('editRole').value      = String(data.userTypeNr || 3);
    document.getElementById('editUserError').style.display = 'none';
    editModal.show();
}

function saveUser() {
    const id  = document.getElementById('editUserId').value;
    const btn = document.getElementById('editUserSaveBtn');
    const err = document.getElementById('editUserError');
    const editMobileInput = document.getElementById('editMobile');
    const sanitizedMobile = sanitizePhoneInput(editMobileInput.value);
    editMobileInput.value = sanitizedMobile;
    const body = {
        FirstName:  document.getElementById('editFirstName').value.trim(),
        LastName:   document.getElementById('editLastName').value.trim(),
        email:      document.getElementById('editEmail').value.trim(),
        mobile:     sanitizedMobile,
        userTypeNr: parseInt(document.getElementById('editRole').value),
    };
    if (!body.FirstName || !body.LastName || !body.email) {
        err.textContent = 'First name, last name and email are required.';
        err.style.display = '';
        return;
    }
    if (body.mobile && countPhoneDigits(body.mobile) < 7) {
        err.textContent = 'Mobile number must contain at least 7 digits.';
        err.style.display = '';
        return;
    }
    btn.disabled = true; btn.textContent = 'Saving…';
    fetch(`/api/users/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            editModal.hide();
            // Update the row in place
            const row = document.querySelector(`#usersTableBody tr[data-userid="${id}"]`);
            if (row) {
                const fullName = (body.FirstName + ' ' + body.LastName).trim();
                const roleMap  = {1:'Admin', 2:'Manager', 3:'Volunteer', 4:'Supervisor'};
                const roleCls  = {1:'bg-danger', 2:'bg-primary', 3:'bg-success', 4:'bg-warning text-dark'};
                row.cells[1].innerHTML = `<strong>${escHtml(fullName)}</strong>`;
                row.cells[2].textContent = body.email;
                row.cells[3].textContent = body.mobile || '—';
                row.cells[4].innerHTML = `<span class="badge ${roleCls[body.userTypeNr] || 'bg-secondary'}">${roleMap[body.userTypeNr] || 'Unknown'}</span>`;
                row.dataset.search = (fullName + ' ' + body.email + ' ' + (roleMap[body.userTypeNr] || '')).toLowerCase();
            } else {
                location.reload();
            }
            // Update mobile accordion card
            const roleMap  = {1:'Admin', 2:'Manager', 3:'Volunteer', 4:'Supervisor'};
            const roleCls  = {1:'bg-danger', 2:'bg-primary', 3:'bg-success', 4:'bg-warning text-dark'};
            const fullName = (body.FirstName + ' ' + body.LastName).trim();
            const mobileCard = document.querySelector(`#mobileUserList [data-userid="${id}"]`);
            if (mobileCard) {
                const nameEl = mobileCard.querySelector('.mobile-user-name');
                if (nameEl) nameEl.textContent = fullName || 'No name';
                const emailEl = mobileCard.querySelector('.mobile-user-email');
                if (emailEl) emailEl.textContent = body.email;
                const mobileEl = mobileCard.querySelector('.mobile-user-mobile');
                if (mobileEl) mobileEl.textContent = body.mobile || '—';
                const roleEl = mobileCard.querySelector('.mobile-user-role');
                if (roleEl) roleEl.innerHTML = `<span class="badge ${roleCls[body.userTypeNr] || 'bg-secondary'}">${roleMap[body.userTypeNr] || 'Unknown'}</span>`;
                const mEditBtn = mobileCard.querySelector('.mobile-edit-btn');
                if (mEditBtn) mEditBtn.setAttribute('onclick',
                    `openEditModal(${id}, ${JSON.stringify({FirstName:body.FirstName,LastName:body.LastName,email:body.email,mobile:body.mobile,userTypeNr:body.userTypeNr}).replace(/"/g,'&quot;')})`);
            }
        } else {
            err.textContent = data.message || 'Could not save changes.';
            err.style.display = '';
        }
    })
    .catch(e => { err.textContent = 'Network error: ' + e.message; err.style.display = ''; })
    .finally(() => { btn.disabled = false; btn.textContent = 'Save Changes'; });
}

// ── Toggle active / inactive ─────────────────────────────────────
function toggleStatus(id, btn) {
    const isDeactivating = btn.classList.contains('btn-warning');
    const label = isDeactivating ? 'Deactivating…' : 'Activating…';
    btn.disabled = true; btn.textContent = label;
    fetch(`/api/users/${id}/toggle-status`, {
        method: 'PATCH',
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const enabled = !!data.userEnabled;
            // Desktop table row
            const row = document.querySelector(`#usersTableBody tr[data-userid="${id}"]`);
            if (row) {
                row.cells[5].innerHTML = enabled
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
                const dBtn = document.getElementById(`deactivateBtn_${id}`);
                if (dBtn) {
                    dBtn.className = enabled ? 'btn btn-sm btn-warning' : 'btn btn-sm btn-outline-success';
                    dBtn.innerHTML = enabled ? '<i class="bi bi-person-x"></i> Deactivate' : '<i class="bi bi-person-check"></i> Activate';
                }
            }
            // Mobile accordion card
            const mobileCard = document.querySelector(`#mobileUserList [data-userid="${id}"]`);
            if (mobileCard) {
                const statusEl = mobileCard.querySelector('.mobile-user-status');
                if (statusEl) statusEl.innerHTML = enabled
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
                const mBtn = document.getElementById(`mobileDeactivateBtn_${id}`);
                if (mBtn) {
                    mBtn.className = enabled
                        ? 'btn btn-sm btn-warning mobile-toggle-btn flex-fill'
                        : 'btn btn-sm btn-outline-success mobile-toggle-btn flex-fill';
                    mBtn.innerHTML = enabled
                        ? '<i class="bi bi-person-x"></i> Deactivate'
                        : '<i class="bi bi-person-check"></i> Activate';
                }
            }
        } else {
            alert('Error: ' + (data.message || 'Could not update status.'));
        }
    })
    .catch(e => alert('Network error: ' + e.message))
    .finally(() => { btn.disabled = false; });
}

// ── Mobile accordion toggle ──────────────────────────────────────
function toggleUserAccordion(id) {
    const item = document.querySelector(`#mobileUserList [data-userid="${id}"]`);
    if (!item) return;
    const details = item.querySelector('.mobile-user-details');
    const isOpen  = item.classList.contains('open');
    // Close all open cards first
    document.querySelectorAll('#mobileUserList .mobile-user-item.open').forEach(el => {
        el.classList.remove('open');
        el.querySelector('.mobile-user-details').style.display = 'none';
    });
    if (!isOpen) {
        item.classList.add('open');
        details.style.display = 'block';
    }
}

function escHtml(str) {
    return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function sanitizePhoneInput(value) {
    return String(value || '')
        .replace(/[^\d+()\-\s]/g, '')
        .replace(/\s+/g, ' ')
        .trim();
}

function countPhoneDigits(value) {
    return String(value || '').replace(/\D/g, '').length;
}

function clearSearch() {
    window.location = '/manageUsers';
}

</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>