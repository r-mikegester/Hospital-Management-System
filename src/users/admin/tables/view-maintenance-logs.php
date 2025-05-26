<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding/editing maintenance log
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $maintenance_id = $_POST['maintenance_id'] ?? null;
    if (!$maintenance_id) {
        die("Please select a maintenance record.");
    }

    $maintenance_name = $_POST['maintenance_name'];
    $log_date = $_POST['log_date'];
    $details = $_POST['details'];
    $action_taken = $_POST['action_taken'];

    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO maintenance_log (maintenance_id, maintenance_name, log_date, details, action) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$maintenance_id, $maintenance_name, $log_date, $details, $action_taken]);
        } elseif ($_POST['action'] === 'edit') {
            $log_id = $_POST['log_id'];
            $stmt = $pdo->prepare("UPDATE maintenance_log SET maintenance_id = ?, maintenance_name = ?, log_date = ?, details = ?, action = ? WHERE log_id = ?");
            $stmt->execute([$maintenance_id, $maintenance_name, $log_date, $details, $action_taken, $log_id]);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a maintenance log
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM maintenance_log WHERE log_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch maintenance records for dropdown
try {
    $stmt = $pdo->prepare("SELECT maintenance_id, maintenance_name FROM maintenance_log");
    $stmt->execute();
    $maintenanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all maintenance logs
try {
    $stmt = $pdo->prepare("SELECT * FROM maintenance_log");
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Maintenance Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="relative min-h-screen flex flex-col">
        <?php include("../../../../components/head.php"); ?>
        <header>
            <?php include("../../../../components/navbar.php"); ?>
        </header>
        <div class="flex flex-1">
            <aside class="w-64">
                <?php include("../../../../components/sidebar2.php"); ?>
            </aside>

            <div class="flex-1 mt-20 p-6">
                <h1 class="text-3xl font-bold mb-10">Maintenance Logs</h1>
                <div>
                    <!-- Add Log Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addLogModal">Add Maintenance Log</button>

                    <!-- Logs Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Log ID</th>
                                <th>Maintenance ID</th>
                                <th>Maintenance Name</th>
                                <th>Log Date</th>
                                <th>Details</th>
                                <th>Action Taken</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= $log['log_id'] ?></td>
                                    <td><?= $log['maintenance_id'] ?></td>
                                    <td><?= htmlspecialchars($log['maintenance_name']) ?></td>
                                    <td><?= $log['log_date'] ?></td>
                                    <td><?= htmlspecialchars($log['details']) ?></td>
                                    <td><?= htmlspecialchars($log['action']) ?></td>
                                    <td><?= $log['created_at'] ?></td>
                                    <td><?= $log['updated_at'] ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openEditModal(
                                                <?= $log['log_id'] ?>,
                                                <?= $log['maintenance_id'] ?>,
                                                '<?= htmlspecialchars(addslashes($log['maintenance_name'])) ?>',
                                                '<?= $log['log_date'] ?>',
                                                '<?= htmlspecialchars(addslashes($log['details'])) ?>',
                                                '<?= htmlspecialchars(addslashes($log['action'])) ?>'
                                            )">Edit</button>
                                        <a href="?delete_id=<?= $log['log_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this log?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Add Log Modal -->
                    <div class="modal fade" id="addLogModal" tabindex="-1" aria-labelledby="addLogModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addLogModalLabel">Add Maintenance Log</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="add">

                                    <!-- Maintenance Select -->
                                    <div class="mb-3">
                                        <label for="add-maintenance-id" class="form-label">Select Maintenance</label>
                                        <select name="maintenance_id" id="add-maintenance-id" class="form-control" required>
                                            <option value="">-- Select Maintenance --</option>
                                            <?php foreach ($maintenanceRecords as $record): ?>
                                                <option value="<?= $record['maintenance_id'] ?>"><?= $record['maintenance_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-maintenance-name" class="form-label">Maintenance Name</label>
                                        <input type="text" name="maintenance_name" id="add-maintenance-name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-log-date" class="form-label">Log Date</label>
                                        <input type="datetime-local" name="log_date" id="add-log-date" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-details" class="form-label">Details</label>
                                        <textarea name="details" id="add-details" class="form-control" rows="4"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-action" class="form-label">Action Taken</label>
                                        <input type="text" name="action_taken" id="add-action" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Log</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Log Modal -->
                    <div class="modal fade" id="editLogModal" tabindex="-1" aria-labelledby="editLogModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLogModalLabel">Edit Maintenance Log</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="log_id" id="edit-log-id">

                                    <!-- Maintenance Select -->
                                    <div class="mb-3">
                                        <label for="edit-maintenance-id" class="form-label">Select Maintenance</label>
                                        <select name="maintenance_id" id="edit-maintenance-id" class="form-control" required>
                                            <option value="">-- Select Maintenance --</option>
                                            <?php foreach ($maintenanceRecords as $record): ?>
                                                <option value="<?= $record['maintenance_id'] ?>"><?= $record['maintenance_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-maintenance-name" class="form-label">Maintenance Name</label>
                                        <input type="text" name="maintenance_name" id="edit-maintenance-name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-log-date" class="form-label">Log Date</label>
                                        <input type="datetime-local" name="log_date" id="edit-log-date" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-details" class="form-label">Details</label>
                                        <textarea name="details" id="edit-details" class="form-control" rows="4"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-action" class="form-label">Action Taken</label>
                                        <input type="text" name="action_taken" id="edit-action" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function openEditModal(logId, maintenanceId, maintenanceName, logDate, details, actionTaken) {
                const modal = new bootstrap.Modal(document.getElementById('editLogModal'));
                document.getElementById('edit-log-id').value = logId;
                document.getElementById('edit-maintenance-id').value = maintenanceId;
                document.getElementById('edit-maintenance-name').value = maintenanceName;
                document.getElementById('edit-log-date').value = logDate;
                document.getElementById('edit-details').value = details;
                document.getElementById('edit-action').value = actionTaken;
                modal.show();
            }
        </script>
    </div>
</body>

</html>
