<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding/editing maintenance schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $maintenance_id = $_POST['maintenance_id'] ?? null;
    if (!$maintenance_id) {
        die("Please select a maintenance record.");
    }

    $asset_id = $_POST['asset_id'];
    $frequency = $_POST['frequency'];
    $next_schedule_date = $_POST['next_schedule_date'];
    $last_schedule_date = $_POST['last_schedule_date'];
    $notes = $_POST['notes'];

    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO maintenance_schedule (maintenance_id, asset_id, frequency, next_schedule_date, last_schedule_date, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$maintenance_id, $asset_id, $frequency, $next_schedule_date, $last_schedule_date, $notes]);
        } elseif ($_POST['action'] === 'edit') {
            $schedule_id = $_POST['schedule_id'];
            $stmt = $pdo->prepare("UPDATE maintenance_schedule SET maintenance_id = ?, asset_id = ?, frequency = ?, next_schedule_date = ?, last_schedule_date = ?, notes = ? WHERE schedule_id = ?");
            $stmt->execute([$maintenance_id, $asset_id, $frequency, $next_schedule_date, $last_schedule_date, $notes, $schedule_id]);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a maintenance schedule
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM maintenance_schedule WHERE schedule_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch maintenance records for dropdown
try {
    $stmt = $pdo->prepare("SELECT maintenance_id, title FROM maintenance");
    $stmt->execute();
    $maintenanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all maintenance schedules
try {
    $stmt = $pdo->prepare("SELECT * FROM maintenance_schedule");
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Maintenance Schedule</title>
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
                <h1 class="text-3xl font-bold mb-10">Maintenance Schedules</h1>
                <div>
                    <!-- Add Schedule Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addScheduleModal">Add Maintenance Schedule</button>

                    <!-- Schedules Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Schedule ID</th>
                                <th>Maintenance ID</th>
                                <th>Asset ID</th>
                                <th>Frequency</th>
                                <th>Next Schedule Date</th>
                                <th>Last Schedule Date</th>
                                <th>Notes</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?= $schedule['schedule_id'] ?></td>
                                    <td><?= $schedule['maintenance_id'] ?></td>
                                    <td><?= $schedule['asset_id'] ?></td>
                                    <td><?= htmlspecialchars($schedule['frequency']) ?></td>
                                    <td><?= $schedule['next_schedule_date'] ?></td>
                                    <td><?= $schedule['last_schedule_date'] ?></td>
                                    <td><?= htmlspecialchars($schedule['notes']) ?></td>
                                    <td><?= $schedule['created_at'] ?></td>
                                    <td><?= $schedule['updated_at'] ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openEditModal(
                                                <?= $schedule['schedule_id'] ?>,
                                                <?= $schedule['maintenance_id'] ?>,
                                                <?= $schedule['asset_id'] ?>,
                                                '<?= htmlspecialchars(addslashes($schedule['frequency'])) ?>',
                                                '<?= $schedule['next_schedule_date'] ?>',
                                                '<?= $schedule['last_schedule_date'] ?>',
                                                '<?= htmlspecialchars(addslashes($schedule['notes'])) ?>'
                                            )">Edit</button>
                                        <a href="?delete_id=<?= $schedule['schedule_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Add Schedule Modal -->
                    <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addScheduleModalLabel">Add Maintenance Schedule</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="add">

                                    <div class="mb-3">
                                        <label for="add-maintenance-id" class="form-label">Select Maintenance</label>
                                        <select name="maintenance_id" id="add-maintenance-id" class="form-control" required>
                                            <option value="">-- Select Maintenance --</option>
                                            <?php foreach ($maintenanceRecords as $record): ?>
                                                <option value="<?= $record['maintenance_id'] ?>"><?= $record['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-asset-id" class="form-label">Asset ID</label>
                                        <input type="text" name="asset_id" id="add-asset-id" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-frequency" class="form-label">Frequency</label>
                                        <input type="text" name="frequency" id="add-frequency" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-next-schedule-date" class="form-label">Next Schedule Date</label>
                                        <input type="date" name="next_schedule_date" id="add-next-schedule-date" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-last-schedule-date" class="form-label">Last Schedule Date</label>
                                        <input type="date" name="last_schedule_date" id="add-last-schedule-date" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-notes" class="form-label">Notes</label>
                                        <textarea name="notes" id="add-notes" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Schedule</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Schedule Modal -->
                    <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editScheduleModalLabel">Edit Maintenance Schedule</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="schedule_id" id="edit-schedule-id">

                                    <div class="mb-3">
                                        <label for="edit-maintenance-id" class="form-label">Select Maintenance</label>
                                        <select name="maintenance_id" id="edit-maintenance-id" class="form-control" required>
                                            <option value="">-- Select Maintenance --</option>
                                            <?php foreach ($maintenanceRecords as $record): ?>
                                                <option value="<?= $record['maintenance_id'] ?>"><?= $record['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-asset-id" class="form-label">Asset ID</label>
                                        <input type="text" name="asset_id" id="edit-asset-id" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-frequency" class="form-label">Frequency</label>
                                        <input type="text" name="frequency" id="edit-frequency" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-next-schedule-date" class="form-label">Next Schedule Date</label>
                                        <input type="date" name="next_schedule_date" id="edit-next-schedule-date" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-last-schedule-date" class="form-label">Last Schedule Date</label>
                                        <input type="date" name="last_schedule_date" id="edit-last-schedule-date" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-notes" class="form-label">Notes</label>
                                        <textarea name="notes" id="edit-notes" class="form-control" rows="4"></textarea>
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
            </div>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditModal(scheduleId, maintenanceId, assetId, frequency, nextScheduleDate, lastScheduleDate, notes) {
            document.getElementById('edit-schedule-id').value = scheduleId;
            document.getElementById('edit-maintenance-id').value = maintenanceId;
            document.getElementById('edit-asset-id').value = assetId;
            document.getElementById('edit-frequency').value = frequency;
            document.getElementById('edit-next-schedule-date').value = nextScheduleDate;
            document.getElementById('edit-last-schedule-date').value = lastScheduleDate;
            document.getElementById('edit-notes').value = notes;

            var editModal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
            editModal.show();
        }
    </script>
</body>

</html>
