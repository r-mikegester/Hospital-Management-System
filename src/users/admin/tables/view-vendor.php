<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $company_id = $_POST['company_id'];
    $start_date = $_POST['start_date'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO contracts (company_id, start_date, expiry_date, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$company_id, $start_date, $expiry_date, $status]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Fetch all companies
try {
    $stmt = $pdo->prepare("SELECT company_id, company_name FROM registration_info");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all contracts
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
    <style>
        .table {
            border: 1px solid #dee2e6;
        }

        

        .table td {
            text-align: center;
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
                <h1 class="text-3xl font-bold mb-10">Vendor List</h1>

                <!-- Add Vendor Button -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addVendorModal">Add Vendor</button>

                <!-- Vendor Table -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Contract ID</th>
                            <th>Company Name</th>
                            <th>Start Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Vendor Modal -->
        <div class="modal fade" id="addVendorModal" tabindex="-1" aria-labelledby="addVendorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addVendorModalLabel">Add Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">

                        <div class="mb-3">
                            <label for="company-id" class="form-label">Company</label>
                            <select name="company_id" id="company-id" class="form-select" required>
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?= $company['company_id'] ?>"><?= htmlspecialchars($company['company_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start-date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start-date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="expiry-date" class="form-label">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry-date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Vendor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
