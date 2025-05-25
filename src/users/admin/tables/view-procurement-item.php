<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding/editing procurement item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $procurement_id = $_POST['procurement_id'] ?? null;
    if (!$procurement_id) {
        die("Please select a procurement.");
    }

    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO procurement_item (procurement_id, item_name, quantity, unit_price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$procurement_id, $item_name, $quantity, $unit_price]);
        } elseif ($_POST['action'] === 'edit') {
            $item_id = $_POST['item_id'];
            $stmt = $pdo->prepare("UPDATE procurement_item SET procurement_id = ?, item_name = ?, quantity = ?, unit_price = ? WHERE item_id = ?");
            $stmt->execute([$procurement_id, $item_name, $quantity, $unit_price, $item_id]);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a procurement item
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM procurement_item WHERE item_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch procurements for dropdown
try {
    $stmt = $pdo->prepare("SELECT procurement_id FROM procurement");
    $stmt->execute();
    $procurements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all procurement items
try {
    $stmt = $pdo->prepare("SELECT * FROM procurement_item");
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Procurement Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .table {
            border: 1px solid #dee2e6;
        }

        .table td,
        .table th {
            text-align: center;
            vertical-align: middle;
        }

        .btn-close {
            color: #fff;
        }
    </style>
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

            <main class="flex-1 mt-20 p-6 overflow-auto">
                <h1 class="text-3xl font-bold mb-4">Procurement Items</h1>

                <!-- Add Item Button -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Procurement Item</button>

                <!-- Items Table -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Procurement ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= $item['item_id'] ?></td>
                                <td><?= $item['procurement_id'] ?></td>
                                <td><?= htmlspecialchars($item['item_name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['unit_price'], 2) ?></td>
                                <td><?= number_format($item['quantity'] * $item['unit_price'], 2) ?></td>
                                <td><?= $item['created_at'] ?></td>
                                <td><?= $item['updated_at'] ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        onclick="openEditModal(
                                            <?= $item['item_id'] ?>,
                                            <?= $item['procurement_id'] ?>,
                                            '<?= htmlspecialchars(addslashes($item['item_name'])) ?>',
                                            <?= $item['quantity'] ?>,
                                            <?= $item['unit_price'] ?>
                                        )">Edit</button>

                                    <a href="?delete_id=<?= $item['item_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Add Item Modal -->
                <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addItemModalLabel">Add Procurement Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="add">

                                <!-- Procurement select -->
                                <div class="mb-3">
                                    <label for="add-procurement-id" class="form-label">Select Procurement</label>
                                    <select name="procurement_id" id="add-procurement-id" class="form-control" required>
                                        <option value="">-- Select Procurement --</option>
                                        <?php foreach ($procurements as $proc): ?>
                                            <option value="<?= $proc['procurement_id'] ?>"><?= $proc['procurement_id'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="add-item-name" class="form-label">Item Name</label>
                                    <input type="text" name="item_name" id="add-item-name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="add-quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="add-quantity" class="form-control" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label for="add-unit-price" class="form-label">Unit Price</label>
                                    <input type="number" step="0.01" name="unit_price" id="add-unit-price" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Item</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Edit Item Modal -->
                <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editItemModalLabel">Edit Procurement Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="item_id" id="edit-item-id">

                                <!-- Procurement select -->
                                <div class="mb-3">
                                    <label for="edit-procurement-id" class="form-label">Select Procurement</label>
                                    <select name="procurement_id" id="edit-procurement-id" class="form-control" required>
                                        <option value="">-- Select Procurement --</option>
                                        <?php foreach ($procurements as $proc): ?>
                                            <option value="<?= $proc['procurement_id'] ?>"><?= $proc['procurement_id'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-item-name" class="form-label">Item Name</label>
                                    <input type="text" name="item_name" id="edit-item-name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="edit-quantity" class="form-control" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-unit-price" class="form-label">Unit Price</label>
                                    <input type="number" step="0.01" name="unit_price" id="edit-unit-price" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function openEditModal(itemId, procurementId, itemName, quantity, unitPrice) {
                const modal = new bootstrap.Modal(document.getElementById('editItemModal'));
                document.getElementById('edit-item-id').value = itemId;

                // Set procurement_id in the select dropdown
                document.getElementById('edit-procurement-id').value = procurementId;

                document.getElementById('edit-item-name').value = itemName;
                document.getElementById('edit-quantity').value = quantity;
                document.getElementById('edit-unit-price').value = unitPrice;
                modal.show();
            }
        </script>
    </div>
</body>

</html>
