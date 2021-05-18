<?php 	

	if (!empty($_POST['search'])) {
	setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); 
	} else {
	if(empty($_GET['pageno'])){
		unset($_COOKIE['search']); 
		setcookie('search', null, -1, '/'); 
		}
	}
	include('header.php');
	if(empty($_SESSION['user_id'])){
		header('location:login.php');
	}

	
	if(!empty($_GET['cat_id'])){
		$catProduct = $_GET['cat_id'];
	}
	if (!empty($_GET['pageno'])) {
	$pageno = $_GET['pageno'];
	} else {
	$pageno = 1;
	}
	$numofRec = 3;
	$offset = ($pageno - 1) * $numofRec;
	if (empty($_POST['search']) && empty($_COOKIE['search'])) {
		if(empty($catProduct)){
			$statement = $pdo->prepare("SELECT * FROM `products` WHERE quantity > 0 ORDER BY id DESC");
			$statement->execute();
			$result = $statement->fetchAll();
			$totalpage = ceil(count($result) / $numofRec);
			$statement = $pdo->prepare("SELECT * FROM `products` WHERE quantity > 0 ORDER BY id DESC LIMIT $offset , $numofRec");
			$statement->execute();
			$products = $statement->fetchAll();
		}else{
			$statement = $pdo->prepare("SELECT * FROM `products` WHERE  category_id = :cat_id AND quantity > 0");
			$statement->execute([":cat_id" => $catProduct]);
			$result = $statement->fetchAll();
			$totalpage = ceil(count($result) / $numofRec);
			$statement = $pdo->prepare("SELECT * FROM `products` WHERE  category_id = :cat_id AND quantity > 0 LIMIT $offset , $numofRec");
			$statement->execute([':cat_id' => $catProduct]);
			$products = $statement->fetchAll();
		}
	} else {
		if(!empty($_POST['search'])){
			$search = $_POST['search'];
		}else {
			if(!empty($_COOKIE['search'])) {
			  $search = $_COOKIE['search'];
			}
		  }
		$statement = $pdo->prepare("SELECT * FROM `products` WHERE `name` LIKE '%$search%' AND quantity > 0 ORDER BY id DESC");
		$statement->execute();
		$result = $statement->fetchAll();
		$totalpage = ceil(count($result) / $numofRec);
		$statement = $pdo->prepare("SELECT * FROM `products` WHERE `name` LIKE '%$search%' AND quantity > 0 ORDER BY id DESC LIMIT $offset , $numofRec");
		$statement->execute();
		$products = $statement->fetchAll(); 

	}


?>
	<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
						<ul class="main-categories">
							<li class="main-nav-list"  id="cat_link">
								<?php 	$catStmt = $pdo->prepare("SELECT * FROM categories");
										$catStmt->execute();
										$catRes = $catStmt->fetchAll();
										if($catRes){
											foreach($catRes as $value){
								?>
										<a href="?cat_id=<?php echo $value['id']; ?>" class="text-default" ><?= $value['name'];?></a>
								<?php
										}
									}
								?>	
							</li>		
						</ul>
				</div>
			</div>
	<div class="col-xl-9 col-lg-8 col-md-7">
						<!-- Start Filter Bar -->
			<div class="filter-bar d-flex flex-wrap align-items-center">
				<div class="pagination">
					<a href="?pageno=1" class="prev-arrow">First</a>
					<!-- previous -->
					<a <?php if($pageno <= 1){echo "disabled";} ?> href="<?php if($pageno <= 1){echo "";}else{ echo "?pageno=".($pageno-1);}?>" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
					<a href="#" class="active"><?php echo escape($pageno); ?></a></a>
					<!-- next -->
					<a class="<?php if($pageno >= $totalpage){echo "disabled";} ?>"  href="<?php if($pageno >= $totalpage){echo "";}else{echo "?pageno=".($pageno+1);}?>" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					<a href="?pageno=<?php echo $totalpage; ?>" class="prev-arrow">Last</a>
				</div>
			</div>
	<!-- End Filter Bar -->
	<!-- Start Best Seller -->
		<section class="lattest-product-area pb-40 category-list">
			<div class="row">
				<?php
					if($products){
						foreach($products as $pValue){
				?>
						<!-- single product -->
					<div class="col-lg-4 col-md-6">
						<div class="single-product">
							<a href="product_detail.php?id=<?= $pValue['id'];?>"><img class="img-fluid" src="Admin/images/<?= $pValue['image'];?>" alt=""></a>
							<div class="product-details">
								<h6><?= $pValue['name'];?></h6>
								<div class="price">
									<h6><?= $pValue['price'];?>ကျပ်</h6>
								</div>
								<div class="prd-bottom">
									<form action="addTocart.php" method="post">
										<input name="_token" type="hidden" value="<?php echo empty($_SESSION['_token']) ? '' : $_SESSION['_token']; ?>">
										<input name="id" type="hidden" value="<?=$pValue['id'] ; ?>">
										<input name="qty" type="hidden" value="1">

											<div class="social-info" >
												<button class="social-info" style="display: contents;">
													<span class="ti-bag"></span>
													<p class="hover-text" style="left: 20px;">add to bag</p>
												</button>
											</div>
											<a href="product_detail.php?id=<?= $pValue['id']; ?>" class="social-info">
												<span class="lnr lnr-move"></span>
												<p class="hover-text">view more</p>
											</a>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- single product -->
				<?php
						}
					}
				?>
				
			
			</div>
		</section>
				<!-- End Best Seller -->
<?php include('footer.php');?>

