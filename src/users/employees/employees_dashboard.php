<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['user_role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success px-4">
        <a class="navbar-brand" href="#">Logistics User</a>
        <div class="ms-auto">
            <span class="navbar-text me-3">Logged in as: <?php echo htmlspecialchars($_SESSION['email']); ?></span>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Welcome, User!</h1>
        <p>This is a temporary user dashboard.</p>
        <!-- Add user features here -->
    </div>
</body>

</html>