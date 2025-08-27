<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: ./admin/?id=634829175920");
    } elseif ($_SESSION['role'] == 'mayor') {
        header("Location: ./admin/?id=405671293814");
    } elseif ($_SESSION['role'] == 'staff') {
        header("Location: ./admin/?id=823746519032");
    }
    exit();
}

include './includes/connect.php';

// Default user_id or handle the case where user_id is not set
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 21;

$stmt = $conn->prepare("SELECT background_image, favicon, logo, security_settings, demo FROM settings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$background_image = '';
$favicon = '';
$logo = '';
$security_settings = 0;
$demo = 0;
$cache_buster = time(); // Forces the browser to refresh the favicon

if ($result->num_rows > 0) {
    $settings = $result->fetch_assoc();
    $background_image = htmlspecialchars($settings['background_image']);
    $favicon = htmlspecialchars($settings['favicon']);
    $logo = htmlspecialchars($settings['logo']);
    $security_settings = $settings["security_settings"];
    $demo = $settings["demo"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log in</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="./dist/css/adminlte.min.1.css">
  <link rel="stylesheet" href="./dist/css/blur.css">
  <link rel="icon" type="image/png" href="./assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
  <?php if ($background_image) : ?>
  <style>
    body {
        background: url('./assets/<?= htmlspecialchars($background_image) ?>') no-repeat center center fixed;
        background-size: cover;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        height: 100%;
    }
  </style>
  <?php endif; ?>
</head>
<div id="blur-overlay"></div>
<body class="hold-transition login-page">
<div class="preloader flex-column justify-content-center align-items-center">
  <?php if ($logo): ?>
    <img class="animation__shake" src="./assets/<?= htmlspecialchars($logo) ?>" alt="Walang Logo" height="400" width="400">
  <?php endif; ?>
</div>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.php" class="h1"><b>OSCA</b>Manaoag</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <?php if (isset($_SESSION['error'])): ?>
        <script src="./plugins/jquery/jquery.min.js"></script>
        <script src="./plugins/sweetalert2/sweetalert2.min.js"></script>
        <script>
          $(document).ready(function() {
            Swal.fire({
              icon: 'warning',
              title: 'Warning',
              text: '<?= htmlspecialchars($_SESSION['error']) ?>'
            });
          });
        </script>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['login_success'])): ?>
        <script src="./plugins/jquery/jquery.min.js"></script>
        <script src="./plugins/sweetalert2/sweetalert2.min.js"></script>
        <script>
          $(document).ready(function() {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: '<?= htmlspecialchars($_SESSION['login_success']) ?>'
            });
          });
        </script>
        <?php unset($_SESSION['login_success']); ?>
      <?php endif; ?>

      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <input type="hidden" name="capture_image" id="capture_image">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
      </form>

      <div class="social-auth-links text-center mt-2 mb-3">
        <p class="mb-1">
          <a href="forgot/?id=123123456456">I forgot my password</a>
        </p>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="./plugins/jquery/jquery.min.js"></script>
  <script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./dist/js/adminlte.min.js"></script>
  <script src="./js/image.js"></script>
  <script src="./js/blur.js"></script>
  <script src="./js/crypto-js.min.js"></script>
  <?php if ($security_settings): ?><script src="./js/settings.js"></script><?php endif; ?>
  <?php if ($demo): ?><script src="./js/demo.js"></script><?php endif; ?>
</body>
</html>
