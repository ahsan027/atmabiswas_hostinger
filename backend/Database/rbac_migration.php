<?php
/**
 * ATMABISWAS RBAC Migration
 * Run once: /backend/Database/rbac_migration.php
 * Creates all RBAC tables, seeds roles/permissions, designates owner.
 */
session_start();
require_once 'db.php';

// Only allow if already logged in OR if no admins exist yet
$pdo = (new Db())->connect();

try {
    $count = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($count > 0 && !isset($_SESSION['username'])) {
        http_response_code(403);
        die('<h2>Access Denied</h2><p>You must be logged in to run migrations.</p>');
    }
} catch (PDOException $e) {
    die('Migration cannot run: ' . htmlspecialchars($e->getMessage()));
}

$log   = [];
$error = null;

try {
    $pdo->beginTransaction();

    /* ── 1. Alter admins table ────────────────────────────────────── */
    $existing = $pdo->query(
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'admins'"
    )->fetchAll(PDO::FETCH_COLUMN);

    $add_cols = [
        'role_id'      => "ALTER TABLE admins ADD COLUMN role_id INT UNSIGNED NULL DEFAULT NULL",
        'is_owner'     => "ALTER TABLE admins ADD COLUMN is_owner TINYINT(1) NOT NULL DEFAULT 0",
        'is_protected' => "ALTER TABLE admins ADD COLUMN is_protected TINYINT(1) NOT NULL DEFAULT 0",
        'is_active'    => "ALTER TABLE admins ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1",
    ];
    foreach ($add_cols as $col => $ddl) {
        if (!in_array($col, $existing, true)) {
            $pdo->exec($ddl);
            $log[] = "✓ Added column admins.{$col}";
        } else {
            $log[] = "– Column admins.{$col} already exists";
        }
    }

    /* ── 2. Create roles table ────────────────────────────────────── */
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS roles (
            id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name        VARCHAR(100) NOT NULL,
            slug        VARCHAR(100) NOT NULL UNIQUE,
            role_level  TINYINT UNSIGNED NOT NULL DEFAULT 10,
            description VARCHAR(255) NULL,
            is_system   TINYINT(1) NOT NULL DEFAULT 0,
            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $log[] = "✓ Table roles ready";

    /* ── 3. Create permissions table ─────────────────────────────── */
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permissions (
            id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name        VARCHAR(100) NOT NULL,
            slug        VARCHAR(100) NOT NULL UNIQUE,
            module      VARCHAR(50)  NOT NULL,
            action      VARCHAR(50)  NOT NULL,
            description VARCHAR(255) NULL,
            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $log[] = "✓ Table permissions ready";

    /* ── 4. Create role_permissions table ────────────────────────── */
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS role_permissions (
            id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            role_id       INT UNSIGNED NOT NULL,
            permission_id INT UNSIGNED NOT NULL,
            UNIQUE KEY uq_rp (role_id, permission_id),
            FOREIGN KEY (role_id)       REFERENCES roles(id)       ON DELETE CASCADE,
            FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $log[] = "✓ Table role_permissions ready";

    /* ── 5. Create user_permissions table ────────────────────────── */
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_permissions (
            id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            admin_id      INT UNSIGNED NOT NULL,
            permission_id INT UNSIGNED NOT NULL,
            granted       TINYINT(1)   NOT NULL DEFAULT 1,
            granted_by    INT UNSIGNED NOT NULL DEFAULT 0,
            granted_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uq_up (admin_id, permission_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $log[] = "✓ Table user_permissions ready";

    /* ── 6. Create permission_audit_log table ────────────────────── */
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permission_audit_log (
            id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            changed_by      INT UNSIGNED NOT NULL DEFAULT 0,
            target_admin_id INT UNSIGNED NOT NULL,
            action          VARCHAR(100) NOT NULL,
            old_value       TEXT NULL,
            new_value       TEXT NULL,
            ip_address      VARCHAR(45)  NULL,
            created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_target (target_admin_id),
            INDEX idx_changed_by (changed_by),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    $log[] = "✓ Table permission_audit_log ready";

    /* ── 7. Seed roles ────────────────────────────────────────────── */
    $roles = [
        ['Head Office IT (Arafat)', 'head_office_it_arafat', 100, 'Ultimate IT authority — permanent owner', 1],
        ['Head Office IT',          'head_office_it',        90,  'Administrative IT authority', 1],
        ['Senior IT',               'senior_it',             70,  'Senior technical staff', 0],
        ['Junior IT',               'junior_it',             50,  'Junior technical staff', 0],
        ['HR',                      'hr',                    40,  'Human resources', 0],
        ['Accounts',                'accounts',              35,  'Finance & accounts', 0],
        ['Branch Manager',          'branch_manager',        30,  'Branch-level management', 0],
        ['Regional Office',         'regional_office',       25,  'Regional operational staff', 0],
        ['Data Entry',              'data_entry',            20,  'Data entry operators', 0],
        ['Viewer',                  'viewer',                10,  'Read-only access', 0],
    ];
    $ins_role = $pdo->prepare("
        INSERT INTO roles (name, slug, role_level, description, is_system)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE name=VALUES(name), role_level=VALUES(role_level), description=VALUES(description)
    ");
    foreach ($roles as $r) {
        $ins_role->execute($r);
    }
    $log[] = "✓ Seeded " . count($roles) . " roles";

    /* ── 8. Seed permissions ──────────────────────────────────────── */
    $permissions = [
        // Dashboard
        ['View Dashboard',       'dashboard.view',    'dashboard',  'view',   'Access the main dashboard'],
        // Users
        ['View Users',           'user.view',         'user',       'view',   'View admin user list'],
        ['Create Users',         'user.create',       'user',       'create', 'Create new admin users'],
        ['Edit Users',           'user.edit',         'user',       'edit',   'Edit admin user details'],
        ['Delete Users',         'user.delete',       'user',       'delete', 'Delete admin users'],
        // Roles & Permissions
        ['Manage Roles',         'role.manage',       'role',       'manage', 'Create and edit roles'],
        ['Manage Permissions',   'permission.manage', 'permission', 'manage', 'Assign permissions to users/roles'],
        // Press / News
        ['View Press',           'press.view',        'press',      'view',   'View press posts'],
        ['Create Press',         'press.create',      'press',      'create', 'Create press posts'],
        ['Edit Press',           'press.edit',        'press',      'edit',   'Edit press posts'],
        ['Delete Press',         'press.delete',      'press',      'delete', 'Delete press posts'],
        // Jobs
        ['View Jobs',            'job.view',          'job',        'view',   'View job listings'],
        ['Create Jobs',          'job.create',        'job',        'create', 'Create job postings'],
        ['Edit Jobs',            'job.edit',          'job',        'edit',   'Edit job postings'],
        ['Delete Jobs',          'job.delete',        'job',        'delete', 'Delete job postings'],
        // Branches / Offices
        ['View Branches',        'branch.view',       'branch',     'view',   'View branches and offices'],
        ['Edit Branches',        'branch.edit',       'branch',     'edit',   'Add/edit branches and offices'],
        // Gallery
        ['View Gallery',         'gallery.view',      'gallery',    'view',   'View image gallery'],
        ['Edit Gallery',         'gallery.edit',      'gallery',    'edit',   'Upload/manage gallery images'],
        // Notices
        ['View Notices',         'notice.view',       'notice',     'view',   'View uploaded notices/PDFs'],
        ['Edit Notices',         'notice.edit',       'notice',     'edit',   'Upload/manage notices'],
        // Settings
        ['View Settings',        'settings.view',     'settings',   'view',   'View system settings'],
        ['Edit Settings',        'settings.edit',     'settings',   'edit',   'Edit credentials and settings'],
    ];
    $ins_perm = $pdo->prepare("
        INSERT INTO permissions (name, slug, module, action, description)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE name=VALUES(name), module=VALUES(module), action=VALUES(action), description=VALUES(description)
    ");
    foreach ($permissions as $p) {
        $ins_perm->execute($p);
    }
    $log[] = "✓ Seeded " . count($permissions) . " permissions";

    /* ── 9. Assign permissions to roles ──────────────────────────── */
    // Get role IDs
    $role_ids = $pdo->query("SELECT slug, id FROM roles")->fetchAll(PDO::FETCH_KEY_PAIR);
    // Get permission IDs
    $perm_ids = $pdo->query("SELECT slug, id FROM permissions")->fetchAll(PDO::FETCH_KEY_PAIR);

    $ins_rp = $pdo->prepare("
        INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?, ?)
    ");

    $all_perms = array_values($perm_ids);

    // Head Office IT (Arafat) — all permissions
    if (isset($role_ids['head_office_it_arafat'])) {
        foreach ($all_perms as $pid) {
            $ins_rp->execute([$role_ids['head_office_it_arafat'], $pid]);
        }
        $log[] = "✓ Assigned ALL permissions to Head Office IT (Arafat)";
    }

    // Head Office IT — all permissions except user.delete of protected accounts (enforced in code)
    if (isset($role_ids['head_office_it'])) {
        foreach ($all_perms as $pid) {
            $ins_rp->execute([$role_ids['head_office_it'], $pid]);
        }
        $log[] = "✓ Assigned ALL permissions to Head Office IT";
    }

    // Senior IT — most perms, no user.delete, no role.manage
    $senior_it_perms = [
        'dashboard.view','user.view','user.create','user.edit',
        'permission.manage','press.view','press.create','press.edit','press.delete',
        'job.view','job.create','job.edit','job.delete',
        'branch.view','branch.edit','gallery.view','gallery.edit',
        'notice.view','notice.edit','settings.view','settings.edit',
    ];
    if (isset($role_ids['senior_it'])) {
        foreach ($senior_it_perms as $slug) {
            if (isset($perm_ids[$slug])) $ins_rp->execute([$role_ids['senior_it'], $perm_ids[$slug]]);
        }
        $log[] = "✓ Assigned permissions to Senior IT";
    }

    // Junior IT — limited
    $junior_it_perms = [
        'dashboard.view','press.view','press.create','press.edit',
        'job.view','job.create','job.edit','gallery.view','gallery.edit',
        'notice.view','notice.edit','branch.view',
    ];
    if (isset($role_ids['junior_it'])) {
        foreach ($junior_it_perms as $slug) {
            if (isset($perm_ids[$slug])) $ins_rp->execute([$role_ids['junior_it'], $perm_ids[$slug]]);
        }
        $log[] = "✓ Assigned permissions to Junior IT";
    }

    // HR — HR/job related
    $hr_perms = ['dashboard.view','user.view','job.view','job.create','job.edit','job.delete','branch.view'];
    if (isset($role_ids['hr'])) {
        foreach ($hr_perms as $slug) {
            if (isset($perm_ids[$slug])) $ins_rp->execute([$role_ids['hr'], $perm_ids[$slug]]);
        }
        $log[] = "✓ Assigned permissions to HR";
    }

    // Accounts — view only + settings
    $accounts_perms = ['dashboard.view','user.view','press.view','job.view','branch.view','settings.view'];
    if (isset($role_ids['accounts'])) {
        foreach ($accounts_perms as $slug) {
            if (isset($perm_ids[$slug])) $ins_rp->execute([$role_ids['accounts'], $perm_ids[$slug]]);
        }
        $log[] = "✓ Assigned permissions to Accounts";
    }

    // Branch Manager
    $bm_perms = ['dashboard.view','branch.view','branch.edit','press.view','job.view','notice.view'];
    if (isset($role_ids['branch_manager'])) {
        foreach ($bm_perms as $slug) {
            if (isset($perm_ids[$slug])) $ins_rp->execute([$role_ids['branch_manager'], $perm_ids[$slug]]);
        }
        $log[] = "✓ Assigned permissions to Branch Manager";
    }

    // Regional Office
    $ro_perms = ['dashboard.view','branch.view','press.view','notice.view'];
    if (isset($role_ids['regional_office'])) {
        foreach ($ro_perms as $slug) {
            if (isset($perm_ids[$slug])) $ins_rp->execute([$role_ids['regional_office'], $perm_ids[$slug]]);
        }
        $log[] = "✓ Assigned permissions to Regional Office";
    }

    // Data Entry
    $de_perms = ['dashboard.view','press.view','press.create','job.view','notice.view'];
    if (isset($role_ids['data_entry'])) {
        foreach ($de_perms as $slug) {
            if (isset($perm_ids[$slug])) $ins_rp->execute([$role_ids['data_entry'], $perm_ids[$slug]]);
        }
        $log[] = "✓ Assigned permissions to Data Entry";
    }

    // Viewer — read only
    $viewer_perms = ['dashboard.view','press.view','job.view','branch.view','notice.view'];
    if (isset($role_ids['viewer'])) {
        foreach ($viewer_perms as $slug) {
            if (isset($perm_ids[$slug])) $ins_rp->execute([$role_ids['viewer'], $perm_ids[$slug]]);
        }
        $log[] = "✓ Assigned permissions to Viewer";
    }

    /* ── 10. Designate owner ─────────────────────────────────────── */
    // Mark first admin as owner, OR the one explicitly specified by form POST
    $owner_id = null;
    if (!empty($_POST['owner_admin_id'])) {
        $owner_id = (int)$_POST['owner_admin_id'];
    } else {
        // Pick first admin
        $owner_id = $pdo->query("SELECT adminId FROM admins ORDER BY adminId ASC LIMIT 1")->fetchColumn();
    }

    if ($owner_id) {
        // Set owner role
        $owner_role_id = $role_ids['head_office_it_arafat'] ?? null;
        $pdo->prepare("
            UPDATE admins SET is_owner=1, is_protected=1, role_id=?, is_active=1 WHERE adminId=?
        ")->execute([$owner_role_id, $owner_id]);

        // Set all other admins to Head Office IT role (if they don't have a role yet)
        $ho_role_id = $role_ids['head_office_it'] ?? null;
        $pdo->prepare("
            UPDATE admins SET role_id=?, is_active=1
            WHERE role_id IS NULL AND adminId != ?
        ")->execute([$ho_role_id, $owner_id]);

        $owner = $pdo->prepare("SELECT fullname, email FROM admins WHERE adminId=?")->execute([$owner_id]);
        $owner = $pdo->prepare("SELECT fullname, email FROM admins WHERE adminId=?")->execute([$owner_id]);
        $owner_info = $pdo->prepare("SELECT fullname, email FROM admins WHERE adminId=?");
        $owner_info->execute([$owner_id]);
        $owner_info = $owner_info->fetch(PDO::FETCH_ASSOC);

        $log[] = "✓ Owner designated: [{$owner_id}] " . ($owner_info['fullname'] ?? '?') . " — role: Head Office IT (Arafat)";
    } else {
        $log[] = "⚠ No admin found — run again after creating an admin account";
    }

    $pdo->commit();
    $log[] = "<strong style='color:green'>✓ Migration completed successfully.</strong>";

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $error = $e->getMessage();
}

// Get all admins to show "select owner" form
$admins = [];
try {
    $admins = $pdo->query("SELECT adminId, fullname, email, is_owner FROM admins ORDER BY adminId")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>RBAC Migration — ATMABISWAS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background:#f5f9ff; font-family:system-ui,-apple-system,sans-serif; }
.card { border-radius:12px; box-shadow:0 2px 10px rgba(0,0,0,.08); }
pre { background:#1e3a5f; color:#7ed321; padding:1rem; border-radius:8px; font-size:.82rem; line-height:1.7; }
</style>
</head>
<body class="p-4">
<div class="container" style="max-width:760px;">
    <div class="card p-4 mb-4">
        <h3 class="fw-bold mb-1"><i class="fas fa-database text-primary me-2"></i>RBAC Migration</h3>
        <p class="text-muted small mb-0">ATMABISWAS Admin Role-Based Access Control Setup</p>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($log)): ?>
    <div class="card p-4 mb-4">
        <h5 class="fw-bold mb-3">Migration Log</h5>
        <pre><?php foreach ($log as $line) echo $line . "\n"; ?></pre>
    </div>
    <?php endif; ?>

    <?php if (!empty($admins)): ?>
    <div class="card p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-crown text-warning me-2"></i>Designate Owner</h5>
        <p class="text-muted small">Select which admin account should be the permanent <strong>Head Office IT (Arafat)</strong> owner. This cannot be undone without a database edit.</p>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Select Owner Admin</label>
                <select name="owner_admin_id" class="form-select">
                    <?php foreach ($admins as $a): ?>
                    <option value="<?= $a['adminId'] ?>" <?= $a['is_owner'] ? 'selected' : '' ?>>
                        [<?= $a['adminId'] ?>] <?= htmlspecialchars($a['fullname']) ?>
                        (<?= htmlspecialchars($a['email']) ?>)
                        <?= $a['is_owner'] ? '★ CURRENT OWNER' : '' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-warning fw-bold">
                <i class="fas fa-crown me-2"></i>Set as Owner & Re-run Migration
            </button>
        </form>
    </div>
    <?php endif; ?>

    <div class="card p-4 mb-4">
        <h5 class="fw-bold mb-3">Next Steps</h5>
        <ol class="mb-0 small">
            <li>Confirm the correct owner is marked above.</li>
            <li>Go to <a href="../DashBoard/access_control.php">Access Control</a> to manage permissions.</li>
            <li>Delete or restrict access to this migration file when done.</li>
            <li class="text-danger fw-semibold">Never leave this file publicly accessible in production.</li>
        </ol>
    </div>

    <div class="text-center">
        <a href="../DashBoard/dashboard.php" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>
</body>
</html>
