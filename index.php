<?php
// Prevent browser caching and back navigation
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$user = $_SESSION['user'];
$userId = $user['id'];
$roleType = $user['role_type'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/dbims/app/models/UserModel.php';
$userModel = new UserModel();
$updatedUser = $userModel->getUserById($userId);

require_once $_SERVER['DOCUMENT_ROOT'] . '/dbims/app/controllers/PortalController.php';

if (!$updatedUser) {
    $_SESSION['toast'] = ['type' => 'error', 'message' => 'User data not found.'];
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - DBIMS</title>
  <link rel="stylesheet" href="/dbims/node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/dbims/node_modules/bootstrap-icons/font/bootstrap-icons.css">
  <style>
    body {
      overflow-x: hidden;
    }
    .sidebar {
  width: 250px;
  transition: width 0.3s ease;
  overflow-x: hidden;
  white-space: nowrap;
}

.sidebar.collapsed {
  width: 70px;
}

.sidebar .nav-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px;
}

.sidebar .nav-link .link-text {
  transition: opacity 0.3s ease, visibility 0.3s ease;
}

.sidebar.collapsed .link-text {
  opacity: 0;
  visibility: hidden;
  width: 0;
}



.content {
  margin-left: 250px;
  transition: margin-left 0.3s ease;
}

.content.collapsed {
  margin-left: 70px;
}

/* For small screens: sidebar slides over content */
@media (max-width: 768px) {
  .sidebar {
    left: -250px;
    transition: left 0.3s ease;
  }

  .sidebar.show {
    left: 0;
    box-shadow: 2px 0 5px rgba(0,0,0,0.3);
  }

  .content,
  .content.collapsed {
    margin-left: 0 !important;
  }

  body.sidebar-open {
    overflow: hidden;
  }
}

    .navbar-logo-title img {
      height: 40px;
    }
    .navbar-logo-title span {
      white-space: nowrap;
    }
    .toggle-btn {
      background: none;
      border: none;
      font-size: 1.5rem;
    }
  </style>
</head>
<body class="bg-white">

<!-- Sidebar -->
<div class="bg-dark text-white position-fixed vh-100 sidebar d-flex flex-column p-3" id="sidebar">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="fw-bold fs-4">
    <div class="navbar-logo-title d-flex align-items-center gap-2">
        <img src="/dbims/assets/images/logo.png" alt="Logo" class="rounded-circle">
        <span class="fw-bold fs-5">DBIMS</span>
      </div>
    </div>
  </div>
  <ul class="nav nav-pills flex-column gap-2">
  <li>
    <a href="?page=home" class="nav-link text-white" data-bs-toggle="tooltip" data-bs-placement="right" title="Home">
      <i class="bi bi-speedometer2"></i>
      <span class="link-text">Dashboard</span>
    </a>
  </li>
  <li>
  <a href="?page=activity_logs" class="nav-link text-white" data-bs-toggle="tooltip" data-bs-placement="right" title="Activity Logs">
    <i class="bi bi-clock-history"></i>
    <span class="link-text">Activity Logs</span>
  </a>
</li>

  <li>
    <a href="?page=account" class="nav-link text-white" data-bs-toggle="tooltip" data-bs-placement="right" title="Account">
      <i class="bi bi-person-fill"></i>
      <span class="link-text">Account</span>
    </a>
  </li>
</ul>

</div>

<!-- Main Content -->
<div class="content" id="mainContent">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-light shadow sticky-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <button class="toggle-btn" id="toggleSidebar"><i class="bi bi-list"></i></button>
      
      <div class="d-flex align-items-center gap-3">
        <button class="btn position-relative">
          <i class="bi bi-bell-fill fs-5"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
        </button>
        <div class="fw-semibold"><?= htmlspecialchars($updatedUser['firstname'] . ' ' . $updatedUser['lastname']) ?></div>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <form method="POST" class="px-3">
                <input type="hidden" name="id" value="<?= htmlspecialchars($updatedUser['id']); ?>">
                <button type="submit" name="logout" class="btn btn-danger w-100">Logout</button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Secondary Nav Buttons -->
  <div class="bg-white border-bottom px-4 py-3 d-flex gap-3 justify-content-end">
    <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#createPasscodeModal">
      <i class="bi bi-key-fill"></i> Create Passcode
    </button>
    <button class="btn btn-sm btn-dark">
      <i class="bi bi-shield-lock-fill"></i> Change Passcode
    </button>
    <button class="btn btn-sm btn-dark">
      <i class="bi bi-plus-circle-fill"></i> New Activity
    </button>
  </div>

  <!-- Page Content -->
  <main class="container-fluid mt-4 px-4">
    <?php
      $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'home';
      $allowedPages = ['home', 'activity_logs', 'account'];
      if (!in_array($page, $allowedPages, true)) { $page = '404'; }
      $viewFile = __DIR__ . '/templates/' . $page . '.php';
      if (is_readable($viewFile)) {
          include $viewFile;
      } else {
          http_response_code(404);
          echo '<h2 class="text-center">404 - Page Not Found</h2>';
      }
    ?>
  </main>
</div>

<!-- CREATE PASSCODE MODAL -->
<div class="modal fade" id="createPasscodeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Create your passcode for security purpose.</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="" method="POST">
          <input type="hidden" name="id" value="<?= htmlspecialchars($updatedUser['id']); ?>">
          <div class="mb-3">
            <label class="form-label">Type your passcode:</label>
            <input type="number" class="form-control" name="passcode">
          </div>
          <div class="mb-3 d-flex justify-content-end">
            <button class="btn btn-success btn-sm" type="submit" name="submit_passcode">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="/dbims/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
 document.addEventListener('DOMContentLoaded', function () {
    // Enable Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleSidebar');

    toggleBtn.addEventListener('click', function () {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('collapsed');

      // For mobile view - show sidebar as overlay
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('show');
      }
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function (e) {
      if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
        if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
          sidebar.classList.remove('show');
        }
      }
    });

    // On window resize, reset mobile sidebar
    window.addEventListener('resize', () => {
      if (window.innerWidth > 768) {
        sidebar.classList.remove('show');
      }
    });
  });
</script>

</body>
</html>
