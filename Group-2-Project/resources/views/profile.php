<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claddagh | My Profile</title>
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
            font-size: 1.35rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 4px;
        }
        .profile-header-info p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.65);
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
        .profile-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }
        @media (max-width: 520px) {
            .profile-info-grid { grid-template-columns: 1fr; }
            .profile-card-header { flex-direction: column; align-items: center; text-align: center; }
        }
        .profile-info-item label {
            display: block;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .profile-info-item span {
            font-size: 0.95rem;
            font-weight: 500;
            color: #1e293b;
        }
        .profile-info-item span.empty {
            color: #cbd5e1;
            font-style: italic;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .status-active { background: #dcfce7; color: #15803d; }
        .status-inactive { background: #fee2e2; color: #b91c1c; }
        .role-badge {
            display: inline-block;
            padding: 3px 12px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .profile-card-footer {
            padding: 20px 32px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-start;
        }
        .btn-edit-profile {
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
            text-decoration: none;
            transition: background 0.18s, box-shadow 0.18s;
        }
        .btn-edit-profile:hover {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        }
    </style>
</head>
<body class="profile-page-wrapper">
<?php
    // authenticate and identify current user
    $__payload = \App\Helpers\JwtHelper::fromCookie();
    if (!$__payload) {
        header('Location: ' . url('/login')); exit;
    }
    $currentId   = (int)($__payload['sub'] ?? 0);
    $currentUser = \App\Models\User::find($currentId);

    if (!$currentUser) {
        header('Location: ' . url('/home')); exit;
    }

    // determine whose profile we are looking at; admins/managers may pass ?id= to view others
    $profileUser = $currentUser;
    if (isset($_GET['id']) && in_array($currentUser->userTypeNr, [1,2])) {
        $maybe = \App\Models\User::find((int)$_GET['id']);
        if ($maybe) {
            $profileUser = $maybe;
        }
    }

    $roleMap = [1 => 'Admin', 2 => 'Manager', 3 => 'Volunteer', 4 => 'Super'];
    $roleName = $roleMap[$profileUser->userTypeNr] ?? 'Unknown';
    $fullName = trim(($profileUser->FirstName ?? '') . ' ' . ($profileUser->LastName ?? ''));
    $initials = strtoupper(substr($profileUser->FirstName ?? 'U', 0, 1) . substr($profileUser->LastName ?? '', 0, 1));

    // can edit if looking at own profile or if current user is admin/manager
    $canEdit = ($profileUser->id === $currentUser->id) || in_array($currentUser->userTypeNr, [1,2]);
?>
<?php @require resource_path('views/templates/header.php'); ?>

<div class="profile-container">
    <div class="profile-card">

        <!-- Header -->
        <div class="profile-card-header">
            <div class="profile-avatar-lg"><?php echo htmlspecialchars($initials ?: 'U'); ?></div>
            <div class="profile-header-info">
                <h1><?php echo htmlspecialchars($fullName ?: 'Unknown User'); ?></h1>
                <p><?php echo htmlspecialchars($profileUser->email ?? ''); ?></p>
            </div>
        </div>

        <!-- Body -->
        <div class="profile-card-body">
            <div class="profile-section-title"><i class="bi bi-person-lines-fill text-primary"></i>Personal Information</div>

            <div class="profile-info-grid">
                <div class="profile-info-item">
                    <label><i class="bi bi-person me-1"></i>First Name</label>
                    <span><?php echo htmlspecialchars($profileUser->FirstName ?? ''); ?></span>
                </div>
                <div class="profile-info-item">
                    <label><i class="bi bi-person me-1"></i>Last Name</label>
                    <span><?php echo htmlspecialchars($profileUser->LastName ?? ''); ?></span>
                </div>
                <div class="profile-info-item">
                    <label><i class="bi bi-envelope me-1"></i>Email</label>
                    <span><?php echo htmlspecialchars($profileUser->email ?? ''); ?></span>
                </div>
                <div class="profile-info-item">
                    <label><i class="bi bi-phone me-1"></i>Mobile</label>
                    <?php if ($profileUser->mobile): ?>
                        <span><?php echo htmlspecialchars($profileUser->mobile); ?></span>
                    <?php else: ?>
                        <span class="empty">Not provided</span>
                    <?php endif; ?>
                </div>
                <div class="profile-info-item">
                    <label><i class="bi bi-shield me-1"></i>Role</label>
                    <span><span class="role-badge"><?php echo htmlspecialchars($roleName); ?></span></span>
                </div>
                <div class="profile-info-item">
                    <label><i class="bi bi-toggles me-1"></i>Account Status</label>
                    <span>
                        <?php if ($profileUser->userEnabled): ?>
                            <span class="status-pill status-active"><i class="bi bi-check-circle-fill"></i>Active</span>
                        <?php else: ?>
                            <span class="status-pill status-inactive"><i class="bi bi-x-circle-fill"></i>Inactive</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="profile-card-footer">
            <a href="<?php echo url('/profile/edit'); ?>" class="btn-edit-profile">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<?php @require resource_path('views/templates/footer.php'); ?>
</body>
</html>
