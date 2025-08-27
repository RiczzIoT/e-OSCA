<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

include "../includes/connect.php";

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

// Validate URL parameter
$id = null;
if (isset($_GET['id']) && preg_match('/^\d{12}$/', $_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: ../404/");
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
$name = $user_data ? $user_data["name"] : "";
$profile_picture = $user_data ? $user_data["profile_picture"] : "";

$user_count_result = $conn->query("SELECT COUNT(*) as user_count FROM users");
$user_count_row = $user_count_result->fetch_assoc();
$user_count = $user_count_row["user_count"];

$senior_count_result = $conn->query("SELECT COUNT(*) as senior_count FROM senior");
$senior_count_row = $senior_count_result->fetch_assoc();
$senior_count = $senior_count_row["senior_count"];

$user_logs_count_result = $conn->query("SELECT COUNT(*) as user_logs_count FROM user_logs");
$user_logs_count_row = $user_logs_count_result->fetch_assoc();
$user_logs_count = $user_logs_count_row["user_logs_count"];

$age_groups = [];
for ($i = 0; $i <= 130; $i += 10) {
    $age_groups[] = $conn->query("SELECT COUNT(*) as count FROM senior WHERE age BETWEEN $i AND " . ($i + 9))->fetch_assoc()["count"];
}

$total_senior_result = $conn->query("SELECT COUNT(*) as total_senior FROM senior");
$total_senior_row = $total_senior_result->fetch_assoc();
$total_senior = $total_senior_row["total_senior"];

if ($role === "admin" || $role === "mayor" || $role === "staff") {
    // Authorized roles have access
} else {
    header("Location: admin/?id=177985647998");
    exit();
}

// Fetch messages for the logged-in user
//$stmt = $conn->prepare("SELECT * FROM messages WHERE recipient_id = ? ORDER BY timestamp DESC LIMIT 3");
//$stmt->bind_param("i", $user_id);
//$stmt->execute();
//$messages_result = $stmt->get_result();
//$messages = $messages_result->fetch_all(MYSQLI_ASSOC);
//$message_count = count($messages);

// Query for new seniors added per day for the last 30 days
$new_seniors_daily = [];
for ($i = 0; $i < 30; $i++) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM senior WHERE DATE(created_at) = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()["count"];
    $new_seniors_daily[$date] = $count;
}

// Query for new seniors added per month
$new_seniors_monthly = [];
$stmt = $conn->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count FROM senior GROUP BY month ORDER BY month DESC LIMIT 12");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $new_seniors_monthly[$row['month']] = $row['count'];
}

// Query for gender distribution of seniors
$stmt = $conn->prepare("SELECT sex, COUNT(*) as count FROM senior GROUP BY sex");
$stmt->execute();
$result = $stmt->get_result();
$gender_distribution = [];
while ($row = $result->fetch_assoc()) {
    $gender_distribution[$row['sex']] = $row['count'];
}

// Now prepare the data for the chart
$labels_daily = json_encode(array_keys($new_seniors_daily));
$data_daily = json_encode(array_values($new_seniors_daily));

$labels_gender = json_encode(array_keys($gender_distribution));
$data_gender = json_encode(array_values($gender_distribution));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../dist/css/blur.css">
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">
  <link rel="icon" type="image/png" href="../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div id="blur-overlay"></div>
<div class="wrapper">

<div class="preloader flex-column justify-content-center align-items-center">
    <?php if ($role === 'admin' || $role === 'staff' || $role === 'mayor'): ?>
        <img class="animation__shake" src="../assets/<?= htmlspecialchars($logo) ?>" alt="Walang Logo" height="400" width="400">
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
  
<!-- Messages Dropdown Menu 
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        </?php if ($message_count > 0): ?>
            <span id="messages-badge" class="badge badge-danger navbar-badge"></?php echo $message_count; ?></span>
        </?php else: ?>
            <span id="messages-badge" class="badge badge-danger navbar-badge" style="display: none;"></span>
        </?php endif; ?>
    </a>
    <div id="messages-dropdown-menu" class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        </?php if ($message_count > 0): ?>
            </?php foreach ($messages as $message): ?>
                <a href="../mailbox/read-mail.php?id=</?php echo $message['id']; ?>" class="dropdown-item">

                    <div class="media">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                </?php echo htmlspecialchars($message['email']); ?>
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm"></?php echo htmlspecialchars($message['subject']); ?></p>
                            <p class="text-sm text-muted" data-timestamp="</?php echo $message['timestamp']; ?>"></p>
                        </div>
                    </div>

                </a>
                <div class="dropdown-divider"></div>
            </?php endforeach; ?>
            <a href="../mailbox/read-mail.php" class="dropdown-item dropdown-footer">See All Messages</a>
        </?php else: ?>
            <a href="#" class="dropdown-item">No Messages</a>
        </?php endif; ?>
    </div>
</li>-->

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
    <a href="?id=177985647998" class="brand-link">
    <?php if ($logo): ?>
      <img src="../assets/<?= htmlspecialchars($logo) ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
          <a href="../users/view/?id=<?= $user_id ?>"><?=($role) ?>, <?= htmlspecialchars($name) ?></a>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
               <li class="nav-item menu-open">
                <a href="?id=177985647998" class="nav-link active">
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
            <h1 class="m-0">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <?php if ($role === 'admin'): ?>
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $user_count ?></h3>

                <p>Users</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
              <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
          <?php endif; ?>
          <?php if ($role === 'admin' || $role === 'mayor'): ?>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $senior_count ?><sup style="font-size: 20px"></sup></h3>

                <p>Seniors</p>
              </div>
              <div class="icon">
                <i class="fas fa-wheelchair"></i>
              </div>
              <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
          <?php endif; ?>
          <?php if ($role === 'admin'): ?>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= $user_logs_count ?></h3>

                <p>User Logs</p>
              </div>
              <div class="icon">
                <i class="fas fa-history"></i>
              </div>
              <!--<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
            </div>
          </div>
          <?php endif; ?>
          <!-- 
          <div class="col-lg-3 col-6">
            
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>0000000000000</h3>

                <p>Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
        </div>
        <div class="row">
  <!-- Line Chart -->
  <div class="col-md-6">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">
          <i class="far fa-chart-bar"></i> Line Chart
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="lineChart" style="height: 300px;"></canvas>
      </div>
    </div>
  </div>

  <!-- Donut Chart -->
  <div class="col-md-6">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <div class="card-body">
        <canvas id="donutChart" style="height: 300px;"></canvas>
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
        <a href="../logout.php" class="btn btn-primary">Logout</a>
      </div>
    </div>
  </div>
</div>
<!-- ./wrapper -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/chart.js/Chart.min.js"></script>
<script src="../plugins/sparklines/sparkline.js"></script>
<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="../plugins/moment/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../plugins/summernote/summernote-bs4.min.js"></script>
<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../dist/js/adminlte.js"></script>
<script src="../dist/js/demo.js"></script>
<script src="../dist/js/pages/dashboard.js"></script>
<script src='../js/324234723498.js'></script>
<script src="../js/blur.js"></script>
<script src="../js/crypto-js.min.js"></script>
<?php if ($security_settings): ?><script src="../js/settings.js"></script><?php endif; ?>
<?php if ($demo): ?><script src="../js/demo.js"></script><?php endif; ?>
  <!-- Script for charts -->
<script>
  // Line Chart
const ctxLine = document.getElementById('lineChart').getContext('2d');
const lineChart = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: <?= $labels_daily ?>,
        datasets: [{
            label: 'New Seniors Added Daily',
            data: <?= $data_daily ?>,
            fill: true,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { display: false } // This hides the legend
        }
    }
});

// Donut Chart
const ctxDonut = document.getElementById('donutChart').getContext('2d');
const donutChart = new Chart(ctxDonut, {
    type: 'doughnut',
    data: {
        labels: <?= $labels_gender ?>,
        datasets: [{
            label: 'Gender Distribution',
            data: <?= $data_gender ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false } // This hides the legend
        }
    }
});

</script>

</body>
</html>
