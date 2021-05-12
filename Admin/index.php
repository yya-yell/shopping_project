<?php
session_start();
require_once("../config/config.php");
require_once("../config/common.php");
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("location:/Admin/login.php");
}
if($_SESSION['role'] != 1){
  header("location:/Admin/login.php");
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
$numofRec = 1;
$offset = ($pageno - 1) * $numofRec;
if (empty($_POST['search']) && empty($_COOKIE['search'])) {
  $statement = $pdo->prepare("SELECT * FROM `users` ORDER BY id DESC");
  $statement->execute();
  $result = $statement->fetchAll();
  $totalpage = ceil(count($result) / $numofRec);
  $statement = $pdo->prepare("SELECT * FROM `users` ORDER BY id DESC LIMIT $offset , $numofRec");
  $statement->execute();
  $users = $statement->fetchAll();
} else {
  if(!empty($_POST['search'])){
    $search = $_POST['search'];
  } else {
    if(!empty($_COOKIE['search'])) {
      $search = $_COOKIE['search'];
    }
  }
  $statement = $pdo->prepare("SELECT * FROM `users` WHERE `name` LIKE '%$search%' ORDER BY id DESC");
  $statement->execute();
  $result = $statement->fetchAll();
  $totalpage = ceil(count($result) / $numofRec);
  $statement = $pdo->prepare("SELECT * FROM `users` WHERE `name` LIKE '%$search%' ORDER BY id DESC LIMIT $offset , $numofRec");
  $statement->execute();
  $users = $statement->fetchAll(); 
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
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h2>Users</h2>
                <a href="create_user.php" class="btn btn-success mt-2">Create New User</a>
                <p class="mt-1 text-info">Total Users: <?php echo count($tt_user_count);?></p>
                <p class="mt-1 text-info">Total Categories: <?php echo count($tot_category);?></p>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 200px">Action</th>
                    </tr>
                  </thead>
                  <?php if($users) {
                    $i = 1;
                    foreach($users as $allUser) {
                  ?>
                  <tbody>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo escape($allUser['name']); ?></td>
                      <td><?php echo substr($allUser['email'] , 0 , 150); ?></td>
                      <td><?php echo $allUser['role'] == 1 ? 'Admin' : 'User'; ?></td>
                      <td>
                        <a href="user_edit.php?id=<?php echo escape($allUser['id']); ?>" class="btn btn-warning btn-md mr-3">Edit</a>
                        <a href="delete.php?id=<?php echo escape($allUser['id']); ?>" class="btn btn-danger btn-md" 
                        onclick="return confirm('Are you sure to delete this user?<?php echo $allUser['name']; ?>')">Delete</a>
                      </td>
                    </tr>                   
                  </tbody>
                  <?php 
                  $i++;
                    }
                  }; 
                  ?>
                </table>
                  <ul class="pagination justify-content-end mt-3">
                    <li class="page-itam"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno <= 1){echo "disabled";}?>">
                      <a class="page-link" 
                        href="<?php if($pageno <= 1){echo "disabled";}else{echo "?pageno=".($pageno-1);}?>">
                        Previous
                      </a>
                    </li>
                    <li class="page-item"><a class="page-link" href=""><?php echo escape($pageno); ?></a></li>
                    <li class="page-item <?php if($pageno >= $totalpage){echo "disabled";} ?>">
                      <a class="page-link" 
                        href="<?php if($pageno >= $totalpage){echo "disabled";}else{echo "?pageno=".($pageno+1);} ?>">
                        Next
                      </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalpage; ?>">End</a></li>
                  </ul>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('footer.html'); ?>