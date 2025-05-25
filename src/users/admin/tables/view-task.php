<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding or editing a task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $name = $_POST['name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $project_id = $_POST['project_id'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO task (name, project_id, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $project_id, $start_date, $end_date, $status]);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        $id = $_POST['id'] ?? null;
        if (!$id) {
            die("Task ID is required for editing.");
        }
        try {
            $stmt = $pdo->prepare("UPDATE task SET name = ?, project_id = ?, start_date = ?, end_date = ?, status = ? WHERE task_id = ?");
            $stmt->execute([$name, $project_id, $start_date, $end_date, $status, $id]);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a task
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM task WHERE task_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all tasks
try {
    $stmt = $pdo->prepare("SELECT * FROM task");
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all projects
try {
    $stmt = $pdo->prepare("SELECT * FROM project");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Tasks</title>
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
                <h1 class="text-3xl font-bold mb-10">Tasks</h1>
                <div>
                    <!-- Add Task Form -->
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="action" value="add">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Task Name" required>
                            </div>
                            <div class="col-md-2">
                                <select name="project_id" id="add-project-id" class="form-control" required>
                                    <option value="" disabled selected>Select Project</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-grid">
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Project</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?= $task['task_id'] ?></td>
                                    <td><?= htmlspecialchars($task['name']) ?></td>
                                    <td>
                                        <?php
                                        // Fix: Use $project['id'] to match with $task['project_id']
                                        $projectName = '';
                                        foreach ($projects as $project) {
                                            if ($project['project_id'] == $task['project_id']) {
                                                $projectName = htmlspecialchars($project['name']);
                                                break;
                                            }
                                        }
                                        echo $projectName;
                                        ?>
                                    </td>
                                    <td><?= $task['start_date'] ?></td>
                                    <td><?= $task['end_date'] ?></td>
                                    <td><?= $task['status'] ?></td>
                                    <td><?= $task['created_at'] ?></td>
                                    <td><?= $task['updated_at'] ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button class="btn btn-warning btn-sm" onclick="editTask(
                                            <?= $task['task_id'] ?>,
                                            '<?= addslashes(htmlspecialchars($task['name'])) ?>',
                                            '<?= $task['start_date'] ?>',
                                            '<?= $task['end_date'] ?>',
                                            '<?= $task['status'] ?>',
                                            <?= $task['project_id'] ?>
                                        )">Edit</button>

                                        <!-- Delete Button -->
                                        <a href="?delete_id=<?= $task['task_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
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
                            <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <input type="hidden" name="action" value="edit">

                            <div class="mb-3">
                                <label for="edit-name" class="form-label">Task Name</label>
                                <input type="text" name="name" id="edit-name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-project-id" class="form-label">Project</label>
                                <select name="project_id" id="edit-project-id" class="form-control" required>
                                    <option value="" disabled>Select Project</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit-start-date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="edit-start-date" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-end-date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="edit-end-date" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-status" class="form-label">Status</label>
                                <select name="status" id="edit-status" class="form-control" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Completed">Completed</option>
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
                function editTask(id, name, startDate, endDate, status, projectId) {
                    const modal = new bootstrap.Modal(document.getElementById('editModal'));
                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-start-date').value = startDate;
                    document.getElementById('edit-end-date').value = endDate;
                    document.getElementById('edit-status').value = status;
                    document.getElementById('edit-project-id').value = projectId;
                    modal.show();
                }
            </script>
        </div>
</body>

</html>