<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Hospital-Management-System/config/config.php');

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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Optionally add FontAwesome if your sidebar uses icons -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">

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
                    <div class="mb-10">
                        <h class="text-3xl font-bold">Project Management</h>
                    </div>
                    <div class="grid grid-cols-3 grid-rows-3 gap-10 ">
                        <div class="shadow-2xl bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-20">
                            <div class="flex justify-between items-center w-full px-20">
                                <div class="text-xl font-semibold">Total Project</div>
                                <div class="text-xl font-semibold"><?php echo $totalProjects; ?></div>
                            </div>
                        </div>
                        <div class="shadow-2xl bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-20">
                            <div class="flex justify-between items-center w-full px-20">
                                <div class="text-xl font-semibold">Total Task</div>
                                <div class="text-xl font-semibold"><?php echo $totalTasks; ?></div>
                            </div>
                        </div>
                        <div class="shadow-2xl bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-20">
                            <div class="flex justify-between items-center w-full px-20">
                                <div class="text-xl font-semibold">Total Employee</div>
                                <div class="text-xl font-semibold"><?php echo $totalEmployees; ?></div>
                            </div>
                        </div>


                        <a href="/Hospital-Management-System/src/users/admin/tables/view-project.php" class="shadow-2xl btn bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-40">
                            <div class="flex flex-col justify-center items-center w-full">
                                <div class="text-3xl font-semibold">Project Management</div>
                            </div>
                        </a>
                        <div class="shadow-2xl bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-40">
                            <div class="flex flex-col justify-center rounded-2xl items-center w-full">
                                <div class="text-xl font-semibold">Task Management</div>
                                <div class="flex justify-between w-full px-16 py-10">
                                    <div class="text-xl bg-gray-400 p-2 rounded-xl font-semibold btn">
                                        <h1>Add Task</h1>
                                    </div>
                                    <a href="/Hospital-Management-System/src/users/admin/tables/view-task.php" class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>View Task</h1>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="shadow-2xl bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-40">
                            <div class="flex flex-col justify-center rounded-2xl items-center w-full">
                                <div class="text-xl font-semibold">Employee Management</div>
                                <div class="flex justify-between w-full px-16 py-10">
                                    <div class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>Add Employee</h1>
                                    </div>
                                    <div class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>View Employees</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shadow-2xl bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-40">
                            <div class="flex flex-col justify-center rounded-2xl items-center w-full">
                                <div class="text-xl font-semibold">Resource Management</div>
                                <div class="flex justify-between w-full px-16 py-10">
                                    <div class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>Add Resource</h1>
                                    </div>
                                    <div class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>View Resources</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shadow-2xl bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-40">
                            <div class="flex flex-col justify-center rounded-2xl items-center w-full">
                                <div class="text-xl font-semibold">Risk Management</div>
                                <div class="flex justify-between w-full px-16 py-10">
                                    <div class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>Add Risk</h1>
                                    </div>
                                    <div class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>View Risks</h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shadow-2xl bg-white border border-gray-200 rounded-2xl flex justify-center items-center h-40">
                            <div class="flex flex-col justify-center rounded-2xl items-center w-full">
                                <div class="text-xl font-semibold">Progress Report</div>
                                <div class="flex justify-between w-full px-16 py-10">
                                    <div class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>Add Progress</h1>
                                    </div>
                                    <div class="text-xl bg-gray-400 btn p-2 rounded-xl font-semibold">
                                        <h1>View Progress</h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>


        <!-- Add Project Modal -->


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