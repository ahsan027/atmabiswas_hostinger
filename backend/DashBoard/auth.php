<?php
/**
 * ATMABISWAS RBAC Auth Middleware
 * Include at the top of every admin page (after session_start).
 * Provides: can(), authorize(), canManageUser(), logPermissionChange(), reloadPermissions()
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ── Require login ───────────────────────────────────────────────── */
function requireLogin(): void {
    if (!isset($_SESSION['username'])) {
        header('Location: ../login/loging.php');
        exit();
    }
}

/* ── Check if current admin has a permission ─────────────────────── */
function can(string $permission): bool {
    if (!isset($_SESSION['admin_id'])) return false;
    // Owner always passes — no permission check needed
    if (!empty($_SESSION['is_owner'])) return true;
    return in_array($permission, $_SESSION['permissions'] ?? [], true);
}

/* ── Authorize or show 403 ───────────────────────────────────────── */
function authorize(string $permission): void {
    if (!isset($_SESSION['username'])) {
        header('Location: ../login/loging.php');
        exit();
    }
    // If admin_id is missing, reload from session username
    if (!isset($_SESSION['admin_id'])) {
        _bootstrapSession();
    }
    if (!can($permission)) {
        http_response_code(403);
        $reason = "You do not have the required permission: <strong>" . htmlspecialchars($permission) . "</strong>";
        include __DIR__ . '/403.php';
        exit();
    }
}

/* ── Can current admin manage a specific user? (hierarchy check) ─── */
function canManageUser(int $target_admin_id): bool {
    if (!isset($_SESSION['admin_id'])) return false;
    if (!empty($_SESSION['is_owner'])) return true;

    try {
        require_once __DIR__ . '/../Database/db.php';
        $pdo = (new Db())->connect();
        $stmt = $pdo->prepare("
            SELECT a.is_owner, a.is_protected, COALESCE(r.role_level, 0) AS role_level
            FROM admins a
            LEFT JOIN roles r ON a.role_id = r.id
            WHERE a.adminId = ?
        ");
        $stmt->execute([$target_admin_id]);
        $target = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$target) return false;
        // Protected accounts and owner accounts cannot be managed
        if ($target['is_protected'] || $target['is_owner']) return false;
        $my_level     = (int)($_SESSION['role_level'] ?? 0);
        $target_level = (int)$target['role_level'];
        return $my_level > $target_level;
    } catch (Exception $e) {
        return false;
    }
}

/* ── Get current admin's role level ─────────────────────────────── */
function myRoleLevel(): int {
    return (int)($_SESSION['role_level'] ?? 0);
}

/* ── Can current admin suspend a specific user? ──────────────────── */
function canSuspendUser(int $target_admin_id): bool {
    if (!isset($_SESSION['admin_id'])) return false;
    if (!can('user.suspend')) return false;
    // Cannot suspend self
    if ($target_admin_id === (int)$_SESSION['admin_id']) return false;

    try {
        require_once __DIR__ . '/../Database/db.php';
        $pdo  = (new Db())->connect();
        $stmt = $pdo->prepare("
            SELECT a.is_owner, a.is_protected, COALESCE(r.role_level, 0) AS role_level
            FROM admins a
            LEFT JOIN roles r ON a.role_id = r.id
            WHERE a.adminId = ?
        ");
        $stmt->execute([$target_admin_id]);
        $target = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$target) return false;
        // Owner and protected accounts are immune — cannot be suspended by anyone
        if ($target['is_owner'] || $target['is_protected']) return false;
        // Strict hierarchy: my level must be greater than target's level
        return myRoleLevel() > (int)$target['role_level'];
    } catch (Exception) {
        return false;
    }
}

/* ── Log a permission change ─────────────────────────────────────── */
function logPermissionChange(int $target_id, string $action, ?string $old, ?string $new): void {
    try {
        require_once __DIR__ . '/../Database/db.php';
        $pdo = (new Db())->connect();
        $pdo->prepare("
            INSERT INTO permission_audit_log
                (changed_by, target_admin_id, action, old_value, new_value, ip_address)
            VALUES (?, ?, ?, ?, ?, ?)
        ")->execute([
            (int)($_SESSION['admin_id'] ?? 0),
            $target_id,
            $action,
            $old,
            $new,
            $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    } catch (Exception $e) {
        error_log('RBAC audit log failed: ' . $e->getMessage());
    }
}

/* ── Load / reload permissions for an admin into session ─────────── */
function reloadPermissions(int $admin_id): void {
    try {
        require_once __DIR__ . '/../Database/db.php';
        $pdo = (new Db())->connect();

        // Fetch admin + role data
        $stmt = $pdo->prepare("
            SELECT a.adminId, a.fullname, a.role_id, a.is_owner, a.is_protected, a.is_active,
                   COALESCE(r.role_level, 0) AS role_level,
                   r.name AS role_name, r.slug AS role_slug
            FROM admins a
            LEFT JOIN roles r ON a.role_id = r.id
            WHERE a.adminId = ?
        ");
        $stmt->execute([$admin_id]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$admin) return;

        $_SESSION['admin_id']    = (int)$admin['adminId'];
        $_SESSION['role_id']     = $admin['role_id'] ? (int)$admin['role_id'] : null;
        $_SESSION['role_level']  = (int)$admin['role_level'];
        $_SESSION['role_name']   = $admin['role_name'] ?? 'No Role';
        $_SESSION['is_owner']    = (bool)$admin['is_owner'];
        $_SESSION['is_protected']= (bool)$admin['is_protected'];

        // Owner gets everything — signal with wildcard
        if ($admin['is_owner']) {
            $_SESSION['permissions'] = ['*'];
            return;
        }

        // Load role permissions
        $role_perms = [];
        if ($admin['role_id']) {
            $stmt = $pdo->prepare("
                SELECT p.slug
                FROM role_permissions rp
                JOIN permissions p ON rp.permission_id = p.id
                WHERE rp.role_id = ?
            ");
            $stmt->execute([(int)$admin['role_id']]);
            $role_perms = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        // Load user overrides
        $stmt = $pdo->prepare("
            SELECT p.slug, up.granted
            FROM user_permissions up
            JOIN permissions p ON up.permission_id = p.id
            WHERE up.admin_id = ?
        ");
        $stmt->execute([$admin_id]);
        $overrides = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $perm_set = array_flip($role_perms);
        foreach ($overrides as $ov) {
            if ($ov['granted']) {
                $perm_set[$ov['slug']] = true;
            } else {
                unset($perm_set[$ov['slug']]);
            }
        }

        $_SESSION['permissions'] = array_keys($perm_set);

    } catch (Exception $e) {
        error_log('reloadPermissions failed: ' . $e->getMessage());
        $_SESSION['permissions'] = [];
    }
}

/* ── Bootstrap session if admin_id is missing (e.g. old sessions) ── */
function _bootstrapSession(): void {
    if (isset($_SESSION['admin_id']) || !isset($_SESSION['username'])) return;
    try {
        require_once __DIR__ . '/../Database/db.php';
        $pdo  = (new Db())->connect();
        $stmt = $pdo->prepare("SELECT adminId FROM admins WHERE fullname = ? LIMIT 1");
        $stmt->execute([$_SESSION['username']]);
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            reloadPermissions((int)$row['adminId']);
        } else {
            // Fallback: set safe defaults so old sessions still work
            $_SESSION['admin_id']    = 0;
            $_SESSION['role_level']  = 0;
            $_SESSION['is_owner']    = false;
            $_SESSION['permissions'] = [];
        }
    } catch (Exception $e) {
        error_log('_bootstrapSession failed: ' . $e->getMessage());
    }
}

// Auto-bootstrap on every include so old sessions work transparently
if (isset($_SESSION['username']) && !isset($_SESSION['admin_id'])) {
    _bootstrapSession();
}
