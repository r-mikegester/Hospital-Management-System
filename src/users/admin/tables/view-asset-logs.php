<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submissions for adding, editing, or deleting assets
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $asset_name = $_POST['asset_name'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $acquisition_date = $_POST['acquisition_date'];
    $location = $_POST['location'];
    $warehouse_id = $_POST['warehouse_id'];
    $project_status = $_POST['project_status'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO asset (asset_name, category, status, acquisition_date, location, warehouse_id, project_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$asset_name, $category, $status, $acquisition_date, $location, $warehouse_id, $project_status]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        try {
            $stmt = $pdo->prepare("UPDATE asset SET asset_name = ?, category = ?, status = ?, acquisition_date = ?, location = ?, warehouse_id = ?, project_status = ? WHERE asset_id = ?");
            $stmt->execute([$asset_name, $category, $status, $acquisition_date, $location, $warehouse_id, $project_status, $id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of an asset
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM asset WHERE asset_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all assets
try {
    $stmt = $pdo->prepare("SELECT asset.*, warehouse.location AS warehouse_location FROM asset LEFT JOIN warehouse ON asset.warehouse_id = warehouse.warehouse_id");
    $stmt->execute();
    $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all warehouses
try {
    $stmt = $pdo->prepare("SELECT * FROM warehouse");
    $stmt->execute();
    $warehouses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Assets</title>
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

            <div class="flex-1 mt-20 p-6 overflow-auto h-full">
                <h1 class="text-3xl font-bold mb-10">Asset Logs</h1>

        

        <!-- Assets Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Acquisition Date</th>
                    <th>Location</th>
                    <th>Warehouse</th>
                    <th>Project Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assets as $asset): ?>
                    <tr>
                        <td><?= $asset['asset_id'] ?></td>
                        <td><?= htmlspecialchars($asset['asset_name']) ?></td>
                        <td><?= htmlspecialchars($asset['category']) ?></td>
                        <td><?= htmlspecialchars($asset['status']) ?></td>
                        <td><?= htmlspecialchars($asset['acquisition_date']) ?></td>
                        <td><?= htmlspecialchars($asset['location']) ?></td>
                        <td><?= htmlspecialchars($asset['warehouse_location']) ?></td>
                        <td><?= htmlspecialchars($asset['project_status']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editAsset(<?= $asset['asset_id'] ?>, '<?= htmlspecialchars($asset['asset_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($asset['category'], ENT_QUOTES) ?>', '<?= htmlspecialchars($asset['status'], ENT_QUOTES) ?>', '<?= $asset['acquisition_date'] ?>', '<?= htmlspecialchars($asset['location'], ENT_QUOTES) ?>', <?= $asset['warehouse_id'] ?>, '<?= htmlspecialchars($asset['project_status'], ENT_QUOTES) ?>')">Edit</button>
                            <a href="?delete_id=<?= $asset['asset_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this asset?')">Delete</a>
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
                        <h5 class="modal-title" id="editModalLabel">Edit Asset</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <input type="hidden" name="action" value="edit">
                        <div class="mb-3">
                            <label for="edit-name" class="form-label">Asset Name</label>
                            <input type="text" name="asset_name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-category" class="form-label">Category</label>
                            <input type="text" name="category" id="edit-category" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-status" class="form-label">Status</label>
                            <select name="status" id="edit-status" class="form-control" required>
                                <option value="Available">Available</option>
                                <option value="In Use">In Use</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-acquisition-date" class="form-label">Acquisition Date</label>
                            <input type="date" name="acquisition_date" id="edit-acquisition-date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-location" class="form-label">Location</label>
                            <input type="text" name="location" id="edit-location" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-warehouse-id" class="form-label">Warehouse</label>
                            <select name="warehouse_id" id="edit-warehouse-id" class="form-control" required>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id'] ?>"><?= htmlspecialchars($warehouse['location']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-project-status" class="form-label">Project Status</label>
                            <select name="project_status" id="edit-project-status" class="form-control" required>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editAsset(id, name, category, status, acquisitionDate, location, warehouseId, projectStatus) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-category').value = category;
            document.getElementById('edit-status').value = status;
            document.getElementById('edit-acquisition-date').value = acquisitionDate;
            document.getElementById('edit-location').value = location;
            document.getElementById('edit-warehouse-id').value = warehouseId;
            document.getElementById('edit-project-status').value = projectStatus;

            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }
    </script>
</body>

</html>
