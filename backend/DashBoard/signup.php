<?php
session_start();
require_once '../Database/db.php';
require_once 'auth.php';

requireLogin();
authorize('user.manage');

$conn = (new Db())->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname         = trim($_POST['fullname'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role_id          = !empty($_POST['role_id']) ? (int)$_POST['role_id'] : null;

    if ($password !== $confirm_password) {
        header('Location: adminSignup.php?err=mismatch');
        exit();
    }

    // Validate role hierarchy: cannot assign role at or above own level
    if ($role_id) {
        $rs = $conn->prepare("SELECT role_level FROM roles WHERE id=?");
        $rs->execute([$role_id]);
        $rl = (int)$rs->fetchColumn();
        if ($rl >= myRoleLevel()) {
            header('Location: adminSignup.php?err=role_level');
            exit();
        }
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admins (fullname, email, pswd, role_id) VALUES (:fullname, :email, :pswd, :role_id)");
    $stmt->bindValue(':fullname', $fullname);
    $stmt->bindValue(':email',    $email);
    $stmt->bindValue(':pswd',     $hash);
    $stmt->bindValue(':role_id',  $role_id, $role_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

    if ($stmt->execute()) {
        $new_id = (int)$conn->lastInsertId();
        logPermissionChange($new_id, 'create_admin', null, "role_id:{$role_id}");
        header('Location: successfulRegistration.php');
        exit();
    } else {
        header('Location: adminSignup.php?err=db');
        exit();
    }
}
