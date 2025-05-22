<?php
session_start();
include("config/config.php"); // $conn is your PDO connection

$email = $password = "";
$emailErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim inputs
    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($email)) {
        $emailErr = "Email is required";
    }
    if (empty($password)) {
        $passwordErr = "Password is required";
    }

    if (empty($emailErr) && empty($passwordErr)) {
        // Prepare and execute PDO statement
        $stmt = $conn->prepare("SELECT email, password, user_role FROM login WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $db_pass = $row['password'];
            $db_acc_type = $row['user_role'];

            // Verify password
            if (password_verify($password, $db_pass)) {
                $_SESSION['email'] = $email;
                $_SESSION['user_role'] = $db_acc_type;

                // Redirect based on role
                if ($db_acc_type == "admin") {
                    header("Location: ./src/users/admin/admin-dashboard.php");
                    exit();
                } elseif ($db_acc_type == "manager") {
                    header("Location:  ./src/users/hr/hr-dashboard.php");
                    exit();
                } else {
                    header("Location:  ./src/users/employees/employees-dashboard.php");
                    exit();
                }
            } else {
                $passwordErr = "Incorrect password";
            }
        } else {
            $emailErr = "Your email is unregistered";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link href="public/css/login.css" rel="stylesheet">
    <link rel="icon" href="public/imgs/logo/logo.ico" type="image/x-icon">
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <img src="public/imgs/logo.png" alt="Logo" class="logo">
        </div>
        <div class="container">
            <form method="POST" class="login-form">
                <h1 class="login-title">Login</h1>

                <!-- Email Input -->
                <div class="input-box">
                    <i class='bx bxs-user'></i>
                    <input type="text" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <span class="errorMessage"><?php echo htmlspecialchars($emailErr); ?></span>

                <!-- Password Input -->
                <div class="input-box">
                    <i class='bx bxs-lock-alt'></i>
                    <input type="password" name="password" placeholder="Password" value="<?php echo htmlspecialchars($password); ?>">
                </div>
                <span class="errorMessage"><?php echo htmlspecialchars($passwordErr); ?></span>

                <!-- Remember Me and Forgot Password -->
                <div class="remember-forgot-box">
                    <div>
                        <label for="remember">
                            <input type="checkbox" id="remember">
                            Remember me
                        </label>
                    </div>
                    <div class="forgotpass">
                        <a href="#">Forgot Password?</a>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-btn">Login</button>

                <!-- Register Link -->
                <p class="register">
                    Don't have an account?
                    <a href="signup.php">Register</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>