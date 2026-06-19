<?php
session_start();
require_once '../Database/db.php';
require_once 'auth.php';

requireLogin();
authorize('permission.manage');

$pdo = (new Db())->connect();

$target_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$target_id) {
    header('Location: access_control.php');
    exit();
}

/* ── Fetch target admin ──────────────────────────────────────────── */
$stmt = $pdo->prepare("
    SELECT a.*, r.name AS role_name, r.role_level, r.id AS role_id_val
    FROM admins a
    LEFT JOIN roles r ON a.role_id = r.id
    WHERE a.adminId = ?
");
$stmt->execute([$target_id]);
$target = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$target) {
    header('Location: access_control.php');
    exit();
}

/* ── Hierarchy + protection guard ────────────────────────────────── */
if ($target['is_protected'] || $target['is_owner']) {
    $reason = "This is a protected account and cannot be modified.";
    http_response_code(403);
    include '403.php';
    exit();
}
if (!canManageUser($target_id)) {
    $reason = "You do not have authority to manage this user (insufficient role level).";
    http_response_code(403);
    include '403.php';
    exit();
}

$msg   = '';
$msgT  = '';

/* ── Handle POST: save permissions ──────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['_action'] ?? 'save';

    if ($action === 'reset') {
        // Remove all user overrides → back to role defaults
        $old_count = $pdo->prepare("SELECT COUNT(*) FROM user_permissions WHERE admin_id=?")->execute([$target_id]);
        $pdo->prepare("DELETE FROM user_permissions WHERE admin_id=?")->execute([$target_id]);
        logPermissionChange($target_id, 'reset_to_role_defaults', 'had user overrides', 'cleared all overrides');
        $msg  = 'User permissions reset to role defaults.';
        $msgT = 'success';

    } elseif ($action === 'clone') {
        // Clone from another user
        $clone_from = filter_input(INPUT_POST, 'clone_from', FILTER_VALIDATE_INT);
        if ($clone_from && $clone_from !== $target_id) {
            $from_user = $pdo->prepare("SELECT fullname FROM admins WHERE adminId=?")->execute([$clone_from]);
            $from_user = $pdo->prepare("SELECT fullname FROM admins WHERE adminId=?");
            $from_user->execute([$clone_from]);
            $from_info = $from_user->fetch(PDO::FETCH_ASSOC);

            // Copy overrides
            $pdo->prepare("DELETE FROM user_permissions WHERE admin_id=?")->execute([$target_id]);
            $src = $pdo->prepare("SELECT permission_id, granted FROM user_permissions WHERE admin_id=?");
            $src->execute([$clone_from]);
            $ins = $pdo->prepare("INSERT INTO user_permissions (admin_id, permission_id, granted, granted_by) VALUES (?,?,?,?)");
            $count = 0;
            while ($row = $src->fetch(PDO::FETCH_ASSOC)) {
                $ins->execute([$target_id, $row['permission_id'], $row['granted'], $_SESSION['admin_id']]);
                $count++;
            }
            logPermissionChange($target_id, 'clone_permissions', null, "cloned from admin #{$clone_from} ({$from_info['fullname']}) — {$count} overrides");
            $msg  = "Permissions cloned from {$from_info['fullname']} ({$count} overrides copied).";
            $msgT = 'success';
        }

    } else {
        // Save individual checkboxes
        // Submitted: granted[] = permission slugs that are checked
        // Denied:    denied[]  = permission slugs explicitly denied
        $all_perms = $pdo->query("SELECT id, slug FROM permissions")->fetchAll(PDO::FETCH_ASSOC);
        $granted_slugs = (array)($_POST['granted'] ?? []);
        $denied_slugs  = (array)($_POST['denied']  ?? []);

        $old_ups = $pdo->prepare("
            SELECT p.slug, up.granted
            FROM user_permissions up JOIN permissions p ON up.permission_id = p.id
            WHERE up.admin_id = ?
        ");
        $old_ups->execute([$target_id]);
        $old_state = [];
        while ($row = $old_ups->fetch(PDO::FETCH_ASSOC)) {
            $old_state[$row['slug']] = $row['granted'];
        }

        // Remove all existing overrides and rebuild
        $pdo->prepare("DELETE FROM user_permissions WHERE admin_id=?")->execute([$target_id]);

        $ins = $pdo->prepare("
            INSERT INTO user_permissions (admin_id, permission_id, granted, granted_by)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE granted=VALUES(granted), granted_by=VALUES(granted_by)
        ");

        $changes = [];
        foreach ($all_perms as $perm) {
            if (in_array($perm['slug'], $granted_slugs, true)) {
                $ins->execute([$target_id, $perm['id'], 1, $_SESSION['admin_id']]);
                if (!isset($old_state[$perm['slug']]) || $old_state[$perm['slug']] != 1) {
                    $changes[] = "+{$perm['slug']}";
                }
            } elseif (in_array($perm['slug'], $denied_slugs, true)) {
                $ins->execute([$target_id, $perm['id'], 0, $_SESSION['admin_id']]);
                if (!isset($old_state[$perm['slug']]) || $old_state[$perm['slug']] != 0) {
                    $changes[] = "-{$perm['slug']}";
                }
            }
            // If neither checked → remove override (inherit from role) — already deleted above
        }

        if ($changes) {
            logPermissionChange($target_id, 'update_user_permissions',
                implode(',', array_keys($old_state)),
                implode(',', $changes)
            );
        }

        $msg  = 'Permissions saved successfully.';
        $msgT = 'success';
    }

    header("Location: user_permissions.php?id={$target_id}&msg=" . urlencode($msg) . "&msgt={$msgT}");
    exit();
}

if (!empty($_GET['msg'])) {
    $msg  = $_GET['msg'];
    $msgT = $_GET['msgt'] ?? 'success';
}

/* ── Load permission state ───────────────────────────────────────── */
// All permissions
$all_perms = $pdo->query("SELECT * FROM permissions ORDER BY module, action")->fetchAll(PDO::FETCH_ASSOC);

// Role permissions (what the user gets from their role)
$role_perm_ids = [];
if ($target['role_id_val']) {
    $rp = $pdo->prepare("SELECT permission_id FROM role_permissions WHERE role_id=?");
    $rp->execute([$target['role_id_val']]);
    $role_perm_ids = $rp->fetchAll(PDO::FETCH_COLUMN);
}

// User overrides
$up = $pdo->prepare("SELECT permission_id, granted FROM user_permissions WHERE admin_id=?");
$up->execute([$target_id]);
$user_overrides = $up->fetchAll(PDO::FETCH_KEY_PAIR); // permission_id => granted

// Other admins (for clone feature)
$other_admins = $pdo->query("
    SELECT adminId, fullname FROM admins
    WHERE adminId != {$target_id} AND is_protected = 0 AND is_active = 1
    ORDER BY fullname
")->fetchAll(PDO::FETCH_ASSOC);

// Group perms by module
$modules = [];
foreach ($all_perms as $p) {
    $modules[$p['module']][] = $p;
}

$module_labels = [
    'dashboard'  => ['Dashboard',        'fa-chart-line'],
    'user'       => ['User Management',  'fa-users'],
    'role'       => ['Role Management',  'fa-layer-group'],
    'permission' => ['Permissions',      'fa-key'],
    'press'      => ['Press / News',     'fa-newspaper'],
    'job'        => ['Job Postings',     'fa-briefcase'],
    'branch'     => ['Branches',         'fa-code-branch'],
    'gallery'    => ['Gallery',          'fa-images'],
    'notice'     => ['Notices',          'fa-bell'],
    'settings'   => ['Settings',         'fa-cog'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>User Permissions — <?= htmlspecialchars($target['fullname']) ?> | ATMABISWAS</title>
<link rel="icon" type="image/png" href="../images/logo/logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/admin-sidebar.css">
<style>
:root { --pri:#0073e6; --dark:#1e3a5f; --success:#16a34a; --danger:#dc2626; }
body { background:#f5f9ff; font-family:system-ui,-apple-system,'Segoe UI',sans-serif; }
.am-header { background:linear-gradient(135deg,var(--dark),var(--pri)); color:#fff; padding:1.25rem 0; margin-bottom:1.5rem; }
.am-header h1 { font-size:1.25rem; font-weight:800; margin:0; }
.panel { background:#fff; border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.07); padding:1.25rem; margin-bottom:1.25rem; }
.panel-title { font-size:.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#94a3b8; margin-bottom:.75rem; }
.module-header { display:flex; align-items:center; gap:.5rem; font-size:.82rem; font-weight:800; color:#1e3a5f; margin-bottom:.75rem; padding-bottom:.5rem; border-bottom:1.5px solid #e2e8f0; }
.perm-row { display:grid; grid-template-columns:1fr repeat(3,80px); align-items:center; padding:.35rem 0; gap:.5rem; font-size:.82rem; border-bottom:1px solid #f1f5f9; }
.perm-row:last-child { border-bottom:none; }
.perm-name { color:#374151; }
.perm-name small { color:#94a3b8; display:block; font-size:.68rem; }
.cb-grant:checked  { accent-color:var(--success); }
.cb-deny:checked   { accent-color:var(--danger); }
.inherited-dot { display:inline-block; width:8px; height:8px; border-radius:50%; background:#94a3b8; margin-right:.25rem; }
.inherited-dot.yes { background:#16a34a; }
.col-hdr { font-size:.68rem; font-weight:700; text-align:center; color:#64748b; text-transform:uppercase; }
legend-row { display:flex; gap:1rem; font-size:.75rem; align-items:center; }
.legend-item { display:flex; align-items:center; gap:.3rem; }
.legend-dot { width:10px; height:10px; border-radius:50%; }
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
                <h1><i class="fas fa-user-shield me-2"></i>User Permissions</h1>
                <div class="small opacity-75"><?= htmlspecialchars($target['fullname']) ?> · <?= htmlspecialchars($target['role_name'] ?? 'No Role') ?></div>
            </div>
            <a href="access_control.php" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-5">

<?php if ($msg): ?>
<div class="alert alert-<?= $msgT === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show">
    <i class="fas fa-<?= $msgT === 'success' ? 'check-circle' : 'exclamation-circle' ?> me-2"></i><?= htmlspecialchars($msg) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- User card -->
<div class="panel d-flex align-items-center gap-3 flex-wrap">
    <div style="width:48px;height:48px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-weight:800;color:#1d4ed8;font-size:1.1rem;">
        <?= strtoupper(substr($target['fullname'],0,1)) ?>
    </div>
    <div class="flex-1">
        <div class="fw-bold" style="color:#1e3a5f;"><?= htmlspecialchars($target['fullname']) ?></div>
        <div class="small text-muted"><?= htmlspecialchars($target['email']) ?> · <?= htmlspecialchars($target['role_name'] ?? 'No Role') ?> (Level <?= (int)$target['role_level'] ?>)</div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <!-- Clone form -->
        <form method="POST" class="d-flex gap-2 align-items-center">
            <input type="hidden" name="_action" value="clone">
            <select name="clone_from" class="form-select form-select-sm" style="width:180px;">
                <option value="">— Clone from user —</option>
                <?php foreach ($other_admins as $oa): ?>
                <option value="<?= $oa['adminId'] ?>"><?= htmlspecialchars($oa['fullname']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary fw-bold">
                <i class="fas fa-copy me-1"></i>Clone
            </button>
        </form>
        <!-- Reset form -->
        <form method="POST" onsubmit="return confirm('Reset all user overrides to role defaults?');">
            <input type="hidden" name="_action" value="reset">
            <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">
                <i class="fas fa-undo me-1"></i>Reset to Role
            </button>
        </form>
    </div>
</div>

<!-- Legend -->
<div class="d-flex gap-3 mb-3 flex-wrap" style="font-size:.75rem;">
    <div class="legend-item"><div class="legend-dot" style="background:#16a34a;border-radius:50%;width:10px;height:10px;"></div><span>Green = User granted (overrides role)</span></div>
    <div class="legend-item"><div class="legend-dot" style="background:#dc2626;border-radius:50%;width:10px;height:10px;"></div><span>Red = User denied (overrides role)</span></div>
    <div class="legend-item"><div class="legend-dot" style="background:#94a3b8;border-radius:50%;width:10px;height:10px;"></div><span>Gray = Inherited from role (no override)</span></div>
</div>

<!-- Select All / Deselect All -->
<div class="d-flex gap-2 mb-3">
    <button type="button" class="btn btn-sm btn-outline-success fw-bold" onclick="selectAll(true)">
        <i class="fas fa-check-double me-1"></i>Grant All
    </button>
    <button type="button" class="btn btn-sm btn-outline-secondary fw-bold" onclick="selectAll(false)">
        <i class="fas fa-times-circle me-1"></i>Clear All Overrides
    </button>
</div>

<form method="POST" id="permForm">
    <input type="hidden" name="_action" value="save">

    <!-- Column header -->
    <div class="perm-row mb-1" style="border-bottom:2px solid #e2e8f0;padding-bottom:.5rem;">
        <div class="col-hdr text-start">Permission</div>
        <div class="col-hdr">Role Has</div>
        <div class="col-hdr text-success">Grant</div>
        <div class="col-hdr text-danger">Deny</div>
    </div>

    <?php foreach ($modules as $module => $perms):
        [$label, $icon] = $module_labels[$module] ?? [ucfirst($module), 'fa-circle'];
    ?>
    <div class="panel mb-3">
        <div class="module-header">
            <i class="fas <?= $icon ?>" style="color:var(--pri);"></i>
            <?= $label ?>
            <span class="ms-auto">
                <button type="button" class="btn btn-sm btn-outline-success py-0 px-1" style="font-size:.65rem;" onclick="grantModule('<?= $module ?>')">Grant All</button>
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" style="font-size:.65rem;" onclick="denyModule('<?= $module ?>')">Deny All</button>
                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" style="font-size:.65rem;" onclick="inheritModule('<?= $module ?>')">Inherit</button>
            </span>
        </div>

        <?php foreach ($perms as $p):
            $role_has  = in_array($p['id'], $role_perm_ids, false); // cast-safe
            $role_has  = in_array((int)$p['id'], array_map('intval', $role_perm_ids));
            $override  = isset($user_overrides[$p['id']]) ? (int)$user_overrides[$p['id']] : null;
            $is_granted = $override === 1;
            $is_denied  = $override === 0;
        ?>
        <div class="perm-row" data-module="<?= $module ?>">
            <div class="perm-name">
                <?= htmlspecialchars($p['name']) ?>
                <small><code><?= $p['slug'] ?></code></small>
            </div>
            <div class="text-center">
                <?php if ($role_has): ?>
                <span title="Role includes this permission" style="color:#16a34a;"><i class="fas fa-check-circle"></i></span>
                <?php else: ?>
                <span title="Role does not include this permission" style="color:#e2e8f0;"><i class="fas fa-times-circle"></i></span>
                <?php endif; ?>
            </div>
            <div class="text-center">
                <input type="checkbox" name="granted[]" value="<?= $p['slug'] ?>"
                       class="cb-grant form-check-input" data-module="<?= $module ?>"
                       <?= $is_granted ? 'checked' : '' ?>
                       onchange="if(this.checked) document.getElementById('deny_<?= $p['id'] ?>').checked=false;">
            </div>
            <div class="text-center">
                <input type="checkbox" name="denied[]" value="<?= $p['slug'] ?>"
                       class="cb-deny form-check-input" id="deny_<?= $p['id'] ?>" data-module="<?= $module ?>"
                       <?= $is_denied ? 'checked' : '' ?>
                       onchange="if(this.checked) document.querySelector('input[name=\'granted[]\'][value=\'<?= $p['slug'] ?>\']').checked=false;">
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>

    <div class="d-flex gap-2 mt-3 pb-4">
        <button type="submit" class="btn btn-primary fw-bold px-4">
            <i class="fas fa-save me-2"></i>Save Permissions
        </button>
        <a href="access_control.php" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function selectAll(grant) {
    document.querySelectorAll('.cb-grant').forEach(cb => { cb.checked = grant; });
    document.querySelectorAll('.cb-deny').forEach(cb => { cb.checked = false; });
}
function grantModule(m) {
    document.querySelectorAll(`.cb-grant[data-module="${m}"]`).forEach(cb => { cb.checked = true; });
    document.querySelectorAll(`.cb-deny[data-module="${m}"]`).forEach(cb => { cb.checked = false; });
}
function denyModule(m) {
    document.querySelectorAll(`.cb-deny[data-module="${m}"]`).forEach(cb => { cb.checked = true; });
    document.querySelectorAll(`.cb-grant[data-module="${m}"]`).forEach(cb => { cb.checked = false; });
}
function inheritModule(m) {
    document.querySelectorAll(`.cb-grant[data-module="${m}"],.cb-deny[data-module="${m}"]`).forEach(cb => { cb.checked = false; });
}
</script>
</body>
</html>
