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

    /* Delete */
    if ($action === 'delete' && $adminId) {
        if ($adminId === (int)$_SESSION['admin_id']) {
            $message = 'You cannot delete your own account.';
            $msgType = 'error';
        } elseif (!canManageUser($adminId)) {
            $message = 'You do not have authority to delete this account.';
            $msgType = 'error';
        } else {
            $stmt = $conn->prepare("SELECT fullname, is_owner, is_protected FROM admins WHERE adminId=?");
            $stmt->execute([$adminId]);
            $target = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$target) {
                $message = 'Admin not found.'; $msgType = 'error';
            } elseif ($target['is_owner'] || $target['is_protected']) {
                $message = 'This account is protected and cannot be deleted.'; $msgType = 'error';
            } else {
                $conn->prepare("DELETE FROM admins WHERE adminId=?")->execute([$adminId]);
                logPermissionChange($adminId, 'delete_admin', $target['fullname'], null);
                $message = 'Admin "' . htmlspecialchars($target['fullname']) . '" deleted.';
                $msgType = 'success';
            }
        }
    }

    /* Assign role */
    if ($action === 'assign_role' && $adminId) {
        $role_id = $_POST['role_id'] ? (int)$_POST['role_id'] : null;
        if (!canManageUser($adminId)) {
            $message = 'You do not have authority to modify this account.'; $msgType = 'error';
        } else {
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
                $old_stmt = $conn->prepare("SELECT r.name FROM admins a LEFT JOIN roles r ON a.role_id=r.id WHERE a.adminId=?");
                $old_stmt->execute([$adminId]);
                $old_role = $old_stmt->fetchColumn() ?: 'None';
                $conn->prepare("UPDATE admins SET role_id=? WHERE adminId=?")->execute([$role_id, $adminId]);
                reloadPermissions($adminId);
                $new_stmt = $conn->prepare("SELECT name FROM roles WHERE id=?");
                $new_stmt->execute([$role_id]);
                $new_role = $new_stmt->fetchColumn() ?: 'None';
                logPermissionChange($adminId, 'assign_role', $old_role, $new_role);
                $message = 'Role updated successfully.'; $msgType = 'success';
            }
        }
    }

    /* Suspend */
    if ($action === 'suspend' && $adminId) {
        $reason = trim($_POST['reason'] ?? '');
        if (!can('user.suspend')) {
            $message = 'You do not have permission to suspend users.'; $msgType = 'error';
        } elseif ($adminId === (int)$_SESSION['admin_id']) {
            $message = 'You cannot suspend your own account.'; $msgType = 'error';
        } elseif (!canSuspendUser($adminId)) {
            $message = 'You do not have authority to suspend this account (hierarchy or immunity rule).';
            $msgType = 'error';
        } else {
            $t = $conn->prepare("SELECT fullname FROM admins WHERE adminId=?");
            $t->execute([$adminId]);
            $tname = $t->fetchColumn();
            $conn->prepare("
                UPDATE admins
                SET is_suspended=1, suspended_at=NOW(), suspended_by=?, suspension_reason=?
                WHERE adminId=?
            ")->execute([(int)$_SESSION['admin_id'], $reason ?: null, $adminId]);
            logPermissionChange($adminId, 'suspend_user', 'active', 'suspended|reason:' . $reason);
            $message = '"' . htmlspecialchars($tname) . '" has been suspended.'; $msgType = 'success';
        }
    }

    /* Unsuspend */
    if ($action === 'unsuspend' && $adminId) {
        if (!can('user.suspend')) {
            $message = 'You do not have permission to activate users.'; $msgType = 'error';
        } elseif (!canSuspendUser($adminId)) {
            $message = 'You do not have authority over this account.'; $msgType = 'error';
        } else {
            $t = $conn->prepare("SELECT fullname FROM admins WHERE adminId=?");
            $t->execute([$adminId]);
            $tname = $t->fetchColumn();
            $conn->prepare("
                UPDATE admins
                SET is_suspended=0, suspended_at=NULL, suspended_by=NULL, suspension_reason=NULL
                WHERE adminId=?
            ")->execute([$adminId]);
            logPermissionChange($adminId, 'unsuspend_user', 'suspended', 'active');
            $message = '"' . htmlspecialchars($tname) . '" has been re-activated.'; $msgType = 'success';
        }
    }
}

/* ── Load admins ─────────────────────────────────────────────────── */
$admins = $conn->query("
    SELECT a.adminId, a.fullname, a.email,
           a.is_owner, a.is_protected, a.is_active,
           a.is_suspended, a.suspended_at, a.suspended_by, a.suspension_reason,
           r.id AS role_id, r.name AS role_name, r.role_level
    FROM admins a
    LEFT JOIN roles r ON a.role_id = r.id
    ORDER BY COALESCE(r.role_level, 0) DESC, a.fullname ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* ── Suspended-by names ──────────────────────────────────────────── */
$sus_by_ids = array_filter(array_column($admins, 'suspended_by'));
$sus_by_map = [];
if ($sus_by_ids) {
    $in   = implode(',', array_map('intval', array_unique($sus_by_ids)));
    $rows = $conn->query("SELECT adminId, fullname FROM admins WHERE adminId IN ($in)")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) $sus_by_map[(int)$r['adminId']] = $r['fullname'];
}

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
    .role-badge    { display:inline-block;font-size:.68rem;font-weight:700;padding:.15rem .45rem;border-radius:4px;background:#dbeafe;color:#1d4ed8; }
    .badge-owner   { background:#fef3c7;color:#92400e; }
    .badge-protected { background:#e5e7eb;color:#374151; }
    .badge-disabled  { background:#fee2e2;color:#b91c1c; }
    .badge-self    { background:#dcfce7;color:#166534; }
    .badge-suspended { background:#fef2f2;color:#b91c1c;border:1px solid #fca5a5; }
    .level-bar  { display:inline-block;width:50px;height:5px;background:#e2e8f0;border-radius:3px;vertical-align:middle;margin-right:.3rem; }
    .level-fill { height:100%;background:#0073e6;border-radius:3px; }
    .assign-form { display:flex;gap:.4rem;align-items:center;flex-wrap:wrap; }
    .assign-form select { font-size:.75rem;padding:.2rem .4rem;border-radius:4px;border:1px solid #d1d5db; }
    .assign-form button { font-size:.72rem;padding:.2rem .6rem; }
    .sus-detail { font-size:.72rem;color:#b91c1c;background:#fef2f2;border-radius:4px;padding:.3rem .5rem;margin-top:.35rem; }
    .admin-card.is-suspended { border-left:3px solid #ef4444; }
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
                        <p class="page-subtitle">View, manage, suspend, and assign roles to admin accounts</p>
                    </div>
                    <div class="header-actions">
                        <?php if (can('user.manage')): ?>
                        <a href="adminSignup.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create New Admin</a>
                        <?php endif; ?>
                        <?php if (can('permission.manage')): ?>
                        <a href="access_control.php" class="btn btn-outline"><i class="fas fa-shield-alt"></i> Access Control</a>
                        <?php endif; ?>
                        <a href="dashboard.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Dashboard</a>
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
                                <p>Manage admin user accounts, roles, and suspensions</p>
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
                                $is_self     = (int)$admin['adminId'] === (int)$_SESSION['admin_id'];
                                $can_manage  = !$is_self && canManageUser((int)$admin['adminId']);
                                $can_suspend = !$is_self && canSuspendUser((int)$admin['adminId']);
                                $is_sus      = !empty($admin['is_suspended']);
                                $lvl         = (int)($admin['role_level'] ?? 0);
                            ?>
                            <div class="admin-card <?= $is_self ? 'current-user' : '' ?> <?= $is_sus ? 'is-suspended' : '' ?>">
                                <div class="admin-avatar"><i class="fas fa-user-shield"></i></div>
                                <div class="admin-info">
                                    <h3 class="admin-name">
                                        <?= htmlspecialchars($admin['fullname']) ?>
                                        <?php if ($is_self):    ?><span class="badge-self"      style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;">You</span><?php endif; ?>
                                        <?php if ($admin['is_owner']): ?><span class="badge-owner"   style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;"><i class="fas fa-crown"></i> Owner</span><?php endif; ?>
                                        <?php if ($admin['is_protected']): ?><span class="badge-protected" style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;"><i class="fas fa-lock"></i> Protected</span><?php endif; ?>
                                        <?php if ($is_sus): ?><span class="badge-suspended" style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;"><i class="fas fa-ban"></i> Suspended</span><?php endif; ?>
                                        <?php if (isset($admin['is_active']) && !$admin['is_active'] && !$is_sus): ?><span class="badge-disabled" style="font-size:.65rem;padding:.15rem .4rem;border-radius:3px;">Disabled</span><?php endif; ?>
                                    </h3>
                                    <p class="admin-email"><i class="fas fa-envelope"></i> <?= htmlspecialchars($admin['email'] ?: '—') ?></p>
                                    <p class="admin-id"><i class="fas fa-id-card"></i> ID: <?= $admin['adminId'] ?></p>

                                    <div style="margin-top:.35rem;">
                                        <?php if ($admin['role_name']): ?>
                                        <span class="role-badge"><?= htmlspecialchars($admin['role_name']) ?></span>
                                        <span style="font-size:.68rem;color:#94a3b8;margin-left:.35rem;">
                                            <span class="level-bar"><span class="level-fill" style="width:<?= min(100,$lvl) ?>%;"></span></span>
                                            Level <?= $lvl ?>
                                        </span>
                                        <?php else: ?>
                                        <span style="font-size:.72rem;color:#94a3b8;">No role assigned</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($is_sus): ?>
                                    <div class="sus-detail">
                                        <i class="fas fa-ban me-1"></i>
                                        Suspended <?= $admin['suspended_at'] ? date('M j, Y', strtotime($admin['suspended_at'])) : '' ?>
                                        <?php if ($admin['suspended_by'] && isset($sus_by_map[(int)$admin['suspended_by']])): ?>
                                        by <strong><?= htmlspecialchars($sus_by_map[(int)$admin['suspended_by']]) ?></strong>
                                        <?php endif; ?>
                                        <?php if ($admin['suspension_reason']): ?>
                                        — "<?= htmlspecialchars($admin['suspension_reason']) ?>"
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>

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
                                        <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-save"></i> Assign</button>
                                    </form>
                                    <?php endif; ?>
                                </div>

                                <div class="admin-actions">
                                    <?php if ($is_self): ?>
                                    <span class="btn btn-outline btn-sm disabled"><i class="fas fa-lock"></i> Current User</span>

                                    <?php elseif ($admin['is_owner'] || $admin['is_protected']): ?>
                                    <span class="btn btn-outline btn-sm disabled"><i class="fas fa-shield-alt"></i> Immune</span>

                                    <?php else: ?>
                                        <?php if ($can_manage && can('permission.manage')): ?>
                                        <a href="user_permissions.php?id=<?= $admin['adminId'] ?>" class="btn btn-outline btn-sm">
                                            <i class="fas fa-key"></i> Permissions
                                        </a>
                                        <?php endif; ?>

                                        <?php if ($can_suspend): ?>
                                            <?php if ($is_sus): ?>
                                            <button class="btn btn-sm" style="background:#dcfce7;color:#166534;border:1px solid #86efac;"
                                                onclick="confirmUnsuspend(<?= $admin['adminId'] ?>, '<?= htmlspecialchars($admin['fullname'], ENT_QUOTES) ?>')">
                                                <i class="fas fa-check-circle"></i> Activate
                                            </button>
                                            <?php else: ?>
                                            <button class="btn btn-sm" style="background:#fef2f2;color:#b91c1c;border:1px solid #fca5a5;"
                                                onclick="openSuspendModal(<?= $admin['adminId'] ?>, '<?= htmlspecialchars($admin['fullname'], ENT_QUOTES) ?>')">
                                                <i class="fas fa-ban"></i> Suspend
                                            </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if ($can_manage): ?>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="confirmDelete(<?= $admin['adminId'] ?>, '<?= htmlspecialchars($admin['fullname'], ENT_QUOTES) ?>')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                        <?php elseif (!$can_suspend): ?>
                                        <span class="btn btn-outline btn-sm disabled"><i class="fas fa-ban"></i> Restricted</span>
                                        <?php endif; ?>
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
                    <div class="notice-item"><i class="fas fa-crown"></i><span>Head IT (Arafat) is immune — cannot be suspended by anyone</span></div>
                    <div class="notice-item"><i class="fas fa-info-circle"></i><span>Suspension authority requires role level strictly above the target</span></div>
                    <div class="notice-item"><i class="fas fa-history"></i><span>All suspensions and activations are recorded in the audit log</span></div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Delete modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header"><h3><i class="fas fa-exclamation-triangle"></i> Confirm Delete</h3></div>
        <div class="modal-body">
            <p>Delete admin account for: <strong id="deleteName"></strong>?</p>
            <p class="warning-text">This cannot be undone!</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModals()">Cancel</button>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="admin_id" id="deleteId">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
            </form>
        </div>
    </div>
</div>

<!-- Suspend modal -->
<div id="suspendModal" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="background:#fef2f2;">
            <h3 style="color:#b91c1c;"><i class="fas fa-ban"></i> Suspend Account</h3>
        </div>
        <div class="modal-body">
            <p>Suspending: <strong id="suspendName"></strong></p>
            <p style="font-size:.85rem;color:#6b7280;">The user will be blocked from logging in immediately.</p>
            <label style="font-size:.85rem;font-weight:600;display:block;margin-bottom:.35rem;">Reason <span style="color:#94a3b8;font-weight:400;">(optional)</span></label>
            <textarea id="suspendReason" name="reason" rows="3"
                style="width:100%;border:1px solid #d1d5db;border-radius:6px;padding:.5rem;font-size:.85rem;resize:vertical;"
                placeholder="Enter suspension reason…"></textarea>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModals()">Cancel</button>
            <form method="POST" id="suspendForm" style="display:inline;">
                <input type="hidden" name="action" value="suspend">
                <input type="hidden" name="admin_id" id="suspendId">
                <input type="hidden" name="reason" id="suspendReasonHidden">
                <button type="submit" class="btn btn-danger" onclick="syncReason()"><i class="fas fa-ban"></i> Suspend</button>
            </form>
        </div>
    </div>
</div>

<!-- Unsuspend confirm -->
<div id="unsuspendModal" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="background:#f0fdf4;">
            <h3 style="color:#166534;"><i class="fas fa-check-circle"></i> Re-activate Account</h3>
        </div>
        <div class="modal-body">
            <p>Re-activate account for: <strong id="unsuspendName"></strong>?</p>
            <p style="font-size:.85rem;color:#6b7280;">The user will be able to log in again immediately.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModals()">Cancel</button>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="unsuspend">
                <input type="hidden" name="admin_id" id="unsuspendId">
                <button type="submit" class="btn" style="background:#16a34a;color:#fff;"><i class="fas fa-check-circle"></i> Activate</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function closeModals() {
    document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
}
window.onclick = e => {
    document.querySelectorAll('.modal').forEach(m => { if (e.target === m) closeModals(); });
};

function confirmDelete(id, name) {
    document.getElementById('deleteId').value   = id;
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteModal').style.display = 'flex';
}
function openSuspendModal(id, name) {
    document.getElementById('suspendId').value    = id;
    document.getElementById('suspendName').textContent = name;
    document.getElementById('suspendReason').value = '';
    document.getElementById('suspendModal').style.display = 'flex';
}
function confirmUnsuspend(id, name) {
    document.getElementById('unsuspendId').value    = id;
    document.getElementById('unsuspendName').textContent = name;
    document.getElementById('unsuspendModal').style.display = 'flex';
}
function syncReason() {
    document.getElementById('suspendReasonHidden').value = document.getElementById('suspendReason').value;
}

setTimeout(() => {
    document.querySelectorAll('.notification').forEach(n => {
        n.style.opacity = '0';
        setTimeout(() => n.style.display = 'none', 300);
    });
}, 5000);
</script>
</body>
</html>
