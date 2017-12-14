<?php
	$filepath = realpath(dirname(__FILE__));
	include_once $filepath.'/../lib/Session.php';
	Session::init();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reservation</title>
	     <link rel="stylesheet" href="css/main.css" media="screen" type="text/css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link rel="icon" href="favicon-1.ico" type="image/x-icon">
</head>

<?php
	if (isset($_GET['action']) && $_GET['action'] == "logout") {
		Session::destroy();
	}
?>

<body>
	<div class="container">
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="index.php">Booking.com</a>
				</div>
				<ul class="nav navbar-nav main-nav  clear navbar-right ">

				<?php
					$id = Session::get("id");
					$userlogin = Session::get("login");
					if ($userlogin == true) {
					
				?>
					<li><a class="color_animation" href="profile.php?id=<?php echo $id ?>">Profile</a></li>
					<li><a class="color_animation" href="?action=logout">Logout</a></li>
					
				<?php
					}else{
				?>

					<li><a class="color_animation" href="login.php">Login</a></li>
					<li><a class="color_animation" href="register.php">Register</a></li>
				
				<?php
					}
				?>

				</ul>
			</div>
		</nav>