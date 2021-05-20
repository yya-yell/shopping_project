<?php
    session_start();
    require_once("../config/config.php");
    require_once("../config/common.php");
    if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location:/Admin/login.php");}
    if($_SESSION['role'] != 1){
    header("location:/Admin/login.php");}

    include_once('header.php');

   
    $stmt = $pdo->prepare("SELECT * FROM sale_order_detail WHERE quantity > 10 ORDER BY id DESC");
    $stmt->execute();
    $bsRpRes = $stmt->fetchAll();


?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <h1>Best Celler Items</h1>
        <div class="row">
              <div class="col-10">
                  <table id="rp-table" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Best Selling</th>
                                <th>Order_date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bsRpRes as $val):
                                $pstmt =  $pdo->prepare("SELECT * from products WHERE id=:p_id ");
                                $pstmt->execute([':p_id'=>$val['product_id']]);
                                $resPsmt = $pstmt->fetchAll();
                            ?> 
                            <tr>
                                <td><?= $resPsmt[0]['name'];?></td>
                                <td><?= $val['quantity'];?></td>
                                <td><?= date('Y-m-d' , strtotime($val['order_date']));?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                  </table>
              </div>
        </div>
        <!-- /.row -->
   
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('footer.html'); ?>
<script>
    $(document).ready(function() {
    $('#rp-table').DataTable();
    } );
</script>