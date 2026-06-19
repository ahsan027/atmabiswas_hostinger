<?php
/**
 * Login Diagnostics Tool — DELETE AFTER DEBUGGING
 * Access: yourdomain.com/backend/login/debug_login.php?token=atma-debug-2024
 */
if (($_GET['token'] ?? '') !== 'atma-debug-2024') {
    http_response_code(403); die('Forbidden. Append ?token=atma-debug-2024');
}

include '../Database/db.php';
$pdo = (new Db())->connect();

$email    = 'arafathaquebiswas@gmail.com';
$password = 'Arafat@12';

$checks = [];

/* ── 1. Row lookup ───────────────────────────────────────────────── */
$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$checks['Row found'] = $user ? '✅ YES (adminId=' . $user['adminId'] . ')' : '❌ NO — email not in admins table';

if ($user) {
    /* ── 2. Column presence ──────────────────────────────────────── */
    $checks['pswd column exists']        = array_key_exists('pswd', $user)         ? '✅ YES' : '❌ MISSING';
    $checks['is_active column exists']   = array_key_exists('is_active', $user)    ? '✅ YES' : '⚠️ MISSING (column not added yet — run rbac migration)';
    $checks['is_suspended column exists']= array_key_exists('is_suspended', $user) ? '✅ YES' : '⚠️ MISSING (run suspension migration)';

    /* ── 3. Hash inspection ──────────────────────────────────────── */
    $hash = $user['pswd'] ?? '';
    $checks['Hash stored']        = $hash ? '✅ Not empty' : '❌ EMPTY — no password set';
    $checks['Hash length']        = strlen($hash) . ' chars (need 60+)';
    $checks['Hash starts with $'] = str_starts_with($hash, '$') ? '✅ Looks like bcrypt' : '❌ NOT a bcrypt hash — plaintext or wrong algo';

    /* ── 4. password_verify ──────────────────────────────────────── */
    $verify_raw     = password_verify($password, $hash);
    $checks['password_verify() raw']     = $verify_raw ? '✅ PASSES' : '❌ FAILS';

    // Simulate what FILTER_SANITIZE_SPECIAL_CHARS does to the password
    $filtered_pass = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
    $checks['Password after FILTER_SANITIZE_SPECIAL_CHARS'] = '"' . $filtered_pass . '"' .
        ($filtered_pass !== $password ? ' ⚠️ MODIFIED — THIS IS THE BUG' : ' (unchanged)');
    $verify_filtered = password_verify($filtered_pass, $hash);
    $checks['password_verify() with filtered password'] = $verify_filtered ? '✅ PASSES' : '❌ FAILS';

    /* ── 5. Account flag states ──────────────────────────────────── */
    $checks['is_active']    = isset($user['is_active'])    ? ($user['is_active']    ? '✅ Active'    : '❌ DISABLED — login blocked') : 'N/A';
    $checks['is_suspended'] = isset($user['is_suspended']) ? ($user['is_suspended'] ? '❌ SUSPENDED — login blocked' : '✅ Not suspended') : 'N/A';
    $checks['is_owner']     = isset($user['is_owner'])     ? ($user['is_owner']     ? '✅ Owner'     : 'No')          : 'N/A';
    $checks['role_id']      = $user['role_id'] ?? 'NULL (no role assigned)';

    /* ── 6. Session test ─────────────────────────────────────────── */
    if (session_status() === PHP_SESSION_NONE) session_start();
    $checks['session_start()'] = '✅ Works';

    /* ── 7. auth.php loadable ────────────────────────────────────── */
    try {
        require_once '../DashBoard/auth.php';
        $checks['auth.php include'] = '✅ Loads without error';
        reloadPermissions((int)$user['adminId']);
        $checks['reloadPermissions()'] = '✅ Ran OK — permissions: ' . implode(', ', array_slice($_SESSION['permissions'] ?? [], 0, 5)) . (count($_SESSION['permissions'] ?? []) > 5 ? '…' : '');
    } catch (Throwable $e) {
        $checks['auth.php / reloadPermissions()'] = '❌ ERROR: ' . $e->getMessage();
    }

    /* ── 8. Redirect header check ────────────────────────────────── */
    $checks['headers_sent() before redirect'] = headers_sent($file, $line)
        ? "❌ HEADERS ALREADY SENT at $file:$line — redirect will fail"
        : '✅ Headers not sent — redirect will work';
}

/* ── Display ─────────────────────────────────────────────────────── */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login Debug</title>
<style>
body{font-family:system-ui,sans-serif;max-width:780px;margin:2rem auto;padding:1rem;background:#f8fafc;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08);}
th,td{padding:.65rem 1rem;text-align:left;border-bottom:1px solid #f1f5f9;font-size:.88rem;}
th{background:#1e3a5f;color:#fff;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;}
tr:last-child td{border-bottom:none;}
tr:hover td{background:#f8faff;}
.warn{background:#fffbeb;}
code{background:#e2e8f0;padding:.1rem .3rem;border-radius:3px;font-size:.82rem;}
h2{color:#1e3a5f;}
.del{background:#fef2f2;border:1.5px solid #fca5a5;border-radius:8px;padding:.75rem 1rem;margin-top:1.5rem;color:#991b1b;font-size:.85rem;}
</style>
</head>
<body>
<h2>Login Diagnostics — <?= htmlspecialchars($email) ?></h2>
<table>
<tr><th>Check</th><th>Result</th></tr>
<?php foreach ($checks as $k => $v): ?>
<tr class="<?= (str_contains($v, '❌') || str_contains($v, 'BUG')) ? 'warn' : '' ?>">
    <td><strong><?= htmlspecialchars($k) ?></strong></td>
    <td><?= $v ?></td>
</tr>
<?php endforeach; ?>
</table>
<div class="del">⚠️ Delete <code>backend/login/debug_login.php</code> immediately after debugging.</div>
</body>
</html>
