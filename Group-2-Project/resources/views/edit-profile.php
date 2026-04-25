<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claddagh | Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="icon" href="<?php echo asset('images/favicon.ico'); ?>">
    <style>
        .profile-page-wrapper {
            min-height: 100vh;
            background: #f0f4f8;
            padding-bottom: 0;
        }
        .profile-container {
            max-width: 700px;
            margin: 40px auto 0;
            padding: 0 20px;
        }
        .profile-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.09);
            overflow: hidden;
        }
        .profile-card-header {
            background: #1e3a5f;
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-avatar-lg {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1e3a5f);
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 3px solid rgba(255,255,255,0.25);
        }
        .profile-header-info h1 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 4px;
        }
        .profile-header-info p {
            font-size: 0.83rem;
            color: rgba(255,255,255,0.6);
            margin: 0;
        }
        .profile-card-body {
            padding: 28px 32px;
        }
        .profile-section-title {
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .profile-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        .readonly-field {
            background: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed;
            border-color: #e2e8f0 !important;
        }
        .readonly-notice {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.82rem;
            color: #92400e;
            margin-bottom: 20px;
        }
        .profile-card-footer {
            padding: 20px 32px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1e3a5f;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 22px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s, box-shadow 0.18s;
        }
        .btn-save:hover {
            background: #2563eb;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        }
        .btn-save:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 22px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.18s;
        }
        .btn-cancel:hover {
            background: #e2e8f0;
            color: #334155;
        }
        .alert-fixed {
            position: fixed;
            top: 76px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            min-width: 320px;
            max-width: 90vw;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15);
            display: none;
        }
        @media (max-width: 520px) {
            .profile-card-header { flex-direction: column; align-items: center; text-align: center; }
        }
    </style>
</head>
<body class="profile-page-wrapper">
<?php
    $__payload = \App\Helpers\JwtHelper::fromCookie();
    if (!$__payload) {
        header('Location: ' . url('/login')); exit;
    }
    $currentId   = (int)($__payload['sub'] ?? 0);
    $currentUser = \App\Models\User::find($currentId);

    if (!$currentUser) {
        header('Location: ' . url('/home')); exit;
    }

    // determine which profile is being edited
    $targetUser = $currentUser;
    if (isset($_GET['id']) && in_array($currentUser->userTypeNr, [1,2])) {
        $maybe = \App\Models\User::find((int)$_GET['id']);
        if ($maybe) {
            $targetUser = $maybe;
        }
    }

    $isAdminManager = in_array($currentUser->userTypeNr, [1,2]);
    $editingSelf    = ($targetUser->id === $currentUser->id);

    $roleMap  = [1 => 'Admin', 2 => 'Manager', 3 => 'Volunteer', 4 => 'Super'];
    $roleName = $roleMap[$targetUser->userTypeNr] ?? 'Unknown';
    $fullName = trim(($targetUser->FirstName ?? '') . ' ' . ($targetUser->LastName ?? ''));
    $initials = strtoupper(substr($targetUser->FirstName ?? 'U', 0, 1) . substr($targetUser->LastName ?? '', 0, 1));
?>
<?php @require resource_path('views/templates/header.php'); ?>

<!-- Alert feedback -->
<div class="alert alert-fixed" id="feedbackAlert" role="alert"></div>

<div class="profile-container">
    <div class="profile-card">

        <!-- Header -->
        <div class="profile-card-header">
            <div class="profile-avatar-lg"><?php echo htmlspecialchars($initials ?: 'U'); ?></div>
            <div class="profile-header-info">
                <h1><?php echo htmlspecialchars($fullName ?: 'Edit Profile'); ?></h1>
                <p><?php echo htmlspecialchars($targetUser->email ?? ''); ?></p>
            </div>
        </div>

        <!-- Body -->
        <div class="profile-card-body">

            <?php if (! $isAdminManager): ?>
            <div class="readonly-notice">
                <i class="bi bi-info-circle-fill"></i>
                Your profile is read-only. Contact an admin to make any changes.
            </div>
            <?php endif; ?>

            <?php // determine whether name fields should be editable
            $nameFieldsDisabled = $isAdminManager ? '' : 'disabled readonly';
            ?>

            <?php if ($isAdminManager): ?>
                <div class="profile-section-title"><i class="bi bi-pencil-square text-primary"></i>Edit Your Detail</div>
            <?php else: ?>
                <div class="profile-section-title"><i class="bi bi-lock text-secondary"></i>Read-Only Information</div>
            <?php endif; ?>

            <form id="editProfileForm">
                <?php if ($isAdminManager): ?>
                    <input type="hidden" name="user_id" id="userIdField" value="<?php echo $targetUser->id; ?>">
                <?php endif; ?>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label for="firstName" class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php echo $nameFieldsDisabled ? 'readonly-field' : ''; ?>" id="firstName" name="first_name"
                               value="<?php echo htmlspecialchars($targetUser->FirstName ?? ''); ?>"
                               placeholder="Enter first name" <?php echo $nameFieldsDisabled; ?> <?php echo $nameFieldsDisabled ? '' : 'required'; ?> maxlength="100">
                    </div>
                    <div class="col-sm-6">
                        <label for="lastName" class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php echo $nameFieldsDisabled ? 'readonly-field' : ''; ?>" id="lastName" name="last_name"
                               value="<?php echo htmlspecialchars($targetUser->LastName ?? ''); ?>"
                               placeholder="Enter last name" <?php echo $nameFieldsDisabled; ?> <?php echo $nameFieldsDisabled ? '' : 'required'; ?> maxlength="100">
                    </div>
                </div>

                <?php if ($isAdminManager): ?>
                    <!-- extra fields for admin/manager without separate section title -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php echo htmlspecialchars($targetUser->email ?? ''); ?>"
                                   placeholder="Enter email" required maxlength="255">
                        </div>
                        <div class="col-sm-6">
                            <label for="mobile" class="form-label fw-semibold">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile"
                                   value="<?php echo htmlspecialchars($targetUser->mobile ?? ''); ?>"
                                   placeholder="Enter mobile number" maxlength="25">
                        </div>
                        <div class="col-sm-6">
                            <label for="role" class="form-label fw-semibold">Role</label>
                            <select class="form-select" id="role" name="userTypeNr" required>
                                <?php foreach ($roleMap as $num => $label): ?>
                                    <option value="<?php echo $num; ?>" <?php echo $targetUser->userTypeNr === $num ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6 d-flex align-items-center">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="1" id="enabled" name="userEnabled" <?php echo $targetUser->userEnabled ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="enabled">
                                    Account enabled
                                </label>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-secondary">Email</label>
                            <input type="email" class="form-control readonly-field" value="<?php echo htmlspecialchars($targetUser->email ?? ''); ?>" disabled>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-secondary">Mobile</label>
                            <input type="text" class="form-control readonly-field" value="<?php echo htmlspecialchars($targetUser->mobile ?? 'Not provided'); ?>" disabled>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-secondary">Role</label>
                            <input type="text" class="form-control readonly-field" value="<?php echo htmlspecialchars($roleName); ?>" disabled>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Footer -->
        <div class="profile-card-footer">
            <button type="button" class="btn-save" id="saveBtn" onclick="saveProfile()" <?php echo $isAdminManager ? '' : 'disabled'; ?>>
                <i class="bi bi-check-lg"></i> Save Changes
            </button>
            <a href="<?php echo url('/profile'); ?>" class="btn-cancel">
                <i class="bi bi-x"></i> Cancel
            </a>
        </div>

    </div>
</div>

<script>
    const canEditProfile = <?php echo $isAdminManager ? 'true' : 'false'; ?>;

    async function saveProfile() {
        if (!canEditProfile) {
            showFeedback('You are not allowed to update this profile.', 'danger');
            return;
        }
        const firstName = document.getElementById('firstName').value.trim();
        const lastName  = document.getElementById('lastName').value.trim();

        if (!firstName || !lastName) {
            showFeedback('Please enter both first and last name.', 'danger');
            return;
        }

        // build payload
        const payload = { first_name: firstName, last_name: lastName };
        <?php if ($isAdminManager): ?>
            payload.user_id = document.getElementById('userIdField').value;
            payload.email   = document.getElementById('email').value.trim();
            payload.mobile  = document.getElementById('mobile').value.trim();
            payload.userTypeNr = parseInt(document.getElementById('role').value, 10);
            payload.userEnabled = document.getElementById('enabled').checked ? 1 : 0;
        <?php endif; ?>

        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving…';

        const redirectUrl = '<?php echo url('/profile') .
            (( $isAdminManager && ! $editingSelf) ? '?id=' . $targetUser->id : ''); ?>';

        try {
            const res  = await fetch('/api/profile/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();

            if (res.ok && data.success) {
                showFeedback('Profile updated successfully! Redirecting…', 'success');
                setTimeout(() => { window.location.href = redirectUrl; }, 1400);
            } else {
                showFeedback(data.message || 'Could not save profile.', 'danger');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
            }
        } catch (e) {
            showFeedback('Network error — please try again.', 'danger');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
        }
    }

    function showFeedback(msg, type) {
        const el = document.getElementById('feedbackAlert');
        const colorMap = { success: 'alert-success', danger: 'alert-danger', warning: 'alert-warning' };
        el.className = `alert alert-fixed ${colorMap[type] || 'alert-info'}`;
        el.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'} me-2"></i>${msg}`;
        el.style.display = 'block';
        if (type === 'success') return; // auto-redirect handles it
        setTimeout(() => { el.style.display = 'none'; }, 4000);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>
