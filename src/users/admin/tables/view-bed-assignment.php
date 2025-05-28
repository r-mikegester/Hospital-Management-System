<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submissions for adding, editing, or deleting bed assignments
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $bed_id = $_POST['bed_id'];
    $patient_id = $_POST['patient_id'];
    $assigned_date = $_POST['assigned_date'];
    $discharge_date = !empty($_POST['discharge_date']) ? $_POST['discharge_date'] : null;
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO bed_assignments (bed_id, patient_id, assigned_date, discharge_date, status, notes) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$bed_id, $patient_id, $assigned_date, $discharge_date, $status, $notes]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        $assignment_id = $_POST['assignment_id'];
        try {
            $stmt = $pdo->prepare("UPDATE bed_assignments SET bed_id = ?, patient_id = ?, assigned_date = ?, discharge_date = ?, status = ?, notes = ? WHERE assignment_id = ?");
            $stmt->execute([$bed_id, $patient_id, $assigned_date, $discharge_date, $status, $notes, $assignment_id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a bed assignment
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM bed_assignments WHERE assignment_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all bed assignments with related bed and patient info
try {
    $stmt = $pdo->prepare("SELECT bed_assignments.*, bed_inventory.bed_number, patients.name AS patient_name FROM bed_assignments LEFT JOIN bed_inventory ON bed_assignments.bed_id = bed_inventory.bed_id LEFT JOIN patients ON bed_assignments.patient_id = patients.patient_id");
    $stmt->execute();
    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all beds
try {
    $stmt = $pdo->prepare("SELECT * FROM bed_inventory");
    $stmt->execute();
    $beds = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all patients
try {
    $stmt = $pdo->prepare("SELECT * FROM patients");
    $stmt->execute();
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bed Assignments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="relative min-h-screen max-h-screen overflow-auto flex flex-col">
        <?php include("../../../../components/head.php"); ?>
        <header>
            <?php include("../../../../components/navbar.php"); ?>
        </header>
        <div class="flex flex-1">
            <aside class="w-64">
                <?php include("../../../../components/sidebar2.php"); ?>
            </aside>
            <div class="container mt-5">
                <h1 class="mb-4">Bed Assignments</h1>

                <!-- Add Bed Assignment Form -->
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="bed_id" class="form-control" required>
                                <option value="" disabled selected>Select Bed</option>
                                <?php foreach ($beds as $bed): ?>
                                    <option value="<?= $bed['bed_id'] ?>"><?= htmlspecialchars($bed['bed_number']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            
                            <select name="patient_id" class="form-control" required>
                                <option value="" disabled selected>Select Patient</option>
                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?= $patient['patient_id'] ?>"><?= htmlspecialchars($patient['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="datetime-local" name="assigned_date" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <input type="datetime-local" name="discharge_date" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Discharged">Discharged</option>
                                <option value="Transferred">Transferred</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="notes" class="form-control" placeholder="Notes">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>

                <!-- Bed Assignments Table -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bed Number</th>
                            <th>Patient Name</th>
                            <th>Assigned Date</th>
                            <th>Discharge Date</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $assignment): ?>
                            <tr>
                                <td><?= $assignment['assignment_id'] ?></td>
                                <td><?= htmlspecialchars($assignment['bed_number']) ?></td>
                                <td><?= htmlspecialchars($assignment['patient_name']) ?></td>
                                <td><?= $assignment['assigned_date'] ?></td>
                                <td><?= $assignment['discharge_date'] ?></td>
                                <td><?= htmlspecialchars($assignment['status']) ?></td>
                                <td><?= htmlspecialchars($assignment['notes']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editAssignment(<?= $assignment['assignment_id'] ?>, <?= $assignment['bed_id'] ?>, <?= $assignment['patient_id'] ?>, '<?= $assignment['assigned_date'] ?>', '<?= $assignment['discharge_date'] ?>', '<?= $assignment['status'] ?>', '<?= htmlspecialchars($assignment['notes'], ENT_QUOTES) ?>')">Edit</button>
                                    <a href="?delete_id=<?= $assignment['assignment_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this assignment?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Bed Assignment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="assignment_id" id="edit-assignment-id">
                                <input type="hidden" name="action" value="edit">
                                <div class="mb-3">
                                    <label for="edit-bed-id" class="form-label">Bed</label>
                                    <select name="bed_id" id="edit-bed-id" class="form-control" required>
                                        <?php foreach ($beds as $bed): ?>
                                            <option value="<?= $bed['bed_id'] ?>"><?= htmlspecialchars($bed['bed_number']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-patient-id" class="form-label">Patient</label>
                                    <select name="patient_id" id="edit-patient-id" class="form-control" required>
                                        <?php foreach ($patients as $patient): ?>
                                            <option value="<?= $patient['patient_id'] ?>"><?= htmlspecialchars($patient['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-assigned-date" class="form-label">Assigned Date</label>
                                    <input type="datetime-local" name="assigned_date" id="edit-assigned-date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-discharge-date" class="form-label">Discharge Date</label>
                                    <input type="datetime-local" name="discharge_date" id="edit-discharge-date" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="edit-status" class="form-label">Status</label>
                                    <select name="status" id="edit-status" class="form-control" required>
                                        <option value="Active">Active</option>
                                        <option value="Discharged">Discharged</option>
                                        <option value="Transferred">Transferred</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-notes" class="form-label">Notes</label>
                                    <input type="text" name="notes" id="edit-notes" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function editAssignment(id, bedId, patientId, assignedDate, dischargeDate, status, notes) {
                    document.getElementById('edit-assignment-id').value = id;
                    document.getElementById('edit-bed-id').value = bedId;
                    document.getElementById('edit-patient-id').value = patientId;
                    document.getElementById('edit-assigned-date').value = assignedDate;
                    document.getElementById('edit-discharge-date').value = dischargeDate;
                    document.getElementById('edit-status').value = status;
                    document.getElementById('edit-notes').value = notes;

                    new bootstrap.Modal(document.getElementById('editModal')).show();
                }
            </script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>