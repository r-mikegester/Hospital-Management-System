<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HR Management System">
    <meta name="author" content="Your Name">
    <title>HR Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/css/custom.css">

    <!-- Favicon -->
    <link rel="icon" href="public/images/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-side min-vh-100">
                <div class="d-flex flex-column align-items-sm-start px-3 pt-2 text-white" style="background: #4A628A;">
                    <!-- Logo -->
                    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <img src="../logo.png" width="65" height="65" alt="HR3 Logo" class="me-2">
                        <span class="fs-5 d-none d-sm-inline fw-bold">HR3</span>
                    </a>
                    <hr>

                    <!-- Navigation Menu -->
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-sm-start" id="menu">
                        <!-- Time Attendance -->
                        <li class="nav-item">
                            <a href="#submenu" data-bs-toggle="collapse" class="nav-link text-white">
                                <i class="fs-4 fa fa-clock"></i>
                                <span class="ms-2 d-none d-sm-inline">Time Attendance</span>
                            </a>
                            <ul class="collapse nav flex-column ms-1 px-3" id="submenu">
                                <li>
                                    <a href="time tracking.php" class="nav-link text-white">Attendance Tracking</a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link text-muted disabled">Disabled</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Time Sheets -->
                        <li class="nav-item">
                            <a href="#submenu1" data-bs-toggle="collapse" class="nav-link text-white">
                                <i class="fs-4 fa fa-table"></i>
                                <span class="ms-2 d-none d-sm-inline">Time Sheets</span>
                            </a>
                            <ul class="collapse nav flex-column ms-1 px-3" id="submenu1">
                                <li>
                                    <a href="#" class="nav-link text-white">Item</a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link text-white">Item</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Other Menus (Example: Leave Management) -->
                        <li class="nav-item">
                            <a href="#submenu3" data-bs-toggle="collapse" class="nav-link text-white">
                                <i class="fs-4 fa fa-briefcase"></i>
                                <span class="ms-2 d-none d-sm-inline">Leave Management</span>
                            </a>
                            <ul class="collapse nav flex-column ms-1 px-3" id="submenu3">
                                <li>
                                    <a href="#" class="nav-link text-white">Item</a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link text-white">Item</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <hr>

                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../logo.png" width="30" height="30" class="rounded-circle me-2" alt="User">
                            <span class="d-none d-sm-inline">Profile</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-small">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Help & Support</a></li>
                            <li><a class="dropdown-item" href="#">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col py-3">
                <!-- Content goes here -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
