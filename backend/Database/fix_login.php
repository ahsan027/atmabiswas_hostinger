<?php
/**
 * Emergency login fix — sets password for the owner account.
 * DELETE THIS FILE immediately after running.
 */
if (($_GET['go'] ?? '') !== 'fix') {
    die('Append <b>?go=fix</b> to run this script.');
}

require_once __DIR__ . '/db.php';
$pdo = (new Db())->connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$email    = 'arafathaquebiswas@gmail.com';
$fullname = 'Head IT (Arafat)';
$password = 'Arafat@123';
$hash     = password_hash($password, PASSWORD_BCRYPT);

/* ─── Ensure required columns exist ────────────────────────────── */
$cols = $pdo->query("SHOW COLUMNS FROM admins")->fetchAll(PDO::FETCH_COLUMN);
foreach ([
    'is_owner'    => "ALTER TABLE admins ADD COLUMN is_owner TINYINT(1) NOT NULL DEFAULT 0",
    'is_protected'=> "ALTER TABLE admins ADD COLUMN is_protected TINYINT(1) NOT NULL DEFAULT 0",
    'is_active'   => "ALTER TABLE admins ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1",
    'is_suspended'=> "ALTER TABLE admins ADD COLUMN is_suspended TINYINT(1) NOT NULL DEFAULT 0",
    'role_id'     => "ALTER TABLE admins ADD COLUMN role_id INT(11) DEFAULT NULL",
] as $col => $sql) {
    if (!in_array($col, $cols)) $pdo->exec($sql);
}

/* ─── Upsert account ────────────────────────────────────────────── */
$stmt = $pdo->prepare("SELECT adminId FROM admins WHERE LOWER(email)=? LIMIT 1");
$stmt->execute([strtolower($email)]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $id = (int)$row['adminId'];
    $pdo->prepare("
        UPDATE admins
        SET fullname=?, pswd=?, is_owner=1, is_protected=1,
            is_active=1, is_suspended=0
        WHERE adminId=?
    ")->execute([$fullname, $hash, $id]);
    $action = "Updated existing account (ID: $id)";
} else {
    $pdo->prepare("
        INSERT INTO admins (fullname, email, pswd, is_owner, is_protected, is_active)
        VALUES (?,?,?,1,1,1)
    ")->execute([$fullname, $email, $hash]);
    $id     = (int)$pdo->lastInsertId();
    $action = "Created new account (ID: $id)";
}

/* ─── Assign level-100 role if roles table exists ───────────────── */
$role_msg = 'Roles table not found — run rbac_migration.php separately';
try {
    $role = $pdo->query("SELECT id, name FROM roles WHERE slug='head_office_it_arafat' LIMIT 1")->fetch();
    if ($role) {
        $pdo->prepare("UPDATE admins SET role_id=? WHERE adminId=?")->execute([$role['id'], $id]);
        $role_msg = "Role '{$role['name']}' assigned (Level 100)";
    } else {
        $role_msg = "Role slug not found — run rbac_migration.php first";
    }
} catch (PDOException) {}

/* ─── Verify hash from DB ───────────────────────────────────────── */
$saved    = $pdo->prepare("SELECT pswd FROM admins WHERE adminId=?");
$saved->execute([$id]);
$ok       = password_verify($password, $saved->fetchColumn());

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
<title>Login Fix</title>
<style>
*{box-sizing:border-box}
body{font-family:system-ui,sans-serif;background:#f5f9ff;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
.card{background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,.1);padding:2.5rem;max-width:500px;width:100%;}
.status{font-size:2.5rem;text-align:center;margin-bottom:1rem;}
h2{margin:0 0 1.5rem;text-align:center;color:#1e3a5f;}
.row{display:flex;justify-content:space-between;padding:.55rem 0;border-bottom:1px solid #f1f5f9;font-size:.88rem;}
.row:last-child{border:none;}
.label{color:#94a3b8;font-weight:600;}
.val{font-weight:700;color:#1e3a5f;}
.cred{background:#1e3a5f;color:#fff;border-radius:10px;padding:1.25rem;margin:1.25rem 0;}
.cred p{margin:.3rem 0;font-size:.9rem;}
.cred .lbl{font-size:.72rem;opacity:.6;text-transform:uppercase;}
.btn{display:block;background:#0073e6;color:#fff;text-decoration:none;text-align:center;padding:.85rem;border-radius:10px;font-weight:700;font-size:1rem;margin-top:1rem;}
.btn:hover{background:#005bb5;}
.del{background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:.75rem;margin-top:1rem;font-size:.82rem;color:#991b1b;text-align:center;}
</style></head><body>
<div class="card">
  <div class="status"><?= $ok ? '✅' : '❌' ?></div>
  <h2><?= $ok ? 'Login Fixed!' : 'Something Went Wrong' ?></h2>

  <div class="row"><span class="label">Action</span><span class="val"><?= $action ?></span></div>
  <div class="row"><span class="label">Role</span><span class="val"><?= $role_msg ?></span></div>
  <div class="row"><span class="label">password_verify()</span><span class="val"><?= $ok ? '✅ PASSES' : '❌ FAILS' ?></span></div>
  <div class="row"><span class="label">is_owner</span><span class="val">1 ✅</span></div>
  <div class="row"><span class="label">is_protected</span><span class="val">1 ✅</span></div>
  <div class="row"><span class="label">is_suspended</span><span class="val">0 ✅</span></div>

  <?php if ($ok): ?>
  <div class="cred">
    <p><span class="lbl">Email</span><br><strong>arafathaquebiswas@gmail.com</strong></p>
    <p><span class="lbl">Password</span><br><strong>Arafat@123</strong></p>
  </div>
  <a href="../login/loging.php" class="btn">Go to Login →</a>
  <?php endif; ?>

  <div class="del">⚠️ Delete <code>backend/Database/fix_login.php</code> after logging in.</div>
</div>
</body></html>
