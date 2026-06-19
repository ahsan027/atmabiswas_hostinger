<?php
/**
 * Suspension System Migration
 * Run once — safe to re-run.
 * Adds suspension columns to admins and registers user.suspend permission.
 */
require_once __DIR__ . '/db.php';
$pdo = (new Db())->connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$log = [];

/* ── 1. Add suspension columns to admins ─────────────────────────── */
$existing_cols = $pdo->query("SHOW COLUMNS FROM admins")->fetchAll(PDO::FETCH_COLUMN);

$sus_cols = [
    'is_suspended'       => "ALTER TABLE admins ADD COLUMN is_suspended TINYINT(1) NOT NULL DEFAULT 0 AFTER is_active",
    'suspended_at'       => "ALTER TABLE admins ADD COLUMN suspended_at TIMESTAMP NULL DEFAULT NULL AFTER is_suspended",
    'suspended_by'       => "ALTER TABLE admins ADD COLUMN suspended_by INT(11) DEFAULT NULL AFTER suspended_at",
    'suspension_reason'  => "ALTER TABLE admins ADD COLUMN suspension_reason TEXT DEFAULT NULL AFTER suspended_by",
];

foreach ($sus_cols as $col => $sql) {
    if (!in_array($col, $existing_cols)) {
        $pdo->exec($sql);
        $log[] = "✅ Column <code>$col</code> added to admins.";
    } else {
        $log[] = "— Column <code>$col</code> already exists.";
    }
}

/* ── 2. Register user.suspend permission ─────────────────────────── */
try {
    $pdo->prepare("
        INSERT INTO permissions (slug, name, module, action)
        VALUES ('user.suspend', 'Suspend / Activate Users', 'user', 'suspend')
        ON DUPLICATE KEY UPDATE name=VALUES(name)
    ")->execute();
    $log[] = "✅ Permission <code>user.suspend</code> registered.";
} catch (PDOException $e) {
    $log[] = "⚠️ Could not register permission (permissions table may not exist — run rbac_migration.php first): " . $e->getMessage();
}

/* ── 3. Grant user.suspend to Head IT (Arafat) and Head IT roles ─── */
try {
    $perm = $pdo->query("SELECT id FROM permissions WHERE slug='user.suspend' LIMIT 1")->fetchColumn();
    if ($perm) {
        $slugs = ['head_office_it_arafat', 'head_office_it'];
        foreach ($slugs as $slug) {
            $role = $pdo->prepare("SELECT id FROM roles WHERE slug=? LIMIT 1");
            $role->execute([$slug]);
            $rid = $role->fetchColumn();
            if ($rid) {
                $pdo->prepare("INSERT IGNORE INTO role_permissions (role_id, permission_id) VALUES (?,?)")
                    ->execute([$rid, $perm]);
                $log[] = "✅ <code>user.suspend</code> granted to role <strong>$slug</strong>.";
            } else {
                $log[] = "⚠️ Role <code>$slug</code> not found — run rbac_migration.php first.";
            }
        }
    }
} catch (PDOException $e) {
    $log[] = "⚠️ Could not grant permission to roles: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Suspension Migration</title>
<style>
  body{font-family:system-ui,sans-serif;max-width:640px;margin:3rem auto;padding:1rem;}
  .box{background:#f0fdf4;border:1.5px solid #86efac;border-radius:10px;padding:1.5rem;}
  h2{color:#166534;margin-top:0;}
  code{background:#e2e8f0;padding:.1rem .35rem;border-radius:4px;}
  p.log{margin:.35rem 0;font-size:.88rem;}
</style>
</head>
<body>
<div class="box">
  <h2>Suspension Migration Complete</h2>
  <?php foreach ($log as $l): ?><p class="log"><?= $l ?></p><?php endforeach; ?>
  <hr>
  <p><a href="../DashBoard/manageAdmins.php">→ Go to Manage Admins</a></p>
</div>
</body>
</html>
