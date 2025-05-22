<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Fetching data related to assets, tasks, and employees
try {
    $stmt = $pdo->prepare("
        SELECT 
            (SELECT COUNT(*) FROM asset) AS total_assets,
            (SELECT COUNT(*) FROM task) AS total_tasks,
            (SELECT COUNT(*) FROM employee) AS total_employees
    ");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalAssets = $data['total_assets'];
    $totalTasks = $data['total_tasks'];
    $totalEmployees = $data['total_employees'];
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Asset Management</title>
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
                <div>
                    <div class="mb-10">
                        <h1 class="text-3xl font-bold">Asset Management Dashboard</h1>
                    </div>
                    <div class="grid grid-cols-3 gap-10">
                        <!-- Total Assets Card -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-blue-500 text-3xl" data-icon="mdi:folder-outline"></span>
                                <div class="text-3xl font-semibold">Total Assets</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalAssets; ?></div>
                            </div>
                        </div>

                        <!-- Total Tasks Card -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-green-500 text-3xl" data-icon="mdi:clipboard-text-outline"></span>
                                <div class="text-3xl font-semibold">Total Tasks</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalTasks; ?></div>
                            </div>
                        </div>

                        <!-- Total Employees Card -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-orange-500 text-3xl" data-icon="mdi:account-group-outline"></span>
                                <div class="text-3xl font-semibold">Total Employees</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalEmployees; ?></div>
                            </div>
                        </div>

                        <!-- Navigation Links -->
                        <a href="/Logistics/src/users/admin/tables/view-assets.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:file-document-edit-outline"></span>
                            <div class="text-3xl font-semibold">View Assets</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-tasks.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:clipboard-list-outline"></span>
                            <div class="text-3xl font-semibold">Warehouse Management</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-employees.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:account-multiple-outline"></span>
                            <div class="text-3xl font-semibold">Maintenance Management</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-employees.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:account-multiple-outline"></span>
                            <div class="text-3xl font-semibold">Employee Management</div>
                        </a>
                        <a href="/Logistics/src/users/admin/tables/view-employees.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:account-multiple-outline"></span>
                            <div class="text-3xl font-semibold">Asset Log</div>
                        </a>
                        
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
