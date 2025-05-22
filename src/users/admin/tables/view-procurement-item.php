<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Hospital-Management-System/config/config.php');

// Handle form submission for adding a procurement item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $procurement_id = $_POST['procurement_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO procurement_item (procurement_id, item_name, quantity, unit_price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$procurement_id, $item_name, $quantity, $unit_price]);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Handle editing a procurement item
    if ($_POST['action'] === 'edit') {
        $item_id = $_POST['item_id'];
        try {
            $stmt = $pdo->prepare("UPDATE procurement_item SET procurement_id = ?, item_name = ?, quantity = ?, unit_price = ? WHERE item_id = ?");
            $stmt->execute([$procurement_id, $item_name, $quantity, $unit_price, $item_id]);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a procurement item
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM procurement_item WHERE item_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
// Fetch all procurements for the dropdown
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
                <h1 class="text-3xl font-bold mb-10">Procurement Items</h1>
                <div>
                    <!-- Add Procurement Item Form -->
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-3">
                            <!-- Remove procurement_id select -->
                            <div class="col-md-4">
                                <input type="text" name="item_name" class="form-control" placeholder="Item Name" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="Unit Price" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Add Item</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
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
                                    <td><?= $item['item_name'] ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= $item['unit_price'] ?></td>
                                    <td><?= $item['quantity'] * $item['unit_price'] ?></td>
                                    <td><?= $item['created_at'] ?></td>
                                    <td><?= $item['updated_at'] ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm " onclick="editItem(<?= $item['item_id'] ?>, <?= $item['procurement_id'] ?>, '<?= htmlspecialchars($item['item_name']) ?>', <?= $item['quantity'] ?>, <?= $item['unit_price'] ?>)">Edit</button>

                                        <!-- Delete Button -->
                                        <a href="?delete_id=<?= $item['item_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
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
                            <h5 class="modal-title" id="editModalLabel">Edit Procurement Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="item_id" id="edit-item-id">
                            <input type="hidden" name="action" value="edit">
                            <div class="mb-3">
                                <label for="edit-procurement-id" class="form-label">Procurement ID</label>
                                <input type="number" name="procurement_id" id="edit-procurement-id" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="edit-item-name" class="form-label">Item Name</label>
                                <input type="text" name="item_name" id="edit-item-name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="edit-quantity" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-unit-price" class="form-label">Unit Price</label>
                                <input type="number" step="0.01" name="unit_price" id="edit-unit-price" class="form-control" required>
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
                function editItem(itemId, procurementId, itemName, quantity, unitPrice) {
                    const modal = new bootstrap.Modal(document.getElementById('editModal'));
                    document.getElementById('edit-item-id').value = itemId;
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