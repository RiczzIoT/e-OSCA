<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; 

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

// Fetch messages for the user
$stmt = $conn->prepare("SELECT * FROM messages WHERE recipient_id = ? ORDER BY timestamp DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$messages_result = $stmt->get_result();
$messages = $messages_result->fetch_all(MYSQLI_ASSOC);

// Fetch messages for the user
$messages_result = $conn->query("SELECT * FROM messages WHERE recipient_id=$user_id ORDER BY timestamp DESC");
$messages = $messages_result->fetch_all(MYSQLI_ASSOC);
$message_count = count(array_filter($messages, function($msg) { return !$msg['is_read']; }));

// Handle marking messages as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_as_read'])) {
    $message_id = $_POST['message_id'];
    
    $stmt = $conn->prepare("UPDATE messages SET is_read = TRUE WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    
    header("Location: inbox.php");
    exit();
}

// Handle deleting messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $message_id = $_POST['message_id'];
    
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    
    header("Location: inbox.php");
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
      <!-- Messages Dropdown Menu-->
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <?php if ($message_count > 0): ?>
            <span id="messages-badge" class="badge badge-danger navbar-badge"><?php echo $message_count; ?></span>
        <?php else: ?>
            <span id="messages-badge" class="badge badge-danger navbar-badge" style="display: none;"></span>
        <?php endif; ?>
    </a>
    <div id="messages-dropdown-menu" class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php if ($message_count > 0): ?>
            <?php foreach ($messages as $message): ?>
                <a href="javascript:void(0);" onclick="markAsRead(<?php echo $message['id']; ?>);" class="dropdown-item">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                <?php echo htmlspecialchars($message['email']); ?>
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm"><?php echo htmlspecialchars($message['subject']); ?></p>
                            <p class="text-sm text-muted" data-timestamp="<?php echo $message['timestamp']; ?>"></p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
            <?php endforeach; ?>
            <a href="../mailbox/read-mail.php" class="dropdown-item dropdown-footer">See All Messages</a>
        <?php else: ?>
            <a href="#" class="dropdown-item">No Messages</a>
        <?php endif; ?>
    </div>
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
                  <i class="far fa-list nav-icon"></i>
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

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="compose.html" class="btn btn-primary btn-block mb-3">Compose</a>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Folders</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item active">
                  <a href="inbox.php" class="nav-link">
                    <i class="fas fa-inbox"></i> Inbox
                    <span class="badge bg-primary float-right"><?php echo count(array_filter($messages, function($msg) { return !$msg['is_read']; })); ?></span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-envelope"></i> Sent
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-file-alt"></i> Drafts
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="fas fa-filter"></i> Junk
                    <span class="badge bg-warning float-right">65</span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-trash-alt"></i> Trash
                  </a>
                </li>
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Labels</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Inbox</h3>

              <div class="card-tools">
                <div class="input-group input-group-sm">
                  <input type="text" class="form-control" placeholder="Search Mail">
                  <div class="input-group-append">
                    <div class="btn btn-primary">
                      <i class="fas fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="far fa-trash-alt"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-reply"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-share"></i>
                  </button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm">
                  <i class="fas fa-sync-alt"></i>
                </button>
                <div class="float-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm">
                      <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-default btn-sm">
                      <i class="fas fa-chevron-right"></i>
                    </button>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.float-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                  <?php foreach ($messages as $message): ?>
                    <tr>
                      <td>
                        <div class="icheck-primary">
                          <input type="checkbox" value="" id="check1">
                          <label for="check1"></label>
                        </div>
                      </td>
                      <td class="mailbox-name"><a href="read-mail.php?id=<?php echo $message['id']; ?>"><?php echo htmlspecialchars($message['email']); ?></a></td>
                      <td class="mailbox-subject"><b><?php echo htmlspecialchars($message['subject']); ?></b> - <?php echo htmlspecialchars(substr($message['body'], 0, 40)) . '...'; ?></td>
                      <td class="mailbox-date"><?php echo htmlspecialchars($message['timestamp']); ?></td>
                      <td>
                        <form method="post" action="" style="display:inline;">
                          <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                          <button type="submit" name="mark_as_read" class="btn btn-sm btn-default">Mark as Read</button>
                        </form>
                        <form method="post" action="" style="display:inline;">
                          <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                          <button type="submit" name="delete_message" class="btn btn-sm btn-default">Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle">
                  <i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="far fa-trash-alt"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-reply"></i>
                  </button>
                  <button type="button" class="btn btn-default btn-sm">
                    <i class="fas fa-share"></i>
                  </button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm">
                  <i class="fas fa-sync-alt"></i>
                </button>
                <div class="float-right">
                  1-50/200
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm">
                      <i class="fas fa-chevron-left"></i>
                    </button>
                    <button type="button" class="btn btn-default btn-sm">
                      <i class="fas fa-chevron-right"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
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
    <strong>Copyright &copy; 2024 <a href="https://www.facebook.com/RiczzTV">Municipality of Manaoag</a>.</strong> All rights reserved.
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
function markAsRead(message_id) {
    var form = document.createElement('form');
    form.method = 'post';
    form.action = '';

    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'message_id';
    input.value = message_id;
    form.appendChild(input);

    var input2 = document.createElement('input');
    input2.type = 'hidden';
    input2.name = 'mark_as_read';
    input2.value = '1';
    form.appendChild(input2);

    document.body.appendChild(form);
    form.submit();
}
</script>
</body>
</html>
