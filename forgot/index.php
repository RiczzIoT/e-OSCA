<?php
session_start();
include '../includes/connect.php';

if (isset($_GET['id']) && preg_match('/^\d{12}$/', $_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("Location: ../404/");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $age = $user['age'];
        $birthdate = $user['birthdate'];
        $name = $user['name'];

        $zip_password = $birthdate . '-' . $age . '-' . $name;

        $new_password = bin2hex(random_bytes(4));
        $new_password_hash = hash('sha256', $new_password);

        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $new_password_hash, $email);
        $update_stmt->execute();

        $zip = new ZipArchive();
        $zip_file = tempnam(sys_get_temp_dir(), 'password') . '.zip';

        if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
            $zip->addFromString('new_password.txt', "New Password: " . $new_password);
            $zip->setPassword($zip_password);
            $zip->setEncryptionName('new_password.txt', ZipArchive::EM_AES_256);
            $zip->close();

            $_SESSION['message'] = 'Pumunta ka ngayon sa downloads.';
            $_SESSION['message_type'] = 'success';

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="YYYY-MM-DD-AGE-Name.zip"');
            header('Content-Length: ' . filesize($zip_file));

            readfile($zip_file);

            unlink($zip_file);
            exit();
        } else {
            $_SESSION['message'] = 'Failed to create zip file.';
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Email not found.';
        $_SESSION['message_type'] = 'warning';
    }

    header("Location: ./index.php?id=123123456456");
    exit();
} else {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id = 21;
    }

    $stmt = $conn->prepare("SELECT capture_image, background_image, favicon, logo, security_settings FROM settings WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $background_image = '';
    $favicon = '';
    if ($result->num_rows > 0) {
        $settings = $result->fetch_assoc();
        $background_image = $settings['background_image'];
        $favicon = $settings['favicon'];
        $logo = $settings['logo'];
        $cache_buster = time();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../dist/css/blur.css">
  <link rel="icon" type="image/png" href="../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
  <?php if ($background_image) : ?>
  <style>
    body {
        background: url('../assets/<?= htmlspecialchars($background_image) ?>') no-repeat center center fixed;
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
<!--<div class="preloader flex-column justify-content-center align-items-center">
  </?php if ($logo): ?>
    <img class="animation__shake" src="../assets/</?= htmlspecialchars($logo) ?>" alt="Walang Logo" height="400" width="400">
  </?php endif; ?>
</div>-->
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
    <a href="index.php" class="h1"><b>OSCA</b>Manaoag</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
      <form action="./index.php?id=123123456456" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Enter your email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-1">
        <a href="../">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="../js/blur.js"></script>
<script>
  $(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

    <?php if (isset($_SESSION['message']) && isset($_SESSION['message_type'])): ?>
      var messageType = '<?= $_SESSION['message_type'] ?>';
      var message = '<?= $_SESSION['message'] ?>';

      Toast.fire({
        icon: messageType,
        title: message
      });

      <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
    <?php endif; ?>
  });
</script>
</body>
</html>
<?php
}
?>