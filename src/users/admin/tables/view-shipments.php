<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Fetch warehouse data for dropdown (still needed for form)
try {
    $warehousetmt = $pdo->prepare("SELECT warehouse_id, warehouse_id FROM warehouse");
    $warehousetmt->execute();
    $warehouse = $warehousetmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Handle form submission for adding/editing shipment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $shipment_id = $_POST['shipment_id'] ?? null;
    $shipment_date = $_POST['shipment_date'] ?? null;
    $status = $_POST['status'] ?? null;
    $type = $_POST['type'] ?? null;
    $warehouse_id = $_POST['warehouse_id'] ?? null;

    if (!$shipment_date || !$status || !$type || !$warehouse_id) {
        die("Shipment date, status, type, and warehouse are required.");
    }

    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO shipment (shipment_date, status, type, warehouse_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$shipment_date, $status, $type, $warehouse_id]);
        } elseif ($_POST['action'] === 'edit') {
            if (!$shipment_id) {
                die("Invalid shipment ID.");
            }
            $stmt = $pdo->prepare("UPDATE shipment SET shipment_date = ?, status = ?, type = ?, warehouse_id = ? WHERE shipment_id = ?");
            $stmt->execute([$shipment_date, $status, $type, $warehouse_id, $shipment_id]);
        }
        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a shipment record
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM shipment WHERE shipment_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all shipment records WITHOUT warehouse_id join
try {
    $stmt = $pdo->prepare("SELECT * FROM shipment ORDER BY shipment_date DESC");
    $stmt->execute();
    $shipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shipment Tracking</title>
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
                <h1 class="text-3xl font-bold mb-10">Shipment Tracking</h1>

                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addShipmentModal">Add Shipment</button>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Shipment Date</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Warehouse ID</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shipments as $shipment): ?>
                            <tr>
                                <td><?= htmlspecialchars($shipment['shipment_id']) ?></td>
                                <td><?= htmlspecialchars($shipment['shipment_date']) ?></td>
                                <td><?= htmlspecialchars($shipment['status']) ?></td>
                                <td><?= htmlspecialchars($shipment['type']) ?></td>
                                <td><?= htmlspecialchars($shipment['warehouse_id']) ?></td>
                                <td><?= htmlspecialchars($shipment['created_at']) ?></td>
                                <td><?= htmlspecialchars($shipment['updated_at']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        onclick="openEditModal(
                                            <?= htmlspecialchars($shipment['shipment_id']) ?>,
                                            '<?= htmlspecialchars($shipment['shipment_date']) ?>',
                                            '<?= addslashes(htmlspecialchars($shipment['status'])) ?>',
                                            '<?= addslashes(htmlspecialchars($shipment['type'])) ?>',
                                            <?= htmlspecialchars($shipment['warehouse_id']) ?>
                                        )">Edit</button>
                                    <a href="?delete_id=<?= htmlspecialchars($shipment['shipment_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this shipment?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Add Shipment Modal -->
                <div class="modal fade" id="addShipmentModal" tabindex="-1" aria-labelledby="addShipmentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addShipmentModalLabel">Add Shipment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="add">

                                <div class="mb-3">
                                    <label for="add-shipment-date" class="form-label">Shipment Date</label>
                                    <input type="date" name="shipment_date" id="add-shipment-date" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="add-status" class="form-label">Status</label>
                                    <select name="status" id="add-status" class="form-control" required>
                                        <option value="">-- Select Status --</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Shipped">Shipped</option>
                                        <option value="In Transit">In Transit</option>
                                        <option value="Delivered">Delivered</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="add-type" class="form-label">Type</label>
                                    <input type="text" name="type" id="add-type" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="add-warehouse-id" class="form-label">Warehouse</label>
                                    <select name="warehouse_id" id="add-warehouse-id" class="form-control" required>
                                        <option value="">-- Select Warehouse --</option>
                                        <?php foreach ($warehouse as $wh): ?>
                                            <option value="<?= htmlspecialchars($wh['warehouse_id']) ?>"><?= htmlspecialchars($wh['location']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Shipment</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Edit Shipment Modal -->
                <div class="modal fade" id="editShipmentModal" tabindex="-1" aria-labelledby="editShipmentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editShipmentModalLabel">Edit Shipment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="shipment_id" id="edit-shipment-id">

                                <div class="mb-3">
                                    <label for="edit-shipment-date" class="form-label">Shipment Date</label>
                                    <input type="date" name="shipment_date" id="edit-shipment-date" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-status" class="form-label">Status</label>
                                    <select name="status" id="edit-status" class="form-control" required>
                                        <option value="">-- Select Status --</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Shipped">Shipped</option>
                                        <option value="In Transit">In Transit</option>
                                        <option value="Delivered">Delivered</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-type" class="form-label">Type</label>
                                    <input type="text" name="type" id="edit-type" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-warehouse-id" class="form-label">Warehouse</label>
                                    <select name="warehouse_id" id="edit-warehouse-id" class="form-control" required>
                                        <option value="">-- Select Warehouse --</option>
                                        <?php foreach ($warehouse as $wh): ?>
                                            <option value="<?= htmlspecialchars($wh['warehouse_id']) ?>"><?= htmlspecialchars($wh['location']) ?></option>
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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fill edit modal form fields and show modal
        function openEditModal(id, date, status, type, warehouseId) {
            const modal = new bootstrap.Modal(document.getElementById('editShipmentModal'));
            document.getElementById('edit-shipment-id').value = id;
            document.getElementById('edit-shipment-date').value = date;
            document.getElementById('edit-status').value = status;
            document.getElementById('edit-type').value = type;
            document.getElementById('edit-warehouse-id').value = warehouseId;
            modal.show();
        }
    </script>
</body>

</html>
