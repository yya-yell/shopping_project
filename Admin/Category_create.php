<?php
  session_start();
  require_once("../config/config.php");
  require_once("../config/common.php");


  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location:/Admin/login.php");  }
  if($_SESSION['role'] != 1){
    header("location:/Admin/login.php");  }
  if($_POST) {
    if(empty($_POST['name']) || empty($_POST['description'])){
      if(empty($_POST['name'])) {
        $nameError = "Name Cannot be blank";
      }
      if(empty($_POST['description'])) {
        $emailError = "Description Cannot be blank";
      }
    } else {
      $name = $_POST['name'];
      $description = $_POST['description'];
      // $created_at = "2021-1-26 9:58:30";
      $stat = $pdo->prepare("INSERT INTO categories (name , description) VALUES 
      (:name , :description)");
      $result = $stat->execute([":name"=>$name , ":description"=>$description]);
      if($result) {
        echo "<script>alert('Successfully add one Category');window.location.href='Category_mana.php';</script>";
      }
    }
    
  }
  include_once('header.php');
?>
  
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <h2>Create Category</h2>
        <div class="row">
          <div class="col-md-12">
            <form action="Category_create.php" method="post">
              <input name="_token" type="hidden" value="<?php echo empty($_SESSION['_token']) ? '' : $_SESSION['_token']; ?>">
                <div class="form-group">
                    <label for="">Name</label>
                    <small class="text-danger d-block"><?php echo empty($nameError) ? '': '*'.$nameError; ?></small>
                    <input type="text" class="form-control <?php echo empty($nameError) ? '' : 'is-invalid'; ?>" name="name">
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    <small class="text-danger d-block"><?php echo empty($emailError) ? '': '*'.$emailError; ?></small>
                    <input type="text" class="form-control <?php echo empty($emailError) ? '' : 'is-invalid'; ?>" name="description">
                </div>
                
                <input type="submit" class="btn btn-success">
                <a href="Category_mana.php" class="btn btn-warning">Back</a>
            </form>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php require_once('footer.html'); ?>