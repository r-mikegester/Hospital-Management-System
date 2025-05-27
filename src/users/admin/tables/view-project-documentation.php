<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submission for adding or editing project documentation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_documentation' || $_POST['action'] === 'edit_documentation') {
        $project_id = trim($_POST['project_id']);
        $doc_name = trim($_POST['doc_name']);
        $doc_type = trim($_POST['doc_type']);
        $submission_date = $_POST['submission_date'];
        $review_status = $_POST['review_status'];
        $compliance_status = $_POST['compliance_status'];

        if (empty($project_id) || empty($doc_name) || empty($doc_type) || empty($submission_date) || empty($review_status) || empty($compliance_status)) {
            die("All fields are required.");
        }

        try {
            if ($_POST['action'] === 'add_documentation') {
                $stmt = $pdo->prepare("
                    INSERT INTO project_documentation (project_id, doc_name, doc_type, submission_date, review_status, compliance_status) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$project_id, $doc_name, $doc_type, $submission_date, $review_status, $compliance_status]);
            } elseif ($_POST['action'] === 'edit_documentation') {
                $doc_id = $_POST['doc_id'];
                $stmt = $pdo->prepare("
                    UPDATE project_documentation 
                    SET project_id = ?, doc_name = ?, doc_type = ?, submission_date = ?, review_status = ?, compliance_status = ? 
                    WHERE doc_id = ?
                ");
                $stmt->execute([$project_id, $doc_name, $doc_type, $submission_date, $review_status, $compliance_status, $doc_id]);
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a project documentation
if (isset($_GET['delete_doc_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM project_documentation WHERE doc_id = ?");
        $stmt->execute([$_GET['delete_doc_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all projects and project documentation
try {
    $stmt = $pdo->prepare("SELECT project_id, name FROM project");
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT d.doc_id, d.project_id, d.doc_name, d.doc_type, d.submission_date, d.review_status, d.compliance_status, d.created_at, d.updated_at, p.name AS project_name 
        FROM project_documentation d 
        JOIN project p ON d.project_id = p.project_id
    ");
    $stmt->execute();
    $documentation = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Project Documentation</title>
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
                <h1 class="text-3xl font-bold mb-10">Project Documentation</h1>
                <div>
                    <!-- Add Documentation Form -->
                    <form method="POST" class="mb-4">
                        <input type="hidden" name="action" value="add_documentation">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <select name="project_id" class="form-control" required>
                                    <option value="">Select Project</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project['project_id'] ?>"><?= htmlspecialchars($project['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="doc_name" class="form-control" placeholder="Document Name" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="doc_type" class="form-control" placeholder="Document Type" required>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="submission_date" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <select name="review_status" class="form-control" required>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="compliance_status" class="form-control" required>
                                    <option value="compliant">Compliant</option>
                                    <option value="non-compliant">Non-Compliant</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Add Documentation</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Project</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Submission Date</th>
                                <th>Review Status</th>
                                <th>Compliance Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documentation as $doc): ?>
                                <tr>
                                    <td><?= $doc['doc_id'] ?></td>
                                    <td><?= htmlspecialchars($doc['project_name']) ?></td>
                                    <td><?= htmlspecialchars($doc['doc_name']) ?></td>
                                    <td><?= $doc['doc_type'] ?></td>
                                    <td><?= $doc['submission_date'] ?></td>
                                    <td><?= $doc['review_status'] ?></td>
                                    <td><?= $doc['compliance_status'] ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editDocumentation(<?= $doc['doc_id'] ?>, <?= $doc['project_id'] ?>, '<?= htmlspecialchars($doc['doc_name'], ENT_QUOTES) ?>', '<?= $doc['doc_type'] ?>', '<?= $doc['submission_date'] ?>', '<?= $doc['review_status'] ?>', '<?= $doc['compliance_status'] ?>')">Edit</button>
                                        <a href="?delete_doc_id=<?= $doc['doc_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
</body>

</html>
