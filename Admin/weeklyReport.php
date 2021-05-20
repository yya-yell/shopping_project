<?php
    session_start();
    require_once("../config/config.php");
    require_once("../config/common.php");
    if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location:/Admin/login.php");}
    if($_SESSION['role'] != 1){
    header("location:/Admin/login.php");}

    include_once('header.php');

    $current = date('Y-m-d');
    $currentDate = date('Y-m-d' , strtotime($current . '+ 1 day'));
    $lastDate = date('Y-m-d' , strtotime($current . "- 7 day"));
    $stmt = $pdo->prepare("SELECT * FROM sale_order WHERE order_date < :current AND order_date >= :last ORDER BY id DESC");
    $stmt->execute([
        ':current'=> $currentDate,
        ':last'=> $lastDate
    ]);
    $wkRpRes = $stmt->fetchAll();


?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <h1>Weekly Report</h1>
        <p>Last  : <?= $lastDate; ?></p> 
        <p>Now : <?= $currentDate; ?></p>
        <div class="row">
              <div class="col-10">
                  <table id="rp-table" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>total Price</th>
                                <th>Order_date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($wkRpRes as $val):
                                $pstmt =  $pdo->prepare("SELECT * from users WHERE id=:user_id ");
                                $pstmt->execute([':user_id'=>$val['user_id']]);
                                $resPsmt = $pstmt->fetchAll();  
                            ?> 
                            <tr>
                                <td><?= $resPsmt[0]['name'];?></td>
                                <td><?= $val['total_price'];?> kyats</td>
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