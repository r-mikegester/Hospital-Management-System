<?php
session_start();
include("config/config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link href="./public/css/output.css" rel="stylesheet">
    <link rel="icon" href="public/imgs/logo/logo.ico" type="image/x-icon">

    <script>
        setTimeout(() => {
            location.reload();
        }, 5000); // Refresh every 5 seconds
    </script>

</head>

<body>
    <div class="container text-center py-5">
        <h1 class="mb-4">Welcome to the Hospital Management System</h1>
        <p class="lead">Your PHP and MySQL setup is working!</p>
        <a href="login.php" class="btn btn-primary">Go to Login</a>
    </div>

    <!-- PHP Test -->
    <div class="container mt-5">
        <h2>PHP Test:</h2>
        <p>
        <h1 class="text-3xl bg-red-500 text-red-500 font-bold underline">
            Hello world!
        </h1>
        <?php
        echo "The current date and time is: " . date('Y-m-d H:i:s');
        ?>
        </p>
    </div>
</body>

</html>