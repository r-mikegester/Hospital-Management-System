<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding a contract
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $company_id = $_POST['company_id'];
    $start_date = $_POST['start_date'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO contracts (company_id, start_date, expiry_date, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$company_id, $start_date, $expiry_date, $status]);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Handle editing a contract
    if ($_POST['action'] === 'edit') {
        $contract_id = $_POST['contract_id'];
        try {
            $stmt = $pdo->prepare("UPDATE contracts SET company_id = ?, start_date = ?, expiry_date = ?, status = ? WHERE contract_id = ?");
            $stmt->execute([$company_id, $start_date, $expiry_date, $status, $contract_id]);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a contract
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM contracts WHERE contract_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all companies for the dropdown
try {
    $stmt = $pdo->prepare("SELECT company_id, company_name FROM registration_info");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all contracts with company names using JOIN
try {
    $stmt = $pdo->prepare("SELECT c.*, r.company_name FROM contracts c JOIN registration_info r ON c.company_id = r.company_id");
    $stmt->execute();
    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Vendor List</title>
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
                <h1 class="text-3xl font-bold mb-10">Vendor List</h1>
                <div>
                    

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Contract ID</th>
                                <th>Company Name</th>
                                <th>Start Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contracts as $contract): ?>
                                <tr>
                                    <td><?= $contract['contract_id'] ?></td>
                                    <td><?= htmlspecialchars($contract['company_name']) ?></td>
                                    <td><?= $contract['start_date'] ?></td>
                                    <td><?= $contract['expiry_date'] ?></td>
                                    <td><?= ucfirst($contract['status']) ?></td>
                                    <td><?= $contract['created_at'] ?></td>
                                    <td><?= $contract['updated_at'] ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" onclick="editContract(
                                            <?= $contract['contract_id'] ?>,
                                            <?= $contract['company_id'] ?>,
                                            '<?= $contract['start_date'] ?>',
                                            '<?= $contract['expiry_date'] ?>',
                                            '<?= $contract['status'] ?>'
                                        )">Edit</button>

                                        <!-- Delete Button -->
                                        <a href="?delete_id=<?= $contract['contract_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this contract?')">Delete</a>
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
                            <h5 class="modal-title" id="editModalLabel">Edit Contract</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="contract_id" id="edit-contract-id">
                            <input type="hidden" name="action" value="edit">

                            <div class="mb-3">
                                <label for="edit-company-id" class="form-label">Company</label>
                                <select name="company_id" id="edit-company-id" class="form-select" required>
                                    <?php foreach ($companies as $company): ?>
                                        <option value="<?= htmlspecialchars($company['company_id']) ?>">
                                            <?= htmlspecialchars($company['company_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit-start-date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="edit-start-date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-expiry-date" class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" id="edit-expiry-date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-status" class="form-label">Status</label>
                                <select name="status" id="edit-status" class="form-select" required>
                                    <option value="pending">Pending</option>
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                    <option value="terminated">Terminated</option>
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

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function editContract(contractId, companyId, startDate, expiryDate, status) {
                    const modal = new bootstrap.Modal(document.getElementById('editModal'));
                    document.getElementById('edit-contract-id').value = contractId;
                    document.getElementById('edit-company-id').value = companyId;
                    document.getElementById('edit-start-date').value = startDate;
                    document.getElementById('edit-expiry-date').value = expiryDate;
                    document.getElementById('edit-status').value = status;
                    modal.show();
                }
            </script>
        </div>
</body>

</html>
