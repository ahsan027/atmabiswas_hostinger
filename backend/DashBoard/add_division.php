<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login/loging.php");
    exit();
}

require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/csrf_helper.php';

$db   = new Db();
$conn = $db->connect();

$errors = [];
$values = ['name' => '', 'status' => 1, 'display_order' => 0];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        $values['name']          = trim($_POST['name'] ?? '');
        $values['status']        = (int)($_POST['status'] ?? 1);
        $values['display_order'] = (int)($_POST['display_order'] ?? 0);

        if ($values['name'] === '') {
            $errors[] = 'Division name is required.';
        } elseif (strlen($values['name']) > 100) {
            $errors[] = 'Division name must be 100 characters or fewer.';
        } else {
            $check = $conn->prepare("SELECT id FROM divisions WHERE LOWER(name) = LOWER(:name)");
            $check->bindParam(':name', $values['name'], PDO::PARAM_STR);
            $check->execute();
            if ($check->fetch()) {
                $errors[] = '"' . htmlspecialchars($values['name']) . '" already exists. Division names must be unique.';
            }
        }

        if (empty($errors)) {
            $stmt = $conn->prepare(
                "INSERT INTO divisions (name, status, display_order) VALUES (:name, :status, :display_order)"
            );
            $stmt->execute([
                ':name'          => $values['name'],
                ':status'        => $values['status'],
                ':display_order' => $values['display_order'],
            ]);
            header('Location: divisions.php?msg=' . rawurlencode('Division "' . $values['name'] . '" added successfully.') . '&type=success');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Division — Admin</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/contacts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar-container"><?php include 'sidebar.php'; ?></div>
    <div class="main-content">
        <?php include 'navbar.inc.php'; ?>
        <div class="dashboard-main">

            <div class="cm-header">
                <div>
                    <div class="cm-title">Add Division</div>
                    <div class="cm-subtitle">Divisions appear in the Add Branch dropdown and Contact page filter</div>
                </div>
                <a href="divisions.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Divisions
                </a>
            </div>

            <?php if (!empty($errors)): ?>
            <div class="cm-alert cm-alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= implode('<br>', $errors) ?>
            </div>
            <?php endif; ?>

            <div class="cm-form-card">
                <form method="POST">
                    <?= csrf_field() ?>

                    <div class="cm-form-group">
                        <label>Division Name <span class="required">*</span></label>
                        <input class="cm-form-control" type="text" name="name" required
                               maxlength="100" placeholder="e.g. Dhaka"
                               value="<?= htmlspecialchars($values['name']) ?>">
                        <div class="cm-form-hint">Must be unique. This name will appear in the Branch dropdown and on the Contact page.</div>
                    </div>

                    <div class="cm-form-row">
                        <div class="cm-form-group">
                            <label>Display Order</label>
                            <input class="cm-form-control" type="number" name="display_order"
                                   min="0" value="<?= (int)$values['display_order'] ?>">
                            <div class="cm-form-hint">Lower number appears first. 0 means alphabetical.</div>
                        </div>
                        <div class="cm-form-group">
                            <label>Status</label>
                            <select class="cm-form-control" name="status">
                                <option value="1" <?= $values['status'] ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= !$values['status'] ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <div class="cm-form-hint">Inactive divisions are hidden from the Contact page.</div>
                        </div>
                    </div>

                    <div class="cm-form-actions">
                        <button class="btn-primary" type="submit">
                            <i class="fas fa-save"></i> Add Division
                        </button>
                        <a href="divisions.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</body>
</html>
