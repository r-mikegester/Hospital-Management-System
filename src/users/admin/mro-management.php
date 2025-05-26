<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Fetching data counts dynamically from the database
try {
    $stmt = $pdo->prepare("
        SELECT 
            (SELECT COUNT(*) FROM asset) AS total_asset,
            (SELECT COUNT(*) FROM maintenance) AS total_schedules,
            (SELECT COUNT(*) FROM spareparts) AS total_spareparts,
            (SELECT COUNT(*) FROM employee) AS total_employee,
            (SELECT COUNT(*) FROM supplier) AS total_supplier,
            (SELECT COUNT(*) FROM maintenance_log) AS total_logs
    ");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalasset = $data['total_asset'];
    $totalSchedules = $data['total_schedules'];
    $totalSpareParts = $data['total_spareparts'];
    $totalemployee = $data['total_employee'];
    $totalsupplier = $data['total_supplier'];
    $totalLogs = $data['total_logs'];
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MRO Management Dashboard</title>
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
                <div class="">
                    <div class="mb-10">
                        <h1 class="text-3xl font-bold">MRO Management Dashboard</h1>
                    </div>
                    <div class="grid grid-cols-3 gap-10">
                        <!-- Total asset -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-blue-500 text-3xl" data-icon="mdi:warehouse"></span>
                                <div class="text-3xl font-semibold">Total asset</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalasset; ?></div>
                            </div>
                        </div>

                        <!-- Maintenance Schedules -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-green-500 text-3xl" data-icon="mdi:calendar-check"></span>
                                <div class="text-3xl font-semibold">Maintenance Schedules</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalSchedules; ?></div>
                            </div>
                        </div>

                        <!-- Spare Parts -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-orange-500 text-3xl" data-icon="mdi:cogs"></span>
                                <div class="text-3xl font-semibold">Spare Parts</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalSpareParts; ?></div>
                            </div>
                        </div>

                        <!-- Employee Management -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-yellow-500 text-3xl" data-icon="mdi:account-multiple"></span>
                                <div class="text-3xl font-semibold">employee</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalemployee; ?></div>
                            </div>
                        </div>

                        <!-- supplier -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-purple-500 text-3xl" data-icon="mdi:truck"></span>
                                <div class="text-3xl font-semibold">supplier</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalsupplier; ?></div>
                            </div>
                        </div>

                        <!-- Maintenance Logs -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-red-500 text-3xl" data-icon="mdi:clipboard-list"></span>
                                <div class="text-3xl font-semibold">Maintenance Logs</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalLogs; ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Links to Sections -->
                    <div class="grid grid-cols-3 gap-10 mt-10">
                        <a href="/Logistics/src/users/admin/tables/view-assets.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:warehouse"></span>
                            <div class="text-3xl font-semibold">Asset Management</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-maintenance-schedules.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:calendar-check"></span>
                            <div class="text-3xl font-semibold">Maintenance Schedules</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-spare-parts.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:cogs"></span>
                            <div class="text-3xl font-semibold">Spare Parts</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-employee.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:account-multiple"></span>
                            <div class="text-3xl font-semibold">Employee Management</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-supplier.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:truck"></span>
                            <div class="text-3xl font-semibold">Supplier Management</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-maintenance-logs.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:clipboard-list"></span>
                            <div class="text-3xl font-semibold">Maintenance Logs</div>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
