<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding or editing a purchase record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $bill_number = $_POST['bill_number'];
    $supplier = $_POST['supplier'];
    $purchase_date = $_POST['purchase_date'];
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];
    $total_price = $_POST['total_price'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("SELECT id, bill_number, su       pplier, purchase_date, item_id, item_name, quantity, unit_price, total_price FROM purchases ORDER BY purchase_date DESC");

            $stmt->execute([$bill_number, $supplier, $purchase_date, $item_id, $item_name, $quantity, $unit_price, $total_price]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        try {
            $stmt = $pdo->prepare("INSERT INTO purchases (bill_number, supplier, purchase_date, item_id, item_name, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$bill_number, $supplier, $purchase_date, $item_id, $item_name, $quantity, $unit_price, $total_price]);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a purchase record
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM purchases WHERE id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all purchase records
try {
    $stmt = $pdo->prepare("SELECT * FROM purchases ORDER BY purchase_date DESC");
    $stmt->execute();
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Purchases</title>
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
                <h1 class="text-3xl font-bold mb-10">Purchases</h1>
                <div>

                    <!-- Add Purchase Form -->
                    <form method="POST" class="mb-5">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <input type="text" name="bill_number" class="form-control" placeholder="Bill Number" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="supplier" class="form-control" placeholder="Supplier" required>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="purchase_date" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="item_id" class="form-control" placeholder="Item ID" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="item_name" class="form-control" placeholder="Item Name" required>
                            </div>
                            <div class="col-md-1">
                                <input type="number" name="quantity" class="form-control" placeholder="Qty" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="Unit Price" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="total_price" class="form-control" placeholder="Total Price" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Add Purchase</button>
                            </div>
                        </div>
                    </form>

                    <!-- Purchases Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bill Number</th>
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Item ID</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchases as $purchase): ?>
                                <tr>

                                    <td><?= htmlspecialchars($purchase['bill_number']) ?></td>
                                    <td><?= htmlspecialchars($purchase['supplier']) ?></td>
                                    <td><?= htmlspecialchars($purchase['purchase_date']) ?></td>
                                    <td><?= htmlspecialchars($purchase['item_id']) ?></td>
                                    <td><?= htmlspecialchars($purchase['item_name']) ?></td>
                                    <td><?= htmlspecialchars($purchase['quantity']) ?></td>
                                    <td><?= htmlspecialchars(number_format($purchase['unit_price'], 2)) ?></td>
                                    <td><?= htmlspecialchars(number_format($purchase['total_price'], 2)) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editPurchase(
                                
                                '<?= addslashes(htmlspecialchars($purchase['bill_number'])) ?>',
                                '<?= addslashes(htmlspecialchars($purchase['supplier'])) ?>',
                                '<?= $purchase['purchase_date'] ?>',
                                '<?= addslashes(htmlspecialchars($purchase['item_id'])) ?>',
                                '<?= addslashes(htmlspecialchars($purchase['item_name'])) ?>',
                                <?= $purchase['quantity'] ?>,
                                <?= $purchase['unit_price'] ?>,
                                <?= $purchase['total_price'] ?>
                            )">Edit</button>


                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Purchase</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="edit-id">
                                    <input type="hidden" name="action" value="edit">

                                    <div class="mb-3">
                                        <label for="edit-bill-number" class="form-label">Bill Number</label>
                                        <input type="text" name="bill_number" id="edit-bill-number" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-supplier" class="form-label">Supplier</label>
                                        <input type="text" name="supplier" id="edit-supplier" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-purchase-date" class="form-label">Purchase Date</label>
                                        <input type="date" name="purchase_date" id="edit-purchase-date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-item-id" class="form-label">Item ID</label>
                                        <input type="text" name="item_id" id="edit-item-id" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-item-name" class="form-label">Item Name</label>
                                        <input type="text" name="item_name" id="edit-item-name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-quantity" class="form-label">Quantity</label>
                                        <input type="number" name="quantity" id="edit-quantity" min="1" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-unit-price" class="form-label">Unit Price</label>
                                        <input type="number" step="0.01" name="unit_price" id="edit-unit-price" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-total-price" class="form-label">Total Price</label>
                                        <input type="number" step="0.01" name="total_price" id="edit-total-price" class="form-control" required>
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

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
                <script>
                    function editPurchase(id, billNumber, supplier, purchaseDate, itemId, itemName, quantity, unitPrice, totalPrice) {
                        const modal = new bootstrap.Modal(document.getElementById('editModal'));
                        document.getElementById('edit-id').value = id;
                        document.getElementById('edit-bill-number').value = billNumber;
                        document.getElementById('edit-supplier').value = supplier;
                        document.getElementById('edit-purchase-date').value = purchaseDate;
                        document.getElementById('edit-item-id').value = itemId;
                        document.getElementById('edit-item-name').value = itemName;
                        document.getElementById('edit-quantity').value = quantity;
                        document.getElementById('edit-unit-price').value = unitPrice;
                        document.getElementById('edit-total-price').value = totalPrice;
                        modal.show();
                    }
                </script>
</body>

</html>