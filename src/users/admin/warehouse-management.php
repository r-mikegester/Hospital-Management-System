<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

try {
    // Fetch counts based on your schema
    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM warehouse");
    $totalWarehouses = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM inventory_item");
    $totalInventoryItems = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM shipment");
    $totalShipments = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM employee");
    $totalemployee = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
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
    <title>Warehouse Management Dashboard</title>

    <!-- CSS and Icon libraries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet" />
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
                <div>
                    <div class="mb-10">
                        <h1 class="text-3xl font-bold">Warehouse Management Dashboard</h1>
                    </div>

                    <!-- Counts Grid -->
                    <div class="grid grid-cols-4 gap-8">
                        <!-- Warehouses -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3 items-center">
                                <span class="iconify text-red-500 text-3xl" data-icon="mdi:warehouse"></span>
                                <div class="text-3xl font-semibold">Warehouses</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold">
                                    <?php echo $totalWarehouses; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory Items -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3 items-center">
                                <span class="iconify text-indigo-500 text-3xl" data-icon="mdi:package-variant"></span>
                                <div class="text-3xl font-semibold">Inventory Items</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold">
                                    <?php echo $totalInventoryItems; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Shipments -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3 items-center">
                                <span class="iconify text-green-500 text-3xl" data-icon="mdi:truck-delivery"></span>
                                <div class="text-3xl font-semibold">Shipments</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold">
                                    <?php echo $totalShipments; ?>
                                </div>
                            </div>
                        </div>

                        <!-- employee -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3 items-center">
                                <span class="iconify text-yellow-500 text-3xl" data-icon="mdi:account"></span>
                                <div class="text-3xl font-semibold">employee</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold">
                                    <?php echo $totalemployee; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="grid grid-cols-4 gap-10 mt-12">
                        <a href="/Logistics/src/users/admin/tables/view-warehouse.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:warehouse"></span>
                            <div class="text-3xl font-semibold mt-4">Warehouse Overview</div>
                        </a>

                        <a href="/Logistics/src/users/admin/tables/view-inventory.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:package-variant"></span>
                            <div class="text-3xl font-semibold mt-4">Inventory Management</div>
                        </a>

                        <a href="/Logistics/src/users/admin/tables/view-shipments.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:truck-delivery"></span>
                            <div class="text-3xl font-semibold mt-4">Shipment Tracking</div>
                        </a>

                        <a href="/Logistics/src/users/admin/tables/view-employee.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:account"></span>
                            <div class="text-3xl font-semibold mt-4">Employee Management</div>
                        </a>

                        <a href="/Logistics/src/users/admin/tables/view-beds.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:bed-king"></span>
                            <div class="text-3xl font-semibold mt-4">Bed Inventory</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-linen.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:bed-king"></span>
                            <div class="text-3xl font-semibold mt-4">Linen Inventory</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-inventory-movement.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:bed-king"></span>
                            <div class="text-3xl font-semibold mt-4">Inventory Movement</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-bed-assignment.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:bed-king"></span>
                            <div class="text-3xl font-semibold mt-4">Bed Assignments</div>
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