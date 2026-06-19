<?php
session_start();
require_once '../Database/db.php';
require_once 'auth.php';

requireLogin();
authorize('permission.manage');

$pdo = (new Db())->connect();

/* ── Load all admins with role info ─────────────────────────────── */
$admins = $pdo->query("
    SELECT a.adminId, a.fullname, a.email, a.is_owner, a.is_protected, a.is_active,
           r.name AS role_name, r.role_level, r.slug AS role_slug,
           (SELECT COUNT(*) FROM user_permissions up WHERE up.admin_id = a.adminId AND up.granted=1) AS user_perm_grants,
           (SELECT COUNT(*) FROM user_permissions up WHERE up.admin_id = a.adminId AND up.granted=0) AS user_perm_denies
    FROM admins a
    LEFT JOIN roles r ON a.role_id = r.id
    ORDER BY COALESCE(r.role_level, 0) DESC, a.fullname ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* ── Load all roles ──────────────────────────────────────────────── */
$roles = $pdo->query("
    SELECT r.*, COUNT(rp.permission_id) AS perm_count,
           COUNT(DISTINCT a.adminId) AS user_count
    FROM roles r
    LEFT JOIN role_permissions rp ON rp.role_id = r.id
    LEFT JOIN admins a ON a.role_id = r.id
    GROUP BY r.id
    ORDER BY r.role_level DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* ── Search filter ───────────────────────────────────────────────── */
$search = trim($_GET['search'] ?? '');
if ($search) {
    $admins = array_filter($admins, fn($a) =>
        stripos($a['fullname'], $search) !== false ||
        stripos($a['email'],    $search) !== false ||
        stripos($a['role_name'] ?? '', $search) !== false
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Access Control — ATMABISWAS Admin</title>
<link rel="icon" type="image/png" href="../images/logo/logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/admin-sidebar.css">
<style>
:root { --pri:#0073e6; --dark:#1e3a5f; }
body { background:#f5f9ff; font-family:system-ui,-apple-system,'Segoe UI',sans-serif; }
.am-header { background:linear-gradient(135deg,var(--dark),var(--pri)); color:#fff; padding:1.25rem 0; margin-bottom:2rem; }
.am-header h1 { font-size:1.35rem; font-weight:800; margin:0; }
.panel { background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.07); padding:1.25rem; margin-bottom:1.5rem; }
.panel-title { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8; margin-bottom:1rem; }
.user-row { display:flex; align-items:center; gap:1rem; padding:.9rem 1rem; border-bottom:1px solid #f1f5f9; transition:background .15s; }
.user-row:last-child { border-bottom:none; }
.user-row:hover { background:#f8faff; }
.user-avatar { width:40px; height:40px; border-radius:50%; background:#dbeafe; display:flex; align-items:center; justify-content:center; color:#1d4ed8; font-weight:800; font-size:.95rem; flex-shrink:0; }
.user-avatar.owner { background:#fef9c3; color:#92400e; }
.user-info { flex:1; min-width:0; }
.user-name { font-weight:700; font-size:.9rem; color:#1e3a5f; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.user-email { font-size:.75rem; color:#64748b; }
.badge-owner { background:#f59e0b; color:#fff; font-size:.65rem; font-weight:800; padding:.2rem .45rem; border-radius:4px; }
.badge-protected { background:#6b7280; color:#fff; font-size:.65rem; font-weight:800; padding:.2rem .45rem; border-radius:4px; }
.badge-disabled { background:#ef4444; color:#fff; font-size:.65rem; font-weight:800; padding:.2rem .45rem; border-radius:4px; }
.role-badge { font-size:.72rem; padding:.2rem .5rem; border-radius:4px; background:#e0f2fe; color:#0369a1; font-weight:700; }
.level-bar { display:inline-block; background:#e2e8f0; border-radius:3px; height:5px; vertical-align:middle; margin-right:.4rem; }
.level-fill { height:100%; background:var(--pri); border-radius:3px; }
.perm-stat { font-size:.72rem; color:#64748b; }
.perm-stat .grant { color:#16a34a; font-weight:700; }
.perm-stat .deny  { color:#dc2626; font-weight:700; }
.btn-perm { background:#0073e6; color:#fff; border:none; border-radius:6px; padding:.3rem .75rem; font-size:.78rem; font-weight:700; text-decoration:none; white-space:nowrap; }
.btn-perm:hover { background:#005bb5; color:#fff; }
.role-card { background:#fff; border-radius:10px; border:1.5px solid #e2e8f0; padding:1rem; }
.role-card.level-100 { border-color:#f59e0b; }
.role-card.level-90  { border-color:#0073e6; }
.role-name-badge { font-size:.82rem; font-weight:800; color:#1e3a5f; }
.tab-btn { background:none; border:none; padding:.6rem 1rem; font-size:.82rem; font-weight:600; color:#64748b; border-bottom:3px solid transparent; }
.tab-btn.active { color:#0073e6; border-bottom-color:#0073e6; }
</style>
</head>
<body>
<div class="dashboard-container" style="display:flex;">
    <div class="sidebar-container"><?php include 'sidebar.php'; ?></div>
    <div class="main-content" style="flex:1;overflow:auto;">

<div class="am-header">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1><i class="fas fa-shield-alt me-2"></i>Access Control</h1>
                <div class="small opacity-75">Manage roles, permissions, and user access</div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <?php if (can('role.manage')): ?>
                <a href="role_permissions.php" class="btn btn-light btn-sm fw-bold">
                    <i class="fas fa-user-tag me-1"></i>Role Permissions
                </a>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-5">

    <!-- Tabs -->
    <div class="mb-3" style="border-bottom:2px solid #e2e8f0;">
        <button class="tab-btn active" onclick="showTab('users')">
            <i class="fas fa-users me-1"></i>User Permissions
        </button>
        <button class="tab-btn" onclick="showTab('roles')">
            <i class="fas fa-layer-group me-1"></i>Role Overview
        </button>
        <button class="tab-btn" onclick="showTab('audit')">
            <i class="fas fa-history me-1"></i>Audit Log
        </button>
    </div>

    <!-- Tab: Users -->
    <div id="tab-users">
        <div class="panel">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="panel-title mb-0">All Admin Users (<?= count($admins) ?>)</div>
                <form class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search users…" value="<?= htmlspecialchars($search) ?>" style="width:200px;">
                    <button class="btn btn-sm btn-outline-secondary">Search</button>
                    <?php if ($search): ?><a href="access_control.php" class="btn btn-sm btn-outline-danger">Clear</a><?php endif; ?>
                </form>
            </div>

            <?php if (empty($admins)): ?>
            <div class="text-center text-muted py-4"><i class="fas fa-users fa-2x mb-2 d-block opacity-50"></i>No users found.</div>
            <?php else: ?>
            <?php foreach ($admins as $admin):
                $initial   = strtoupper(substr($admin['fullname'], 0, 1));
                $lvl       = (int)($admin['role_level'] ?? 0);
                $fill_pct  = min(100, $lvl);
                $can_manage = canManageUser((int)$admin['adminId']);
            ?>
            <div class="user-row">
                <div class="user-avatar <?= $admin['is_owner'] ? 'owner' : '' ?>"><?= $initial ?></div>
                <div class="user-info">
                    <div class="user-name">
                        <?= htmlspecialchars($admin['fullname']) ?>
                        <?php if ($admin['is_owner']):    ?>&nbsp;<span class="badge-owner"><i class="fas fa-crown"></i> OWNER</span><?php endif; ?>
                        <?php if ($admin['is_protected']): ?>&nbsp;<span class="badge-protected"><i class="fas fa-lock"></i> PROTECTED</span><?php endif; ?>
                        <?php if (!$admin['is_active']):  ?>&nbsp;<span class="badge-disabled">DISABLED</span><?php endif; ?>
                    </div>
                    <div class="user-email"><?= htmlspecialchars($admin['email']) ?></div>
                </div>
                <div class="text-center d-none d-md-block" style="min-width:130px;">
                    <?php if ($admin['role_name']): ?>
                    <div class="role-badge mb-1"><?= htmlspecialchars($admin['role_name']) ?></div>
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <div class="level-bar" style="width:60px;"><div class="level-fill" style="width:<?= $fill_pct ?>%;"></div></div>
                        <span style="font-size:.7rem;color:#94a3b8;">L<?= $lvl ?></span>
                    </div>
                    <?php else: ?>
                    <span class="text-muted small">No role</span>
                    <?php endif; ?>
                </div>
                <div class="perm-stat d-none d-md-block text-center" style="min-width:100px;">
                    <?php if ($admin['is_owner']): ?>
                    <span class="grant">All permissions</span>
                    <?php else: ?>
                    <span class="grant">+<?= (int)$admin['user_perm_grants'] ?> grants</span><br>
                    <span class="deny">−<?= (int)$admin['user_perm_denies'] ?> denies</span>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($admin['is_protected'] || $admin['is_owner']): ?>
                    <span class="btn-perm" style="background:#94a3b8;cursor:default;" title="Protected account">
                        <i class="fas fa-lock me-1"></i>Protected
                    </span>
                    <?php elseif ($can_manage): ?>
                    <a href="user_permissions.php?id=<?= $admin['adminId'] ?>" class="btn-perm">
                        <i class="fas fa-sliders-h me-1"></i>Permissions
                    </a>
                    <?php else: ?>
                    <span class="btn-perm" style="background:#94a3b8;cursor:default;" title="You do not have authority over this user">
                        <i class="fas fa-ban me-1"></i>Restricted
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tab: Roles -->
    <div id="tab-roles" style="display:none;">
        <div class="row g-3">
        <?php foreach ($roles as $role): ?>
        <div class="col-md-6 col-lg-4">
            <div class="role-card level-<?= $role['role_level'] ?>">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="role-name-badge"><?= htmlspecialchars($role['name']) ?></div>
                        <div class="text-muted small">Level <?= $role['role_level'] ?></div>
                    </div>
                    <?php if ($role['is_system']): ?><span style="font-size:.65rem;background:#e0f2fe;color:#0369a1;padding:.2rem .45rem;border-radius:4px;font-weight:700;">SYSTEM</span><?php endif; ?>
                </div>
                <div class="d-flex gap-3 small text-muted mb-2">
                    <span><i class="fas fa-key me-1"></i><?= (int)$role['perm_count'] ?> permissions</span>
                    <span><i class="fas fa-users me-1"></i><?= (int)$role['user_count'] ?> users</span>
                </div>
                <?php if (!empty($role['description'])): ?>
                <p class="small text-muted mb-2"><?= htmlspecialchars($role['description']) ?></p>
                <?php endif; ?>
                <?php if (can('role.manage')): ?>
                <a href="role_permissions.php?role_id=<?= $role['id'] ?>" class="btn btn-sm" style="background:#e0f2fe;color:#0369a1;border:none;font-weight:700;font-size:.75rem;">
                    <i class="fas fa-edit me-1"></i>Edit Permissions
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
    </div>

    <!-- Tab: Audit Log -->
    <div id="tab-audit" style="display:none;">
        <?php
        $audit = $pdo->query("
            SELECT pal.*, a1.fullname AS changed_by_name, a2.fullname AS target_name
            FROM permission_audit_log pal
            LEFT JOIN admins a1 ON a1.adminId = pal.changed_by
            LEFT JOIN admins a2 ON a2.adminId = pal.target_admin_id
            ORDER BY pal.created_at DESC
            LIMIT 100
        ")->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="panel">
            <div class="panel-title">Last 100 Permission Changes</div>
            <?php if (empty($audit)): ?>
            <div class="text-muted text-center py-3">No audit log entries yet.</div>
            <?php else: ?>
            <div class="table-responsive">
            <table class="table table-sm" style="font-size:.8rem;">
                <thead><tr><th>When</th><th>Changed By</th><th>Target User</th><th>Action</th><th>Old</th><th>New</th><th>IP</th></tr></thead>
                <tbody>
                <?php foreach ($audit as $row): ?>
                <tr>
                    <td class="text-muted"><?= date('M j, H:i', strtotime($row['created_at'])) ?></td>
                    <td><?= htmlspecialchars($row['changed_by_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['target_name'] ?? '—') ?></td>
                    <td><code><?= htmlspecialchars($row['action']) ?></code></td>
                    <td class="text-muted"><?= htmlspecialchars($row['old_value'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($row['new_value'] ?? '—') ?></td>
                    <td class="text-muted"><?= htmlspecialchars($row['ip_address'] ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div><!-- /container -->
    </div><!-- /main-content -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showTab(name) {
    ['users','roles','audit'].forEach(t => {
        document.getElementById('tab-' + t).style.display = t === name ? '' : 'none';
    });
    document.querySelectorAll('.tab-btn').forEach((btn, i) => {
        btn.classList.toggle('active', ['users','roles','audit'][i] === name);
    });
}
</script>
</body>
</html>
