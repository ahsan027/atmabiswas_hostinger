<?php
/**
 * One-time setup: create the Head Office IT (Arafat) owner account.
 * DELETE THIS FILE after running it once.
 */

/* ── Simple run-once token guard ─────────────────────────────────── */
define('RUN_TOKEN', 'atmabiswas-create-owner-2024');
if (($_GET['token'] ?? '') !== RUN_TOKEN) {
    http_response_code(403);
    die('<h2 style="font-family:sans-serif;color:#dc2626">Access Denied.</h2><p>Append <code>?token=' . RUN_TOKEN . '</code> to the URL to run this script.</p>');
}

require_once __DIR__ . '/db.php';
$pdo = (new Db())->connect();

$email    = 'arafathaquebiswas@gmail.com';
$fullname = 'Arafat Haque Biswas';
$password = 'Arafat@12';
$hash     = password_hash($password, PASSWORD_DEFAULT);

$log = [];

/* ── Find or create the admin account ───────────────────────────── */
$stmt = $pdo->prepare("SELECT adminId, fullname, email FROM admins WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    // Update password and mark as owner
    $pdo->prepare("UPDATE admins SET pswd=?, fullname=?, is_owner=1, is_protected=1, is_active=1 WHERE email=?")
        ->execute([$hash, $fullname, $email]);
    $admin_id = (int)$existing['adminId'];
    $log[] = "✅ Found existing account (ID: $admin_id) — password updated, owner flag set.";
} else {
    // Insert brand-new account
    $pdo->prepare("INSERT INTO admins (fullname, email, pswd, is_owner, is_protected, is_active) VALUES (?,?,?,1,1,1)")
        ->execute([$fullname, $email, $hash]);
    $admin_id = (int)$pdo->lastInsertId();
    $log[] = "✅ New admin account created (ID: $admin_id).";
}

/* ── Assign Head Office IT (Arafat) role (level 100) ────────────── */
$role_stmt = $pdo->prepare("SELECT id FROM roles WHERE slug = 'head_office_it_arafat' LIMIT 1");
$role_stmt->execute();
$role = $role_stmt->fetch(PDO::FETCH_ASSOC);

if ($role) {
    $pdo->prepare("UPDATE admins SET role_id=? WHERE adminId=?")->execute([$role['id'], $admin_id]);
    $log[] = "✅ Role 'Head Office IT (Arafat)' (Level 100) assigned.";
} else {
    $log[] = "⚠️ Role 'head_office_it_arafat' not found — run rbac_migration.php first, then re-run this script.";
}

/* ── Summary ─────────────────────────────────────────────────────── */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Owner Account Setup</title>
<style>
  body { font-family:system-ui,sans-serif; max-width:600px; margin:3rem auto; padding:1rem; }
  .box { background:#f0fdf4; border:1.5px solid #86efac; border-radius:10px; padding:1.5rem; }
  .box.warn { background:#fffbeb; border-color:#fcd34d; }
  h2 { color:#166534; margin-top:0; }
  code { background:#e2e8f0; padding:.1rem .35rem; border-radius:4px; font-size:.9rem; }
  .cred { background:#1e3a5f; color:#fff; border-radius:8px; padding:1rem 1.5rem; margin:1rem 0; }
  .cred p { margin:.3rem 0; font-size:.9rem; }
  .delete-warn { background:#fef2f2; border:1.5px solid #fca5a5; border-radius:8px; padding:1rem; margin-top:1.5rem; color:#991b1b; }
</style>
</head>
<body>
<div class="box <?= strpos(implode(' ', $log), '⚠️') !== false ? 'warn' : '' ?>">
  <h2>Owner Account Setup</h2>
  <?php foreach ($log as $line): ?>
  <p><?= $line ?></p>
  <?php endforeach; ?>

  <div class="cred">
    <p><strong>Login Email:</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Password:</strong> Arafat@12</p>
    <p><strong>Role:</strong> Head Office IT (Arafat) — Level 100</p>
    <p><strong>Admin ID:</strong> <?= $admin_id ?></p>
  </div>

  <p>→ <a href="../DashBoard/dashboard.php">Go to Dashboard</a> and log in with these credentials.</p>
</div>

<div class="delete-warn">
  ⚠️ <strong>Security:</strong> Delete <code>backend/Database/create_owner.php</code> immediately after logging in successfully.
</div>
</body>
</html>
