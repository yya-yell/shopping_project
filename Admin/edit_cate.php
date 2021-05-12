<?php
session_start();
require_once("../config/config.php");
require_once("../config/common.php");
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header("location:/Admin/login.php");}
if($_SESSION['role'] != 1){
  header("location:/Admin/login.php");}
if ($_POST) {
    if(empty($_POST['name'])|| empty($_POST['description'])){
      if(empty($_POST['name'])) {
        $titleError = "Name Cannot be blank";
      }
      if(empty($_POST['description'])) {
        $contentError = "Description Cannot be blank";
      }
    } else {
      $id = $_POST['id'];
      $name = $_POST['name'];
      $description = $_POST['description']; 
      $statement = $pdo->prepare("UPDATE `categories` SET `name`= :name , `description` = :description WHERE 
      id =:id");
      $result = $statement->execute(
          [":name"=>$name , ":description"=>$description , ":id"=>$id]
        );
        if ($result) {
            echo "<script>alert('Successfully Updated');window.location.href='Category_mana.php';</script>";
        }
      }
}
$statement = $pdo->prepare("SELECT * FROM `categories` WHERE id=" . $_GET['id']);
$statement->execute();
$cate = $statement->fetchAll();
// var_dump($post);
?>
<?php include_once('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Category Edit</h3> <br>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="" method="post">
                  <input name="_token" type="hidden" value="<?php echo empty($_SESSION['_token']) ? '' : $_SESSION['_token']; ?>">
                    <input type="hidden" name="id" value="<?php echo $cate[0]['id']; ?>">
                    <div class="form-group">
                        <label for="">Name</label>
                        <p class="text-danger"><?php echo empty($titleError) ? '' : '*'.$titleError; ?></p>
                        <input type="text" name="name" class="form-control" 
                        value="<?php echo escape($cate[0]['name']); ?>">
                    </div>   
                    <div class="form-group">
                        <label for="">Description</label>
                        <p class="text-danger"><?php echo empty($contentError) ? '' : '*'.$contentError; ?></p>
                        <textarea name="description" class="form-control" cols="30" rows="10"><?php echo escape($cate[0]['description']); ?></textarea>
                    </div>  
                    <input type="submit" class="btn btn-success">    
                    <a href="Category_mana.php" class="btn btn-warning">Back</a>      
                </form>
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