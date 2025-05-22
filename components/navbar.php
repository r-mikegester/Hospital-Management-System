<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default to a placeholder if the session variable is not set
$userEmail = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Guest';
?>

<div class="navbar white text-[#4a628a] px-4 z-10 absolute top-0 w-full shadow-2xl">
    <div class="flex items-center justify-between w-full">

        <!-- Navbar Title -->

        <!-- User Info and Logout -->
        <div class="flex items-end justify-end w-full">
            <span class="navbar-text me-3">Logged in as: <?php echo $userEmail; ?></span>
            <?php if (isset($_SESSION['email'])): ?>
                <a href="../../../logout.php" class="btn btn-outline-light btn-sm bg-[#4a628a]">Logout</a>
            <?php else: ?>
                <a href="../login.php" class="btn btn-outline-light btn-sm">Login</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    
</script>