<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM procurement");
    $totalProcurements = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM purchases");
    $totalPurchases = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM contracts");
    $totalVendors = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM warehouse");
    $totalWarehouses = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

} catch (PDOException $e) {
    echo "Query error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Procurement Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/custom.css" />
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
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
                    <div class="mb-10">
                        <h1 class="text-3xl font-bold">Procurement Management</h1>
                    </div>
                    <div class="grid grid-cols-4 gap-8">
                        <!-- Total Procurements -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3 items-center">
                                <span class="iconify text-indigo-500 text-3xl" data-icon="mdi:cart-outline"></span>
                                <div class="text-3xl font-semibold">Total Procurements</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold">
                                    <?php echo $totalProcurements; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Total Purchases -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3 items-center">
                                <span class="iconify text-green-500 text-3xl" data-icon="mdi:shopping-outline"></span>
                                <div class="text-3xl font-semibold">Total Purchases</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold">
                                    <?php echo $totalPurchases; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Total Vendors -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3 items-center">
                                <span class="iconify text-yellow-500 text-3xl" data-icon="mdi:account-group-outline"></span>
                                <div class="text-3xl font-semibold">Total Vendors</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold">
                                    <?php echo $totalVendors; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Total Warehouses -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3 items-center">
                                <span class="iconify text-red-500 text-3xl" data-icon="mdi:warehouse"></span>
                                <div class="text-3xl font-semibold">Total Warehouses</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold">
                                    <?php echo $totalWarehouses; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-10 mt-12">
                         <!-- Procurement  -->
                        <a href="/Logistics/src/users/admin/tables/view-procurement.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:cart-outline"></span>
                            <div class="text-3xl font-semibold mt-4">Procurement Item</div>
                        </a>
                        <!-- Procurement Link -->
                        <a href="/Logistics/src/users/admin/tables/view-procurement-item.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:cart-outline"></span>
                            <div class="text-3xl font-semibold mt-4">Procurement Item</div>
                        </a>

                        <!-- Purchases Link -->
                        <a href="/Logistics/src/users/admin/tables/view-purchases.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:shopping-outline"></span>
                            <div class="text-3xl font-semibold mt-4">Purchases</div>
                        </a>

                        <!-- Vendors Link -->
                        <a href="/Logistics/src/users/admin/tables/view-vendor.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:account-group-outline"></span>
                            <div class="text-3xl font-semibold mt-4">Vendors</div>
                        </a>

                        <!-- Warehouses Link -->
                        <a href="/Logistics/src/users/admin/tables/view-warehouse.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:warehouse"></span>
                            <div class="text-3xl font-semibold mt-4">Warehouses</div>
                        </a>
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