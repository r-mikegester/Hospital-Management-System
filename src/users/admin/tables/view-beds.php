<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submissions for adding, editing, or deleting beds
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $bed_number = $_POST['bed_number'];
    $room_number = $_POST['room_number'] ?: null; // nullable
    $status = $_POST['status'];
    $condition = $_POST['condition'];
    $warehouse_id = $_POST['warehouse_id'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO bed_inventory (bed_number, room_number, status, `condition`, warehouse_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$bed_number, $room_number, $status, $condition, $warehouse_id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error adding bed: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        try {
            $stmt = $pdo->prepare("UPDATE bed_inventory SET bed_number = ?, room_number = ?, status = ?, `condition` = ?, warehouse_id = ? WHERE bed_id = ?");
            $stmt->execute([$bed_number, $room_number, $status, $condition, $warehouse_id, $id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error updating bed: " . $e->getMessage());
        }
    }
}

// Handle deletion of a bed
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM bed_inventory WHERE bed_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error deleting bed: " . $e->getMessage());
    }
}

// Fetch all beds with warehouse location
try {
    $stmt = $pdo->prepare("SELECT bed_inventory.*, warehouse.location AS warehouse_location FROM bed_inventory LEFT JOIN warehouse ON bed_inventory.warehouse_id = warehouse.warehouse_id");
    $stmt->execute();
    $beds = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching beds: " . $e->getMessage());
}

// Fetch all warehouses
try {
    $stmt = $pdo->prepare("SELECT * FROM warehouse");
    $stmt->execute();
    $warehouses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching warehouses: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bed Inventory</title>
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
            <div class="container my-5">
                <h1 class="mb-4">Bed Inventory</h1>

                <!-- Add Bed Form -->
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="bed_number" class="form-control" placeholder="Bed Number" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="room_number" class="form-control" placeholder="Room Number (optional)">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control" required>
                                <option value="Available">Available</option>
                                <option value="Allocated">Allocated</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Discarded">Discarded</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="condition" class="form-control" required>
                                <option value="New">New</option>
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="warehouse_id" class="form-control" required>
                                <option value="" disabled selected>Select Warehouse</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id'] ?>"><?= htmlspecialchars($warehouse['location']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Add Bed</button>
                        </div>
                    </div>
                </form>

                <!-- Bed Inventory Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bed ID</th>
                            <th>Bed Number</th>
                            <th>Room Number</th>
                            <th>Status</th>
                            <th>Condition</th>
                            <th>Warehouse</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($beds as $bed): ?>
                            <tr>
                                <td><?= $bed['bed_id'] ?></td>
                                <td><?= htmlspecialchars($bed['bed_number']) ?></td>
                                <td><?= htmlspecialchars($bed['room_number']) ?></td>
                                <td><?= htmlspecialchars($bed['status']) ?></td>
                                <td><?= htmlspecialchars($bed['condition']) ?></td>
                                <td><?= htmlspecialchars($bed['warehouse_location']) ?></td>
                                <td><?= htmlspecialchars($bed['created_at']) ?></td>
                                <td><?= htmlspecialchars($bed['updated_at']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editBed(<?= $bed['bed_id'] ?>, '<?= htmlspecialchars($bed['bed_number'], ENT_QUOTES) ?>', '<?= htmlspecialchars($bed['room_number'], ENT_QUOTES) ?>', '<?= $bed['status'] ?>', '<?= $bed['condition'] ?>', <?= $bed['warehouse_id'] ?>)">Edit</button>
                                    <a href="?delete_id=<?= $bed['bed_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this bed?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Bed</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <input type="hidden" name="action" value="edit">
                            <div class="mb-3">
                                <label for="edit-bed-number" class="form-label">Bed Number</label>
                                <input type="text" name="bed_number" id="edit-bed-number" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-room-number" class="form-label">Room Number</label>
                                <input type="text" name="room_number" id="edit-room-number" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="edit-status" class="form-label">Status</label>
                                <select name="status" id="edit-status" class="form-control" required>
                                    <option value="Available">Available</option>
                                    <option value="Allocated">Allocated</option>
                                    <option value="Maintenance">Maintenance</option>
                                    <option value="Discarded">Discarded</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit-condition" class="form-label">Condition</label>
                                <select name="condition" id="edit-condition" class="form-control" required>
                                    <option value="New">New</option>
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Poor">Poor</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit-warehouse-id" class="form-label">Warehouse</label>
                                <select name="warehouse_id" id="edit-warehouse-id" class="form-control" required>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['warehouse_id'] ?>"><?= htmlspecialchars($warehouse['location']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Bed</button>
                        </div>
                    </form>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function editBed(id, bedNumber, roomNumber, status, condition, warehouseId) {
                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-bed-number').value = bedNumber;
                    document.getElementById('edit-room-number').value = roomNumber;
                    document.getElementById('edit-status').value = status;
                    document.getElementById('edit-condition').value = condition;
                    document.getElementById('edit-warehouse-id').value = warehouseId;

                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                }
            </script>
</body>

</html>