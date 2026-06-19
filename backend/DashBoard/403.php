<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>403 — Access Denied | ATMABISWAS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="icon" type="image/png" href="../images/logo/logo.png">
<style>
body { background:#f5f9ff; font-family:system-ui,-apple-system,sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; }
.card-403 { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,.1); padding:3rem 2.5rem; text-align:center; max-width:480px; width:100%; }
.icon-403 { width:80px; height:80px; background:#fef2f2; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:1.5rem; }
.icon-403 i { font-size:2rem; color:#dc3545; }
h1 { font-size:1.5rem; font-weight:800; color:#1e3a5f; margin-bottom:.5rem; }
.reason { background:#fef9e7; border-left:4px solid #f59e0b; border-radius:6px; padding:.75rem 1rem; font-size:.85rem; color:#92400e; text-align:left; margin:1rem 0 1.5rem; }
.btn-go { background:#0073e6; color:#fff; border:none; border-radius:8px; padding:.65rem 1.5rem; font-weight:600; text-decoration:none; }
.btn-go:hover { background:#005bb5; color:#fff; }
</style>
</head>
<body>
<div class="card-403">
    <div class="icon-403"><i class="fas fa-lock"></i></div>
    <h1>Access Denied</h1>
    <p class="text-muted mb-0">You do not have permission to access this page or perform this action.</p>
    <?php if (!empty($reason)): ?>
    <div class="reason"><i class="fas fa-info-circle me-2"></i><?= $reason ?></div>
    <?php endif; ?>
    <p class="text-muted small mb-3">Logged in as: <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Unknown') ?></strong>
    (<?= htmlspecialchars($_SESSION['role_name'] ?? 'No role') ?>)</p>
    <a href="dashboard.php" class="btn-go"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
    &nbsp;
    <a href="../login/loging.php" class="btn btn-outline-secondary btn-sm">Login as different user</a>
</div>
</body>
</html>
