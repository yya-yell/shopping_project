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
if(empty($_GET['pageno'])){
  $pageno = 1;
}else {
  $pageno = $_GET['pageno'];
}
$numOfrec = 3;
$offset = ($pageno - 1) * $numOfrec;

if(empty($_POST['search']) && empty($_COOKIE['search'])){
  $stat_1 = $pdo->prepare("SELECT * FROM categories");
  $stat_1->execute();
  $stat_1_res = $stat_1->fetchAll();
  $totalpage = ceil(count($stat_1_res) / $numOfrec);
  $state = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset , $numOfrec ");
  $state->execute();
  $result = $state->fetchAll();
} else {
  if(!empty($_POST['search'])){
    $search = $_POST['search'];
  } else {
    if(!empty($_COOKIE['search'])) {
      $search = $_COOKIE['search'];
    }
  }
  $search_manu = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$search%' ORDER BY id DESC");
  $search_manu->execute();
  $search_res = $search_manu->fetchAll();
  $totalpage = ceil(count($search_res) / $numOfrec);
  $state_se = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $offset,$numOfrec");
  $state_se->execute();
  $result = $state_se->fetchAll();
}
include_once('header.php');
?>
  
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <h1>Category Management</h1>
        <a href="Category_create.php" class="btn btn-success mt-2 mb-5">Create New Category</a>
        <div class="row">
          <div class="col-10">
            <?php if($result): ?> 
              <?php foreach($result as $cate):?> 
                <div class="card">
                  <div class="card-header bg-light">
                    <h3 class=""><?php echo $cate['name'] ?></h3>
                    <small class="text-muted">Category Name</small>
                  </div>
                  <div class="card-body">
                    <p class="text-muted">Description</p>
                    <p class="card-text"><?php echo $cate['description'];?></p>
                    <a href="edit_cate.php?id=<?php echo $cate['id'];?>" class="btn btn-info">Edit</a>
                    <a href="delete_cate.php?id=<?php echo $cate['id']; ?>" class="btn btn-danger" onclick="return confirm('Do you want to delete <?php echo $cate['name']; ?>');">Delete</a>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
            <ul class="pagination justify-content-end mt-3">
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
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('footer.html'); ?>

       