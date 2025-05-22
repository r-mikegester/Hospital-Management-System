<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding or editing a progress report
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $project_id = $_POST['project_id'];
    $submission_date = $_POST['submission_date'];
    $status = $_POST['status'];
    $details = $_POST['details'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO progressreport (project_id, submission_date, status, details) VALUES (?, ?, ?, ?)");
            $stmt->execute([$project_id, $submission_date, $status, $details]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE progressreport SET project_id = ?, submission_date = ?, status = ?, details = ? WHERE id = ?");
            $stmt->execute([$project_id, $submission_date, $status, $details, $_POST['report_id']]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Load a progress report for editing
if (isset($_GET['edit_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM progressreport WHERE id = ?");
        $stmt->execute([$_GET['edit_id']]);
        $reportToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a progress report
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM progressreport WHERE id = ?");
        $stmt->execute([$delete_id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all progress reports
try {
    $stmt = $pdo->prepare("SELECT pr.*, p.name AS project_name FROM progressreport pr JOIN project p ON pr.project_id = p.id");
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch projects for dropdown
try {
    $stmt = $pdo->prepare("SELECT * FROM project");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Progress Report Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <h1>Progress Report Management</h1>

                <!-- Add Progress Report Form -->
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="project_id" class="form-control" required>
                                <option value="" disabled selected>Select Project</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="submission_date" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="status" class="form-control" placeholder="Status" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="details" class="form-control" placeholder="Details" required>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Add Report</button>
                        </div>
                    </div>
                </form>

                <!-- Progress Reports Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?= $report['id'] ?></td>
                                <td><?= htmlspecialchars($report['project_name']) ?></td>
                                <td><?= htmlspecialchars($report['submission_date']) ?></td>
                                <td><?= htmlspecialchars($report['status']) ?></td>
                                <td><?= htmlspecialchars($report['details']) ?></td>
                                <td>
                                    <a href="#"
                                        class="btn btn-warning btn-sm"
                                        onclick="editReport(
                            <?= $report['id'] ?>,
                            <?= $report['project_id'] ?>,
                            '<?= $report['submission_date'] ?>',
                            '<?= addslashes($report['status']) ?>',
                            '<?= addslashes($report['details']) ?>'
                        )">Edit</a>
                                    <a href="?delete_id=<?= $report['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Progress Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="report_id" id="edit-id">
                        <input type="hidden" name="action" value="edit">
                        <div class="mb-3">
                            <label for="edit-project_id" class="form-label">Project</label>
                            <select name="project_id" id="edit-project_id" class="form-control" required>
                                <option value="" disabled>Select Project</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-submission_date" class="form-label">Submission Date</label>
                            <input type="date" name="submission_date" id="edit-submission_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-status" class="form-label">Status</label>
                            <input type="text" name="status" id="edit-status" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-details" class="form-label">Details</label>
                            <input type="text" name="details" id="edit-details" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function editReport(id, project_id, submission_date, status, details) {
                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-project_id').value = project_id;
                document.getElementById('edit-submission_date').value = submission_date;
                document.getElementById('edit-status').value = status;
                document.getElementById('edit-details').value = details;
                modal.show();
            }
        </script>
</body>

</html>