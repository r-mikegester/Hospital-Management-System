<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// DELETE HANDLER
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM resources WHERE resources_id = ?");
        $stmt->execute([$delete_id]);

        // Redirect to the same page without the query string to prevent resubmission
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit;
    } catch (PDOException $e) {
        die("Error deleting resource: " . $e->getMessage());
    }
}

// Handle form submission for adding or editing a resource
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $availability = $_POST['availability'];
    $quantity = $_POST['quantity'];
    $project_id = $_POST['project_id'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO resources (name, type, availability, quantity, project_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $type, $availability, $quantity, $project_id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        try {
            $stmt = $pdo->prepare("UPDATE resources SET name = ?, type = ?, availability = ?, quantity = ?, project_id = ? WHERE id = ?");
            $stmt->execute([$name, $type, $availability, $quantity, $project_id, $_POST['resource_id']]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }


    if (isset($_GET['edit_id'])) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM resources WHERE id = ?");
            $stmt->execute([$_GET['edit_id']]);
            $resourceToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Fetch resources
try {
    $stmt = $pdo->prepare("SELECT * FROM resources");
    $stmt->execute();
    $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch projects
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
    <title>Resource Management</title>
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
                <h1 class="text-3xl font-bold mb-10">Resource Management</h1>

                <!-- Add Resource Form -->
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="name" class="form-control" placeholder="Resource Name" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="type" class="form-control" placeholder="Type" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="availability" class="form-control" placeholder="Availability" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                        </div>
                        <div class="col-md-2">
                            <select name="project_id" class="form-control" required>
                                <option value="" disabled selected>Select Project</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Add Resource</button>
                        </div>
                    </div>
                </form>
                <!-- Resources Table -->
                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Availability</th>
                            <th>Quantity</th>
                            <th>Project</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resources as $resource): ?>
                            <tr>
                                <td><?= $resource['resources_id'] ?></td>
                                <td><?= htmlspecialchars($resource['name']) ?></td>
                                <td><?= htmlspecialchars($resource['type']) ?></td>
                                <td><?= htmlspecialchars($resource['availability']) ?></td>
                                <td><?= $resource['quantity'] ?></td>
                                <td><?= htmlspecialchars($resource['project_id']) ?></td>
                                <td>
                                    <a href="#"
                                        class="btn btn-warning btn-sm"
                                        onclick="editResource(
                                        <?= $resource['resources_id'] ?>,
                                        '<?= htmlspecialchars($resource['name'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($resource['type'], ENT_QUOTES) ?>',
                                        '<?= htmlspecialchars($resource['availability'], ENT_QUOTES) ?>',
                                        <?= $resource['quantity'] ?>,
                                        <?= $resource['project_id'] ?>
                                        )">
                                        Edit
                                    </a>
                                    <a href="?delete_id=<?= $resource['resources_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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
                        <h5 class="modal-title" id="editModalLabel">Edit Resource</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="resource_id" id="edit-id">
                        <input type="hidden" name="action" value="edit">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Name</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-type" class="form-label">Type</label>
                            <input type="text" name="type" id="edit-type" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-availability" class="form-label">Availability</label>
                            <input type="text" name="availability" id="edit-availability" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="edit-quantity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-project_id" class="form-label">Project</label>
                            <select name="project_id" id="edit-project_id" class="form-control" required>
                                <option value="" disabled>Select Project</option>
                                <?php foreach ($projects as $project): ?>
                                    <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
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
            function editResource(resource_id, name, type, availability, quantity, project_id) {
                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                document.getElementById('edit-id').value = resource_id;
                document.getElementById('edit-name').value = name;
                document.getElementById('edit-type').value = type;
                document.getElementById('edit-availability').value = availability;
                document.getElementById('edit-quantity').value = quantity;
                document.getElementById('edit-project_id').value = project_id;
                modal.show();
            }
        </script>
</body>

</html>
