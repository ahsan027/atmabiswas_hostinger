<?php
session_start();
require_once '../Database/db.php';
require_once 'auth.php';

requireLogin();
authorize('user.manage');

$conn    = (new Db())->connect();
$message = '';
$msgType = '';

/* ── Handle POST actions ─────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action  = $_POST['action'] ?? '';
    $adminId = (int)($_POST['admin_id'] ?? 0);

    if ($action === 'delete' && $adminId) {
        if ($adminId === (int)$_SESSION['admin_id']) {
            $message = 'You cannot delete your own account.';
            $msgType = 'error';
        } elseif (!canManageUser($adminId)) {
            $message = 'You do not have authority to delete this account.';
            $msgType = 'error';
        } else {
            try {
                $stmt = $conn->prepare("SELECT fullname, is_owner, is_protected FROM admins WHERE adminId=?");
                $stmt->execute([$adminId]);
                $target = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$target) {
                    $message = 'Admin not found.';
                    $msgType = 'error';
                } elseif ($target['is_owner'] || $target['is_protected']) {
                    $message = 'This account is protected and cannot be deleted.';
                    $msgType = 'error';
                } else {
                    $conn->prepare("DELETE FROM admins WHERE adminId=?")->execute([$adminId]);
                    logPermissionChange($adminId, 'delete_admin', $target['fullname'], null);
                    $message = 'Admin "' . htmlspecialchars($target['fullname']) . '" deleted.';
                    $msgType = 'success';
                }
            } catch (PDOException $e) {
                $message = 'Database error during delete.';
                $msgType = 'error';
            }
        }
    }

    if ($action === 'assign_role' && $adminId) {
        $role_id = $_POST['role_id'] ? (int)$_POST['role_id'] : null;

        if (!canManageUser($adminId)) {
            $message = 'You do not have authority to modify this account.';
            $msgType = 'error';
        } else {
            // Validate the target role level is strictly below current user's level
            $ok = true;
            if ($role_id) {
                $rs = $conn->prepare("SELECT role_level FROM roles WHERE id=?");
                $rs->execute([$role_id]);
                $rl = $rs->fetchColumn();
                if ($rl !== false && (int)$rl >= myRoleLevel()) {
                    $ok = false;
                    $message = 'You cannot assign a role equal to or higher than your own level.';
                    $msgType = 'error';
                }
            }
            if ($ok) {
                try {
                    $old_stmt = $conn->prepare("SELECT r.name FROM admins a LEFT JOIN roles r ON a.role_id=r.id WHERE a.adminId=?");
                    $old_stmt->execute([$adminId]);
                    $old_role = $old_stmt->fetchColumn() ?? 'None';

                    $conn->prepare("UPDATE admins SET role_id=? WHERE adminId=?")->execute([$role_id, $adminId]);
                    reloadPermissions($adminId);

                    $new_stmt = $conn->prepare("SELECT name FROM roles WHERE id=?");
                    $new_stmt->execute([$role_id]);
                    $new_role = $new_stmt->fetchColumn() ?: 'None';

                    logPermissionChange($adminId, 'assign_role', $old_role, $new_role);
                    $message = 'Role updated successfully.';
                    $msgType = 'success';
                } catch (PDOException $e) {
                    $message = 'Database error during role assignment.';
                    $msgType = 'error';
                }
            }
        }
    }
}

/* ── Load admins with role info ──────────────────────────────────── */
$admins = $conn->query("
    SELECT a.adminId, a.fullname, a.email, a.is_owner, a.is_protected, a.is_active,
           r.id AS role_id, r.name AS role_name, r.role_level
    FROM admins a
    LEFT JOIN roles r ON a.role_id = r.id
    ORDER BY COALESCE(r.role_level, 0) DESC, a.fullname ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* ── Available roles for assign dropdown ─────────────────────────── */
$my_level    = myRoleLevel();
$avail_roles = $conn->prepare("SELECT id, name, role_level FROM roles WHERE role_level < ? ORDER BY role_level DESC");
$avail_roles->execute([$my_level]);
$avail_roles = $avail_roles->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins — ATMABISWAS</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/manageAdmins.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
    <style>
    .role-badge { display:inline-block; font-size:.68rem; font-weight:700; padding:.15rem .45rem; border-radius:4px; background:#dbeafe; color:#1d4ed8; }
    .badge-owner { background:#fef3c7; color:#92400e; }
    .badge-protected { background:#e5e7eb; color:#374151; }
    .badge-disabled { background:#fee2e2; color:#b91c1c; }
    .badge-self { background:#dcfce7; color:#166534; }
    .level-bar { display:inline-block; width:50px; height:5px; background:#e2e8f0; border-radius:3px; vertical-align:middle; margin-right:.3rem; }
    .level-fill { height:100%; background:#0073e6; border-radius:3px; }
    .assign-form { display:flex; gap:.4rem; align-items:center; flex-wrap:wrap; }
    .assign-form select { font-size:.75rem; padding:.2rem .4rem; border-radius:4px; border:1px solid #d1d5db; }
    .assign-form button { font-size:.72rem; padding:.2rem .6rem; }
    </style>
</head>
<body class="bg-gray-50">
<div class="dashboard-container">
    <div class="sidebar-container"><?php include 'sidebar.php'; ?></div>
    <div class="main-content">
        <?php include 'navbar.inc.php'; ?>
        <main class="dashboard-main">
            <div class="page-header">
                <div class="header-content">
                    <div class="header-left">
                        <h1>Manage Admins</h1>
                        <p class="page-subtitle">View, manage, and assign roles to admin accounts</p>
                    </div>
                    <div class="header-actions">
                        <?php if (can('user.manage')): ?>
                        <a href="adminSignup.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Create New Admin
                        </a>
                        <?php endif; ?>
                        <?php if (can('permission.manage')): ?>
                        <a href="access_control.php" class="btn btn-outline">
                            <i class="fas fa-shield-alt"></i> Access Control
                        </a>
                        <?php endif; ?>
                        <a href="dashboard.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <?php if ($message): ?>
            <div class="notification <?= $msgType ?>">
                <i class="fas <?= $msgType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                <span><?= htmlspecialchars($message) ?></span>
            </div>
            <?php endif; ?>

            <div class="admins-section">
                <div class="admins-container">
                    <div class="admins-header">
                        <div class="admins-title">
                            <i class="fas fa-users-cog"></i>
                            <div>
                                <h2>Admin Accounts</h2>
                                <p>Manage admin user accounts and role assignments</p>
                            </div>
                        </div>
                        <div class="admins-count">
                            <span class="count-number"><?= count($admins) ?></span>
                            <span class="count-label">Total Admins</span>
                        </div>
                    </div>

                    <div class="admins-content">
                        <?php if (empty($admins)): ?>
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h3>No Admins Found</h3>
                            <a href="adminSignup.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create First Admin</a>
                        </div>
                        <?php else: ?>
                        <div class="admins-grid">
                            <?php foreach ($admins as $admin):
                                $is_self    = (int)$admin['adminId'] === (int)$_SESSION['admin_id'];
                                $can_manage = !$is_self && canManageUser((int)$admin['adminId']);
                                $lvl        = (int)($admin['role_level'] ?? 0);
                                $fill_pct   = min(100, $lvl);
                            ?>
                            <div class="admin-card <?= $is_self ? 'current-user' : '' ?>">
                                <div class="admin-avatar"><i class="fas fa-user-shield"></i></div>
                                <div class="admin-info">
                                    <h3 class="admin-name">
                                        <?= htmlspecialchars($admin['fullname']) ?>
                                        <?php if ($is_self): ?><span class="badge-self" style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;">You</span><?php endif; ?>
                                        <?php if ($admin['is_owner']): ?><span class="badge-owner" style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;"><i class="fas fa-crown"></i> Owner</span><?php endif; ?>
                                        <?php if ($admin['is_protected']): ?><span class="badge-protected" style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;"><i class="fas fa-lock"></i> Protected</span><?php endif; ?>
                                        <?php if (isset($admin['is_active']) && !$admin['is_active']): ?><span class="badge-disabled" style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;">Disabled</span><?php endif; ?>
                                    </h3>
                                    <p class="admin-email"><i class="fas fa-envelope"></i> <?= htmlspecialchars($admin['email'] ?: '—') ?></p>
                                    <p class="admin-id"><i class="fas fa-id-card"></i> ID: <?= $admin['adminId'] ?></p>
                                    <div style="margin-top:.35rem;">
                                        <?php if ($admin['role_name']): ?>
                                        <span class="role-badge"><?= htmlspecialchars($admin['role_name']) ?></span>
                                        <span style="font-size:.68rem;color:#94a3b8;margin-left:.35rem;">
                                            <span class="level-bar"><span class="level-fill" style="width:<?= $fill_pct ?>%;"></span></span>
                                            Level <?= $lvl ?>
                                        </span>
                                        <?php else: ?>
                                        <span style="font-size:.72rem;color:#94a3b8;">No role assigned</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($can_manage && !empty($avail_roles)): ?>
                                    <form method="POST" class="assign-form mt-2">
                                        <input type="hidden" name="action" value="assign_role">
                                        <input type="hidden" name="admin_id" value="<?= $admin['adminId'] ?>">
                                        <select name="role_id">
                                            <option value="">— No Role —</option>
                                            <?php foreach ($avail_roles as $r): ?>
                                            <option value="<?= $r['id'] ?>" <?= (int)$admin['role_id'] === (int)$r['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($r['name']) ?> (L<?= $r['role_level'] ?>)
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-outline btn-sm" title="Assign Role">
                                            <i class="fas fa-save"></i> Assign
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <div class="admin-actions">
                                    <?php if ($is_self): ?>
                                    <span class="btn btn-outline btn-sm disabled"><i class="fas fa-lock"></i> Current User</span>
                                    <?php elseif ($admin['is_owner'] || $admin['is_protected']): ?>
                                    <span class="btn btn-outline btn-sm disabled" title="Protected account"><i class="fas fa-shield-alt"></i> Protected</span>
                                    <?php elseif ($can_manage): ?>
                                        <?php if (can('permission.manage')): ?>
                                        <a href="user_permissions.php?id=<?= $admin['adminId'] ?>" class="btn btn-outline btn-sm" title="Manage permissions">
                                            <i class="fas fa-key"></i> Permissions
                                        </a>
                                        <?php endif; ?>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="confirmDelete(<?= $admin['adminId'] ?>, '<?= htmlspecialchars($admin['fullname'], ENT_QUOTES) ?>')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    <?php else: ?>
                                    <span class="btn btn-outline btn-sm disabled" title="Your authority level is insufficient"><i class="fas fa-ban"></i> Restricted</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="security-notice">
                <div class="notice-header"><i class="fas fa-shield-alt"></i><h3>Security Notice</h3></div>
                <div class="notice-content">
                    <div class="notice-item"><i class="fas fa-info-circle"></i><span>You can only manage accounts with a role level strictly below yours</span></div>
                    <div class="notice-item"><i class="fas fa-lock"></i><span>Owner and protected accounts cannot be modified or deleted by anyone</span></div>
                    <div class="notice-item"><i class="fas fa-exclamation-triangle"></i><span>All role assignments and deletions are logged in the audit trail</span></div>
                </div>
            </div>
        </main>
    </div>
</div>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h3><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h3></div>
        <div class="modal-body">
            <p>Are you sure you want to delete the admin account for:</p>
            <p class="admin-to-delete"><strong id="adminName"></strong></p>
            <p class="warning-text">This action cannot be undone!</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal()">Cancel</button>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="admin_id" id="adminIdToDelete">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete Admin</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete(id, name) {
    document.getElementById('adminIdToDelete').value = id;
    document.getElementById('adminName').textContent  = name;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeModal() { document.getElementById('deleteModal').style.display = 'none'; }
window.onclick = e => { if (e.target === document.getElementById('deleteModal')) closeModal(); };
setTimeout(() => {
    document.querySelectorAll('.notification').forEach(n => {
        n.style.opacity = '0';
        setTimeout(() => n.style.display = 'none', 300);
    });
}, 5000);
</script>
</body>
</html>
