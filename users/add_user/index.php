<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../includes/connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $suffix = $_POST['suffix'];
    $age = $_POST['age'];
    $birthdate = $_POST['birthdate'];
    $barangay = $_POST['barangay'];
    $profile_picture = $_FILES['profile_picture']['name'];
    $password_hash = hash('sha256', $password);

    // Save profile picture
    $target_dir = "../../Profile_pictures/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    if (!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        echo "Failed to upload profile picture.";
        exit();
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (email, name, password, role, middlename, lastname, suffix, age, birthdate, barangay, profile_picture) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssissss", $email, $name, $password_hash, $role, $middlename, $lastname, $suffix, $age, $birthdate, $barangay, $profile_picture);

    if ($stmt->execute()) {
        header("Location: ../?id=290384756120");
        exit();
    } else {
        echo "Error adding new user.";
    }
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
  header("Location: ../dashboard.php?id=1");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OSCA Manaoag | Add</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../dist/css/blur.css">
  <link rel="icon" type="image/png" href="../../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
</head>
<div id="blur-overlay"></div>
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
                      <a href="?id=177985647998" class="nav-link">
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Form</h1>
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
            <div class="col-md-12">
                <div class="card card-danger">
                    <div class="card-body">
                        <form id="residentForm" action="./?id=518736420533" method="post" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-md-6">
                                    <label for="name">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="middlename">Middle Name:</label>
                                    <input type="text" class="form-control" id="middlename" name="middlename">
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-md-6">
                                    <label for="lastname">Last Name:</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="suffix">Suffix:</label>
                                    <input type="text" class="form-control" id="suffix" name="suffix">
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-md-3">
                                    <label for="birthdate">Birthdate:</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="age">Age:</label>
                                    <input type="number" class="form-control" id="age" name="age" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="role">Role:</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="mayor">mayor</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="barangay">Barangay:</label>
                                    <input type="text" class="form-control" id="barangay" name="barangay" required value="Brgy. " oninput="ensurePrefix()">
                                </div>
                            </div>
                            <div class="form-row mt-3">
                                <div class="col-md-6">
                                <label for="profile_picture">Profile Picture</label>
                              <div class="input-group">
                                <div class="custom-file">
                                <input type="file" class="custom-file-input" id="profile_picture" name="profile_picture" accept="image/*">
                                <label class="custom-file-label" for="profile_picture">Choose file</label>
                             </div>
                            </div>
                          </div>
                        </div>
                            <div class="form-actions mt-3">
                                <button type="submit" class="btn btn-primary" name="insert">Add User</button>
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
              <button type="button" class="btn btn-primary" onclick="window.location.href='../../logout.php';">Logout</button>
            </div>
          </div>
        </div>
      </div>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2024 <a href="https://www.facebook.com/Municipality of Manaoag">Municipality of Manaoag</a>.</strong> All rights reserved.
  </footer>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../dist/js/demo.js"></script>
<script src="../../dist/js/auto_age.js"></script>
<script src="../../js/blur.js"></script>
<script src='../../js/324234723498.js'></script>
<script src="../../js/crypto-js.min.js"></script>
<?php if ($security_settings): ?><script src="../../js/settings.js"></script><?php endif; ?>
<?php if ($demo): ?><script src="../../js/demo.js"></script><?php endif; ?>
  <script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>
