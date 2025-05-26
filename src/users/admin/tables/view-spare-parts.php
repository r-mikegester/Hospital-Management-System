<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding/editing spare parts
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $spareparts_name = $_POST['spareparts_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];
    $supplier_id = $_POST['supplier_id'];

    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO spareparts (spareparts_name, category, quantity, unit_price, supplier_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$spareparts_name, $category, $quantity, $unit_price, $supplier_id]);
        } elseif ($_POST['action'] === 'edit') {
            $spareparts_id = $_POST['spareparts_id'];
            $stmt = $pdo->prepare("UPDATE spareparts SET spareparts_name = ?, category = ?, quantity = ?, unit_price = ?, supplier_id = ? WHERE spareparts_id = ?");
            $stmt->execute([$spareparts_name, $category, $quantity, $unit_price, $supplier_id, $spareparts_id]);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a spare part
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM spareparts WHERE spareparts_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all spare parts
try {
    $stmt = $pdo->prepare("SELECT * FROM spareparts");
    $stmt->execute();
    $spareparts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Spare Parts</title>
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
                <h1 class="text-3xl font-bold mb-10">Spare Parts</h1>
                <div>
                    <!-- Add Spare Part Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSparePartModal">Add Spare Part</button>

                    <!-- Spare Parts Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Supplier ID</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($spareparts as $part): ?>
                                <tr>
                                    <td><?= $part['spareparts_id'] ?></td>
                                    <td><?= htmlspecialchars($part['spareparts_name']) ?></td>
                                    <td><?= htmlspecialchars($part['category']) ?></td>
                                    <td><?= $part['quantity'] ?></td>
                                    <td><?= $part['unit_price'] ?></td>
                                    <td><?= $part['supplier_id'] ?></td>
                                    <td><?= $part['created_at'] ?></td>
                                    <td><?= $part['updated_at'] ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openEditModal(
                                                <?= $part['spareparts_id'] ?>,
                                                '<?= htmlspecialchars(addslashes($part['spareparts_name'])) ?>',
                                                '<?= htmlspecialchars(addslashes($part['category'])) ?>',
                                                <?= $part['quantity'] ?>,
                                                <?= $part['unit_price'] ?>,
                                                <?= $part['supplier_id'] ?>
                                            )">Edit</button>
                                        <a href="?delete_id=<?= $part['spareparts_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this spare part?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Add Spare Part Modal -->
                    <div class="modal fade" id="addSparePartModal" tabindex="-1" aria-labelledby="addSparePartModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSparePartModalLabel">Add Spare Part</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="add">
                                    <div class="mb-3">
                                        <label for="add-spareparts-name" class="form-label">Name</label>
                                        <input type="text" name="spareparts_name" id="add-spareparts-name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add-category" class="form-label">Category</label>
                                        <input type="text" name="category" id="add-category" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="add-quantity" class="form-label">Quantity</label>
                                        <input type="number" name="quantity" id="add-quantity" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="add-unit-price" class="form-label">Unit Price</label>
                                        <input type="number" step="0.01" name="unit_price" id="add-unit-price" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="add-supplier-id" class="form-label">Supplier ID</label>
                                        <input type="number" name="supplier_id" id="add-supplier-id" class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Spare Part</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Spare Part Modal -->
                    <div class="modal fade" id="editSparePartModal" tabindex="-1" aria-labelledby="editSparePartModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSparePartModalLabel">Edit Spare Part</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="spareparts_id" id="edit-spareparts-id">
                                    <div class="mb-3">
                                        <label for="edit-spareparts-name" class="form-label">Name</label>
                                        <input type="text" name="spareparts_name" id="edit-spareparts-name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-category" class="form-label">Category</label>
                                        <input type="text" name="category" id="edit-category" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-quantity" class="form-label">Quantity</label>
                                        <input type="number" name="quantity" id="edit-quantity" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-unit-price" class="form-label">Unit Price</label>
                                        <input type="number" step="0.01" name="unit_price" id="edit-unit-price" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="edit-supplier-id" class="form-label">Supplier ID</label>
                                        <input type="number" name="supplier_id" id="edit-supplier-id" class="form-control">
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
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function openEditModal(sparepartsId, sparepartsName, category, quantity, unitPrice, supplierId) {
                const modal = new bootstrap.Modal(document.getElementById('editSparePartModal'));
                document.getElementById('edit-spareparts-id').value = sparepartsId;
                document.getElementById('edit-spareparts-name').value = sparepartsName;
                document.getElementById('edit-category').value = category;
                document.getElementById('edit-quantity').value = quantity;
                document.getElementById('edit-unit-price').value = unitPrice;
                document.getElementById('edit-supplier-id').value = supplierId;
                modal.show();
            }
        </script>
    </div>
</body>

</html>
