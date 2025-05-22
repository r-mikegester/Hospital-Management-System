<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Procurement Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Optionally add FontAwesome if your sidebar uses icons -->

    <!-- Your custom CSS -->
    <link rel="stylesheet" href="../public/css/custom.css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <div class="relative min-h-screen flex flex-col">
        <?php include("../../../components/head.php"); ?>
        <header>
            <?php include("../../../components/navbar.php"); ?>
        </header>
        <div class="flex flex-1">
            <aside class="w-64">
                <?php include("../../../components/sidebar2.php"); ?>
            </aside>

            <main class="flex-1 mt-20 p-6">
                <div class="mb-10">
                        <h class="text-3xl font-bold">Procurement Management</h>
                    </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <a href="/Logistics/src/users/admin/tables/view-procurement-item.php">
                        <div class="bg-white shadow-xl rounded-2xl p-10 text-center cursor-pointer hover:shadow-2xl transition-shadow">
                            <h1 class="text-3xl font-bold mb-8">Procurement</h1>
                        </div>
                    </a>
                    <a href="/Logistics/src/users/admin/tables/view-purchases.php">
                        <div class="bg-white shadow-xl rounded-2xl p-10 text-center cursor-pointer hover:shadow-2xl transition-shadow">
                            <h1 class="text-3xl font-bold mb-8">Purchases</h1>
                        </div>
                    </a>
                    
                    <a href="/Logistics/src/users/admin/tables/view-vendor.php">
                        <div class="bg-white shadow-xl rounded-2xl p-10 text-center cursor-pointer hover:shadow-2xl transition-shadow">
                            <h1 class="text-3xl font-bold mb-8">Vendor</h1>
                        </div>
                    </a>
                    <a href="/Logistics/src/users/admin/tables/view-warehouse.php">
                        <div class="bg-white shadow-xl rounded-2xl p-10 text-center cursor-pointer hover:shadow-2xl transition-shadow">
                            <h1 class="text-3xl font-bold mb-8">Warehouse</h1>
                        </div>
                    </a>
                </div>
            </main>
        </div>


    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        sidebarToggle.addEventListener('click', () => {
            sidebar.style.width = sidebar.style.width === '0px' ? '250px' : '0px';
            sidebarToggle.textContent = sidebar.style.width === '0px' ? '☰' : '✕';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>