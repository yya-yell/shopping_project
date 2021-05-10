<?php
	session_start();
	require_once("config/config.php");
	require_once("config/common.php");
	if($_POST){
		if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['address']) || empty($_POST['phone']) || strlen($_POST['password']) < 4 ){
			if(empty($_POST['name'])){
				$nameErr = 'User Name cannot be blank';
			}
			if(empty($_POST['email'])){
				$emailErr = 'Email cannot be blank';
			}
			if(empty($_POST['password'])){
				$passwordErr = 'Password cannot be blank';
			}
			if(empty($_POST['Address'])){
				$addressErr = 'Address cannot be blank';
			}
			if(empty($_POST['Phone'])){
				$phoneErr = 'Phone cannot be blank';
			}
			if(strlen($_POST['password']) < 4){
				$passwordErr = 'Password should be at least 4 characters';
			}
		} else {
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$address = $_POST['address'];
			$phone = $_POST['phone'];
			$role = 0;
			$valStmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
			$valStmt->execute([":email"=>$email]);
			$valRes = $valStmt->fetch(PDO::FETCH_ASSOC);
			if($valRes){
				echo "<script>alert('This email is already in use')</script>";
			} else {
				$stmt = $pdo->prepare("INSERT INTO users (name , email , password, address , `phone` , role) 
				VALUES (:name, :email, :password, :address, :phone, :role)");
					$res = $stmt->execute(	
						[
						':name'=>$name,
						':email'=>$email,
						':password'=>$password,
						':address'=>$address,
						':phone'=>$phone,
						':role'=>$role
						]
					);
					if($res){
					header("location:login.php");
					}else{
					echo "<script>alert('Fail')</script>";
					}
			}
		
		}
	}

?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Shopping from Home</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.html"><img src="img/logo.png" alt=""></a>
				</div>
			</nav>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Login/Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="login_form_inner">
						<h3>Register here to get in touch</h3>
						<form class="row login_form" action="" method="post" id="contactForm" novalidate="novalidate">
							<input name="_token" type="hidden" value="<?php echo empty($_SESSION['_token']) ? '' : $_SESSION['_token']; ?>">
							<!-- userName -->
							<div class="col-md-12 form-group">
								<input type="text" class="form-control <?php echo empty($nameErr) ? '' : 'border border-danger'?>" id="name" name="name" placeholder="User name" 
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'User Name'" >
								<?php echo empty($nameErr) ? "" : "<p class='text-danger text-left display-6'>".$nameErr."</p>"; ?>
							</div>
							<!-- Email -->
							<div class="col-md-12 form-group">
								<input type="email" class="form-control <?php echo empty($emailErr) ? '' : 'border border-danger'?>" id="email" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
								<?php echo empty($nameErr) ? "" : "<p class='text-danger text-left display-6'>". $emailErr ."</p>"; ?>
							</div>
							<!-- password -->
							<div class="col-md-12 form-group">
								<input type="password" class="form-control  <?php echo empty($passwordErr) ? '' : 'border border-danger'?>" id="name" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
								<?php echo empty($passwordErr) ? "" : "<p class='text-danger text-left display-6'>". $passwordErr ."</p>"; ?>
							</div>
							<!-- address -->
							<div class="col-md-12 form-group">
								<input type="text" class="form-control  <?php echo empty($addressErr) ? '' : 'border border-danger'?>" id="address" name="address" placeholder="Address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'address'">
								<?php echo empty($addressErr) ? "" : "<p class='text-danger text-left display-6'>". $addressErr ."</p>"; ?>
							</div>
							<!-- phone -->
							<div class="col-md-12 form-group">
								<input type="number" class="form-control  <?php echo empty($phoneErr) ? '' : 'border border-danger'?>" id="phone" name="phone" placeholder="Phone Number" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'">
								<?php echo empty($phoneErr) ? "" : "<p class='text-danger text-left display-6'>". $phoneErr ."</p>"; ?>
							</div>
						
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn">Register</button>
								<a href='login.php' class="primary-btn text-white">Login</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<?php include('footer.php');?>

	<!-- End footer Area -->


	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>