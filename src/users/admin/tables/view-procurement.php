<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding/editing procurement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $procurement_id = $_POST['procurement_id'] ?? null;
    $date = $_POST['date'] ?? null;
    $status = $_POST['status'] ?? 'Pending';
    $supplier_id = $_POST['supplier_id'] ?? null;

    if (!$date || !$supplier_id) {
        die("Date and Supplier are required.");
    }

    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO procurement (date, total_amount, status, supplier_id) VALUES (?, 0, ?, ?)");
            $stmt->execute([$date, $status, $supplier_id]);
        } elseif ($_POST['action'] === 'edit') {
            if (!$procurement_id) {
                die("Invalid procurement ID.");
            }
            $stmt = $pdo->prepare("UPDATE procurement SET date = ?, status = ?, supplier_id = ? WHERE procurement_id = ?");
            $stmt->execute([$date, $status, $supplier_id, $procurement_id]);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a procurement record
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM procurement WHERE procurement_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all procurement records
try {
    $stmt = $pdo->prepare("SELECT * FROM procurement");
    $stmt->execute();
    $procurements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch suppliers for dropdown
try {
    $stmt = $pdo->prepare("SELECT supplier_id, supplier_name FROM supplier");
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Procurements</title>
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
                <h1 class="text-3xl font-bold mb-10">Procurement</h1>
                <div>

                            <!-- Add Procurement Button -->
                            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProcurementModal">Add Procurement</button>

                            <!-- Procurements Table -->
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Supplier</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($procurements as $proc): ?>
                                        <tr>
                                            <td><?= $proc['procurement_id'] ?></td>
                                            <td><?= htmlspecialchars($proc['date']) ?></td>
                                            <td><?= htmlspecialchars($proc['status']) ?></td>
                                            <td>
                                                <?php
                                                $supplierName = '';
                                                foreach ($suppliers as $sup) {
                                                    if ($sup['supplier_id'] == $proc['supplier_id']) {
                                                        $supplierName = htmlspecialchars($sup['supplier_name']);
                                                        break;
                                                    }
                                                }
                                                echo $supplierName;
                                                ?>
                                            </td>
                                            <td><?= number_format($proc['total_amount'], 2) ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm"
                                                    onclick="openEditModal(
                                                <?= $proc['procurement_id'] ?>,
                                                '<?= $proc['date'] ?>',
                                                '<?= $proc['status'] ?>',
                                                <?= $proc['supplier_id'] ?>
                                            )">Edit</button>
                                                <a href="?delete_id=<?= $proc['procurement_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this procurement?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- Add Procurement Modal -->
                            <div class="modal fade" id="addProcurementModal" tabindex="-1" aria-labelledby="addProcurementModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addProcurementModalLabel">Add Procurement</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="add">

                                            <div class="mb-3">
                                                <label for="add-date" class="form-label">Date</label>
                                                <input type="date" name="date" id="add-date" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="add-status" class="form-label">Status</label>
                                                <select name="status" id="add-status" class="form-control" required>
                                                    <option value="Pending" selected>Pending</option>
                                                    <option value="Approved">Approved</option>
                                                    <option value="Rejected">Rejected</option>
                                                    <option value="Completed">Completed</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="add-supplier-id" class="form-label">Supplier</label>
                                                <select name="supplier_id" id="add-supplier-id" class="form-control" required>
                                                    <option value="">-- Select Supplier --</option>
                                                    <?php foreach ($suppliers as $sup): ?>
                                                        <option value="<?= $sup['supplier_id'] ?>"><?= htmlspecialchars($sup['supplier_name']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Add Procurement</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Edit Procurement Modal -->
                            <div class="modal fade" id="editProcurementModal" tabindex="-1" aria-labelledby="editProcurementModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editProcurementModalLabel">Edit Procurement</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="action" value="edit">
                                            <input type="hidden" name="procurement_id" id="edit-procurement-id">

                                            <div class="mb-3">
                                                <label for="edit-date" class="form-label">Date</label>
                                                <input type="date" name="date" id="edit-date" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="edit-status" class="form-label">Status</label>
                                                <select name="status" id="edit-status" class="form-control" required>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Approved">Approved</option>
                                                    <option value="Rejected">Rejected</option>
                                                    <option value="Completed">Completed</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="edit-supplier-id" class="form-label">Supplier</label>
                                                <select name="supplier_id" id="edit-supplier-id" class="form-control" required>
                                                    <option value="">-- Select Supplier --</option>
                                                    <?php foreach ($suppliers as $sup): ?>
                                                        <option value="<?= $sup['supplier_id'] ?>"><?= htmlspecialchars($sup['supplier_name']) ?></option>
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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditModal(procurementId, date, status, supplierId) {
            const modal = new bootstrap.Modal(document.getElementById('editProcurementModal'));
            document.getElementById('edit-procurement-id').value = procurementId;
            document.getElementById('edit-date').value = date;
            document.getElementById('edit-status').value = status;
            document.getElementById('edit-supplier-id').value = supplierId;
            modal.show();
        }
    </script>
</body>

</html>