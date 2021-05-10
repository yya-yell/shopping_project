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

include_once('header.php');
if (!empty($_GET['pageno'])) {
  $pageno = $_GET['pageno'];
} else {
  $pageno = 1;
}
$numofRec = 3;
$offset = ($pageno - 1) * $numofRec;
  $statement = $pdo->prepare("SELECT * FROM `sale_order_detail` WHERE id=:id");
  $statement->execute([':id'=>$_GET['id']]);
  $result = $statement->fetchAll();
  $totalpage = ceil(count($result) / $numofRec);
  $statement = $pdo->prepare("SELECT * FROM `sale_order_detail` WHERE id=:id LIMIT $offset , $numofRec");
  $statement->execute([':id'=>$_GET['id']]);
  $order_detail = $statement->fetchAll();
 
?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h2>Sale Orders Detail </h2>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>ပစည်း နာမည်</th>
                      <th>အရေအတွက်</th>
                      <th>မှာခဲ့သည့် အချိန်</th>
                      <th style="width: 200px">Action</th>
                    </tr>
                  </thead>
                  <?php if($order_detail) {
                    $i = 1;
                    foreach($order_detail as $detail_values) {
                  ?>
                  <?php 
                    $nameStatement = $pdo->prepare("SELECT * FROM products WHERE id=:id");
                    $nameStatement->execute([':id'=>$detail_values['product_id']]);
                    $nameResult = $nameStatement->fetchAll();
                  ?>
                  <tbody>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo escape($nameResult[0]['name']); ?></td>
                      <td><?php echo escape($detail_values['quantity']); ?><span class="text-danger"> ခု</span></td>
                      <td><?php echo escape($detail_values['order_date']); ?></td>
                      <td>
                        <a href="sale_order.php" class="btn btn-warning btn-md mr-3">back</a>
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