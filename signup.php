<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link href="public/css/signup2.css" rel="stylesheet">
    <link rel="icon" href="public/imgs/logo/logo.ico" type="image/x-icon">
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <img src="public/imgs/logo.png" alt="Logo" class="logo">
        </div>
        <div class="container">
            <form action="signup-handler.php" method="POST" class="login-form">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <h1 class="login-title">Sign up</h1>
                <div class="input-box">
                    <input type="text" name="email" placeholder="Email" required>
                </div>
                <div class="input-box">
                    <select name="user_role" class="form-select" required>
                        <option disabled selected>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="manager">HR Manager</option>
                        <option value="user">Employee</option>
                    </select>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-box">
                    <input type="password" name="cpassword" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="login-btn">Sign up</button>
                <p class="register">
                    Already have an account? <a href="login.php">Login</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>