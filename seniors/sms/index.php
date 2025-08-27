<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../includes/connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$page_id = isset($_GET["id"]) ? $_GET["id"] : null;
$id = null;
if (isset($_GET["id"]) && preg_match('/^\d{12}$/', $_GET["id"])) {
    $id = $_GET["id"];
} else {
    header("Location: ../../404");
    exit();
}

$stmt = $conn->prepare("SELECT favicon, logo, security_settings, sms_url, demo FROM settings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$settings = $result->fetch_assoc();
$favicon = $settings ? $settings["favicon"] : "";
$logo = $settings ? $settings["logo"] : "";
$security_settings = $settings ? $settings["security_settings"] : 0;
$demo = $settings ? $settings["demo"] : 0;
$sms_url = $settings ? $settings["sms_url"] : ""; // Add default empty value for sms_url
$cache_buster = time();

$stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$name = $user_data ? $user_data['name'] : '';
$profile_picture = $user_data ? $user_data['profile_picture'] : '';

if ($role === 'admin' || $role === 'mayor' || $role === 'staff') {
} else {
    header("Location: ../../dashboard.php?id=1");
    exit();
}

// Fetch seniors data
$stmt = $conn->prepare("
    SELECT 
        id,
        CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name, ' ', IFNULL(suffix, '')) AS full_name,
        contact AS contact_number,
        qr_code
    FROM senior
    WHERE status = 'active'
");
$stmt->execute();
$result = $stmt->get_result();
$seniors = $result->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OSCA Manaoag | Senior Registration</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../dist/css/blur.css">
  <link rel="icon" type="image/png" href="../../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
  <!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="../../plugins/sweetalert2/sweetalert2.min.css">
<script src="../../plugins/sweetalert2/sweetalert2.min.js"></script>

</head>
<div id="blur-overlay"></div>
<?php
if (isset($_SESSION['error'])) {
    echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '" . $_SESSION['error'] . "',
        confirmButtonText: 'Okay'
    });
    </script>";
    unset($_SESSION['error']); // Clear the error message after displaying
}
?>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <div class="preloader flex-column justify-content-center align-items-center">
        <?php if ($logo): ?>
          <img class="animation__shake" src="../../Assets/<?= htmlspecialchars($logo) ?>" alt="Walang Logo" height="400" width="400">
          <?php endif; ?>
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
                <a><?=($role) ?>, <?= htmlspecialchars($name) ?></a>
              </div>
            </div>

            <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                     <li class="nav-item">
                      <a href="../../admin/?id=177985647998" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                      </a>
                    </li>
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
                      <a href="../../users/?id=290384756120" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Users List</p>
                      </a>
                    </li>
                    <?php if ($role === 'admin'): ?>
                    <li class="nav-item">
                      <a href="../../users/logs/?id=518736420591" class="nav-link">
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
            <a href="./?id=783254690139" class="nav-link active">
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
            <h1>Senior SMS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../?id=783254690132">Back</a></li>
              <li class="breadcrumb-item active">New</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- SMS Form -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Send SMS Notification</h3>
                    </div>
                    <form action="./send_pension_sms.php" method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="date">Pickup Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="form-group">
                                <label for="time">Pickup Time</label>
                                <input type="time" class="form-control" id="time" name="time" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Custom Message (Optional)</label>
                                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Add any additional message for seniors..."></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Send SMS</button>
                        </div>
                    </form>
                </div>
                <!-- /.SMS Form -->
                
                <!-- Seniors List -->
                <div class="card card-secondary mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Seniors List</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contact Number</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($seniors as $index => $senior): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($senior['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($senior['contact_number']); ?></td>
                                        <td>
                                            <span class="badge badge-success">Sent Successfully</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.Seniors List -->
            </div>
        </div>
    </div>
</section>
</div>


  <div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Logout</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to logout?</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a href="../../logout.php" class="btn btn-primary">Logout</a>
      </div>
    </div>
  </div>
</div>

<?php
  // Display SweetAlert Modals for success, error, or info
  if (isset($_SESSION['success'])) {
      echo "<script>
      Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: '" . $_SESSION['success'] . "',
          confirmButtonText: 'Okay'
      });
      </script>";
      unset($_SESSION['success']);
  }

  if (isset($_SESSION['error'])) {
      echo "<script>
      Swal.fire({
          icon: 'error',
          title: 'Error',
          text: '" . $_SESSION['error'] . "',
          confirmButtonText: 'Okay'
      });
      </script>";
      unset($_SESSION['error']);
  }

  if (isset($_SESSION['info'])) {
      echo "<script>
      Swal.fire({
          icon: 'info',
          title: 'Info',
          text: '" . $_SESSION['info'] . "',
          confirmButtonText: 'Okay'
      });
      </script>";
      unset($_SESSION['info']);
  }
  ?>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2024 <a href="https://www.facebook.com/Municipality of Manaoag">Municipality of Manaoag</a>.</strong> All rights reserved.
  </footer>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../dist/js/demo.js"></script>
<script src="../../js/blur.js"></script>
<script src='../../js/324234723498.js'></script>
<script src="../../js/crypto-js.min.js"></script>
<?php if ($security_settings): ?><script src="../../js/settings.js"></script><?php endif; ?>
<?php if ($demo): ?><script src="../../js/demo.js"></script><?php endif; ?>
</body>
</html>
