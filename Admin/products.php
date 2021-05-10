<?php
session_start();
require_once("../config/config.php");
require_once("../config/common.php");
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("location:login.php");
}
if($_SESSION['role'] != 1){
  header("location: login.php");
}
if (!empty($_POST['search'])) {
  setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); 
} else {
  if(empty($_GET['pageno'])){
    unset($_COOKIE['search']); 
    setcookie('search', null, -1, '/'); 
  }
}
include_once('header.php');
if (!empty($_GET['pageno'])) {
  $pageno = $_GET['pageno'];
} else {
  $pageno = 1;
}
$numofRec = 6;
$offset = ($pageno - 1) * $numofRec;
if (empty($_POST['search']) && empty($_COOKIE['search'])) {
  $statement = $pdo->prepare("SELECT * FROM `products` ORDER BY id DESC");
  $statement->execute();
  $result = $statement->fetchAll();
  $totalpage = ceil(count($result) / $numofRec);
  $statement = $pdo->prepare("SELECT * FROM `products` ORDER BY id DESC LIMIT $offset , $numofRec");
  $statement->execute();
  $products = $statement->fetchAll();
} else {
  if(!empty($_POST['search'])){
    $search = $_POST['search'];
  } else {
    if(!empty($_COOKIE['search'])) {
      $search = $_COOKIE['search'];
    }
  }
  $statement = $pdo->prepare("SELECT * FROM `products` WHERE `name` LIKE '%$search%' ORDER BY id DESC");
  $statement->execute();
  $result = $statement->fetchAll();
  $totalpage = ceil(count($result) / $numofRec);
  $statement = $pdo->prepare("SELECT * FROM `products` WHERE `name` LIKE '%$search%' ORDER BY id DESC LIMIT $offset , $numofRec");
  $statement->execute();
  $products = $statement->fetchAll(); 
}
  $tt_user = $pdo->prepare("SELECT * FROM `users` ORDER BY id DESC");
  $tt_user->execute();
  $tt_user_count = $tt_user->fetchAll();
  $cat_stat = $pdo->prepare("SELECT * FROM `categories`");
  $cat_stat->execute();
  $tot_category = $cat_stat->fetchAll();
?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <h1>Products</h1>
        <a href="product_add.php" class="btn btn-success mb-3">Create Product</a>
        <div class="row">
            <?php if($products) {
               foreach($products as $allProducts) {
            ?> 
            <?php 
              $cate_name = $pdo->prepare("SELECT * FROM categories WHERE id=:allProId");
              $cate_name->execute([":allProId"=>$allProducts['category_id']]);
              $result_of_cate_name = $cate_name->fetchAll();
            ?>
              <div class="col-6 col-md-2">
                <div class="card">
                    <img src="images/<?php echo $allProducts['image'] ?>" style="height: 220px;" class="card-img-top" alt="">
                  <div class="card-body">
                    <small class="text-muted">Product Namle</small>
                    <p class="font-bold"><?php echo $allProducts['name'];?></p>
                    <small class="text-muted">Category</small>
                    <p class="font-bold"><?php echo $result_of_cate_name[0]['name'];?></p>
                    <small class="text-muted">Description</small>
                    <p class="card-text"><?php echo substr($allProducts['description'], 0 , 50);?></p>
                    <p class="card-text">Quantity: &nbsp;<?php echo $allProducts['quantity'];?></p>
                    <p class="card-text">Price: &nbsp;<?php echo $allProducts['price'];?> <span class="text-info">MMK</span></p>
                    <a href="product_edit.php?id=<?php echo $allProducts['id'];?>" class="btn btn-info">Edit</a>
                    <a href="product_delete.php?id=<?php echo $allProducts['id']; ?>" class="btn btn-danger" onclick="return confirm('Do you want to delete <?php echo $allProducts['name']; ?>');">Delete</a>
                  </div>
                </div>
              </div>
            <?php 
              }  
            }
              ?>
        </div>
        <!-- /.row -->
        <ul class="pagination justify-content-center mt-3">
                <li class="page-item"> <a href="?pageno=1" class="page-link">First</a></li>
                <li class="page-item <?php if($pageno <= 1){echo "disabled";} ?>"> 
                  <a href="<?php if($pageno <= 1){echo "disabled";}else{ echo "?pageno=".($pageno-1);}?>" class="page-link">
                    Previous
                  </a>
                </li>
                <li class="page-item"> <a href="" class="page-link"><?php echo escape($pageno); ?></a></li>
                <li class="page-item <?php if($pageno >= $totalpage){echo "disabled";} ?>">
                  <a 
                    href="<?php if($pageno >= $totalpage){echo "disabled";}else{echo "?pageno=".($pageno+1);}?>" class="page-link">
                  Next</a>
                </li>
                <li class="page-item"><a href="?pageno=<?php echo $totalpage; ?>" class="page-link">End</a></li>
          </ul>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('footer.html'); ?>