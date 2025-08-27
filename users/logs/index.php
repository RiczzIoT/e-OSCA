<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../includes/connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; // Get the role from the session

// Check if `id` is set and valid
$page_id = isset($_GET['id']) ? $_GET['id'] : null;

// Validate URL parameter
$id = null;
if (isset($_GET['id']) && preg_match('/^\d{12}$/', $_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: ../../404/");
    exit();
}

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
  header("Location: ../../admin/?id=177985647998");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OSCA Manaoag | Logs</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../dist/css/blur.css">
  <link rel="icon" type="image/png" href="../../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
  <style>
    #popup-image {
        position: absolute;
        display: none;
    }
    #popup-image img {
        width: 220px;
        height: 200px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div id="blur-overlay"></div>
<div class="wrapper">
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../../dist/img/manaoag.png" alt="AdminLTELogo" height="400" width="400">
  </div>

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
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
</nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../../?id=177985647998" class="brand-link">
    <?php if ($logo): ?>
      <img src="../../assets/<?= htmlspecialchars($logo) ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <?php endif; ?>
      <span class="brand-text font-weight-light">OSCA Manaoag</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php if ($profile_picture): ?>
          <img src="../../Profile_pictures/<?= htmlspecialchars($profile_picture) ?>" class="img-circle elevation-2" alt="User Image">
          <?php endif; ?>
        </div>
        <div class="info">
          <a href="#" class="d-block"><?=($role) ?>, <?= htmlspecialchars($name) ?></a>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
               <li class="nav-item">
                <a href="../../?id=177985647998" class="nav-link">
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
              <li class="nav-item">
                <a href="../../users/?id=290384756120" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users List</p>
                </a>
              </li>
              <?php if ($role === 'admin'): ?>
              <li class="nav-item">
                <a href="?id=518736420591" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Logs Users</p>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </li>
          <li class="nav-item">
            <a href="../../seniors/?id=783254690132" class="nav-link">
              <i class="nav-icon fas fa-wheelchair"></i>
              <p>Seniors List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../../seniors/sms/?id=783254690139" class="nav-link">
              <i class="nav-icon fas fa-inbox"></i>
              <p>Seniors SMS</p>
            </a>
          </li>
      </li>
          <?php if ($role === 'admin'): ?>
          <li class="nav-item">
            <a href="../../Settings/?id=401289736548" class="nav-link">
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Login Logs</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../?id=783254690132">Back</a></li>
              <li class="breadcrumb-item active">Logs</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Action</th>
                    <th>Role</th>
                    <th>Log Time</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
    include '../../includes/connect.php';

    $result = $conn->query("SELECT user_logs.user_id, users.email, users.name, user_logs.action, users.role, user_logs.log_time, user_logs.image_path FROM user_logs INNER JOIN users ON user_logs.user_id = users.id");
    while ($row = $result->fetch_assoc()) {
        $imagePath = $row['image_path'] ? "../../" . $row['image_path'] : 'image.png';
        echo "<tr><td style='padding: 10px; border-bottom: 1px solid #ddd;' data-image='" . $imagePath . "'>" . $row['email'] . "</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . $row['name'] . "</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . $row['action'] . "</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . $row['role'] . "</td><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . $row['log_time'] . "</td></tr>";
    }
    ?>
                  </tfoot>
                </table>
                <div id="popup-image">
                  <img id="popup-image-img">
                </div>
              </div>
            </div>
              </div>
            </div>
          </div>
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
      <button type="button" class="btn btn-primary" onclick="window.location.href='../../logout.php';">Logout</button>
    </div>
  </div>
</div>
</div>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2024 <a href="https://www.facebook.com/RiczzTV">Municipality of Manaoag</a>.</strong> All rights reserved.
  </footer>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../../js/blur.js"></script>
<script src="../../js/crypto-js.min.js"></script>
<script src='../../js/324234723498.js'></script>
<?php if ($security_settings): ?><script src="../../js/settings.js"></script><?php endif; ?>
<?php if ($demo): ?><script src="../../js/demo.js"></script><?php endif; ?>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../dist/js/demo.js"></script>
<script src="../../dist/js/image.js"></script>
<script src="../../dist/js/logs.js"></script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
</body>
</html>
