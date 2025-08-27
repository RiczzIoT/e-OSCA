<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../../includes/connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$page_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($page_id === null || !is_numeric($page_id) || $page_id < 1) {
    http_response_code(404);
    include('../../404.php');
    exit();
}

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

$stmt = $conn->prepare("SELECT name, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$name = $user_data ? $user_data['name'] : '';
$profile_picture = $user_data ? $user_data['profile_picture'] : '';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM senior WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = [
            'profile_picture' => $row['profile_picture'],
            'id_number' => $row['id_number'],
            'full_name' => "{$row['first_name']} {$row['middle_name']} {$row['last_name']} {$row['suffix']}",
            'sex' => $row['sex'],
            'birthplace' => $row['birthplace'],
            'birthdate' => $row['birthdate'],
            'age' => $row['age'],
            'civil_status' => $row['civil_status'],
            'contact' => "{$row['contact_type']} - {$row['contact']}",
            'citizenship' => $row['citizenship'],
            'religion' => $row['religion'],
            'barangay' => $row['barangay'],
            'municipal' => $row['municipal'],
            'province' => $row['province'],
            'emergency' => $row['emergency'],
            'email' => $row['email'],
            'sss' => $row['sss'],
            'gsis' => $row['gsis'],
            'philhealth' => $row['philhealth'],
            'classification' => $row['classification'],
            'blood_type' => $row['blood_type'],
            'highest_educ_attainment' => $row['highest_educ_attainment'],
            'employment_status' => $row['employment_status'],
            'monthly_pension' => $row['monthly_pension'],
            'qr_code' => $row['qr_code']
        ];
    } else {
        $response = ['error' => 'No resident found with ID: ' . $id];
    }
} else {
    $response = ['error' => 'Invalid ID'];
}

$conn->close();

if ($role !== 'admin' && $role !== 'mayor' && $role !== 'staff') {
    header("Location: ../dashboard.php?id=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OSCA Manaoag | Sernior Info</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../dist/css/blur.css">
  <link rel="icon" type="image/png" href="../../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
</head>
<body class="hold-transition sidebar-mini">
<div id="blur-overlay"></div>
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
                      <a href="../../?id=177985647998" class="nav-link">
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
                <li class="nav-item mane_open">
                  <a href="../../seniors/?id=783254690132" class="nav-link active">
                    <i class="nav-icon fas fa-wheelchair"></i>
                    <p>Seniors List</p>
                  </a>
                </li>
                <li class="nav-item">
            <a href="../sms/?id=783254690139" class="nav-link">
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
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../?id=964587213670">Back</a></li>
              <li class="breadcrumb-item active">Senior Profile</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                 src="../<?= isset($response['profile_picture']) ? $response['profile_picture'] : 'default-profile.jpg' ?>"
                                 alt="Profile Picture" style="height: 150px; width: 150px;">
                        </div>

                        <h3 class="profile-username text-center"><?= isset($response['full_name']) ? $response['full_name'] : 'No Name' ?></h3>

                        <p class="text-muted text-center"><?= isset($response['age']) ? $response['age'] . ' years old' : 'Age not available' ?></p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Control Number</b> <a class="float-right"><?= isset($response['id_number']) ? $response['id_number'] : 'N/A' ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Contact</b> <a class="float-right"><?= isset($response['contact']) ? $response['contact'] : 'N/A' ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Barangay</b> <a class="float-right"><?= isset($response['barangay']) ? $response['barangay'] : 'N/A' ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
<div class="card card-primary card-outline">
    <div class="card-body box-profile">
    <div class="text-center">
    <img
         src="<?= isset($response['qr_code']) ? $response['qr_code'] : '../default-qr-code.png' ?>"
         src="../<?= isset($response['qr_code']) ? $response['qr_code'] : 'default-qr-code.png' ?>"
         alt="QR Code" style="width: 200px; height: 200px;">
</div>
    </div>
</div>

            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Details</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($response['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $response['error'] ?>
                            </div>
                        <?php else: ?>
                            <table class="table table-bordered">
                                <!--<tr>
                                    <th>ID Number</th>
                                    <td></?= $response['id_number'] ?></td>
                                </tr>-->
                                <tr>
                                    <th>Full Name</th>
                                    <td><?= $response['full_name'] ?></td>
                                </tr>
                                <tr>
                                    <th>Birthdate</th>
                                    <td><?= $response['birthdate'] ?></td>
                                </tr>
                                <tr>
                                    <th>Birth Place</th>
                                    <td><?= $response['birthplace'] ?></td>
                                </tr>
                                <tr>
                                    <th>Sex</th>
                                    <td><?= $response['sex'] ?></td>
                                </tr>
                                <tr>
                                    <th>Age</th>
                                    <td><?= $response['age'] ?></td>
                                </tr>
                                <tr>
                                    <th>Barangay</th>
                                    <td><?= $response['barangay'] ?></td>
                                </tr>
                                <tr>
                                    <th>Municipal</th>
                                    <td><?= $response['municipal'] ?></td>
                                </tr>
                                <tr>
                                    <th>Province</th>
                                    <td><?= $response['province'] ?></td>
                                </tr>
                                <tr>
                                    <th>Civil Status</th>
                                    <td><?= $response['civil_status'] ?></td>
                                </tr>
                                <tr>
                                    <th>Religion</th>
                                    <td><?= $response['religion'] ?></td>
                                </tr>
                                <tr>
                                    <th>Emergency Contact</th>
                                    <td><?= $response['emergency'] ?></td>
                                </tr>
                                <tr>
                                    <th>E-mail</th>
                                    <td><?= $response['email'] ?></td>
                                </tr>
                                <tr>
                                    <th>SSS no.</th>
                                    <td><?= $response['sss'] ?></td>
                                </tr>
                                <tr>
                                    <th>GSIS no.</th>
                                    <td><?= $response['gsis'] ?></td>
                                </tr>
                                <tr>
                                    <th>Philhealth</th>
                                    <td><?= $response['philhealth'] ?></td>
                                </tr>
                                <tr>
                                    <th>Classification</th>
                                    <td><?= $response['classification'] ?></td>
                                </tr>
                                <tr>
                                    <th>Blood Type</th>
                                    <td><?= $response['blood_type'] ?></td>
                                </tr>
                                <tr>
                                    <th>Highest Education Attainment</th>
                                    <td><?= $response['highest_educ_attainment'] ?></td>
                                </tr>
                                <tr>
                                    <th>Employment Status</th>
                                    <td><?= $response['employment_status'] ?></td>
                                </tr>
                                <tr>
                                    <th>Monthly Pension</th>
                                    <td><?= $response['monthly_pension'] ?></td>
                                </tr>
                                <tr>
                                    <th>Citizenship</th>
                                    <td><?= $response['citizenship'] ?></td>
                                </tr>
                            </table>
                        <?php endif; ?>
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
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="../../dist/js/demo.js"></script>
<script src="../../js/blur.js"></script>
<script src='../../js/324234723498.js'></script>
<script src="../../js/crypto-js.min.js"></script>
<?php if ($security_settings): ?><script src="../../js/settings.js"></script><?php endif; ?>
<?php if ($demo): ?><script src="../../js/demo.js"></script><?php endif; ?>
</body>
</html>
