<?php
/**
 * One-time owner account setup.
 * Uses the ADMINS table (NOT users table — this system has no users table).
 * Column mapping: fullname, email, pswd, is_owner, is_protected, is_active, role_id
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

/* ── Account credentials ─────────────────────────────────────────── */
$fullname = 'Head IT (Arafat)';
$email    = 'arafathaquebiswas@gmail.com';
$password = 'Arafat@123';                              // plain text — hashed below
$hash     = password_hash($password, PASSWORD_BCRYPT); // stored in DB

/* ── Step 1: Add RBAC columns to admins if missing ──────────────── */
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
        $log[] = "✅ Column <code>$col</code> added to admins.";
    } else {
        $log[] = "— Column <code>$col</code> already exists.";
    }
}

/* ── Step 2: Insert or update the owner account ──────────────────── */
$stmt = $pdo->prepare("SELECT adminId FROM admins WHERE LOWER(email) = ? LIMIT 1");
$stmt->execute([strtolower($email)]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    $admin_id = (int)$existing['adminId'];
    $pdo->prepare("
        UPDATE admins
        SET fullname=?, pswd=?, is_owner=1, is_protected=1, is_active=1
        WHERE adminId=?
    ")->execute([$fullname, $hash, $admin_id]);
    $log[] = "✅ Existing account (ID: $admin_id) updated — name, password, and owner flags set.";
} else {
    $pdo->prepare("
        INSERT INTO admins (fullname, email, pswd, is_owner, is_protected, is_active)
        VALUES (?, ?, ?, 1, 1, 1)
    ")->execute([$fullname, $email, $hash]);
    $admin_id = (int)$pdo->lastInsertId();
    $log[] = "✅ New owner account created (ID: $admin_id).";
}

/* ── Step 3: Assign Head IT (Arafat) role — level 100 ───────────── */
$role = $pdo->query("SELECT id, name, role_level FROM roles WHERE slug='head_office_it_arafat' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if ($role) {
    $pdo->prepare("UPDATE admins SET role_id=? WHERE adminId=?")->execute([$role['id'], $admin_id]);
    $log[] = "✅ Role '<strong>" . htmlspecialchars($role['name']) . "</strong>' (Level {$role['role_level']}) assigned.";
} else {
    $log[] = "⚠️ Role 'head_office_it_arafat' not found in roles table. Run <code>rbac_migration.php</code> first, then re-run this script.";
}

/* ── Step 4: Verify password hash is correct ─────────────────────── */
$saved = $pdo->prepare("SELECT pswd FROM admins WHERE adminId=?");
$saved->execute([$admin_id]);
$saved_hash = $saved->fetchColumn();
$verified   = password_verify($password, $saved_hash);
$log[] = $verified
    ? "✅ <strong>Password verified</strong> — <code>password_verify('Arafat@123', stored_hash)</code> = TRUE. Login will work."
    : "❌ <strong>Password verification FAILED</strong> — hash in DB does not match. Something went wrong.";

/* ── Step 5: Show what is actually stored ────────────────────────── */
$row = $pdo->prepare("SELECT adminId, fullname, email, is_owner, is_protected, is_active, role_id FROM admins WHERE adminId=?");
$row->execute([$admin_id]);
$stored = $row->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Owner Account Setup</title>
<style>
body{font-family:system-ui,sans-serif;max-width:660px;margin:3rem auto;padding:1rem;}
.box{background:#f0fdf4;border:1.5px solid #86efac;border-radius:10px;padding:1.5rem;}
.box.fail{background:#fef2f2;border-color:#fca5a5;}
h2{margin-top:0;}
p.log{margin:.4rem 0;font-size:.88rem;}
.cred{background:#1e3a5f;color:#fff;border-radius:8px;padding:1rem 1.5rem;margin:1rem 0;font-size:.9rem;}
.cred p{margin:.3rem 0;}
.cred .label{opacity:.65;font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;}
table{width:100%;border-collapse:collapse;margin-top:1rem;font-size:.82rem;}
th,td{padding:.4rem .75rem;border:1px solid #e2e8f0;text-align:left;}
th{background:#f1f5f9;font-weight:700;}
.del{background:#fef2f2;border:1.5px solid #fca5a5;border-radius:8px;padding:.85rem 1rem;margin-top:1.5rem;color:#991b1b;font-size:.85rem;}
code{background:#e2e8f0;padding:.1rem .35rem;border-radius:3px;}
</style>
</head>
<body>
<div class="box <?= $verified ? '' : 'fail' ?>">
  <h2><?= $verified ? '✅ Owner Account Ready' : '❌ Setup Failed' ?></h2>

  <?php foreach ($log as $l): ?><p class="log"><?= $l ?></p><?php endforeach; ?>

  <?php if ($verified): ?>
  <div class="cred">
    <p><span class="label">Full Name</span><br><strong>Head IT (Arafat)</strong></p>
    <p><span class="label">Email</span><br><strong>arafathaquebiswas@gmail.com</strong></p>
    <p><span class="label">Password</span><br><strong>Arafat@123</strong></p>
    <p><span class="label">Role</span><br><strong>Head IT (Arafat) — Level 100</strong></p>
    <p><span class="label">Admin ID</span><br><strong><?= $admin_id ?></strong></p>
  </div>

  <p>→ <a href="../login/loging.php"><strong>Go to Login</strong></a></p>

  <h4 style="margin-bottom:.5rem;">What is stored in DB:</h4>
  <table>
    <?php foreach ($stored as $col => $val): ?>
    <tr><th><?= $col ?></th><td><?= $col === 'pswd' ? '<em style="color:#94a3b8">hidden</em>' : htmlspecialchars((string)$val) ?></td></tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>
</div>

<div class="del">
  ⚠️ <strong>DELETE this file immediately after logging in:</strong><br>
  <code>backend/Database/create_owner.php</code>
</div>
</body>
</html>
