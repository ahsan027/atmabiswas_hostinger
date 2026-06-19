<?php
/**
 * Refreshes the RBAC session for the currently logged-in admin.
 * Visit this URL while logged in to reload all permissions.
 * Safe to keep — only works when already authenticated.
 */
session_start();
require_once '../Database/db.php';
require_once 'auth.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['admin_id'])) {
    header('Location: ../login/loging.php');
    exit();
}

reloadPermissions((int)$_SESSION['admin_id']);

// Redirect back to wherever they came from, default to dashboard
$back = $_GET['back'] ?? 'dashboard.php';
// Safety: only allow relative paths within DashBoard
$back = basename($back);
if (!preg_match('/^[a-zA-Z0-9_-]+\.php$/', $back)) {
    $back = 'dashboard.php';
}

header("Location: $back?session_refreshed=1");
exit();
