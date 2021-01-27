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
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['address']) || empty($_POST['phone'])){
      if(empty($_POST['name'])){
        $nameError = "Name cannot be blank";
      }
      if(empty($_POST['email'])){
        $emailError = "Name cannot be blank";
      }
      if(empty($_POST['address'])){
        $addressErr = "You must add your address";
      }
      if(empty($_POST['phone'])){
        $phoneErr = "You must add your phone number to contact";
      }
    }else if(!empty($_POST['password']) && strlen($_POST['password'])<5) {
        $passwordError = "Password should be 5 characters at least";
    } else {
      $name = $_POST['name'];
      $email = $_POST['email'];
      if(empty($_POST['role'])){
        $role = 0;
        } else {
          $role = 1;
        }
      $address = $_POST['address'];
      $phone = $_POST['phone'];
      $id = $_GET['id'];
      $stat1 = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
      $stat1->execute([":email"=>$email , ":id"=>$id]);
      $stat1_res = $stat1->fetch(PDO::FETCH_ASSOC);
      if($stat1_res){
          echo "<script>alert('Someone used this email');</script>";
      } else {
        $password = $_POST['password'];
        if($password != null) {
          $stat2 = $pdo->prepare("UPDATE users SET name='$name' , email='$email',password='$password', address='$address', phone='$phone', role='$role' WHERE id=$id");
        } else {
          $stat2 = $pdo->prepare("UPDATE users SET name='$name' , email='$email', address='$address', phone='$phone',role='$role' WHERE id=$id");
        }
          $result = $stat2->execute();
          if($result) {
            echo "<script>alert('Update Success');window.location.href='index.php';</script>";
          }
      }
    }
  }
  $stat1 = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $stat1->execute();
  $stat1_res = $stat1->fetchAll();
  include_once('header.php');
?>
  
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <h2>Edit User</h2>
        <div class="row">
          <div class="col-md-12">
            <form action="user_edit.php?id=<?php echo $stat1_res[0]['id'];?>" method="post">
                <input name="_token" type="hidden" value="<?php echo empty($_SESSION['_token']) ? '' : $_SESSION['_token']; ?>">
                <div class="form-group">
                    <label for="">Name</label>
                    <small class="text-danger d-block"><?php echo empty($nameError) ? '': '*'.$nameError; ?></small>
                    <input type="text" class="form-control <?php echo empty($nameError) ? '' : 'is-invalid'; ?>" name="name" value="<?php echo escape($stat1_res[0]['name']); ?>">
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <small class="text-danger d-block"><?php echo empty($emailError) ? '': '*'.$emailError; ?></small>
                    <input type="email" class="form-control <?php echo empty($emailError) ? '' : 'is-invalid'; ?>" name="email" value="<?php echo escape($stat1_res[0]['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <small class="text-danger d-block"><?php echo empty($passwordError) ? '': '*'.$passwordError; ?></small>
                    <input type="password" class="form-control <?php echo empty($passwordError) ? '' : 'is-invalid'; ?>" name="password">
                    <small class="pl-3">* This user already have a password. You can change password here.
                    If you don't want to change, You can leave it.</small>
                </div>
                <div class="form-group">
                    <label for="">Address</label> <p class="text-danger"><?php echo empty($addressErr) ? '' : '*'.$addressErr; ?></p>
                    <input type="text" name="address" class="form-control" value="<?php echo escape($stat1_res[0]['address']) ?>" />
                 </div>  
                <div class="form-group">
                    <label for="">Phone</label> <p class="text-danger"><?php echo empty($phoneErr) ? '' : '*'.$phoneErr; ?></p>
                    <input type="number" name="phone" class="form-control" value="<?php echo escape($stat1_res[0]['phone']); ?>"/>
                </div>  
                <div class="form-check mb-3">
                  <input type="checkbox" class="form-check-input" name="role" value="1" 
                  <?php if($stat1_res[0]['role'] == 1){echo "checked"; }?>>
                  <label class="form-check-label"> : Check IF you want to make Admin</label>
                </div>
                <input type="submit" class="btn btn-success"  value="update">
                <a href="index.php" class="btn btn-warning">Back</a>
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