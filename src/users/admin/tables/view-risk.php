<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding or editing a risk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $probability = $_POST['probability'];
    $impact = $_POST['impact'];
    $mitigation_plan = $_POST['mitigation_plan'];
    $project_id = $_POST['project_id'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO risks (name, description, probability, impact, mitigation_plan, project_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $probability, $impact, $mitigation_plan, $project_id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE risks SET name = ?, description = ?, probability = ?, impact = ?, mitigation_plan = ?, project_id = ? WHERE id = ?");
            $stmt->execute([$name, $description, $probability, $impact, $mitigation_plan, $project_id, $_POST['risk_id']]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Load a risk for editing
if (isset($_GET['edit_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM risks WHERE id = ?");
        $stmt->execute([$_GET['edit_id']]);
        $riskToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a risk
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Convert to integer for safety

    try {
        $stmt = $pdo->prepare("DELETE FROM risks WHERE id = ?");
        $stmt->execute([$delete_id]);

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Delete Error: " . $e->getMessage());
    }
}

// Fetch all risks
try {
    $stmt = $pdo->prepare("SELECT r.*, p.name AS project_name FROM risks r JOIN project p ON r.project_id = p.id");
    $stmt->execute();
    $risks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch projects for the dropdown
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
    <title>Risk Management</title>
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

                <h1>Risk Management</h1>

                <!-- Add Risk Form -->
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="name" class="form-control" placeholder="Risk Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="description" class="form-control" placeholder="Description" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="probability" class="form-control" placeholder="Probability" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="impact" class="form-control" placeholder="Impact" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="mitigation_plan" class="form-control" placeholder="Mitigation Plan" required>
                        </div>
                        <div class="col-md-2">
                            <select name="project_id" class="form-control" required>
                                <option value="" disabled selected>Select Project</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Add Risk</button>
                        </div>
                    </div>
                </form>

                <!-- Risks Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Probability</th>
                            <th>Impact</th>
                            <th>Mitigation Plan</th>
                            <th>Project</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($risks as $risk): ?>
                            <tr>
                                <td><?= htmlspecialchars($risk['id']) ?></td>
                                <td><?= htmlspecialchars($risk['name']) ?></td>
                                <td><?= htmlspecialchars($risk['description']) ?></td>
                                <td><?= htmlspecialchars($risk['probability']) ?></td>
                                <td><?= htmlspecialchars($risk['impact']) ?></td>
                                <td><?= htmlspecialchars($risk['mitigation_plan']) ?></td>
                                <td><?= htmlspecialchars($risk['project_name']) ?></td>
                                <td>
                                    <a href="#"
                                        class="btn btn-warning btn-sm"
                                        onclick="editRisk(
                        <?= $risk['id'] ?>,
                        '<?= addslashes($risk['name']) ?>',
                        '<?= addslashes($risk['description']) ?>',
                        '<?= addslashes($risk['probability']) ?>',
                        '<?= addslashes($risk['impact']) ?>',
                        '<?= addslashes($risk['mitigation_plan']) ?>',
                        <?= $risk['project_id'] ?>
                    )">Edit</a>
                                    <a href="?delete_id=<?= $risk['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>

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
                        <h5 class="modal-title" id="editModalLabel">Edit Risk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="risk_id" id="edit-id">
                        <input type="hidden" name="action" value="edit">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Risk Name</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-description" class="form-label">Description</label>
                            <input type="text" name="description" id="edit-description" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-probability" class="form-label">Probability</label>
                            <input type="text" name="probability" id="edit-probability" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-impact" class="form-label">Impact</label>
                            <input type="text" name="impact" id="edit-impact" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-mitigation_plan" class="form-label">Mitigation Plan</label>
                            <input type="text" name="mitigation_plan" id="edit-mitigation_plan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-project_id" class="form-label">Project</label>
                            <select name="project_id" id="edit-project_id" class="form-control" required>
                                <option value="" disabled>Select Project</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
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
            function editRisk(id, name, description, probability, impact, mitigation_plan, project_id) {
                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-name').value = name;
                document.getElementById('edit-description').value = description;
                document.getElementById('edit-probability').value = probability;
                document.getElementById('edit-impact').value = impact;
                document.getElementById('edit-mitigation_plan').value = mitigation_plan;
                document.getElementById('edit-project_id').value = project_id;
                modal.show();
            }
        </script>
</body>

</html>