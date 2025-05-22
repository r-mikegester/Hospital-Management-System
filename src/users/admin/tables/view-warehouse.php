<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding a warehouse
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $location = $_POST['location'];
    $capacity = $_POST['capacity'];
    $manager_id = $_POST['manager_id'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO warehouse (location, capacity, manager_id) VALUES (?, ?, ?)");
            $stmt->execute([$location, $capacity, $manager_id]);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Handle editing a warehouse
    if ($_POST['action'] === 'edit') {
        $warehouse_id = $_POST['warehouse_id'];
        try {
            $stmt = $pdo->prepare("UPDATE warehouse SET location = ?, capacity = ?, manager_id = ? WHERE warehouse_id = ?");
            $stmt->execute([$location, $capacity, $manager_id, $warehouse_id]);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a warehouse
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM warehouse WHERE warehouse_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all managers for the dropdown
try {
    $stmt = $pdo->prepare("SELECT manager_id, manager_name FROM managers");
    $stmt->execute();
    $managers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all warehouse with manager names using JOIN
try {
    $stmt = $pdo->prepare("SELECT w.*, m.manager_name FROM warehouse w JOIN managers m ON w.manager_id = m.manager_id");
    $stmt->execute();
    $warehouse = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Warehouse List</title>
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
                <h1 class="text-3xl font-bold mb-10">Warehouse List</h1>
                <div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Warehouse ID</th>
                                <th>Location</th>
                                <th>Capacity</th>
                                <th>Manager Name</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($warehouse as $warehouse): ?>
                                <tr>
                                    <td><?= $warehouse['warehouse_id'] ?></td>
                                    <td><?= htmlspecialchars($warehouse['location']) ?></td>
                                    <td><?= $warehouse['capacity'] ?></td>
                                    <td><?= htmlspecialchars($warehouse['manager_name']) ?></td>
                                    <td><?= $warehouse['created_at'] ?></td>
                                    <td><?= $warehouse['updated_at'] ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" onclick="editWarehouse(
                                            <?= $warehouse['warehouse_id'] ?>,
                                            '<?= htmlspecialchars($warehouse['location']) ?>',
                                            <?= $warehouse['capacity'] ?>,
                                            <?= $warehouse['manager_id'] ?>
                                        )">Edit</button>

                                        <!-- Delete Button -->
                                        <a href="?delete_id=<?= $warehouse['warehouse_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this warehouse?')">Delete</a>
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
                            <h5 class="modal-title" id="editModalLabel">Edit Warehouse</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="warehouse_id" id="edit-warehouse-id">
                            <input type="hidden" name="action" value="edit">

                            <div class="mb-3">
                                <label for="edit-location" class="form-label">Location</label>
                                <input type="text" name="location" id="edit-location" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-capacity" class="form-label">Capacity</label>
                                <input type="number" name="capacity" id="edit-capacity" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-manager-id" class="form-label">Manager</label>
                                <select name="manager_id" id="edit-manager-id" class="form-select" required>
                                    <?php foreach ($managers as $manager): ?>
                                        <option value="<?= htmlspecialchars($manager['manager_id']) ?>">
                                            <?= htmlspecialchars($manager['manager_name']) ?>
                                        </option>
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
                function editWarehouse(warehouseId, location, capacity, managerId) {
                    const modal = new bootstrap.Modal(document.getElementById('editModal'));
                    document.getElementById('edit-warehouse-id').value = warehouseId;
                    document.getElementById('edit-location').value = location;
                    document.getElementById('edit-capacity').value = capacity;
                    document.getElementById('edit-manager-id').value = managerId;
                    modal.show();
                }
            </script>
        </div>
</body>

</html>
