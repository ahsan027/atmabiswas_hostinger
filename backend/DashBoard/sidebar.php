<?php
if (!function_exists('can')) { require_once __DIR__ . '/auth.php'; }
$_cur = basename($_SERVER['PHP_SELF']);
function _nav(string $href, string $icon, string $label, string $cur): void {
    $file   = basename($href);
    $active = ($file === $cur || strpos($href, $cur) !== false) ? ' active' : '';
    echo "<a href=\"{$href}\" class=\"nav-item{$active}\"><i class=\"fas {$icon}\"></i><span>{$label}</span></a>\n";
}
?>
<div class="admin-sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-shapes"></i>
            <h1>DashPanel</h1>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php _nav('dashboard.php', 'fa-chart-line', 'Dashboard', $_cur); ?>

        <?php if (can('job.view') || can('job.create') || can('job.edit') || can('job.delete')): ?>
        <div class="sidebar-section-label">Jobs</div>
        <?php if (can('job.create')): ?>
        <?php _nav('addJobPosition.php', 'fa-user-plus', 'Add Job Position', $_cur); ?>
        <?php _nav('createjob.php', 'fa-briefcase-medical', 'Create New Job Post', $_cur); ?>
        <?php endif; ?>
        <?php if (can('job.view') || can('job.edit')): ?>
        <?php _nav('updatejobs.php', 'fa-edit', 'Manage Job Posts', $_cur); ?>
        <?php endif; ?>
        <?php endif; ?>

        <?php if (can('press.view') || can('press.create') || can('press.edit') || can('press.delete')): ?>
        <div class="sidebar-section-label">Press &amp; News</div>
        <?php if (can('press.create')): ?>
        <?php _nav('blog_enhanced.php', 'fa-plus-circle', 'Add Press Post', $_cur); ?>
        <?php endif; ?>
        <?php if (can('press.view') || can('press.edit')): ?>
        <?php _nav('blog_manager.php', 'fa-newspaper', 'All Press Posts', $_cur); ?>
        <?php endif; ?>
        <a href="../../press.php" class="nav-item" target="_blank">
            <i class="fas fa-external-link-alt"></i><span>View Newsroom</span>
        </a>
        <?php endif; ?>

        <?php if (can('gallery.manage')): ?>
        <div class="sidebar-section-label">Media</div>
        <?php _nav('uploadimg.php', 'fa-photo-video', 'Upload Image Gallery', $_cur); ?>
        <?php endif; ?>

        <?php if (can('notice.manage')): ?>
        <?php if (!can('gallery.manage')): ?><div class="sidebar-section-label">Media</div><?php endif; ?>
        <?php _nav('uploadpdf.php', 'fa-bell', 'Upload Notice', $_cur); ?>
        <?php endif; ?>

        <?php if (can('branch.manage')): ?>
        <div class="sidebar-section-label">Contact Management</div>
        <?php _nav('regional_offices.php', 'fa-map-marker-alt', 'Regional Offices', $_cur); ?>
        <?php _nav('divisions.php', 'fa-layer-group', 'Divisions', $_cur); ?>
        <?php _nav('branches.php', 'fa-code-branch', 'Branches', $_cur); ?>
        <?php endif; ?>

        <?php if (can('user.manage')): ?>
        <div class="sidebar-section-label">User Management</div>
        <?php _nav('manageAdmins.php', 'fa-users-cog', 'Manage Admins', $_cur); ?>
        <?php _nav('adminSignup.php', 'fa-user-plus', 'Create Admin', $_cur); ?>
        <?php endif; ?>

        <?php if (can('permission.manage') || can('role.manage')): ?>
        <?php if (!can('user.manage')): ?><div class="sidebar-section-label">Administration</div><?php endif; ?>
        <?php if (can('permission.manage')): ?>
        <?php _nav('access_control.php', 'fa-shield-alt', 'Access Control', $_cur); ?>
        <?php endif; ?>
        <?php if (can('role.manage')): ?>
        <?php _nav('role_permissions.php', 'fa-layer-group', 'Role Permissions', $_cur); ?>
        <?php endif; ?>
        <?php endif; ?>

        <div class="sidebar-section-label">Account</div>
        <?php _nav('changeCredentials.php', 'fa-user-shield', 'Change Credentials', $_cur); ?>
    </nav>

    <div class="sidebar-footer">
        <a href="../login/logout.php" class="nav-item logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        <div class="user-info">
            <a href="../../index.php" class="user-link">
                <img src="../images/logo/logo.png" alt="ATMABISWAS Logo" class="user-logo" />
                <div class="user-details">
                    <div class="user-name">ATMABISWAS</div>
                    <div class="user-role"><?= htmlspecialchars($_SESSION['role_name'] ?? 'Administrator') ?></div>
                </div>
            </a>
        </div>
    </div>
</div>
