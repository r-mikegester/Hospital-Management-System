<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding/editing hospital suppliers
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $supplier_id = $_POST['supplier_id'] ?? null;
    $company_name = $_POST['company_name'] ?? null;
    $contact_person = $_POST['contact_person'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;
    $status = $_POST['status'] ?? 'active';

    // Basic validation
    if (!$company_name || !$contact_person || !$email || !$phone || !$address) {
        die("All fields are required.");
    }

    try {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT INTO hospital_suppliers (company_name, contact_person, email, phone, address, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$company_name, $contact_person, $email, $phone, $address, $status]);
        } elseif ($_POST['action'] === 'edit') {
            if (!$supplier_id) {
                die("Invalid supplier ID.");
            }
            $stmt = $pdo->prepare("UPDATE hospital_suppliers SET company_name = ?, contact_person = ?, email = ?, phone = ?, address = ?, status = ? WHERE supplier_id = ?");
            $stmt->execute([$company_name, $contact_person, $email, $phone, $address, $status, $supplier_id]);
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle deletion of a hospital supplier
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM hospital_suppliers WHERE supplier_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all hospital suppliers
try {
    $stmt = $pdo->prepare("SELECT * FROM hospital_suppliers");
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hospital Suppliers</title>
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
            <div class="container mt-5">
                <h1 class="mb-4">Hospital Suppliers</h1>

                <!-- Add Supplier Button -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSupplierModal">Add Supplier</button>

                <!-- Suppliers Table -->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $sup): ?>
                            <tr>
                                <td><?= $sup['supplier_id'] ?></td>
                                <td><?= htmlspecialchars($sup['company_name']) ?></td>
                                <td><?= htmlspecialchars($sup['contact_person']) ?></td>
                                <td><?= htmlspecialchars($sup['email']) ?></td>
                                <td><?= htmlspecialchars($sup['phone']) ?></td>
                                <td><?= nl2br(htmlspecialchars($sup['address'])) ?></td>
                                <td><?= htmlspecialchars($sup['status']) ?></td>
                                <td><?= $sup['created_at'] ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm"
                                        onclick="openEditModal(
                                    <?= $sup['supplier_id'] ?>,
                                    '<?= addslashes($sup['company_name']) ?>',
                                    '<?= addslashes($sup['contact_person']) ?>',
                                    '<?= addslashes($sup['email']) ?>',
                                    '<?= addslashes($sup['phone']) ?>',
                                    '<?= addslashes($sup['address']) ?>',
                                    '<?= $sup['status'] ?>'
                                )">Edit</button>
                                    <a href="?delete_id=<?= $sup['supplier_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Add Supplier Modal -->
                <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addSupplierModalLabel">Add Hospital Supplier</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="add">

                                <div class="mb-3">
                                    <label for="add-company-name" class="form-label">Company Name</label>
                                    <input type="text" name="company_name" id="add-company-name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="add-contact-person" class="form-label">Contact Person</label>
                                    <input type="text" name="contact_person" id="add-contact-person" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="add-email" class="form-label">Email</label>
                                    <input type="email" name="email" id="add-email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="add-phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" id="add-phone" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="add-address" class="form-label">Address</label>
                                    <textarea name="address" id="add-address" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="add-status" class="form-label">Status</label>
                                    <select name="status" id="add-status" class="form-control" required>
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Supplier</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Edit Supplier Modal -->
                <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSupplierModalLabel">Edit Hospital Supplier</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="supplier_id" id="edit-supplier-id">

                                <div class="mb-3">
                                    <label for="edit-company-name" class="form-label">Company Name</label>
                                    <input type="text" name="company_name" id="edit-company-name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-contact-person" class="form-label">Contact Person</label>
                                    <input type="text" name="contact_person" id="edit-contact-person" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-email" class="form-label">Email</label>
                                    <input type="email" name="email" id="edit-email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" id="edit-phone" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-address" class="form-label">Address</label>
                                    <textarea name="address" id="edit-address" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="edit-status" class="form-label">Status</label>
                                    <select name="status" id="edit-status" class="form-control" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
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

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function openEditModal(id, companyName, contactPerson, email, phone, address, status) {
                    const modal = new bootstrap.Modal(document.getElementById('editSupplierModal'));
                    document.getElementById('edit-supplier-id').value = id;
                    document.getElementById('edit-company-name').value = companyName;
                    document.getElementById('edit-contact-person').value = contactPerson;
                    document.getElementById('edit-email').value = email;
                    document.getElementById('edit-phone').value = phone;
                    document.getElementById('edit-address').value = address;
                    document.getElementById('edit-status').value = status;
                    modal.show();
                }
            </script>
</body>

</html>