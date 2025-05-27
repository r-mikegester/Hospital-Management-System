<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submissions for inventory movements
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movement_action'])) {
    $item_id = $_POST['linen_id'];
    $quantity = $_POST['quantity'];
    $movement_type = $_POST['movement_action'];
    $remarks = $_POST['remarks'];
    $from_warehouse_id = $_POST['from_warehouse_id'] ?? null;
    $to_warehouse_id = $_POST['to_warehouse_id'] ?? null;

    try {
        $pdo->beginTransaction();

        // Adjust inventory and record movement
        if ($movement_type === 'Inbound') {
            $stmt = $pdo->prepare("UPDATE linen_inventory SET quantity = quantity + ? WHERE linen_id = ?");
            $stmt->execute([$quantity, $item_id]);
        } elseif ($movement_type === 'Outbound') {
            $stmt = $pdo->prepare("UPDATE linen_inventory SET quantity = quantity - ? WHERE linen_id = ?");
            $stmt->execute([$quantity, $item_id]);
        } elseif ($movement_type === 'Transfer') {
            if (!$from_warehouse_id || !$to_warehouse_id) {
                throw new Exception('Both source and destination warehouses are required for transfer.');
            }
            // Update warehouse-specific logic here if needed
        }

        $stmt = $pdo->prepare("INSERT INTO inventory_movements (item_type, item_id, from_warehouse_id, to_warehouse_id, quantity, movement_type, remarks) VALUES ('Linen', ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$item_id, $from_warehouse_id, $to_warehouse_id, $quantity, $movement_type, $remarks]);

        $pdo->commit();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}

// Fetch data
try {
    $stmt = $pdo->prepare("SELECT linen_inventory.*, warehouse.location AS warehouse_location FROM linen_inventory LEFT JOIN warehouse ON linen_inventory.warehouse_id = warehouse.warehouse_id");
    $stmt->execute();
    $linens = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Inventory Movements</title>
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

                <h1 class="mb-4">Linen Inventory Movements</h1>

                <!-- Linen Inventory Table -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
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
                                <td><?= htmlspecialchars($linen['warehouse_location']) ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="handleMovement(<?= $linen['linen_id'] ?>, 'Inbound')">Inbound</button>
                                    <button class="btn btn-danger btn-sm" onclick="handleMovement(<?= $linen['linen_id'] ?>, 'Outbound')">Outbound</button>
                                    <button class="btn btn-warning btn-sm" onclick="handleMovement(<?= $linen['linen_id'] ?>, 'Transfer')">Transfer</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Movement Modal -->
                <div class="modal fade" id="movementModal" tabindex="-1" aria-labelledby="movementModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="movementModalLabel">Inventory Movement</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="linen_id" id="modal-linen-id">
                                <input type="hidden" name="movement_action" id="modal-movement-action">
                                <div class="mb-3">
                                    <label for="modal-quantity" class="form-label">Quantity</label>
                                    <input type="number" name="quantity" id="modal-quantity" class="form-control" min="1" required>
                                </div>
                                <div id="warehouse-selects" class="d-none">
                                    <div class="mb-3">
                                        <label for="from-warehouse-id" class="form-label">From Warehouse</label>
                                        <select name="from_warehouse_id" id="from-warehouse-id" class="form-control">
                                            <option value="" disabled selected>Select Warehouse</option>
                                            <?php foreach ($warehouses as $warehouse): ?>
                                                <option value="<?= $warehouse['warehouse_id'] ?>"><?= htmlspecialchars($warehouse['location']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="to-warehouse-id" class="form-label">To Warehouse</label>
                                        <select name="to_warehouse_id" id="to-warehouse-id" class="form-control">
                                            <option value="" disabled selected>Select Warehouse</option>
                                            <?php foreach ($warehouses as $warehouse): ?>
                                                <option value="<?= $warehouse['warehouse_id'] ?>"><?= htmlspecialchars($warehouse['location']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="modal-remarks" class="form-label">Remarks</label>
                                    <textarea name="remarks" id="modal-remarks" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Record Movement</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function handleMovement(linenId, action) {
                    document.getElementById('modal-linen-id').value = linenId;
                    document.getElementById('modal-movement-action').value = action;

                    const warehouseSelects = document.getElementById('warehouse-selects');
                    warehouseSelects.classList.toggle('d-none', action !== 'Transfer');

                    const movementModal = new bootstrap.Modal(document.getElementById('movementModal'));
                    movementModal.show();
                }
            </script>
</body>

</html>