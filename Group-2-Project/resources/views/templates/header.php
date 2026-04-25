<?php
// ── Unified Role-Aware Navbar ──────────────────────────────────────────────
// Reads the JWT from the cw_jwt cookie and exposes:
//   $__navUser     – decoded payload array, or null
//   $__navRole     – int role number (99 = unknown/guest)
//   $__isAdmin     – true for ADMIN(1) or MANAGER(2)
//   $__isSuper     – true for SUPER(4)
//   $__isLoggedIn  – any valid JWT present

// JwtHelper is PSR-4 autoloaded as App\Helpers\JwtHelper
$__navUser    = \App\Helpers\JwtHelper::fromCookie();
$__navRole    = $__navUser ? (int)($__navUser['role'] ?? 99) : 99;
$__isAdmin    = in_array($__navRole, [1, 2], true);   // ADMIN or MANAGER
$__isSuper    = ($__navRole === 4);
$__isLoggedIn = $__navUser !== null;
$__calendarPath = $__isSuper ? '/supervisor/calendar' : '/volunteer';

// optionally preserve ?id= when admin/manager viewing another profile
$__profileQuery = '';
if ($__isAdmin && isset($_GET['id']) && ctype_digit((string)$_GET['id'])) {
    $__profileQuery = '?id=' . (int)$_GET['id'];
}

// Determine "active" page for highlighting nav links
$__currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
function __navActive(string $path, string $currentPath): string {
    return ($currentPath === $path || str_starts_with($currentPath, $path . '/'))
        ? ' active' : '';
}
?>

<!-- Navigation -->
<nav class="cwp-navbar">
    <div class="cwp-nav-inner">

        <!-- Brand / Logo -->
        <a class="cwp-brand" href="<?php echo url($__calendarPath); ?>">
            <img src="<?php echo asset('images/logo.png'); ?>" alt="Claddagh Watch" class="cwp-brand-logo">
            <span class="cwp-brand-name">Claddagh<strong>Watch</strong></span>
        </a>

        <!-- Desktop Links -->
        <ul class="cwp-links d-none d-lg-flex">

            <?php if ($__isLoggedIn): ?>
                <li>
                    <a class="cwp-link<?php echo __navActive($__calendarPath, $__currentPath) ? ' cwp-link--active' : ''; ?>" href="<?php echo url($__calendarPath); ?>">
                        <i class="bi bi-calendar3"></i><span>Calendar</span>
                    </a>
                </li>

                <?php if ($__isAdmin): ?>
                    <li>
                        <a class="cwp-link<?php echo __navActive('/manageUsers', $__currentPath) ? ' cwp-link--active' : ''; ?>" href="<?php echo url('/manageUsers'); ?>">
                            <i class="bi bi-person-circle"></i><span>Manage Users</span>
                        </a>
                    </li>
                <?php endif; ?>

            <?php endif; ?>
        </ul>

        <!-- Right-side actions -->
        <div class="cwp-nav-actions d-none d-lg-flex">
            <button type="button" class="cwp-theme-toggle" data-theme-toggle aria-label="Toggle dark mode" title="Toggle dark mode">
                <i class="bi bi-moon-stars-fill"></i>
            </button>
            <?php if ($__isLoggedIn): ?>
                <?php
                    $__roleName = match($__navRole) {
                        1 => 'Admin', 2 => 'Manager', 3 => 'Volunteer', 4 => 'Super', default => 'User'
                    };
                    $__displayName = $__navUser['name'] ?? $__navUser['email'] ?? 'User';
                    $__initials = strtoupper(substr($__displayName, 0, 1));
                ?>
                <div class="dropdown cwp-profile-dropdown">
                    <button class="cwp-user-chip cwp-user-chip--btn" type="button"
                            data-bs-toggle="dropdown" data-bs-auto-close="true"
                            aria-expanded="false">
                        <span class="cwp-avatar"><?php echo htmlspecialchars($__initials); ?></span>
                        <span class="cwp-user-info">
                            <span class="cwp-user-name"><?php echo htmlspecialchars($__displayName); ?></span>
                            <span class="cwp-user-role"><?php echo $__roleName; ?></span>
                        </span>
                        <i class="bi bi-chevron-down cwp-dd-caret"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end cwp-profile-menu">
                        <li class="cwp-profile-menu-header">
                            <div class="d-flex align-items-center gap-2">
                                <span class="cwp-avatar" style="width:36px;height:36px;font-size:.95rem;flex-shrink:0;"><?php echo htmlspecialchars($__initials); ?></span>
                                <div style="min-width:0;overflow:hidden;">
                                    <div class="cwp-dd-name"><?php echo htmlspecialchars($__displayName); ?></div>
                                    <div class="cwp-dd-email"><?php echo htmlspecialchars($__navUser['email'] ?? ''); ?></div>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item cwp-dd-item" href="<?php echo url('/profile') . $__profileQuery; ?>">
                                <i class="bi bi-person me-2"></i>View Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item cwp-dd-item" href="<?php echo url('/profile/edit') . $__profileQuery; ?>">
                                <i class="bi bi-pencil-square me-2"></i>Edit Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item cwp-dd-item cwp-dd-logout" href="<?php echo url('/logout'); ?>">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a class="cwp-link<?php echo __navActive('/login', $__currentPath) ? ' cwp-link--active' : ''; ?>" href="<?php echo url('/login'); ?>">
                    <i class="bi bi-box-arrow-in-right"></i><span>Login</span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Mobile toggler -->
        <button class="cwp-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-label="Open menu">
            <i class="bi bi-list"></i>
        </button>

    </div>
</nav>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-end cwp-offcanvas" tabindex="-1" id="mobileMenu">
    <div class="cwp-offcanvas-header">
        <div class="cwp-offcanvas-brand">
            <img src="<?php echo asset('images/logo.png'); ?>" alt="Logo" style="height:36px;">
            <span>Claddagh<strong>Watch</strong></span>
        </div>
        <?php if ($__isLoggedIn): ?>
            <div class="cwp-offcanvas-user">
                <span class="cwp-avatar cwp-avatar--sm"><?php echo htmlspecialchars($__initials ?? '?'); ?></span>
                <div>
                    <div class="cwp-user-name" style="font-size:.85rem;"><?php echo htmlspecialchars($__displayName ?? 'User'); ?></div>
                    <div class="cwp-user-role" style="font-size:.72rem;"><?php echo $__roleName ?? 'User'; ?></div>
                </div>
            </div>
        <?php endif; ?>
        <button type="button" class="cwp-offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="cwp-offcanvas-body">
        <nav class="cwp-mobile-nav">

            <button type="button" class="cwp-theme-toggle mb-3 ms-3" data-theme-toggle aria-label="Toggle dark mode" title="Toggle dark mode">
                <i class="bi bi-moon-stars-fill"></i>
            </button>

            <?php if ($__isLoggedIn): ?>
                <a class="cwp-mobile-link<?php echo __navActive($__calendarPath, $__currentPath) ? ' active' : ''; ?>" href="<?php echo url($__calendarPath); ?>">
                    <i class="bi bi-calendar3"></i>Calendar
                </a>

                <?php if ($__isAdmin): ?>
                    <a class="cwp-mobile-link<?php echo __navActive('/manageUsers', $__currentPath) ? ' active' : ''; ?>" href="<?php echo url('/manageUsers'); ?>">
                        <i class="bi bi-person-circle"></i>Manage Users
                    </a>
                <?php endif; ?>

                <div class="cwp-mobile-divider"></div>
                <a class="cwp-mobile-link<?php echo __navActive('/profile', $__currentPath) ? ' active' : ''; ?>" href="<?php echo url('/profile') . $__profileQuery; ?>">
                    <i class="bi bi-person"></i>View Profile
                </a>
                <a class="cwp-mobile-link<?php echo __navActive('/profile/edit', $__currentPath) ? ' active' : ''; ?>" href="<?php echo url('/profile/edit') . $__profileQuery; ?>">
                    <i class="bi bi-pencil-square"></i>Edit Profile
                </a>
                <div class="cwp-mobile-divider"></div>
                <a class="cwp-mobile-link cwp-mobile-logout" href="<?php echo url('/logout'); ?>">
                    <i class="bi bi-box-arrow-right"></i>Logout
                </a>

            <?php else: ?>
                <div class="cwp-mobile-divider"></div>
                <a class="cwp-mobile-link" href="<?php echo url('/login'); ?>">
                    <i class="bi bi-box-arrow-in-right"></i>Login
                </a>
            <?php endif; ?>
        </nav>
    </div>
</div>

<script>
(function () {
    const storageKey = 'cw_theme';

    function applyTheme(theme) {
        const isDark = theme === 'dark';
        document.documentElement.classList.toggle('theme-dark', isDark);

        const toggles = document.querySelectorAll('[data-theme-toggle] i');
        toggles.forEach(icon => {
            icon.className = isDark ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        });
    }

    function getInitialTheme() {
        const saved = localStorage.getItem(storageKey);
        if (saved === 'dark' || saved === 'light') return saved;
        return 'light';
    }

    document.addEventListener('DOMContentLoaded', function () {
        applyTheme(getInitialTheme());

        document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
            btn.addEventListener('click', function () {
                const next = document.documentElement.classList.contains('theme-dark') ? 'light' : 'dark';
                localStorage.setItem(storageKey, next);
                applyTheme(next);
            });
        });
    });
})();
</script>