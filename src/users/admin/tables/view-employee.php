<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding an employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $department = $_POST['department'];
    $contact_info = $_POST['contact_info'];
    $status = $_POST['status'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO employee (name, role, department, contact_info, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $role, $department, $contact_info, $status]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Handle editing an employee
    if ($_POST['action'] === 'edit') {
        if (!isset($_POST['employee_id']) || empty($_POST['employee_id'])) {
            die("Error: Employee ID is missing.");
        }

        $employee_id = $_POST['employee_id'];
        try {
            $stmt = $pdo->prepare("UPDATE employee SET name = ?, role = ?, department = ?, contact_info = ?, status = ? WHERE employee_id = ?");
            $stmt->execute([$name, $role, $department, $contact_info, $status, $employee_id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of an employee
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM employee WHERE employee_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all employees
try {
    $stmt = $pdo->prepare("SELECT * FROM employee");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Employees</title>
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
                <h1 class="text-3xl font-bold mb-10">Employees</h1>
                <div>
                    <!-- Add Employee Form -->
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="name" class="form-control" placeholder="Name" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="role" class="form-control" placeholder="Role" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="department" class="form-control" placeholder="Department" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="contact_info" class="form-control" placeholder="Contact Info" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="status" class="form-control" placeholder="Status" required>
                            </div>
                            <div class="col-md-3 mt-3">
                                <button type="submit" class="btn btn-primary">Add Employee</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>Contact Info</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td><?= $employee['employee_id'] ?></td>
                                    <td><?= $employee['name'] ?></td>
                                    <td><?= $employee['role'] ?></td>
                                    <td><?= $employee['department'] ?></td>
                                    <td><?= $employee['contact_info'] ?></td>
                                    <td><?= $employee['status'] ?></td>
                                    <td><?= $employee['created_at'] ?></td>
                                    <td><?= $employee['updated_at'] ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" onclick="editEmployee(<?= $employee['employee_id'] ?>, '<?= htmlspecialchars($employee['name']) ?>', '<?= $employee['role'] ?>','<?= $employee['department'] ?>', '<?= $employee['contact_info'] ?>', '<?= $employee['status'] ?>')">Edit</button>

                                        <!-- Delete Button -->
                                        <a href="?delete_id=<?= $employee['employee_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?')">Delete</a>
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
                            <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="employee_id" id="edit-id">
                            <input type="hidden" name="action" value="edit">
                            <div class="mb-3">
                                <label for="edit-name" class="form-label">Name</label>
                                <input type="text" name="name" id="edit-name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-role" class="form-label">Role</label>
                                <input type="text" name="role" id="edit-role" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-department" class="form-label">Department</label>
                                <input type="text" name="department" id="edit-department" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-contact-info" class="form-label">Contact Info</label>
                                <input type="text" name="contact_info" id="edit-contact-info" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-status" class="form-label">Status</label>
                                <input type="text" name="status" id="edit-status" class="form-control" required>
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
                function editEmployee(employee_id, name, role, department, contact_info, status) {
                    const modal = new bootstrap.Modal(document.getElementById('editModal'));
                    document.getElementById('edit-id').value = employee_id; // Use employee_id here.
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-role').value = role;
                    document.getElementById('edit-department').value = department;
                    document.getElementById('edit-contact-info').value = contact_info;
                    document.getElementById('edit-status').value = status;
                    modal.show();
                }
            </script>
        </div>
</body>

</html>
