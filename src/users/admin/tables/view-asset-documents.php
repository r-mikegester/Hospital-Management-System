<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Handle form submissions for adding, editing, or deleting documents
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $asset_id = $_POST['asset_id'];
    $document_name = $_POST['document_name'];
    $document_type = $_POST['document_type'];
    $associated_date = $_POST['associated_date'];
    $remarks = $_POST['remarks'];

    if ($_POST['action'] === 'add') {
        try {
            $stmt = $pdo->prepare("INSERT INTO asset_management_documents (asset_id, document_name, document_type, associated_date, remarks) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$asset_id, $document_name, $document_type, $associated_date, $remarks]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    if ($_POST['action'] === 'edit') {
        $record_id = $_POST['record_id'];
        try {
            $stmt = $pdo->prepare("UPDATE asset_management_documents SET asset_id = ?, document_name = ?, document_type = ?, associated_date = ?, remarks = ? WHERE record_id = ?");
            $stmt->execute([$asset_id, $document_name, $document_type, $associated_date, $remarks, $record_id]);
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
}

// Handle deletion of a document
if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM asset_management_documents WHERE record_id = ?");
        $stmt->execute([$_GET['delete_id']]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Fetch all documents
try {
    $stmt = $pdo->prepare("SELECT asset_management_documents.*, asset.asset_name FROM asset_management_documents LEFT JOIN asset ON asset_management_documents.asset_id = asset.asset_id");
    $stmt->execute();
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Fetch all assets
try {
    $stmt = $pdo->prepare("SELECT * FROM asset");
    $stmt->execute();
    $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Document Management</title>
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
        <h1 class="text-center mb-4">Manage Asset Documents</h1>

        <!-- Add Document Form -->
        <form method="POST" class="mb-4">
            <input type="hidden" name="action" value="add">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="asset_id" class="form-control" required>
                        <option value="" disabled selected>Select Asset</option>
                        <?php foreach ($assets as $asset): ?>
                            <option value="<?= $asset['asset_id'] ?>"><?= htmlspecialchars($asset['asset_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="document_name" class="form-control" placeholder="Document Name" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="document_type" class="form-control" placeholder="Document Type" required>
                </div>
                <div class="col-md-2">
                    <input type="date" name="associated_date" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="text" name="remarks" class="form-control" placeholder="Remarks">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Add Document</button>
                </div>
            </div>
        </form>

        <!-- Documents Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Asset Name</th>
                    <th>Document Name</th>
                    <th>Document Type</th>
                    <th>Associated Date</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $document): ?>
                    <tr>
                        <td><?= $document['record_id'] ?></td>
                        <td><?= htmlspecialchars($document['asset_name']) ?></td>
                        <td><?= htmlspecialchars($document['document_name']) ?></td>
                        <td><?= htmlspecialchars($document['document_type']) ?></td>
                        <td><?= htmlspecialchars($document['associated_date']) ?></td>
                        <td><?= htmlspecialchars($document['remarks']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editDocument(<?= $document['record_id'] ?>, <?= $document['asset_id'] ?>, '<?= htmlspecialchars($document['document_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($document['document_type'], ENT_QUOTES) ?>', '<?= $document['associated_date'] ?>', '<?= htmlspecialchars($document['remarks'], ENT_QUOTES) ?>')">Edit</button>
                            <a href="?delete_id=<?= $document['record_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this document?')">Delete</a>
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
                    <h5 class="modal-title" id="editModalLabel">Edit Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="record_id" id="edit-record-id">
                    <input type="hidden" name="action" value="edit">
                    <div class="mb-3">
                        <label for="edit-asset-id" class="form-label">Asset</label>
                        <select name="asset_id" id="edit-asset-id" class="form-control" required>
                            <?php foreach ($assets as $asset): ?>
                                <option value="<?= $asset['asset_id'] ?>"><?= htmlspecialchars($asset['asset_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-document-name" class="form-label">Document Name</label>
                        <input type="text" name="document_name" id="edit-document-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-document-type" class="form-label">Document Type</label>
                        <input type="text" name="document_type" id="edit-document-type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-associated-date" class="form-label">Associated Date</label>
                        <input type="date" name="associated_date" id="edit-associated-date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-remarks" class="form-label">Remarks</label>
                        <input type="text" name="remarks" id="edit-remarks" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Document</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editDocument(id, assetId, name, type, date, remarks) {
            document.getElementById('edit-record-id').value = id;
            document.getElementById('edit-asset-id').value = assetId;
            document.getElementById('edit-document-name').value = name;
            document.getElementById('edit-document-type').value = type;
            document.getElementById('edit-associated-date').value = date;
            document.getElementById('edit-remarks').value = remarks;

            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }
    </script>
</body>

</html>
