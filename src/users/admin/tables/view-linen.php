<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submissions for adding, editing, or deleting linen items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $condition = $_POST['condition'];
    $status = $_POST['status'];
    $last_washed_date = !empty($_POST['last_washed_date']) ? $_POST['last_washed_date'] : null;
    $warehouse_id = $_POST['warehouse_id'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO linen_inventory (item_name, quantity, `condition`, status, last_washed_date, warehouse_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$item_name, $quantity, $condition, $status, $last_washed_date, $warehouse_id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        $linen_id = $_POST['linen_id'];
        try {
            $stmt = $pdo->prepare("UPDATE linen_inventory SET item_name = ?, quantity = ?, `condition` = ?, status = ?, last_washed_date = ?, warehouse_id = ? WHERE linen_id = ?");
            $stmt->execute([$item_name, $quantity, $condition, $status, $last_washed_date, $warehouse_id, $linen_id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a linen item
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM linen_inventory WHERE linen_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all linen inventory items with warehouse info
try {
    $stmt = $pdo->prepare("SELECT linen_inventory.*, warehouse.location AS warehouse_location FROM linen_inventory LEFT JOIN warehouse ON linen_inventory.warehouse_id = warehouse.warehouse_id");
    $stmt->execute();
    $linens = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Linen Inventory</title>
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
                <h1 class="mb-4">Linen Inventory</h1>

                <!-- Add Linen Form -->
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="item_name" class="form-control" placeholder="Item Name" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="quantity" class="form-control" placeholder="Quantity" min="0" required>
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
                            <select name="status" class="form-control" required>
                                <option value="Available">Available</option>
                                <option value="In Use">In Use</option>
                                <option value="In Laundry">In Laundry</option>
                                <option value="Discarded">Discarded</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="last_washed_date" class="form-control" placeholder="Last Washed Date">
                        </div>
                        <div class="col-md-3">
                            <select name="warehouse_id" class="form-control" required>
                                <option value="" disabled selected>Select Warehouse</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?= $warehouse['warehouse_id'] ?>"><?= htmlspecialchars($warehouse['location']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </form>

                <!-- Linen Inventory Table -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Condition</th>
                            <th>Status</th>
                            <th>Last Washed Date</th>
                            <th>Warehouse</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($linens as $linen): ?>
                            <tr>
                                <td><?= $linen['linen_id'] ?></td>
                                <td><?= htmlspecialchars($linen['item_name']) ?></td>
                                <td><?= $linen['quantity'] ?></td>
                                <td><?= htmlspecialchars($linen['condition']) ?></td>
                                <td><?= htmlspecialchars($linen['status']) ?></td>
                                <td><?= $linen['last_washed_date'] ? date('Y-m-d', strtotime($linen['last_washed_date'])) : '' ?></td>
                                <td><?= htmlspecialchars($linen['warehouse_location']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editLinen(<?= $linen['linen_id'] ?>, '<?= htmlspecialchars($linen['item_name'], ENT_QUOTES) ?>', <?= $linen['quantity'] ?>, '<?= $linen['condition'] ?>', '<?= $linen['status'] ?>', '<?= $linen['last_washed_date'] ? date('Y-m-d', strtotime($linen['last_washed_date'])) : '' ?>', <?= $linen['warehouse_id'] ?>)">Edit</button>
                                    <a href="?delete_id=<?= $linen['linen_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this linen item?')">Delete</a>
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
                                <h5 class="modal-title" id="editModalLabel">Edit Linen Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="linen_id" id="edit-linen-id">
                                <input type="hidden" name="action" value="edit">
                                <div class="mb-3">
                                    <label for="edit-item-name" class="form-label">Item Name</label>
                                    <input type="text" name="item_name" id="edit-item-name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="edit-quantity" class="form-control" min="0" required>
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
                                    <label for="edit-status" class="form-label">Status</label>
                                    <select name="status" id="edit-status" class="form-control" required>
                                        <option value="Available">Available</option>
                                        <option value="In Use">In Use</option>
                                        <option value="In Laundry">In Laundry</option>
                                        <option value="Discarded">Discarded</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-last-washed-date" class="form-label">Last Washed Date</label>
                                    <input type="date" name="last_washed_date" id="edit-last-washed-date" class="form-control">
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
                                <button type="submit" class="btn btn-primary">Update Linen Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function editLinen(id, itemName, quantity, condition, status, lastWashedDate, warehouseId) {
                    document.getElementById('edit-linen-id').value = id;
                    document.getElementById('edit-item-name').value = itemName;
                    document.getElementById('edit-quantity').value = quantity;
                    document.getElementById('edit-condition').value = condition;
                    document.getElementById('edit-status').value = status;
                    document.getElementById('edit-last-washed-date').value = lastWashedDate;
                    document.getElementById('edit-warehouse-id').value = warehouseId;

                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                }
            </script>
</body>

</html>