<?php
session_start();
include("config/config.php"); // this defines $conn (PDO)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $user_role = trim($_POST['user_role']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);

    // Input validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: signup.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters.";
        header("Location: signup.php");
        exit();
    }

    if ($password !== $cpassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: signup.php");
        exit();
    }

    // Check if email already exists using PDO
    $query = "SELECT * FROM login WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->execute(['email' => $email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION['error'] = "Email is already registered.";
        header("Location: signup.php");
        exit();
    }

    // Insert into database
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $insert_query = "INSERT INTO login (email, user_role, password) VALUES (:email, :user_role, :password)";
    $insert_stmt = $conn->prepare($insert_query);

    $params = [
        ':email' => $email,
        ':user_role' => $user_role,
        ':password' => $hashed_password
    ];

    if ($insert_stmt->execute($params)) {
        $_SESSION['status'] = "Registration successful. Please log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: signup.php");
        exit();
    }
}
?>
