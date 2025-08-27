<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; // Get the role from the session

// Check if `id` is set and valid
$page_id = isset($_GET['id']) ? $_GET['id'] : null;

// Validate URL parameter
$id = null;
if (isset($_GET['id']) && preg_match('/^\d{12}$/', $_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: ../404/");
    exit();
}

// Fetch users from the database based on the role
if ($role === 'admin') {
    $userQuery = $conn->query("SELECT id, name, lastname, email, role FROM users");
} elseif ($role === 'mayor') {
    $userQuery = $conn->query("SELECT id, name, lastname, email, role FROM users WHERE role != 'admin'");
} elseif ($role === 'staff') {
    $userQuery = $conn->query("SELECT id, name, lastname, email, role FROM users WHERE role = 'staff'");
}
$users = $userQuery->fetch_all(MYSQLI_ASSOC);

// Fetch current settings
$stmt = $conn->prepare("SELECT favicon, logo, security_settings, demo FROM settings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$settings = $result->fetch_assoc();
$favicon = $settings ? $settings["favicon"] : "";
$logo = $settings ? $settings["logo"] : "";
$security_settings = $settings ? $settings["security_settings"] : 0;
$demo = $settings ? $settings["demo"] : 0;
$cache_buster = time();

// Fetch the user's details
$stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$name = $user_data ? $user_data['name'] : '';
$profile_picture = $user_data ? $user_data['profile_picture'] : '';

// Check user role
if ($role === 'admin' || $role === 'mayor' || $role === 'staff') {
    // Continue to the dashboard
} else {
    // Redirect if the user role is not allowed
    header("Location: ../admin/?id=177985647998");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OSCA Manaoag | Users List</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../dist/css//ionicons.min.css">
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../dist/css/blur.css">
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
  <script src="../js/crypto-js.min.js"></script>
  <link rel="icon" type="image/png" href="../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div id="blur-overlay"></div>
<div class="wrapper">

<style>
.view-btn, .edit-btn, .delete-btn {
  padding: 5px 10px;
  margin-right: 5px;
  border: none;
  border-radius: 3px;
  text-decoration: none;
  color: #fff;
  font-size: 14px;
}

.view-btn {
  background-color: #17a2b8; /* Info color */
}

.edit-btn {
  background-color: #ffc107; /* Warning color */
}

.delete-btn {
  background-color: #dc3545; /* Danger color */
}

.view-btn:hover, .edit-btn:hover, .delete-btn:hover {
  opacity: 0.8;
}

</style>

  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/manaoag.png" alt="AdminLTELogo" height="400" width="400">
  </div>

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <?php if ($role === 'admin'): ?>
      <li class="nav-item manu-open">
        <a href="../users/add_user/?id=518736420533" class="nav-link active">Add new user</a>
      </li>
      <?php endif; ?>
    </ul>

    <ul class="navbar-nav ml-auto">
  <li class="nav-item">
    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
      <i class="fas fa-expand-arrows-alt"></i>
    </a>
  </li>
  <?php if ($role === 'admin'): ?>
  <li class="nav-item">
    <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
      <i class="fas fa-th-large"></i>
    </a>
  </li>
  <?php endif; ?>
  <li class="nav-item">
    <a class="nav-link" data-toggle="modal" data-target="#modal-default" role="button">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </li>
</ul>

<!--<div class="card-body">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Launch Default Modal
                </button>-->
</nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../?id=177985647998" class="brand-link">
    <?php if ($logo): ?>
      <img src="../assets/<?= htmlspecialchars($logo) ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <?php endif; ?>
      <span class="brand-text font-weight-light">OSCA Manaoag</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php if ($profile_picture): ?>
          <img src="../Profile_pictures/<?= htmlspecialchars($profile_picture) ?>" class="img-circle elevation-2" alt="User Image">
          <?php endif; ?>
        </div>
        <div class="info">
          <a href="#" class="d-block"><?=($role) ?>, <?= htmlspecialchars($name) ?></a>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

               <li class="nav-item">
                <a href="../?id=177985647998" class="nav-link">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </a>
              </li>
          </li>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item ">
                <a href="../users/?id=290384756120" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users List</p>
                </a>
              </li>
              <?php if ($role === 'admin'): ?>
              <li class="nav-item">
                <a href="../users/logs/?id=518736420591" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logs Users</p>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </li>
          <li class="nav-item">
            <a href="../seniors/?id=783254690132" class="nav-link">
              <i class="nav-icon fas fa-wheelchair"></i>
              <p>Seniors List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../seniors/sms/?id=783254690139" class="nav-link">
              <i class="nav-icon fas fa-inbox"></i>
              <p>Seniors SMS</p>
            </a>
          </li>
      </li>
          <?php if ($role === 'admin'): ?>
          <li class="nav-item">
            <a href="../Settings/?id=401289736548" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings</p>
            </a>
          </li>
      </li>
      <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
  <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Users List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../?id=634829175920">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                  <td><?= htmlspecialchars($user['name']) ?></td>
                  <td><?= htmlspecialchars($user['lastname']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>
                  <td><?= htmlspecialchars($user['role']) ?></td>
                  <td>
                    <a href="./view/?id=<?= htmlspecialchars($user['id']) ?>" class="btn btn-success"><i class="fa fa-eye"></i> View</a>
                    <a href="./edit/?id=<?= htmlspecialchars($user['id']) ?>" class="btn btn-warning"><i class='fa fa-fw fa-edit'></i> Edit</a>
                    <?php if ($role === 'admin' && $user['role'] !== 'admin'): ?>
                      <a href="./delete/?id=<?= htmlspecialchars($user['id']) ?>" class="btn btn-danger"><i class='fa fa-fw fa-trash'></i> Delete</a>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

        </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Logout?</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Do you like to close this page?</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              <button type="button" class="btn btn-primary" onclick="window.location.href='../logout.php';">Logout</button>
            </div>
          </div>
        </div>
      </div>

  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="https://www.Facebook.com/RiczzTV">Municipality of Manaoag</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<!-- REQUIRED SCRIPTS -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../dist/js/adminlte.js"></script>
<script src="../plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../plugins/raphael/raphael.min.js"></script>
<script src="../plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../plugins/jquery-mapael/maps/usa_states.min.js"></script>
<script src="../plugins/chart.js/Chart.min.js"></script>
<script src="../dist/js/demo.js"></script>
<script src="../dist/js/pages/dashboard2.js"></script>
<script src="../js/blur.js"></script>
<?php if ($security_settings): ?><script src="../js/settings.js"></script><?php endif; ?>
<?php if ($demo): ?><script src="../js/demo.js"></script><?php endif; ?>
</body>
</html>
