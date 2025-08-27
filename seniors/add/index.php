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
  header("Location: ../../dashboard.php?id=1");
  exit();
}
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

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
            <h1>Senior Form</h1>
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
              <form id="residentForm" action="../functions.php?id=1" method="post" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-2">
                    <label for="id_number">ID Number</label>
                    <input type="text" class="form-control" id="id_number" name="id_number" readonly>
                  </div>
                  <div class="col-md-3">
                    <label for="province">Province *</label>
                    <select id="province" name="province" class="form-control" required onchange="checkProvince()">
                      <option value="selected">--Select Province--</option>
                      <option value="Pangasinan">Pangasinan</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="municipal">City/Town *</label>
                    <select id="municipal" name="municipal" class="form-control" required onchange="checkMunicipal()" disabled>
                      <option value="selected">Choose Option</option>
                      <option value="Manaoag">Manaoag</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="barangay">Barangay*</label>
                    <select id="barangay" name="barangay" class="form-control" required disabled>
                      <option value="selected">--Select Barangay--</option>
                      <option value="Babasit">Babasit</option>
                      <option value="Baguinay">Baguinay</option>
                      <option value="Baritao">Baritao</option>
                      <option value="Bucao">Bucao</option>
                      <option value="Cabanbanan">Cabanbanan</option>
                      <option value="Calaocan">Calaocan</option>
                      <option value="Inamotan">Inamotan</option>
                      <option value="Lelemaan">Lelemaan</option>
                      <option value="Licsi">Licsi</option>
                      <option value="Lipit-Norte">Lipit Norte</option>
                      <option value="Lipit-Sur">Lipit Sur</option>
                      <option value="Matulong">Matulong</option>
                      <option value="Mermer">Mermer</option>
                      <option value="Nalsian">Nalsian</option>
                      <option value="Oraan-East">Oraan East</option>
                      <option value="Oraan-West">Oraan West</option>
                      <option value="Pantal">Pantal</option>
                      <option value="Pao">Pao</option>
                      <option value="Parian">Parian</option>
                      <option value="Poblacion">Poblacion</option>
                      <option value="Pugaro">Pugaro</option>
                      <option value="San Ramon">San Ramon</option>
                      <option value="Santa Ines">Santa Ines</option>
                      <option value="Sapang">Sapang</option>
                      <option value="Tebuel">Tebuel</option>
                    </select>
                  </div>
                </div>
                <div class="form-row mt-2">

                <div class="col-md-2">
                    <label for="last_name">Last name *</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                  </div>
                  <div class="col-md-2">
                    <label for="first_name">First name *</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                  </div>
                  <div class="col-md-2">
                    <label for="middle_name">Middle name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name">
                  </div>
                  <div class="col-md-2">
                    <label for="suffix">Suffix</label>
                    <select id="suffix" name="suffix" class="form-control">
                      <option value="selected">Choose Option</option>
                      <option value="Jr.">Jr.</option>
                      <option value="Sr.">Sr.</option>
                      <option value="II">II</option>
                      <option value="III">III</option>
                      <option value="Esq">Esq</option>
                      <option value="M.D.">M.D.</option>
                      <option value="Engr.">Engr.</option>
                      <option value="CPA">CPA</option>
                    </select>
                  </div>
                </div>
                <div class="form-row mt-3">
                <div class="col-md-3">
                    <label for="contact_type">Contact Type *</label>
                    <select id="contact_type" name="contact_type" class="form-control" required>
                      <option value="selected">Choose Option</option>
                      <option value="Telephone">Telephone</option>
                      <option value="Mobile">Mobile</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="contact">Contact.*</label>
                    <input type="text" class="form-control" id="contact" name="contact" required>
                  </div>
                  <div class="col-md-3">
                    <label for="email">E-mail Address</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="col-md-3">
                    <label for="sex">Sex*</label>
                    <select id="sex" name="sex" class="form-control" required>
                      <option value="selected">Choose Option</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                </div>
  
                <div class="form-row mt-3">
                <div class="col-md-3">
                    <label for="birthdate">Birthdate *</label>
                    <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                  </div>
                <div class="col-md-3">
                    <label for="birthplace">Place of Birth *</label>
                    <input type="text" class="form-control" id="birthplace" name="birthplace" required>
                  </div>
                  <div class="col-md-3">
                    <label for="age">Age*</label>
                    <input type="number" class="form-control" id="age" name="age" min="60" required readonly>
                  </div>
                  <div class="col-md-3">
                    <label for="civil_status">Civil Status*</label>
                    <select id="civil_status" name="civil_status" class="form-control" required>
                      <option value="Choose option">Choose option</option>
                      <option value="Divorced">Divorced</option>
                      <option value="Married">Married</option>
                      <option value="Separated">Separated</option>
                      <option value="Single">Single</option>
                      <option value="Widowed">Widowed</option>
                    </select>
                  </div>
                </div>

                <div class="form-row mt-3">
                <div class="col-md-3">
                  <label for="blood_type">Blood Type (Optional)</label>
                  <select id="blood_type" name="blood_type" class="form-control">
                    <option value="">Choose option</option>
                    <option value="O+">O+</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                  </select>
                </div>
                  <div class="col-md-3">
                    <label for="religion">Religion *</label>
                    <select id="religion" name="religion" class="form-control" required>
                      <option value="Choose option">Choose option</option>
                      <option value="Roman Catholic">Roman Catholic</option>
                      <option value="Islam">Islam</option>
                      <option value="Evangelicals (PCEC)">Evangelicals (PCEC)</option>
                      <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                      <option value="Aglipayan">Aglipayan</option>
                      <option value="Seventh-day Adventist">Seventh-day Adventist</option>
                      <option value="Bible Baptist Church">Bible Baptist Church</option>
                      <option value="United Church of Christ in the Philippines">United Church of Christ in the Philippines</option>
                      <option value="Jehovas Witnesses">Jehovas Witnesses</option>
                      <option value="Church of Christ">Church of Christ</option>
                      <option value="Jesus is Lord Church">Jesus is Lord Church</option>
                      <option value="United Pentecostal Church Inc.">United Pentecostal Church Inc.</option>
                      <option value="Philippine Independent Catholic Church">Philippine Independent Catholic Church</option>
                      <option value="Union Esperitista Cristiana de Filipinas, Inc.">Union Esperitista Cristiana de Filipinas, Inc.</option>
                      <option value="The Church of Jesus Christ of Latter-Day Saints">The Church of Jesus Christ of Latter-Day Saints</option>
                      <option value="Association of Fundamentals Baptist">Association of Fundamentals Baptist</option>
                      <option value="Churches in the Philippines">Churches in the Philippines</option>
                      <option value="Evangelical Christian Outreach Foundation">Evangelical Christian Outreach Foundation</option>
                      <option value="Convention of the Philippines Baptist Church">Convention of the Philippines Baptist Church</option>
                      <option value="Crusaders of the Divine Church of Christ Inc.">Crusaders of the Divine Church of Christ Inc.</option>
                      <option value="Buddhist">Buddhist</option>
                      <option value="Lutheran Church of the Phillipines">Lutheran Church of the Phillipines</option>
                      <option value="Iglesia sa Dios Espiritu Santo Inc.">Iglesia sa Dios Espiritu Santo Inc.</option>
                      <option value="Philippine Benevolent Missionaries Association">Philippine Benevolent Missionaries Association</option>
                      <option value="Faith Tabernacle Church">Faith Tabernacle Church</option>
                      <option value="Conservative Baptist Association of the Philippines">Conservative Baptist Association of the Philippines</option>
                      <option value="Others">Others</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="educ">Highest Educational Attainment *</label>
                    <select id="educ" name="educ" class="form-control" required>
                      <option value="Choose option">Choose option</option>
                      <option value="Elementary">Elementary</option>
                      <option value="High School">High School</option>
                      <option value="College">College</option>
                      <option value="Vocational">Vocational</option>
                      <option value="Masters Degree">Master's Degree</option>
                      <option value="Doctoral">Doctoral</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                  <label for="gsis">GSIS</label>
                  <input type="text" class="form-control" id="gsis" name="gsis">
                </div>
                <div class="col-md-3">
                  <label for="sss">SSS</label>
                  <input type="text" class="form-control" id="sss" name="sss">
                </div>
                  <div class="col-md-3">
                    <label for="philhealth">Philhealth</label>
                    <input type="text" class="form-control" id="philhealth" name="philhealth">
                  </div>
                  <div class="col-md-3">
                    <label for="emp_status">Employment Status</label>
                    <select id="emp_status" name="emp_status" class="form-control" required>
                      <option value="Choose option">Choose option</option>
                      <option value="Employed">Employed</option>
                      <option value="Self-Employed">Self-Employed</option>
                      <option value="Unemployed">Unemployed</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                <label for="classification">Classification*</label>
                <select class="form-control" id="classification" name="classification" required onchange="checkClassification()">
                    <option value="">Select Option</option>
                    <option value="Indigent">Indigent</option>
                    <option value="Pensioner">Pensioner</option>
                    <option value="Supported">Supported</option>
                </select>
               </div>
                </div>
  
                <div class="form-row mt-3">
                <div class="col-md-3">
                    <label for="pension">Monthly Pension</label>
                    <select class="form-control" id="pension" name="pension" required disabled>
                      <option value="">Select Option</option>
                      <option value="Below P999.00">Below P999.00</option>
                      <option value="P1,000.00-P4,999.00">P1,000.00 - P4,999.00</option>
                      <option value="P5,000.00-P9,999.00">P5,000.00 - P9,999.00</option>
                      <option value="P10,000.00-P14,999.00">P10,000.00 - P14,999.00</option>
                      <option value="P15,000.00-P19,999.00">P15,000.00 - P19,999.00</option>
                      <option value="P20,000.00-P24,999.00">P20,000.00 - P24,999.00</option>
                      <option value="P25,000.00-P29,999.00">P25,000.00 - P29,999.00</option>
                      <option value="P30,000.00-P34,999.00">P30,000.00 - P34,999.00</option>
                      <option value="P35,000.00-P39,999.00">P35,000.00 - P39,999.00</option>
                      <option value="P40,000.00-P44,999.00">P40,000.00 - P44,999.00</option>
                      <option value="P45,000.00-P49,999.00">P45,000.00 - P49,999.00</option>
                      <option value="Above P50,000.00">Above P50,000.00</option>
                    </select>
                  </div>
                <div class="col-md-3">
                    <label for="citizenship">Citizenship*</label>
                    <select id="citizenship" name="citizenship" class="form-control" required>
                      <option value="Filipino">Filipino</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                  <label for="emergency">Emergency Contact</label>
                  <input type="text" class="form-control" id="emergency" name="emergency">
                </div>
                  <div class="col-md-3">
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
                  <button type="submit" class="btn btn-primary" form="residentForm" name="insert">Insert</button>
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
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
  <script>
    function ensurePrefix() {
        var input = document.getElementById('barangay');
        var prefix = "Brgy. ";
        if (!input.value.startsWith(prefix)) {
            input.value = prefix + input.value.slice(prefix.length);
        }
    }
    function validateAge() {
        var age = document.getElementById('age').value;
        if (age < 60) {
            alert('Age must be 60 or above.');
            return false;
        }
        return true;
    }

    document.querySelector('form').onsubmit = validateAge;

    function generateIDNumber() {
        return Math.floor(10000 + Math.random() * 90000);
    }

    document.getElementById('id_number').value = generateIDNumber();
</script>


<script>
    function calculateAge() {
        const birthdate = document.getElementById('birthdate').value;
        const ageInput = document.getElementById('age');

        if (birthdate) {
            const today = new Date();
            const birthDate = new Date(birthdate);
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDifference = today.getMonth() - birthDate.getMonth();

            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            ageInput.value = age;
        } else {
            ageInput.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('birthdate').addEventListener('change', calculateAge);
    });

    function checkClassification() {
            var classification = document.getElementById('classification').value;
            var pensionSelect = document.getElementById('pension');
            
            if (classification === 'Pensioner') {
                pensionSelect.disabled = false;
            } else {
                pensionSelect.disabled = true;
                pensionSelect.value = ''; // reset the value if disabled
            }
        }

        function checkProvince() {
            var province = document.getElementById('province').value;
            var municipalSelect = document.getElementById('municipal');

            if (province !== '') {
                municipalSelect.disabled = false;
            } else {
                municipalSelect.disabled = true;
                municipalSelect.value = ''; // reset the value if disabled
                document.getElementById('barangay').disabled = true; // reset barangay select as well
                document.getElementById('barangay').value = '';
            }
        }

        function checkMunicipal() {
            var municipal = document.getElementById('municipal').value;
            var barangaySelect = document.getElementById('barangay');

            if (municipal !== '') {
                barangaySelect.disabled = false;
            } else {
                barangaySelect.disabled = true;
                barangaySelect.value = ''; // reset the value if disabled
            }
        }
</script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
</body>
</html>
