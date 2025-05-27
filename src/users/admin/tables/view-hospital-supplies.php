<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding/editing hospital supplies
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $supply_id = $_POST['supply_id'] ?? null;
    $supply_name = $_POST['supply_name'] ?? null;
    $category = $_POST['category'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $unit = $_POST['unit'] ?? null;
    $price = $_POST['price'] ?? null;
    $manufacturer = $_POST['manufacturer'] ?? null;
    $supplier_id = $_POST['supplier_id'] ?? null;
    $storage_location = $_POST['storage_location'] ?? null;
    $status = $_POST['status'] ?? 'In Stock';

    // Basic validation
    if (!$supply_name || !$category || $quantity === null || !$unit || !$price || !$supplier_id || !$storage_location) {
        die("Please fill in all required fields.");
    }

    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO hospital_supplies_inventory (supply_name, category, quantity, unit, price, manufacturer, supplier_id, storage_location, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$supply_name, $category, $quantity, $unit, $price, $manufacturer, $supplier_id, $storage_location, $status]);
        } elseif ($_POST['action'] === 'edit') {
            if (!$supply_id) {
                die("Invalid supply ID.");
            }
            $stmt = $pdo->prepare("UPDATE hospital_supplies_inventory SET supply_name = ?, category = ?, quantity = ?, unit = ?, price = ?, manufacturer = ?, supplier_id = ?, storage_location = ?, status = ? WHERE supply_id = ?");
            $stmt->execute([$supply_name, $category, $quantity, $unit, $price, $manufacturer, $supplier_id, $storage_location, $status, $supply_id]);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a supply record
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM hospital_supplies_inventory WHERE supply_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all hospital supplies
try {
    $stmt = $pdo->prepare("SELECT * FROM hospital_supplies_inventory");
    $stmt->execute();
    $supplies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch suppliers for dropdown
try {
    $stmt = $pdo->prepare("SELECT supplier_id, company_name FROM hospital_suppliers WHERE status = 'active'");
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hospital Supplies Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
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
                <h1 class="text-3xl font-bold mb-10">Hospital Supplies Inventory</h1>
                <div>
                    <!-- Add Supply Button -->
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSupplyModal">Add Supply</button>

                    <!-- Supplies Table -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Supply Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Manufacturer</th>
                                <th>Supplier</th>
                                <th>Storage Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($supplies as $supply): ?>
                                <tr>
                                    <td><?= $supply['supply_id'] ?></td>
                                    <td><?= htmlspecialchars($supply['supply_name']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($supply['category'])) ?></td>
                                    <td><?= htmlspecialchars($supply['quantity']) ?></td>
                                    <td><?= htmlspecialchars($supply['unit']) ?></td>
                                    <td><?= number_format($supply['price'], 2) ?></td>
                                    <td><?= htmlspecialchars($supply['manufacturer']) ?></td>
                                    <td>
                                        <?php
                                        $supplierName = '';
                                        foreach ($suppliers as $sup) {
                                            if ($sup['supplier_id'] == $supply['supplier_id']) {
                                                $supplierName = htmlspecialchars($sup['company_name']);
                                                break;
                                            }
                                        }
                                        echo $supplierName;
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($supply['storage_location']) ?></td>
                                    <td><?= htmlspecialchars($supply['status']) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openEditModal(
                                                <?= $supply['supply_id'] ?>,
                                                '<?= addslashes($supply['supply_name']) ?>',
                                                '<?= $supply['category'] ?>',
                                                <?= $supply['quantity'] ?>,
                                                '<?= addslashes($supply['unit']) ?>',
                                                '<?= $supply['price'] ?>',
                                                '<?= addslashes($supply['manufacturer']) ?>',
                                                <?= $supply['supplier_id'] ?>,
                                                '<?= addslashes($supply['storage_location']) ?>',
                                                '<?= $supply['status'] ?>'
                                            )">Edit</button>
                                        <a href="?delete_id=<?= $supply['supply_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this supply?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Add Supply Modal -->
                    <div class="modal fade" id="addSupplyModal" tabindex="-1" aria-labelledby="addSupplyModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSupplyModalLabel">Add Supply</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="add">

                                    <div class="mb-3">
                                        <label for="add-supply-name" class="form-label">Supply Name</label>
                                        <input type="text" name="supply_name" id="add-supply-name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-category" class="form-label">Category</label>
                                        <select name="category" id="add-category" class="form-control" required>
                                            <option value="">-- Select Category --</option>
                                            <option value="linen">Linen</option>
                                            <option value="hospital wear">Hospital Wear</option>
                                            <option value="equipment">Equipment</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-quantity" class="form-label">Quantity</label>
                                        <input type="number" name="quantity" id="add-quantity" class="form-control" min="0" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-unit" class="form-label">Unit</label>
                                        <input type="text" name="unit" id="add-unit" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-price" class="form-label">Price</label>
                                        <input type="number" step="0.01" name="price" id="add-price" class="form-control" min="0" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-manufacturer" class="form-label">Manufacturer</label>
                                        <input type="text" name="manufacturer" id="add-manufacturer" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-supplier-id" class="form-label">Supplier</label>
                                        <select name="supplier_id" id="add-supplier-id" class="form-control" required>
                                            <option value="">-- Select Supplier --</option>
                                            <?php foreach ($suppliers as $sup): ?>
                                                <option value="<?= $sup['supplier_id'] ?>"><?= htmlspecialchars($sup['company_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-storage-location" class="form-label">Storage Location</label>
                                        <input type="text" name="storage_location" id="add-storage-location" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="add-status" class="form-label">Status</label>
                                        <select name="status" id="add-status" class="form-control" required>
                                            <option value="In Stock" selected>In Stock</option>
                                            <option value="Out of Stock">Out of Stock</option>
                                            <option value="Discontinued">Discontinued</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Add Supply</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Supply Modal -->
                    <div class="modal fade" id="editSupplyModal" tabindex="-1" aria-labelledby="editSupplyModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editSupplyModalLabel">Edit Supply</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="edit">
                                    <input type="hidden" name="supply_id" id="edit-supply-id">

                                    <div class="mb-3">
                                        <label for="edit-supply-name" class="form-label">Supply Name</label>
                                        <input type="text" name="supply_name" id="edit-supply-name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-category" class="form-label">Category</label>
                                        <select name="category" id="edit-category" class="form-control" required>
                                            <option value="linen">Linen</option>
                                            <option value="hospital wear">Hospital Wear</option>
                                            <option value="equipment">Equipment</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-quantity" class="form-label">Quantity</label>
                                        <input type="number" name="quantity" id="edit-quantity" class="form-control" min="0" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-unit" class="form-label">Unit</label>
                                        <input type="text" name="unit" id="edit-unit" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-price" class="form-label">Price</label>
                                        <input type="number" step="0.01" name="price" id="edit-price" class="form-control" min="0" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-manufacturer" class="form-label">Manufacturer</label>
                                        <input type="text" name="manufacturer" id="edit-manufacturer" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-supplier-id" class="form-label">Supplier</label>
                                        <select name="supplier_id" id="edit-supplier-id" class="form-control" required>
                                            <option value="">-- Select Supplier --</option>
                                            <?php foreach ($suppliers as $sup): ?>
                                                <option value="<?= $sup['supplier_id'] ?>"><?= htmlspecialchars($sup['company_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-storage-location" class="form-label">Storage Location</label>
                                        <input type="text" name="storage_location" id="edit-storage-location" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit-status" class="form-label">Status</label>
                                        <select name="status" id="edit-status" class="form-control" required>
                                            <option value="In Stock">In Stock</option>
                                            <option value="Out of Stock">Out of Stock</option>
                                            <option value="Discontinued">Discontinued</option>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditModal(id, name, category, quantity, unit, price, manufacturer, supplierId, storageLocation, status) {
            const modal = new bootstrap.Modal(document.getElementById('editSupplyModal'));
            document.getElementById('edit-supply-id').value = id;
            document.getElementById('edit-supply-name').value = name;
            document.getElementById('edit-category').value = category;
            document.getElementById('edit-quantity').value = quantity;
            document.getElementById('edit-unit').value = unit;
            document.getElementById('edit-price').value = price;
            document.getElementById('edit-manufacturer').value = manufacturer;
            document.getElementById('edit-supplier-id').value = supplierId;
            document.getElementById('edit-storage-location').value = storageLocation;
            document.getElementById('edit-status').value = status;
            modal.show();
        }
    </script>
</body>

</html>
