<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

include '../../includes/connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$page_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($page_id === null || !is_numeric($page_id) || $page_id < 1) {
    http_response_code(404);
    include('../../404/');
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM senior WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    header("Location: ../../404/");
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit | Form</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../../csz/blur.css">
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
               <h1>Edit Form</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="../?id=783254690132">Back</a></li>
                  <li class="breadcrumb-item active">Edit</li>
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
            <form id="seniorForm" action="../update/" method="post">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <div class="form-row">
                <div class="col-md-2">
                  <label for="id_number">ID Number</label>
                  <input type="number" class="form-control" id="id_number" name="id_number" value="<?php echo $row['id_number']; ?>" readonly>
                </div>
                <div class="col-md-3">
                  <label for="province">Province *</label>
                  <select id="province" name="province" class="form-control" required onchange="checkProvince()">
                    <option value="" <?php if ($row['province'] == '') echo 'selected'; ?>>--Select Province--</option>
                    <option value="Pangasinan" <?php if ($row['province'] == 'Pangasinan') echo 'selected'; ?>>Pangasinan</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="municipal">City/Town *</label>
                  <select id="municipal" name="municipal" class="form-control" required onchange="checkMunicipal()">
                    <option value="" <?php if ($row['municipal'] == '') echo 'selected'; ?>>Choose Option</option>
                    <option value="Manaoag" <?php if ($row['municipal'] == 'Manaoag') echo 'selected'; ?>>Manaoag</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="barangay">Barangay*</label>
                  <select id="barangay" name="barangay" class="form-control" required>
                    <option value="" <?php if ($row['barangay'] == '') echo 'selected'; ?>>--Select Barangay--</option>
                    <option value="Babasit" <?php if ($row['barangay'] == 'Babasit') echo 'selected'; ?>>Babasit</option>
                    <option value="Baguinay" <?php if ($row['barangay'] == 'Baguinay') echo 'selected'; ?>>Baguinay</option>
                    <option value="Baritao" <?php if ($row['barangay'] == 'Baritao') echo 'selected'; ?>>Baritao</option>
                    <option value="Bucao" <?php if ($row['barangay'] == 'Bucao') echo 'selected'; ?>>Bucao</option>
                    <option value="Cabanbanan" <?php if ($row['barangay'] == 'Cabanbanan') echo 'selected'; ?>>Cabanbanan</option>
                    <option value="Calaocan" <?php if ($row['barangay'] == 'Calaocan') echo 'selected'; ?>>Calaocan</option>
                    <option value="Inamotan" <?php if ($row['barangay'] == 'Inamotan') echo 'selected'; ?>>Inamotan</option>
                    <option value="Lelemaan" <?php if ($row['barangay'] == 'Lelemaan') echo 'selected'; ?>>Lelemaan</option>
                    <option value="Licsi" <?php if ($row['barangay'] == 'Licsi') echo 'selected'; ?>>Licsi</option>
                    <option value="Lipit-Norte" <?php if ($row['barangay'] == 'Lipit-Norte') echo 'selected'; ?>>Lipit Norte</option>
                    <option value="Lipit-Sur" <?php if ($row['barangay'] == 'Lipit-Sur') echo 'selected'; ?>>Lipit Sur</option>
                    <option value="Matulong" <?php if ($row['barangay'] == 'Matulong') echo 'selected'; ?>>Matulong</option>
                    <option value="Mermer" <?php if ($row['barangay'] == 'Mermer') echo 'selected'; ?>>Mermer</option>
                    <option value="Nalsian" <?php if ($row['barangay'] == 'Nalsian') echo 'selected'; ?>>Nalsian</option>
                    <option value="Oraan-East" <?php if ($row['barangay'] == 'Oraan-East') echo 'selected'; ?>>Oraan East</option>
                    <option value="Oraan-West" <?php if ($row['barangay'] == 'Oraan-West') echo 'selected'; ?>>Oraan West</option>
                    <option value="Pantal" <?php if ($row['barangay'] == 'Pantal') echo 'selected'; ?>>Pantal</option>
                    <option value="Pao" <?php if ($row['barangay'] == 'Pao') echo 'selected'; ?>>Pao</option>
                    <option value="Parian" <?php if ($row['barangay'] == 'Parian') echo 'selected'; ?>>Parian</option>
                    <option value="Poblacion" <?php if ($row['barangay'] == 'Poblacion') echo 'selected'; ?>>Poblacion</option>
                    <option value="Pugaro" <?php if ($row['barangay'] == 'Pugaro') echo 'selected'; ?>>Pugaro</option>
                    <option value="San Ramon" <?php if ($row['barangay'] == 'San Ramon') echo 'selected'; ?>>San Ramon</option>
                    <option value="Santa Ines" <?php if ($row['barangay'] == 'Santa Ines') echo 'selected'; ?>>Santa Ines</option>
                    <option value="Sapang" <?php if ($row['barangay'] == 'Sapang') echo 'selected'; ?>>Sapang</option>
                    <option value="Tebuel" <?php if ($row['barangay'] == 'Tebuel') echo 'selected'; ?>>Tebuel</option>
                  </select>
                </div>
              </div>
              <div class="form-row mt-2">
                <div class="col-md-2">
                  <label for="last_name">Last name</label>
                  <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $row['last_name']; ?>">
                </div>
                <div class="col-md-2">
                  <label for="first_name">First name*</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $row['first_name']; ?>">
                </div>
                <div class="col-md-2">
                  <label for="middle_name">Middle name</label>
                  <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?php echo $row['middle_name']; ?>">
                </div>
                <div class="col-md-2">
                  <label for="suffix">Suffix</label>
                  <select id="suffix" name="suffix" class="form-control">
                    <option value="" <?php if ($row['suffix'] == '') echo 'selected'; ?>>Choose Option</option>
                    <option value="Jr." <?php if ($row['suffix'] == 'Jr.') echo 'selected'; ?>>Jr.</option>
                    <option value="Sr." <?php if ($row['suffix'] == 'Sr.') echo 'selected'; ?>>Sr.</option>
                    <option value="II" <?php if ($row['suffix'] == 'II') echo 'selected'; ?>>II</option>
                    <option value="III" <?php if ($row['suffix'] == 'III') echo 'selected'; ?>>III</option>
                    <option value="Esq" <?php if ($row['suffix'] == 'Esq') echo 'selected'; ?>>Esq</option>
                    <option value="M.D." <?php if ($row['suffix'] == 'M.D.') echo 'selected'; ?>>M.D.</option>
                    <option value="Engr." <?php if ($row['suffix'] == 'Engr.') echo 'selected'; ?>>Engr.</option>
                    <option value="CPA" <?php if ($row['suffix'] == 'CPA') echo 'selected'; ?>>CPA</option>
                  </select>
                </div>
              </div>
              <div class="form-row mt-3">
                <div class="col-md-3">
                  <label for="contact_type">Contact Type *</label>
                  <select id="contact_type" name="contact_type" class="form-control" required>
                    <option value="" <?php if ($row['contact_type'] == '') echo 'selected'; ?>>Choose Option</option>
                    <option value="Telephone" <?php if ($row['contact_type'] == 'Telephone') echo 'selected'; ?>>Telephone</option>
                    <option value="Mobile" <?php if ($row['contact_type'] == 'Mobile') echo 'selected'; ?>>Mobile</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="contact">Contact.*</label>
                  <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $row['contact']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="email">E-mail Address</label>
                  <input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="sex">Sex*</label>
                  <select id="sex" name="sex" class="form-control" required>
                    <option value="" <?php if ($row['sex'] == '') echo 'selected'; ?>>Choose Option</option>
                    <option value="Male" <?php if ($row['sex'] == 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($row['sex'] == 'Female') echo 'selected'; ?>>Female</option>
                  </select>
                </div>
              </div>
              <div class="form-row mt-3">
                <div class="col-md-3">
                  <label for="birthdate">Birthdate *</label>
                  <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo $row['birthdate']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="birthplace">Place of Birth *</label>
                  <input type="text" class="form-control" id="birthplace" name="birthplace" value="<?php echo $row['birthplace']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="age">Age*</label>
                  <input type="number" class="form-control" id="age" name="age" value="<?php echo $row['age']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="civil_status">Civil Status*</label>
                  <select id="civil_status" name="civil_status" class="form-control" required>
                    <option value="" <?php if ($row['civil_status'] == '') echo 'selected'; ?>>Choose option</option>
                    <option value="Divorced" <?php if ($row['civil_status'] == 'Divorced') echo 'selected'; ?>>Divorced</option>
                    <option value="Married" <?php if ($row['civil_status'] == 'Married') echo 'selected'; ?>>Married</option>
                    <option value="Separated" <?php if ($row['civil_status'] == 'Separated') echo 'selected'; ?>>Separated</option>
                    <option value="Single" <?php if ($row['civil_status'] == 'Single') echo 'selected'; ?>>Single</option>
                    <option value="Widowed" <?php if ($row['civil_status'] == 'Widowed') echo 'selected'; ?>>Widowed</option>
                  </select>
                </div>
              </div>
              <div class="form-row mt-3">
                <div class="col-md-3">
                  <label for="blood_type">Blood Type *</label>
                  <select id="blood_type" name="blood_type" class="form-control" required>
                    <option value="" <?php if ($row['blood_type'] == '') echo 'selected'; ?>>Choose option</option>
                    <option value="O+" <?php if ($row['blood_type'] == 'O+') echo 'selected'; ?>>O+</option>
                    <option value="A+" <?php if ($row['blood_type'] == 'A+') echo 'selected'; ?>>A+</option>
                    <option value="A-" <?php if ($row['blood_type'] == 'A-') echo 'selected'; ?>>A-</option>
                    <option value="B+" <?php if ($row['blood_type'] == 'B+') echo 'selected'; ?>>B+</option>
                    <option value="B-" <?php if ($row['blood_type'] == 'B-') echo 'selected'; ?>>B-</option>
                    <option value="AB+" <?php if ($row['blood_type'] == 'AB+') echo 'selected'; ?>>AB+</option>
                    <option value="AB-" <?php if ($row['blood_type'] == 'AB-') echo 'selected'; ?>>AB-</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="religion">Religion *</label>
                  <select id="religion" name="religion" class="form-control" required>
                    <option value="" <?php if ($row['religion'] == '') echo 'selected'; ?>>Choose option</option>
                    <option value="Roman Catholic" <?php if ($row['religion'] == 'Roman Catholic') echo 'selected'; ?>>Roman Catholic</option>
                    <option value="Islam" <?php if ($row['religion'] == 'Islam') echo 'selected'; ?>>Islam</option>
                    <option value="Evangelicals (PCEC)" <?php if ($row['religion'] == 'Evangelicals (PCEC)') echo 'selected'; ?>>Evangelicals (PCEC)</option>
                    <option value="Iglesia ni Cristo" <?php if ($row['religion'] == 'Iglesia ni Cristo') echo 'selected'; ?>>Iglesia ni Cristo</option>
                    <option value="Aglipayan" <?php if ($row['religion'] == 'Aglipayan') echo 'selected'; ?>>Aglipayan</option>
                    <option value="Seventh-day Adventist" <?php if ($row['religion'] == 'Seventh-day Adventist') echo 'selected'; ?>>Seventh-day Adventist</option>
                    <option value="Bible Baptist Church" <?php if ($row['religion'] == 'Bible Baptist Church') echo 'selected'; ?>>Bible Baptist Church</option>
                    <option value="United Church of Christ in the Philippines" <?php if ($row['religion'] == 'United Church of Christ in the Philippines') echo 'selected'; ?>>United Church of Christ in the Philippines</option>
                    <option value="Jehovas Witnesses" <?php if ($row['religion'] == 'Jehovas Witnesses') echo 'selected'; ?>>Jehovas Witnesses</option>
                    <option value="Church of Christ" <?php if ($row['religion'] == 'Church of Christ') echo 'selected'; ?>>Church of Christ</option>
                    <option value="Jesus is Lord Church" <?php if ($row['religion'] == 'Jesus is Lord Church') echo 'selected'; ?>>Jesus is Lord Church</option>
                    <option value="United Pentecostal Church Inc." <?php if ($row['religion'] == 'United Pentecostal Church Inc.') echo 'selected'; ?>>United Pentecostal Church Inc.</option>
                    <option value="Philippine Independent Catholic Church" <?php if ($row['religion'] == 'Philippine Independent Catholic Church') echo 'selected'; ?>>Philippine Independent Catholic Church</option>
                    <option value="Union Esperitista Cristiana de Filipinas, Inc." <?php if ($row['religion'] == 'Union Esperitista Cristiana de Filipinas, Inc.') echo 'selected'; ?>>Union Esperitista Cristiana de Filipinas, Inc.</option>
                    <option value="The Church of Jesus Christ of Latter-Day Saints" <?php if ($row['religion'] == 'The Church of Jesus Christ of Latter-Day Saints') echo 'selected'; ?>>The Church of Jesus Christ of Latter-Day Saints</option>
                    <option value="Association of Fundamentals Baptist" <?php if ($row['religion'] == 'Association of Fundamentals Baptist') echo 'selected'; ?>>Association of Fundamentals Baptist</option>
                    <option value="Churches in the Philippines" <?php if ($row['religion'] == 'Churches in the Philippines') echo 'selected'; ?>>Churches in the Philippines</option>
                    <option value="Evangelical Christian Outreach Foundation" <?php if ($row['religion'] == 'Evangelical Christian Outreach Foundation') echo 'selected'; ?>>Evangelical Christian Outreach Foundation</option>
                    <option value="Convention of the Philippines Baptist Church" <?php if ($row['religion'] == 'Convention of the Philippines Baptist Church') echo 'selected'; ?>>Convention of the Philippines Baptist Church</option>
                    <option value="Crusaders of the Divine Church of Christ Inc." <?php if ($row['religion'] == 'Crusaders of the Divine Church of Christ Inc.') echo 'selected'; ?>>Crusaders of the Divine Church of Christ Inc.</option>
                    <option value="Buddhist" <?php if ($row['religion'] == 'Buddhist') echo 'selected'; ?>>Buddhist</option>
                    <option value="Lutheran Church of the Phillipines" <?php if ($row['religion'] == 'Lutheran Church of the Phillipines') echo 'selected'; ?>>Lutheran Church of the Phillipines</option>
                    <option value="Iglesia sa Dios Espiritu Santo Inc." <?php if ($row['religion'] == 'Iglesia sa Dios Espiritu Santo Inc.') echo 'selected'; ?>>Iglesia sa Dios Espiritu Santo Inc.</option>
                    <option value="Philippine Benevolent Missionaries Association" <?php if ($row['religion'] == 'Philippine Benevolent Missionaries Association') echo 'selected'; ?>>Philippine Benevolent Missionaries Association</option>
                    <option value="Faith Tabernacle Church" <?php if ($row['religion'] == 'Faith Tabernacle Church') echo 'selected'; ?>>Faith Tabernacle Church</option>
                    <option value="Conservative Baptist Association of the Philippines" <?php if ($row['religion'] == 'Conservative Baptist Association of the Philippines') echo 'selected'; ?>>Conservative Baptist Association of the Philippines</option>
                    <option value="Others" <?php if ($row['religion'] == 'Others') echo 'selected'; ?>>Others</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="highest_educ_attainment">Highest Educational Attainment *</label>
                  <select id="highest_educ_attainment" name="highest_educ_attainment" class="form-control" required>
                    <option value="" <?php if (isset($row['highest_educ_attainment']) && $row['highest_educ_attainment'] == '') echo 'selected'; ?>>Choose option</option>
                    <option value="Elementary" <?php if (isset($row['highest_educ_attainment']) && $row['highest_educ_attainment'] == 'Elementary') echo 'selected'; ?>>Elementary</option>
                    <option value="High School" <?php if (isset($row['highest_educ_attainment']) && $row['highest_educ_attainment'] == 'High School') echo 'selected'; ?>>High School</option>
                    <option value="College" <?php if (isset($row['highest_educ_attainment']) && $row['highest_educ_attainment'] == 'College') echo 'selected'; ?>>College</option>
                    <option value="Vocational" <?php if (isset($row['highest_educ_attainment']) && $row['highest_educ_attainment'] == 'Vocational') echo 'selected'; ?>>Vocational</option>
                    <option value="Masters Degree" <?php if (isset($row['highest_educ_attainment']) && $row['highest_educ_attainment'] == 'Masters Degree') echo 'selected'; ?>>Master's Degree</option>
                    <option value="Doctoral" <?php if (isset($row['highest_educ_attainment']) && $row['highest_educ_attainment'] == 'Doctoral') echo 'selected'; ?>>Doctoral</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="gsis">GSIS</label>
                  <input type="text" class="form-control" id="gsis" name="gsis" value="<?php echo $row['gsis']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="sss">SSS</label>
                  <input type="text" class="form-control" id="sss" name="sss" value="<?php echo $row['sss']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="philhealth">Philhealth</label>
                  <input type="text" class="form-control" id="philhealth" name="philhealth" value="<?php echo $row['philhealth']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="employment_status">Employment Status</label>
                  <select id="employment_status" name="employment_status" class="form-control" required>
                    <option value="" <?php if (isset($row['employment_status']) && $row['employment_status'] == '') echo 'selected'; ?>>Choose option</option>
                    <option value="Employed" <?php if (isset($row['employment_status']) && $row['employment_status'] == 'Employed') echo 'selected'; ?>>Employed</option>
                    <option value="Self-Employed" <?php if (isset($row['employment_status']) && $row['employment_status'] == 'Self-Employed') echo 'selected'; ?>>Self-Employed</option>
                    <option value="Unemployed" <?php if (isset($row['employment_status']) && $row['employment_status'] == 'Unemployed') echo 'selected'; ?>>Unemployed</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="classification">Classification*</label>
                  <select class="form-control" id="classification" name="classification" required onchange="checkClassification()">
                    <option value="" <?php if ($row['classification'] == '') echo 'selected'; ?>>Select Option</option>
                    <option value="Indigent" <?php if ($row['classification'] == 'Indigent') echo 'selected'; ?>>Indigent</option>
                    <option value="Pensioner" <?php if ($row['classification'] == 'Pensioner') echo 'selected'; ?>>Pensioner</option>
                    <option value="Supported" <?php if ($row['classification'] == 'Supported') echo 'selected'; ?>>Supported</option>
                  </select>
                </div>
              </div>
              <div class="form-row mt-3">
                <div class="col-md-3">
                  <label for="monthly_pension">Monthly Pension</label>
                  <select class="form-control" id="monthly_pension" name="monthly_pension" required>
                    <option value="" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == '') echo 'selected'; ?>>Select Option</option>
                    <option value="Below P999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'Below P999.00') echo 'selected'; ?>>Below P999.00</option>
                    <option value="P1,000.00-P4,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P1,000.00-P4,999.00') echo 'selected'; ?>>P1,000.00 - P4,999.00</option>
                    <option value="P5,000.00-P9,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P5,000.00-P9,999.00') echo 'selected'; ?>>P5,000.00 - P9,999.00</option>
                    <option value="P10,000.00-P14,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P10,000.00-P14,999.00') echo 'selected'; ?>>P10,000.00 - P14,999.00</option>
                    <option value="P15,000.00-P19,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P15,000.00-P19,999.00') echo 'selected'; ?>>P15,000.00 - P19,999.00</option>
                    <option value="P20,000.00-P24,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P20,000.00-P24,999.00') echo 'selected'; ?>>P20,000.00 - P24,999.00</option>
                    <option value="P25,000.00-P29,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P25,000.00-P29,999.00') echo 'selected'; ?>>P25,000.00 - P29,999.00</option>
                    <option value="P30,000.00-P34,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P30,000.00-P34,999.00') echo 'selected'; ?>>P30,000.00 - P34,999.00</option>
                    <option value="P35,000.00-P39,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P35,000.00-P39,999.00') echo 'selected'; ?>>P35,000.00 - P39,999.00</option>
                    <option value="P40,000.00-P44,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P40,000.00-P44,999.00') echo 'selected'; ?>>P40,000.00 - P44,999.00</option>
                    <option value="P45,000.00-P49,999.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'P45,000.00-P49,999.00') echo 'selected'; ?>>P45,000.00 - P49,999.00</option>
                    <option value="Above P50,000.00" <?php if (isset($row['monthly_pension']) && $row['monthly_pension'] == 'Above P50,000.00') echo 'selected'; ?>>Above P50,000.00</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="citizenship">Citizenship*</label>
                  <select id="citizenship" name="citizenship" class="form-control" required>
                    <option value="Filipino" <?php if ($row['citizenship'] == 'Filipino') echo 'selected'; ?>>Filipino</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="emergency">Emergency Contact</label>
                  <input type="text" class="form-control" id="emergency" name="emergency" value="<?php echo $row['emergency']; ?>">
                </div>
              </div>
              <div class="form-actions mt-3">
                <button type="submit" class="btn btn-primary" form="seniorForm" name="insert" value="Update Information">update</button>
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

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2024 <a href="https://www.facebook.com/RiczzIoT">RiczzIoT</a>.</strong> All rights reserved.
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
  <script>
    function validateAge() {
        var age = document.getElementById('age').value;
        if (age < 60) {
            alert('Age must be 60 or above.');
            return false;
        }
        return true;
    }

    document.querySelector('form').onsubmit = validateAge;
</script>
</body>
</html>
