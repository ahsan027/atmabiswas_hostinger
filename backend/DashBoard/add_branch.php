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
$values = ['branch_name' => '', 'address' => '', 'division' => '', 'district' => '', 'display_order' => 0, 'status' => 1];

// Fetch active divisions from the divisions table (with fallback)
$divisions = [];
try {
    $div_stmt  = $conn->query("SELECT name FROM divisions WHERE status = 1 ORDER BY display_order ASC, name ASC");
    $divisions = $div_stmt->fetchAll(PDO::FETCH_COLUMN);
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
            $stmt = $conn->prepare(
                "INSERT INTO branches (branch_name, address, division, district, display_order, status)
                 VALUES (:branch_name, :address, :division, :district, :display_order, :status)"
            );
            $stmt->execute([
                ':branch_name'   => $values['branch_name'],
                ':address'       => $values['address'],
                ':division'      => $values['division'],
                ':district'      => $values['district'],
                ':display_order' => $values['display_order'],
                ':status'        => $values['status'],
            ]);
            header('Location: branches.php?msg=' . rawurlencode('Branch added successfully.') . '&type=success');
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
    <title>Add Branch — Admin</title>
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
                    <div class="cm-title">Add Branch</div>
                    <div class="cm-subtitle">Add a new ATMABISWAS branch</div>
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
                               maxlength="255" placeholder="e.g. Chuadanga Branch"
                               value="<?= htmlspecialchars($values['branch_name']) ?>">
                    </div>

                    <div class="cm-form-group">
                        <label>Address <span class="required">*</span></label>
                        <textarea class="cm-form-control" name="address" required
                                  rows="3" placeholder="Full branch address"><?= htmlspecialchars($values['address']) ?></textarea>
                    </div>

                    <div class="cm-form-section">Location</div>

                    <div class="cm-form-row">
                        <div class="cm-form-group">
                            <label>Division <span class="required">*</span></label>
                            <?php if (empty($divisions)): ?>
                            <div class="cm-alert cm-alert-error" style="margin-bottom:.5rem;">
                                <i class="fas fa-exclamation-triangle"></i>
                                No active divisions found.
                                <a href="add_division.php" style="font-weight:700;color:#991b1b;text-decoration:underline;margin-left:.25rem;">
                                    Add a division first →
                                </a>
                            </div>
                            <select class="cm-form-control" name="division" required disabled>
                                <option value="">No divisions available</option>
                            </select>
                            <?php else: ?>
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
                            <?php endif; ?>
                        </div>
                        <div class="cm-form-group">
                            <label>District <span class="required">*</span></label>
                            <input class="cm-form-control" type="text" name="district" required
                                   maxlength="100" placeholder="e.g. Chuadanga"
                                   value="<?= htmlspecialchars($values['district']) ?>">
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
                        <button class="btn-primary" type="submit"
                                <?= empty($divisions) ? 'disabled title="Add a division first"' : '' ?>>
                            <i class="fas fa-save"></i> Save Branch
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
