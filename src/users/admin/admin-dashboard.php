<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard</title>
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
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="#">Logistics Admin</a>
        <div class="ms-auto">
            <span class="navbar-text me-3">Logged in as: <?php echo htmlspecialchars($_SESSION['email']); ?></span>
            <a href="../../../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </nav> -->

    <div class="relative min-h-screen flex flex-col">
        <?php include("../../../components/head.php"); ?>
        <header>
            <?php include("../../../components/navbar.php"); ?>
        </header>
        <div class="flex flex-1">
            <aside class="w-64">
                <?php include("../../../components/sidebar2.php"); ?>
            </aside>

            <main class="flex-1 mt-20 p-10 bg-gradient-to-b from-gray-100 to-blue-50 rounded-lg">
  <!-- Header Section -->
  <section class="text-center mb-12">
    <div class=" text-[#4a628a] py-8 px-6 rounded-lg ">
      <h1 class="text-5xl font-bold">Logistics1</h1>
      <p class="text-lg mt-4 max-w-2xl mx-auto">
        Empowering businesses with seamless logistics solutions. Together, we optimize your operations for success.
      </p>
    </div>
  </section>

  <!-- Team Section -->
  <section class="max-w-5xl mx-auto">
    <h2 class="text-3xl font-bold text-[#4a628a] mb-6 text-center">Meet Our Team</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- Russel Santillan -->
      <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
        <div class="text-center">
          <img src="https://api.dicebear.com/6.x/adventurer/svg?seed=Russel" alt="Russel Santillan" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-[#4a628a]">Russel Santillan</h3>
          <p class="text-gray-600">Leader</p>
        </div>
      </div>

      <!-- Denver Sabuga -->
      <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
        <div class="text-center">
          <img src="https://api.dicebear.com/6.x/adventurer/svg?seed=Denver" alt="Denver Sabuga" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-[#4a628a]">Denver Sabuga</h3>
          <p class="text-gray-600">Programmer</p>
        </div>
      </div>

      <!-- James Carabuena -->
      <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
        <div class="text-center">
          <img src="https://api.dicebear.com/6.x/adventurer/svg?seed=James" alt="James Carabuena" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-[#4a628a]">James Carabuena</h3>
          <p class="text-gray-600">Document Specialist</p>
        </div>
      </div>

      <!-- Mark Joseph Narciso -->
      <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
        <div class="text-center">
          <img src="https://api.dicebear.com/6.x/adventurer/svg?seed=Mark" alt="Mark Joseph Narciso" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-[#4a628a]">Mark Joseph Narciso</h3>
          <p class="text-gray-600">UI Designer</p>
        </div>
      </div>

      <!-- John Lloyd Morales -->
      <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow">
        <div class="text-center">
          <img src="https://api.dicebear.com/6.x/adventurer/svg?seed=John" alt="John Lloyd Morales" class="w-24 h-24 rounded-full mx-auto mb-4">
          <h3 class="text-xl font-semibold text-[#4a628a]">John Lloyd Morales</h3>
          <p class="text-gray-600">Support Staff</p>
        </div>
      </div>
    </div>
  </section>
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