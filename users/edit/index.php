<?php
session_start();
include '../../includes/connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; // Get the role from the session

// Check if `id` is set and valid
$user_id_from_url = isset($_GET['id']) ? $_GET['id'] : null;

if ($user_id_from_url === null || !is_numeric($user_id_from_url) || $user_id_from_url < 1) {
    // Show 404 error
    http_response_code(404);
    include('../../404/');
    exit();
}

// Fetch user details from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id_from_url);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // Show 404 error if user not found
    http_response_code(404);
    include('../../404/');
    exit();
}

// Check if user is trying to update details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user details
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $suffix = $_POST['suffix'];
    $age = $_POST['age'];
    $birthdate = $_POST['birthdate'];
    $barangay = $_POST['barangay'];

    // Check if a new profile picture is uploaded
    if ($_FILES['profile_picture']['error'] === 0) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target_dir = "../../Profile_pictures/";
        $target_file = $target_dir . basename($profile_picture);
        // Move uploaded file to target directory
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    } else {
        $profile_picture = $user['profile_picture'];
    }

    // Check if password is provided and hash it
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $password_hash = hash('sha256', $password);
    } else {
        $password_hash = $user['password']; // Keep the current password if not changed
    }

    // Prevent changing role if user is admin
    if ($user['role'] === 'admin') {
        $role = 'admin';
    }

    // Update query
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, role = ?, middlename = ?, lastname = ?, suffix = ?, age = ?, birthdate = ?, barangay = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("sssssssissss", $name, $email, $password_hash, $role, $middlename, $lastname, $suffix, $age, $birthdate, $barangay, $profile_picture, $user_id_from_url);
    $stmt->execute();

    header("Location: ../?id=290384756120");
    exit();
}

// Fetch current settings (assuming this part is correct)
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
  header("Location: ../../dashboard.php?id=1");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OSCA Manaoag | Edit User</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="../../dist/css/blur.css">
  <link rel="icon" type="image/png" href="../../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
  <style>
    main {
    max-width: 1200px;
    margin: auto;
}

main h2 {
    text-align: center;
}

main form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

main .row {
    display: flex;
    width: 100%;
    justify-content: space-between;
    margin-bottom: 20px;
}

main .row.center {
    justify-content: center;
}

main .row.right {
    justify-content: flex-end;
}

main .column {
    flex: 1;
    margin: 0 10px;
    display: flex;
    flex-direction: column;
}

main label {
    margin-bottom: 5px;
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

<!--<div class="card-body">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                  Launch Default Modal
                </button>-->
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
              <li class="nav-item ">
                <a href="../../users/?id=290384756120" class="nav-link active">
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
  <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../?id=964587213670">Back</a></li>
              <li class="breadcrumb-item active">Edit</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
  <div class="container-fluid">
  <main>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row center">
            <div class="column">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="column">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
        </div>
        <div class="row">
            <div class="column">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="column">
                <label for="middlename">Middle Name:</label>
                <input type="text" id="middlename" name="middlename" value="<?= htmlspecialchars($user['middlename']) ?>">
            </div>
            <div class="column">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>">
            </div>
            <div class="column">
                <label for="suffix">Suffix:</label>
                <input type="text" id="suffix" name="suffix" value="<?= htmlspecialchars($user['suffix']) ?>">
            </div>
        </div>
        <div class="row">
            <div class="column">
                <label for="birthdate">Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate" value="<?= htmlspecialchars($user['birthdate']) ?>" required>
            </div>
            <div class="column">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?= htmlspecialchars($user['age']) ?>">
            </div>
            <div class="column">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <?php if ($role === 'admin'): ?>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                        <option value="mayor" <?= $user['role'] == 'mayor' ? 'selected' : '' ?>>mayor</option>
                    <?php elseif ($role === 'mayor'): ?>
                        <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                        <option value="mayor" <?= $user['role'] == 'mayor' ? 'selected' : '' ?>>mayor</option>
                    <?php elseif ($role === 'staff'): ?>
                        <option value="staff" <?= $user['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="column">
                <label for="barangay">Barangay:</label>
                <input type="text" id="barangay" name="barangay" value="<?= htmlspecialchars($user['barangay']) ?>">
            </div>
        </div>
        <div class="row center">
            <div class="column">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture">
            </div>
        </div>
        <div class="row right">
            <button class="btn btn-info btn-block btn-flat" type="submit"><i class="fa fa-check"></i>Update User</button>
        </div>
    </form>
</main>
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
    <strong>Copyright &copy; 2024 <a href="https://www.Facebook.com/RiczzTV">Municipality of Manaoag</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../../dist/js/adminlte.js"></script>
<script src="../../plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="../../plugins/raphael/raphael.min.js"></script>
<script src="../../plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="../../plugins/jquery-mapael/maps/usa_states.min.js"></script>
<script src="../../plugins/chart.js/Chart.min.js"></script>
<script src="../../dist/js/demo.js"></script>
<script src="../../dist/js/pages/dashboard2.js"></script>
<script src="../../js/blur.js"></script>
<script src='../../js/324234723498.js'></script>
<script src="../../js/crypto-js.min.js"></script>
<?php if ($security_settings): ?><script src="../../js/settings.js"></script><?php endif; ?>
<?php if ($demo): ?><script src="../../js/demo.js"></script><?php endif; ?>
</body>
</html>
