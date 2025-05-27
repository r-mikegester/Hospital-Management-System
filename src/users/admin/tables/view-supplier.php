<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submissions for adding, editing, or deleting suppliers
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $supplier_name = $_POST['supplier_name'];
    $contact_info = $_POST['contact_info'];
    $rating = $_POST['rating'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO supplier (supplier_name, contact_info, rating) VALUES (?, ?, ?)");
            $stmt->execute([$supplier_name, $contact_info, $rating]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        try {
            $stmt = $pdo->prepare("UPDATE supplier SET supplier_name = ?, contact_info = ?, rating = ? WHERE supplier_id = ?");
            $stmt->execute([$supplier_name, $contact_info, $rating, $id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a supplier
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM supplier WHERE supplier_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all suppliers
try {
    $stmt = $pdo->prepare("SELECT * FROM supplier");
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Supplier Management</title>
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
            <div class="container my-5">
                <h1 class="mb-4">Supplier Management</h1>

                <!-- Add Supplier Form -->
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="supplier_name" class="form-control" placeholder="Supplier Name" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="contact_info" class="form-control" placeholder="Contact Info" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="rating" class="form-control" step="0.1" min="0" max="5" placeholder="Rating" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Add Supplier</button>
                        </div>
                    </div>
                </form>

                <!-- Suppliers Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact Info</th>
                            <th>Rating</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $supplier): ?>
                            <tr>
                                <td><?= $supplier['supplier_id'] ?></td>
                                <td><?= htmlspecialchars($supplier['supplier_name']) ?></td>
                                <td><?= htmlspecialchars($supplier['contact_info']) ?></td>
                                <td><?= htmlspecialchars($supplier['rating']) ?></td>
                                <td><?= htmlspecialchars($supplier['created_at']) ?></td>
                                <td><?= htmlspecialchars($supplier['updated_at']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editSupplier(<?= $supplier['supplier_id'] ?>, '<?= htmlspecialchars($supplier['supplier_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($supplier['contact_info'], ENT_QUOTES) ?>', <?= $supplier['rating'] ?>)">Edit</button>
                                    <a href="?delete_id=<?= $supplier['supplier_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Supplier</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <input type="hidden" name="action" value="edit">
                            <div class="mb-3">
                                <label for="edit-name" class="form-label">Supplier Name</label>
                                <input type="text" name="supplier_name" id="edit-name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-contact-info" class="form-label">Contact Info</label>
                                <input type="text" name="contact_info" id="edit-contact-info" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-rating" class="form-label">Rating</label>
                                <input type="number" name="rating" id="edit-rating" class="form-control" step="0.1" min="0" max="5" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Supplier</button>
                        </div>
                    </form>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function editSupplier(id, name, contactInfo, rating) {
                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-contact-info').value = contactInfo;
                    document.getElementById('edit-rating').value = rating;

                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                }
            </script>
</body>

</html>