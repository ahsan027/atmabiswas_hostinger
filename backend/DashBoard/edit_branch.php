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

$id = (int)($_GET['id'] ?? 0);
if ($id < 1) {
    header('Location: branches.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM branches WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$branch = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$branch) {
    header('Location: branches.php?msg=' . urlencode('Branch not found.') . '&type=error');
    exit();
}

$errors = [];
$values = $branch;

// Fetch active divisions from the divisions table (with fallback)
$divisions = [];
try {
    $div_stmt  = $conn->query("SELECT name FROM divisions WHERE status = 1 ORDER BY display_order ASC, name ASC");
    $divisions = $div_stmt->fetchAll(PDO::FETCH_COLUMN);

    // If the branch's current division isn't in the active list, include it anyway so the form doesn't lose it
    if ($values['division'] !== '' && !in_array($values['division'], $divisions, true)) {
        array_unshift($divisions, $values['division']);
    }
} catch (PDOException $e) {
    try {
        $div_stmt  = $conn->query("SELECT DISTINCT division FROM branches WHERE division != '' ORDER BY division ASC");
        $divisions = $div_stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e2) {
        $divisions = [];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        $values['branch_name']   = trim($_POST['branch_name']   ?? '');
        $values['address']       = trim($_POST['address']       ?? '');
        $values['division']      = trim($_POST['division']      ?? '');
        $values['district']      = trim($_POST['district']      ?? '');
        $values['display_order'] = (int)($_POST['display_order'] ?? 0);
        $values['status']        = (int)($_POST['status']       ?? 1);

        if ($values['branch_name'] === '') $errors[] = 'Branch name is required.';
        if ($values['address']     === '') $errors[] = 'Address is required.';
        if ($values['division']    === '') $errors[] = 'Division is required.';
        if ($values['district']    === '') $errors[] = 'District is required.';

        if (empty($errors)) {
            $upd = $conn->prepare(
                "UPDATE branches
                 SET branch_name   = :branch_name,
                     address       = :address,
                     division      = :division,
                     district      = :district,
                     display_order = :display_order,
                     status        = :status
                 WHERE id = :id"
            );
            $upd->execute([
                ':branch_name'   => $values['branch_name'],
                ':address'       => $values['address'],
                ':division'      => $values['division'],
                ':district'      => $values['district'],
                ':display_order' => $values['display_order'],
                ':status'        => $values['status'],
                ':id'            => $id,
            ]);
            header('Location: branches.php?msg=' . urlencode('Branch updated successfully.') . '&type=success');
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
    <title>Edit Branch — Admin</title>
    <link rel="stylesheet" href="css/admin-sidebar.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/contacts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../images/logo/logo.png">
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar-container">
        <?php include 'sidebar.php'; ?>
    </div>
    <div class="main-content">
        <?php include 'navbar.inc.php'; ?>
        <div class="dashboard-main">

            <div class="cm-header">
                <div>
                    <div class="cm-title">Edit Branch</div>
                    <div class="cm-subtitle">ID #<?= $id ?> — <?= htmlspecialchars($branch['branch_name']) ?></div>
                </div>
                <a href="branches.php" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

            <?php if (!empty($errors)): ?>
            <div class="cm-alert cm-alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
            </div>
            <?php endif; ?>

            <div class="cm-form-card">
                <form method="POST">
                    <?= csrf_field() ?>

                    <div class="cm-form-section">Branch Information</div>

                    <div class="cm-form-group">
                        <label>Branch Name <span class="required">*</span></label>
                        <input class="cm-form-control" type="text" name="branch_name" required
                               maxlength="255" value="<?= htmlspecialchars($values['branch_name']) ?>">
                    </div>

                    <div class="cm-form-group">
                        <label>Address <span class="required">*</span></label>
                        <textarea class="cm-form-control" name="address" required
                                  rows="3"><?= htmlspecialchars($values['address']) ?></textarea>
                    </div>

                    <div class="cm-form-section">Location</div>

                    <div class="cm-form-row">
                        <div class="cm-form-group">
                            <label>Division <span class="required">*</span></label>
                            <select class="cm-form-control" name="division" required>
                                <option value="">— Select Division —</option>
                                <?php foreach ($divisions as $div): ?>
                                <option value="<?= htmlspecialchars($div) ?>"
                                        <?= $values['division'] === $div ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($div) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="cm-form-hint">
                                Division not listed?
                                <a href="add_division.php" class="cm-add-link">
                                    <i class="fas fa-plus-circle"></i> Add a new division
                                </a>
                            </div>
                        </div>
                        <div class="cm-form-group">
                            <label>District <span class="required">*</span></label>
                            <input class="cm-form-control" type="text" name="district" required
                                   maxlength="100" value="<?= htmlspecialchars($values['district']) ?>">
                        </div>
                    </div>

                    <div class="cm-form-section">Settings</div>

                    <div class="cm-form-row">
                        <div class="cm-form-group">
                            <label>Display Order</label>
                            <input class="cm-form-control" type="number" name="display_order" min="0"
                                   value="<?= (int)$values['display_order'] ?>">
                            <div class="cm-form-hint">Lower number appears first in the list.</div>
                        </div>
                        <div class="cm-form-group">
                            <label>Status</label>
                            <select class="cm-form-control" name="status">
                                <option value="1" <?= $values['status'] ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= !$values['status'] ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <div class="cm-form-hint">Inactive branches are hidden from the Contact page.</div>
                        </div>
                    </div>

                    <div class="cm-form-actions">
                        <button class="btn-primary" type="submit">
                            <i class="fas fa-save"></i> Update Branch
                        </button>
                        <a href="branches.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
</body>
</html>
