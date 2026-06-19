<?php
session_start();
require_once '../Database/db.php';
require_once 'auth.php';

requireLogin();
authorize('role.manage');

$pdo = (new Db())->connect();

/* ── Load roles list ─────────────────────────────────────────────── */
$roles = $pdo->query("SELECT * FROM roles ORDER BY role_level DESC")->fetchAll(PDO::FETCH_ASSOC);

/* ── Selected role ───────────────────────────────────────────────── */
$role_id = filter_input(INPUT_GET, 'role_id', FILTER_VALIDATE_INT)
        ?: ($roles[0]['id'] ?? null);

$selected_role = null;
foreach ($roles as $r) {
    if ((int)$r['id'] === (int)$role_id) { $selected_role = $r; break; }
}

$msg  = '';
$msgT = '';

/* ── Handle POST: save role permissions ──────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $role_id) {
    // Prevent editing system roles if not owner
    if ($selected_role['is_system'] && !$_SESSION['is_owner']) {
        $reason = "Only the system owner can modify built-in system roles.";
        http_response_code(403);
        include '403.php';
        exit();
    }

    $all_perm_ids = $pdo->query("SELECT id FROM permissions")->fetchAll(PDO::FETCH_COLUMN);
    $granted_ids  = array_map('intval', (array)($_POST['granted_perm'] ?? []));

    // Old state for audit
    $old_ids = $pdo->prepare("SELECT permission_id FROM role_permissions WHERE role_id=?");
    $old_ids->execute([$role_id]);
    $old_ids = $old_ids->fetchAll(PDO::FETCH_COLUMN);

    // Rebuild
    $pdo->prepare("DELETE FROM role_permissions WHERE role_id=?")->execute([$role_id]);
    $ins = $pdo->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?,?)");
    foreach ($granted_ids as $pid) {
        if (in_array($pid, array_map('intval', $all_perm_ids))) {
            $ins->execute([$role_id, $pid]);
        }
    }

    $added   = array_diff($granted_ids, array_map('intval', $old_ids));
    $removed = array_diff(array_map('intval', $old_ids), $granted_ids);

    if ($added || $removed) {
        logPermissionChange(
            (int)($selected_role['id']),
            'update_role_permissions:' . $selected_role['slug'],
            'removed:' . implode(',', $removed),
            'added:' . implode(',', $added)
        );
    }

    header("Location: role_permissions.php?role_id={$role_id}&msg=saved&msgt=success");
    exit();
}

if (!empty($_GET['msg'])) {
    $msg  = $_GET['msg'] === 'saved' ? 'Role permissions saved.' : $_GET['msg'];
    $msgT = $_GET['msgt'] ?? 'success';
}

/* ── Load permissions ────────────────────────────────────────────── */
$all_perms = $pdo->query("SELECT * FROM permissions ORDER BY module, action")->fetchAll(PDO::FETCH_ASSOC);

$role_perm_ids = [];
if ($role_id) {
    $rp = $pdo->prepare("SELECT permission_id FROM role_permissions WHERE role_id=?");
    $rp->execute([$role_id]);
    $role_perm_ids = array_map('intval', $rp->fetchAll(PDO::FETCH_COLUMN));
}

// Users in this role
$role_users = [];
if ($role_id) {
    $ru = $pdo->prepare("SELECT adminId, fullname, email FROM admins WHERE role_id=? ORDER BY fullname");
    $ru->execute([$role_id]);
    $role_users = $ru->fetchAll(PDO::FETCH_ASSOC);
}

$modules = [];
foreach ($all_perms as $p) {
    $modules[$p['module']][] = $p;
}
$module_labels = [
    'dashboard'  => ['Dashboard',       'fa-chart-line'],
    'user'       => ['User Management', 'fa-users'],
    'role'       => ['Role Management', 'fa-layer-group'],
    'permission' => ['Permissions',     'fa-key'],
    'press'      => ['Press / News',    'fa-newspaper'],
    'job'        => ['Job Postings',    'fa-briefcase'],
    'branch'     => ['Branches',        'fa-code-branch'],
    'gallery'    => ['Gallery',         'fa-images'],
    'notice'     => ['Notices',         'fa-bell'],
    'settings'   => ['Settings',        'fa-cog'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Role Permissions — ATMABISWAS Admin</title>
<link rel="icon" type="image/png" href="../images/logo/logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/admin-sidebar.css">
<style>
:root { --pri:#0073e6; --dark:#1e3a5f; }
body { background:#f5f9ff; font-family:system-ui,-apple-system,'Segoe UI',sans-serif; }
.am-header { background:linear-gradient(135deg,var(--dark),var(--pri)); color:#fff; padding:1.25rem 0; margin-bottom:1.5rem; }
.am-header h1 { font-size:1.25rem; font-weight:800; margin:0; }
.panel { background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.07); padding:1.25rem; margin-bottom:1.25rem; }
.panel-title { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8; margin-bottom:.75rem; }
.role-tab { padding:.55rem 1rem; border-radius:8px; font-size:.82rem; font-weight:700; color:#374151; background:#f1f5f9; text-decoration:none; display:block; margin-bottom:.35rem; border:1.5px solid transparent; }
.role-tab.active { background:#e0f2fe; color:#0073e6; border-color:#0073e6; }
.role-tab .level { font-size:.65rem; font-weight:400; color:#94a3b8; }
.perm-grid { display:grid; grid-template-columns:1fr auto; align-items:center; padding:.35rem 0; gap:1rem; font-size:.82rem; border-bottom:1px solid #f1f5f9; }
.perm-grid:last-child { border-bottom:none; }
.module-header { display:flex; align-items:center; gap:.5rem; font-size:.82rem; font-weight:800; color:#1e3a5f; margin-bottom:.75rem; padding-bottom:.5rem; border-bottom:1.5px solid #e2e8f0; }
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
                <h1><i class="fas fa-layer-group me-2"></i>Role Permissions</h1>
                <div class="small opacity-75">Set default permissions for each role</div>
            </div>
            <a href="access_control.php" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Access Control
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-5">

<?php if ($msg): ?>
<div class="alert alert-<?= $msgT === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-3">
    <!-- Left: Role list -->
    <div class="col-md-3">
        <div class="panel">
            <div class="panel-title">Roles</div>
            <?php foreach ($roles as $r): ?>
            <a href="role_permissions.php?role_id=<?= $r['id'] ?>"
               class="role-tab <?= (int)$r['id'] === (int)$role_id ? 'active' : '' ?>">
                <?= htmlspecialchars($r['name']) ?>
                <span class="level d-block">Level <?= $r['role_level'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($role_users)): ?>
        <div class="panel">
            <div class="panel-title">Users with this role (<?= count($role_users) ?>)</div>
            <?php foreach ($role_users as $ru): ?>
            <div style="font-size:.78rem;padding:.25rem 0;border-bottom:1px solid #f1f5f9;color:#374151;">
                <i class="fas fa-user me-1 text-primary"></i>
                <?= htmlspecialchars($ru['fullname']) ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Permission matrix -->
    <div class="col-md-9">
        <?php if ($selected_role): ?>
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h5 class="fw-bold mb-0" style="color:#1e3a5f;"><?= htmlspecialchars($selected_role['name']) ?></h5>
                <div class="text-muted small">Level <?= $selected_role['role_level'] ?> · <?= count($role_perm_ids) ?> permissions assigned</div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-success fw-bold" onclick="setAll(true)">Grant All</button>
                <button type="button" class="btn btn-sm btn-outline-secondary fw-bold" onclick="setAll(false)">Clear All</button>
            </div>
        </div>

        <?php if ($selected_role['is_system'] && !$_SESSION['is_owner']): ?>
        <div class="alert alert-warning mb-3">
            <i class="fas fa-lock me-2"></i>This is a system role. Only the owner can modify it.
        </div>
        <?php endif; ?>

        <form method="POST">
        <?php foreach ($modules as $module => $perms):
            [$label, $icon] = $module_labels[$module] ?? [ucfirst($module), 'fa-circle'];
        ?>
        <div class="panel mb-3">
            <div class="module-header">
                <i class="fas <?= $icon ?>" style="color:var(--pri);"></i>
                <?= $label ?>
                <span class="ms-auto d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-success py-0 px-1" style="font-size:.65rem;" onclick="toggleModule('<?= $module ?>',true)">All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" style="font-size:.65rem;" onclick="toggleModule('<?= $module ?>',false)">None</button>
                </span>
            </div>
            <?php foreach ($perms as $p):
                $has = in_array((int)$p['id'], $role_perm_ids);
            ?>
            <div class="perm-grid" data-module="<?= $module ?>">
                <div>
                    <div style="font-size:.82rem;color:#374151;"><?= htmlspecialchars($p['name']) ?></div>
                    <code style="font-size:.68rem;color:#94a3b8;"><?= $p['slug'] ?></code>
                </div>
                <div>
                    <input type="checkbox" name="granted_perm[]" value="<?= $p['id'] ?>"
                           class="form-check-input perm-cb" data-module="<?= $module ?>"
                           style="width:18px;height:18px;accent-color:#0073e6;"
                           <?= $has ? 'checked' : '' ?>
                           <?= ($selected_role['is_system'] && !$_SESSION['is_owner']) ? 'disabled' : '' ?>>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <?php if (!$selected_role['is_system'] || $_SESSION['is_owner']): ?>
        <button type="submit" class="btn btn-primary fw-bold px-4">
            <i class="fas fa-save me-2"></i>Save Role Permissions
        </button>
        <?php endif; ?>
        </form>

        <?php else: ?>
        <div class="panel text-center text-muted py-4">Select a role from the left to manage its permissions.</div>
        <?php endif; ?>
    </div>
</div>

</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function setAll(val) {
    document.querySelectorAll('.perm-cb:not([disabled])').forEach(cb => cb.checked = val);
}
function toggleModule(m, val) {
    document.querySelectorAll(`.perm-cb[data-module="${m}"]:not([disabled])`).forEach(cb => cb.checked = val);
}
</script>
</body>
</html>
