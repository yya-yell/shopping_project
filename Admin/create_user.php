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
if($_POST) {
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['address']) || empty('phone') ){
      if(empty($_POST['name'])) {
        $nameError = "Name Cannot be blank";
      }
      if(empty($_POST['email'])) {
        $emailErr = "Email Cannot be blank";
      }
      if(empty($_POST['password'])) {
        $passwordErr = "Password Cannot be blank";
      }
      if(empty($_POST['address'])) {
        $addressErr = "Address Cannot be blank";
      }
      if(empty($_POST['phone'])) {
        $phoneErr = "Phone Cannot be blank";
      }
    } else {
          $name = $_POST['name'];
          $email = $_POST['email'];
          $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
          $address = $_POST['address'];
          $phone = $_POST['phone'];
          if(!empty($_POST['admin'])){
            $admin = 1;
          }else {
            $admin = 0;
          }
          $statement = $pdo->prepare("INSERT INTO `users` (`name` , `email` , `password` , `address` , `phone`, `role`) 
          VALUES 
          (:name , :email, :password , :address, :phone, :role)");
          $post_data = $statement->execute(
              [
                  ':name'=> $name,
                  ':email'=> $email,
                  ':password'=>$password,
                  ':address'=>$address,
                  ':phone'=>$phone,
                  ':role'=>$admin,
              ]
          );
          if ($post_data) {
            echo "<script>
            alert('User Create Success');
            window.location.href='index.php';
            </script>";
          }
    }
}
?>
<?php include_once('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-10">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Create A user</h3> <br>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form action="create_user.php" method="post" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="<?php echo empty($_SESSION['_token']) ? '' : $_SESSION['_token']; ?>">
                    <div class="form-group">
                        <label for="">Name</label><p class="text-danger"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                        <input type="text" name="name" class="form-control">
                    </div>   
                    <div class="form-group">
                        <label for="">Email</label><p class="text-danger"><?php echo empty($emailErr) ? '' : '*'.$emailErr; ?></p>
                        <input type="email" name="email" class="form-control">
                    </div>   
                    <div class="form-group">
                        <label for="">Password</label> <p class="text-danger"><?php echo empty($passwordErr) ? '' : '*'.$passwordErr; ?></p>
                        <input type="password" name="password" class="form-control" />
                    </div>  
                    <div class="form-group">
                        <label for="">Address</label> <p class="text-danger"><?php echo empty($addressErr) ? '' : '*'.$addressErr; ?></p>
                        <input type="text" name="address" class="form-control" />
                    </div>  
                    <div class="form-group">
                        <label for="">Phone</label> <p class="text-danger"><?php echo empty($phoneErr) ? '' : '*'.$phoneErr; ?></p>
                        <input type="number" name="phone" class="form-control" />
                    </div>  
                    <div class="form-check mb-2">
                      <input type="checkbox" class="form-check-input" id="exampleCheck1" name="admin" value="1">
                      <label class="form-check-label" for="exampleCheck1">Check me, If this user is an admin</label>
                    </div>
                    <input type="submit" class="btn btn-success">    
                    <a href="index.php" class="btn btn-warning">Back</a>      
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