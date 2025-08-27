<?php
session_start();

include '../includes/connect.php';

// Fetch settings for current user
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Default user_id or handle the case where user_id is not set
    $user_id = 21; // or handle error
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
    $logo = $settings ? $settings['logo'] : '';
    $cache_buster = time(); // Forces the browser to refresh the favicon
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Oh no!</title>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,700" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/<?= htmlspecialchars($favicon) ?>?v=<?= $cache_buster ?>">
	<style>
		* {
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
}

body {
  padding: 0;
  margin: 0;
}

#notfound {
  position: relative;
  height: 100vh;
}

#notfound .notfound {
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
}

.notfound {
  max-width: 520px;
  width: 100%;
  text-align: center;
  line-height: 1.4;
}

.notfound .notfound-404 {
  height: 190px;
}

.notfound .notfound-404 h1 {
  font-family: 'Montserrat', sans-serif;
  font-size: 146px;
  font-weight: 700;
  margin: 0px;
  color: #232323;
}

.notfound .notfound-404 h1>span {
  display: inline-block;
  width: 120px;
  height: 120px;
  background-image: url('../Assets/O.png');
  background-size: cover;
  -webkit-transform: scale(1.4);
      -ms-transform: scale(1.4);
          transform: scale(1.4);
  z-index: -1;
}

.notfound h2 {
  font-family: 'Montserrat', sans-serif;
  font-size: 22px;
  font-weight: 700;
  margin: 0;
  text-transform: uppercase;
  color: #232323;
}

.notfound p {
  font-family: 'Montserrat', sans-serif;
  color: #000;
  font-weight: 500;
}

.notfound a {
  font-family: 'Montserrat', sans-serif;
  display: inline-block;
  padding: 12px 30px;
  font-weight: 700;
  background-color: #ff0000;
  color: #fff;
  border-radius: 40px;
  text-decoration: none;
  -webkit-transition: 0.2s all;
  transition: 0.2s all;
}

.notfound a:hover {
  opacity: 0.3;
}

@media only screen and (max-width: 767px) {
  .notfound .notfound-404 {
    height: 115px;
  }
  .notfound .notfound-404 h1 {
    font-size: 86px;
  }
  .notfound .notfound-404 h1>span {
    width: 86px;
    height: 86px;
  }
}
	</style>
</head>

<body>

	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>4<span></span>4</h1>
			</div>
			<h2>Oops! Page Not Be Found</h2>
			<p>Sorry but the page you are looking for does not exist, have been removed. name changed or is temporarily unavailable</p>
			<a href="../?id=634829175920">Back to homepage</a>
		</div>
	</div>

</body>

</html>
