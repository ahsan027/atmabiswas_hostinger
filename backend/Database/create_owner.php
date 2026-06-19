<?php
/**
 * One-time setup: create the Head Office IT (Arafat) owner account.
 * DELETE THIS FILE after running it once.
 */

define('RUN_TOKEN', 'atmabiswas-create-owner-2024');
if (($_GET['token'] ?? '') !== RUN_TOKEN) {
    http_response_code(403);
    die('<h2 style="font-family:sans-serif;color:#dc2626">Access Denied.</h2><p>Append <code>?token=' . RUN_TOKEN . '</code> to the URL.</p>');
}

require_once __DIR__ . '/db.php';
$pdo = (new Db())->connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$log = [];

/* ── Step 1: Add RBAC columns to admins if missing ───────────────── */
$cols = $pdo->query("SHOW COLUMNS FROM admins")->fetchAll(PDO::FETCH_COLUMN);

$to_add = [
    'is_owner'     => "ALTER TABLE admins ADD COLUMN is_owner TINYINT(1) NOT NULL DEFAULT 0",
    'is_protected' => "ALTER TABLE admins ADD COLUMN is_protected TINYINT(1) NOT NULL DEFAULT 0",
    'is_active'    => "ALTER TABLE admins ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1",
    'role_id'      => "ALTER TABLE admins ADD COLUMN role_id INT(11) DEFAULT NULL",
];

foreach ($to_add as $col => $sql) {
    if (!in_array($col, $cols)) {
        $pdo->exec($sql);
        $log[] = "✅ Column <code>$col</code> added to admins table.";
    }
}

/* ── Step 2: Insert or update the owner account ──────────────────── */
$email    = 'arafathaquebiswas@gmail.com';
$fullname = 'Arafat Haque Biswas';
$password = 'Arafat@12';
$hash     = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("SELECT adminId FROM admins WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    $admin_id = (int)$existing['adminId'];
    $pdo->prepare("UPDATE admins SET fullname=?, pswd=?, is_owner=1, is_protected=1, is_active=1 WHERE adminId=?")
        ->execute([$fullname, $hash, $admin_id]);
    $log[] = "✅ Existing account (ID: $admin_id) updated — password reset, owner flag set.";
} else {
    $pdo->prepare("INSERT INTO admins (fullname, email, pswd, is_owner, is_protected, is_active) VALUES (?,?,?,1,1,1)")
        ->execute([$fullname, $email, $hash]);
    $admin_id = (int)$pdo->lastInsertId();
    $log[] = "✅ New owner account created (ID: $admin_id).";
}

/* ── Step 3: Assign Level-100 role if roles table exists ─────────── */
try {
    $role = $pdo->query("SELECT id FROM roles WHERE slug='head_office_it_arafat' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    if ($role) {
        $pdo->prepare("UPDATE admins SET role_id=? WHERE adminId=?")->execute([$role['id'], $admin_id]);
        $log[] = "✅ Role 'Head Office IT (Arafat)' (Level 100) assigned.";
    } else {
        $log[] = "ℹ️ Roles table exists but 'head_office_it_arafat' role not seeded yet — run rbac_migration.php after logging in to seed roles.";
    }
} catch (PDOException $e) {
    $log[] = "ℹ️ Roles table not created yet — run rbac_migration.php after logging in to complete RBAC setup.";
}

/* ── Step 4: Verify the password works ───────────────────────────── */
$verify = $pdo->prepare("SELECT pswd FROM admins WHERE adminId=?");
$verify->execute([$admin_id]);
$saved = $verify->fetchColumn();
$verified = password_verify($password, $saved);
$log[] = $verified ? "✅ Password verified — login will work." : "❌ Password verification failed — something went wrong.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Owner Account Setup</title>
<style>
  body{font-family:system-ui,sans-serif;max-width:620px;margin:3rem auto;padding:1rem;}
  .box{background:#f0fdf4;border:1.5px solid #86efac;border-radius:10px;padding:1.5rem;}
  .box.warn{background:#fffbeb;border-color:#fcd34d;}
  h2{color:#166534;margin-top:0;}
  code{background:#e2e8f0;padding:.1rem .35rem;border-radius:4px;font-size:.9rem;}
  .cred{background:#1e3a5f;color:#fff;border-radius:8px;padding:1rem 1.5rem;margin:1rem 0;}
  .cred p{margin:.3rem 0;font-size:.9rem;}
  .delete-warn{background:#fef2f2;border:1.5px solid #fca5a5;border-radius:8px;padding:1rem;margin-top:1.5rem;color:#991b1b;}
  p.log{margin:.4rem 0;font-size:.9rem;}
</style>
</head>
<body>
<div class="box">
  <h2>Owner Account Setup</h2>
  <?php foreach ($log as $line): ?>
  <p class="log"><?= $line ?></p>
  <?php endforeach; ?>

  <div class="cred">
    <p><strong>Login Email:</strong> arafathaquebiswas@gmail.com</p>
    <p><strong>Password:</strong> Arafat@12</p>
    <p><strong>Admin ID:</strong> <?= $admin_id ?></p>
  </div>

  <p>→ <a href="../login/loging.php">Go to Login page</a> and sign in with the credentials above.</p>
</div>

<div class="delete-warn">
  ⚠️ <strong>Delete this file immediately after logging in:</strong><br>
  <code>backend/Database/create_owner.php</code>
</div>
</body>
</html>
