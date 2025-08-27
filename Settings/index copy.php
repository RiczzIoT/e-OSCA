<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
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
    header("Location: ../404.php");
    exit();
}

// Fetch current settings
$result = $conn->query("SELECT capture_image, background_image, favicon, logo, security_settings FROM settings WHERE user_id=$user_id");
$settings = $result->fetch_assoc();
$capture_image = $settings ? $settings['capture_image'] : 0;
$background_image = $settings ? $settings['background_image'] : '';
$favicon = $settings ? $settings['favicon'] : '';
$logo = $settings ? $settings['logo'] : '';
$security_settings = $settings ? $settings['security_settings'] : 0;

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
  <title>OSCA Manaoag | Settings</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <link rel="stylesheet" href="../plugins/bs-stepper/css/bs-stepper.min.css">
  <link rel="stylesheet" href="../plugins/dropzone/min/dropzone.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../dist/css/blur.css">
  <link rel="icon" type="image/png" href="../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
</head>
<div id="blur-overlay"></div>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/manaoag.png" alt="AdminLTELogo" height="400" width="400">
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
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../users/?id=290384756120" class="nav-link">
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
          <?php if ($role === 'admin'): ?>
          <li class="nav-item">
            <a href="../Settings/?id=401289736548" class="nav-link active">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Settings</p>
            </a>
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
            <h1>Settings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../admin/?id=634829175920">Home</a></li>
              <li class="breadcrumb-item active">Settings</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Switch</h3>
          </div>
          <div class="card-body">
            <form action="update_settings.php?id=163280475910" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="user_id">User:</label>
                <select name="user_id" id="user_id" class="form-control">
                  <?php
                  include '../includes/connect.php';
                  $result = $conn->query("SELECT id, role FROM users");
                  while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}' data-id='{$row['id']}'>{$row['role']}</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="capture_image">Capture Image:</label>
                <input type="checkbox" name="capture_image" id="capture_image" <?= $capture_image ? 'checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
              </div>
              <div class="form-group">
                <label for="security_settings">Security Settings:</label>
                <input type="checkbox" name="security_settings" id="security_settings" <?= $security_settings ? 'checked' : '' ?> data-bootstrap-switch data-off-color="danger" data-on-color="success">
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Upload Files</h3>
          </div>
          <div class="card-body">
            <form action="update_image.php?id=249375180920" method="post" enctype="multipart/form-data">
              <div class="form-group">
              <label for="background">Upload Background (JPEG):</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="background_image" name="background_image" accept="image/*">
                    <label class="custom-file-label" for="background_image">Choose file</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="favicon">Upload Favicon (PNG):</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="favicon" <?= $favicon ? 'checked' : '' ?> name="favicon" accept="image/png">
                    <label class="custom-file-label" for="favicon">Choose file</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="logo">Upload Logo (PNG):</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/png">
                    <label class="custom-file-label" for="logo">Choose file</label>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
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
          <button type="button" class="btn btn-primary" onclick="window.location.href='../logout.php';">Logout</button>
        </div>
      </div>
    </div>
  </div>
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2024 <a href="https://www.facebook.com/RiczzTV">RiczzIoT</a>.</strong> All rights reserved.
  </footer>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/select2/js/select2.full.min.js"></script>
<script src="../plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<script src="../plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="../plugins/bs-stepper/js/bs-stepper.min.js"></script>
<script src="../plugins/dropzone/min/dropzone.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/js/demo.js"></script>
<script src="../js/blur.js"></script>
<?php if ($security_settings): ?><script src="../js/settings.js"></script><?php endif; ?>
<script>
  document.getElementById('user_id').addEventListener('change', function() {
    var userId = this.value;

    if (userId) {
        fetch(`get_user_settings.php?user_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                // Update switch states based on the fetched data
                document.getElementById('capture_image').checked = data.capture_image;
                document.getElementById('security_settings').checked = data.security_settings;

                // Re-initialize Bootstrap Switch for updated elements
                $("[data-bootstrap-switch]").bootstrapSwitch('state', data.capture_image);
                $("[data-bootstrap-switch]").bootstrapSwitch('state', data.security_settings);
            })
            .catch(error => console.error('Error:', error));
    }
});

  $(document).ready(function() {
    $("[data-bootstrap-switch]").bootstrapSwitch();
  });
</script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>

