<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Logistics/config/config.php');

// Assuming a PDO connection in your config file:
try {
    $stmt = $pdo->prepare("SELECT 
        (SELECT COUNT(*) FROM project) AS total_projects,
        (SELECT COUNT(*) FROM task) AS total_tasks,
        (SELECT COUNT(*) FROM employee) AS total_employees");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $totalProjects = $data['total_projects'];
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
    <title>Project Management</title>
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
                        <h class="text-3xl font-bold">Project Management</h>
                    </div>
                    <div class="grid grid-cols-3 gap-10">
                        <!-- Total Project Card -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                           <div class="flex space-x-3">
                             <span class="iconify text-blue-500 text-3xl size-10" data-icon="ic:baseline-folder"></span>
                            <div class="text-3xl font-semibold">Total Project</div>
                           </div>
                            <div class="text-right">
                                
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalProjects; ?></div>
                            </div>
                        </div>
                        
                        <!-- Total Task Card -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                             <span class="iconify text-green-500 text-3xl size-10" data-icon="ic:baseline-folder"></span>
                            <div class="text-3xl font-semibold">Total Tasks</div>
                           </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalTasks; ?></div>
                            </div>
                        </div>

                        <!-- Total Employee Card -->
                        <div class="shadow-2xl bg-gray-200 border border-gray-200 rounded-2xl flex justify-between items-center px-6 py-4">
                            <div class="flex space-x-3">
                                <span class="iconify text-orange-500 text-3xl"  size-10 data-icon="mdi:account-multiple"></span>
                            <div class="text-3xl font-semibold">Total Employee</div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl bg-gray-400 border border-gray-500 p-3 rounded-lg font-semibold"><?php echo $totalEmployees; ?></div>
                            </div>
                        </div>

                        <!-- Project Management Link -->
                        <a href="/Logistics/src/users/admin/tables/view-project.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:file-document-edit-outline"></span>
                            <div class="text-3xl font-semibold">Project Management</div>
                        </a>

                        <!-- Task Management Link -->
                        <a href="/Logistics/src/users/admin/tables/view-task.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:clipboard-list-outline"></span>
                            <div class="text-3xl font-semibold">Task Management</div>
                        </a>

                        <!-- Employee Management Link -->
                        <a href="/Logistics/src/users/admin/tables/view-employee.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:account-group-outline"></span>
                            <div class="text-3xl font-semibold">Employee Management</div>
                        </a>

                        <!-- Resource Management Link -->
                        <a href="/Logistics/src/users/admin/tables/view-resource.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:toolbox-outline"></span>
                            <div class="text-3xl font-semibold">Resource Management</div>
                        </a>

                        <!-- Risk Management Link -->
                        <a href="/Logistics/src/users/admin/tables/view-risk.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:alert-circle-outline"></span>
                            <div class="text-3xl font-semibold">Risk Management</div>
                        </a>

                        <!-- Progress Report Link -->
                        <a href="/Logistics/src/users/admin/tables/view-progress.php" class="shadow-2xl bg-white border hover:bg-gray-400 border-gray-200 rounded-2xl flex flex-col justify-center items-center h-40">
                            <span class="iconify text-gray-700 text-5xl" data-icon="mdi:chart-line"></span>
                            <div class="text-3xl font-semibold">Progress Report</div>
                        </a>
                    </div>
                </div>
            </main>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
