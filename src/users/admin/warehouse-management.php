<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Warehouse Management</title>
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
                <div class="">
                    <div class="">
                        <h1>Warehouse Management</h1>
                    </div>
                    <div class="grid grid-cols-3 grid-rows-3 gap-10 ">
                        <div class="bg-red-500"></div>
                        <div class="bg-red-500"></div>
                        <div class="bg-red-500"></div>
                        <div class="bg-red-500"></div>
                        <div class="bg-red-500"></div>
                        <div class="bg-red-500"></div>
                    </div>
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