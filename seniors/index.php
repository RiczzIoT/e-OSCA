<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

include '../includes/connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$page_id = isset($_GET['id']) ? $_GET['id'] : null;

if (isset($_GET['id']) && preg_match('/^\d{12}$/', $_GET['id'])) {
  $id = $_GET['id'];
} else {
  header("Location: ../404.php");
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

if ($role === 'admin' || $role === 'mayor' || $role === 'staff') {
} else {
    header("Location: ../dashboard.php?id=1");
    exit();
}

// Determine the SQL query based on user role
$sql = "SELECT * FROM senior WHERE status = 'active'";
if ($role === 'admin' || $role === 'mayor') {
    $sql = "SELECT * FROM senior WHERE status IN ('active', 'pending')";
}
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OSCA Manaoag | Seniors</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../dist/css/blur.css">
  <link rel="icon" type="image/png" href="../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
</head>
</head>
<div id="blur-overlay"></div>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/manaoag.png" alt="AdminLTELogo" height="400" width="400">
  </div>

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="./add/?id=163280475910" class="nav-link">Add new</a>
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
              <li class="nav-item ">
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
            <a href="../seniors/?id=783254690132" class="nav-link active">
              <i class="nav-icon fas fa-wheelchair"></i>
              <p>Seniors List</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="sms/?id=783254690139" class="nav-link">
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Senior List</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../admin/?id=634829175920">Home</a></li>
              <li class="breadcrumb-item active">List</li>
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
                        <div style="text-align: right; margin-bottom: 10px;">
    <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchTable()" style="padding: 5px; width: 200px;">
</div>

                        <table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Sex</th>
            <th>Citizenship</th>
            <th>Religion</th>
            <th>Civil Status</th>
            <th>Barangay</th>
            <th class="no-print">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include '../includes/connect.php';

        $role = $_SESSION['role'] ?? '';
        $sql = ($role === 'admin' || $role === 'mayor') 
            ? "SELECT s.*, u.name AS requester_name FROM senior s LEFT JOIN users u ON s.delete_requested_by = u.id WHERE s.status != 'deleted'"
            : "SELECT * FROM senior WHERE status = 'active'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['first_name']} {$row['middle_name']} {$row['last_name']} {$row['suffix']}</td>";
                echo "<td>{$row['sex']}</td>";
                echo "<td>{$row['citizenship']}</td>";
                echo "<td>{$row['religion']}</td>";
                echo "<td>{$row['civil_status']}</td>";
                echo "<td>{$row['barangay']}</td>";
                echo "<td>" . ($row['status'] === 'pending' ? $row['requester_name'] : 'N/A') . "</td>";
                
                echo "<td class='action-btns no-print'>";
                echo "<a href='generate_pdf.php?id={$row['id']}' class='btn btn-app bg-success'><i class='fas fa-id-card'></i> Download ID</a>";
                
                if ($role === 'admin' || $role === 'mayor') {
                    echo "<a href='./view/?id={$row['id']}' class='btn btn-app bg-secondary'><i class='fas fa-eye'></i> View info</a>";
                    
                    if ($row['status'] === 'pending') {
                        echo "<button class='btn btn-app bg-success' onclick='approveRequest({$row['id']})'><i class='fas fa-check'></i> Approve</button>";
                        echo "<button class='btn btn-app bg-danger' onclick='denyRequest({$row['id']})'><i class='fas fa-times'></i> Deny</button>";
                    } else {
                        echo "<a href='./edit/?id={$row['id']}' class='btn btn-app bg-warning'><i class='fas fa-edit'></i> Edit</a>";
                        echo "<button class='btn btn-app bg-danger' onclick='deleteSeniorRequest({$row['id']})'><i class='fas fa-trash'></i> Delete</button>";
                    }
                } else if ($role === 'staff') {
                    // Staff sees only the delete request button
                    echo "<button class='btn btn-app bg-warning' onclick='deleteSeniorRequest({$row['id']})'><i class='fas fa-trash'></i> Request Delete</button>";
                }
                
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No residents found</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
    <tfoot>
    </tfoot>
</table>
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

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="https://www.Facebook.com/RiczzTV">Municipality of Manaoag</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
  <!--end-->

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../js/crypto-js.min.js"></script>
<script src="./random.js"></script>
<script src="../js/blur.js"></script>
<script src='../js/324234723498.js'></script>
<?php if ($security_settings): ?><script src="../js/settings.js"></script><?php endif; ?>
<?php if ($demo): ?><script src="../js/demo.js"></script><?php endif; ?>
<script src="../dist/js/adminlte.min.js"></script>
<script src="../dist/js/demo.js"></script>
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
<script>
$(document).ready(function() {
    $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Action)
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Action)
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Action)
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Action)
                }
            }
        ]
    });
});
</script>
<script>
function deleteSeniorRequest(id) {
    if (confirm("Are you sure you want to request the deletion of this senior data?")) {
        fetch('delete_senior.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(response => response.text())
        .then(result => {
            alert(result);  // Show feedback message
            location.reload();  // Reload the page to update status
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
<script>
function approveRequest(id) {
    if (confirm("Are you sure you want to approve this delete request?")) {
        fetch('approve_delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        }).then(response => response.text())
          .then(result => alert(result))
          .then(() => location.reload());
    }
}

function denyRequest(id) {
    if (confirm("Are you sure you want to deny this delete request?")) {
        fetch('deny_delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        }).then(response => response.text())
          .then(result => alert(result))
          .then(() => location.reload());
    }
}

function searchTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("example1");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start at 1 to skip header
        let row = rows[i];
        const cells = row.getElementsByTagName("td");
        let rowContainsFilter = false;

        for (let j = 0; j < cells.length; j++) {
            if (cells[j]) {
                const cellContent = cells[j].textContent || cells[j].innerText;
                if (cellContent.toLowerCase().indexOf(filter) > -1) {
                    rowContainsFilter = true;
                    break;
                }
            }
        }
        row.style.display = rowContainsFilter ? "" : "none";
    }
}

</script>

</body>
</html>
