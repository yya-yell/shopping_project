<?php
session_start();
require_once("../config/config.php");
require_once("../config/common.php");
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location:/Admin/login.php");}
if($_SESSION['role'] != 1) {
    header("location:/Admin/login.php");  }
include_once('header.php');

if($_POST){
    if(empty($_POST['name']) || empty($_POST['desc']) || empty($_POST['category']) || empty($_POST['qty']) 
    || empty($_POST['price'])){
        if(empty($_POST['name'])){
            $nameErr = "Product Name cannot be empty";
        }
        if(empty($_POST['desc'])){
            $descErr = "Product Desc cannot be empty";
        }
        if(empty($_POST['category'])){
            $cateErr = "Product Category cannot be empty";
        }
        if(empty($_POST['qty'])){
            $qtyErr = "Product Quantity cannot be empty";
        }else if(!is_numeric($_POST['qty'])) {
            $qtyErr = "Product Quantity invalid";
        }
        if(empty($_POST['price'])){
            $priceErr = "Product Price cannot be empty";
        }else if(!is_numeric($_POST['price'])) {
            $priceErr = "Product price invalid";
        }
    } else { //validation success
       if($_FILES['image']['name'] != null) {
            $img_pick = "images/" . $_FILES['image']['name'];
            $img_type = pathinfo($img_pick , PATHINFO_EXTENSION);
            if($img_type != "jpg" && $img_type != "png" && $img_type != "jped") {
                $imageErr = "Invalid Image Extension";
            } else { //img validation success
                $name = $_POST['name'];
                $desc = $_POST['desc'];
                $cate = $_POST['category'];
                $qty = $_POST['qty'];
                $price = $_POST['price'];
                $image = $_FILES['image']['name'];
                $id = $_POST['id'];
                move_uploaded_file($_FILES['image']['tmp_name'] , $img_pick);
                $stat = $pdo->prepare("UPDATE products SET name=:name , description=:desc , category_id = :cate, quantity = :qty, price = :price , image= :image
                        WHERE id=:id");
                $result = $stat->execute(
                    [":name"=>$name, ":desc"=>$desc , ":cate"=>$cate , ":qty"=>$qty, ":price"=>$price, ":image"=>$image , ":id"=>$id]
                );
                if($result) {
                    echo "<script>alert('Edit success');window.location.href='products.php';</script>";
                }
            }
       } else {
            $name = $_POST['name'];
            $desc = $_POST['desc'];
            $cate = $_POST['category'];
            $qty = $_POST['qty'];
            $price = $_POST['price'];
            $id = $_POST['id'];
            $stat = $pdo->prepare("UPDATE products SET `name`=:name , `description`=:desc , `category_id` = :cate, `quantity` = :qty, `price` = :price
                    WHERE `id`=:id");
            $result = $stat->execute(
                [":name"=>$name, ":desc"=>$desc , ":cate"=>$cate , ":qty"=>$qty, ":price"=>$price, ":id"=>$id]
            );
            if($result) {
                echo "<script>alert('Edit success');window.location.href='products.php';</script>";
            }
       }
    }
}
$stat_of_product = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stat_of_product->execute([':id'=>$_GET['id']]);
$result_of_all_product = $stat_of_product->fetchAll();
?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <h1>Products Edit</h1>
        <div class="row">
           <div class="col-10 mb-5">
                <form action="" method="post"  enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo empty($_SESSION['_token']) ? '' : $_SESSION['_token']; ?>">
                <input type="hidden" value="<?php echo $result_of_all_product[0]['id']; ?>" name="id">
                <!-- name -->
                    <div class="form-group">
                        <label for="">Name</label><small class="text-danger d-flex justify-content-end"><?php echo empty($nameErr) ? "" : "*" . $nameErr; ?></small>
                        <input type="text" name="name" class="form-control" id="" value="<?php echo $result_of_all_product[0]['name']; ?>">
                    </div>
                <!-- description -->
                    <div class="form-group">
                        <label for="">Description</label><small class="text-danger d-flex justify-content-end"><?php echo empty($descErr) ? "" : "*" . $descErr; ?></small>
                        <input type="text" name="desc" class="form-control" id="" value="<?php echo $result_of_all_product[0]['description']; ?>">
                    </div>
                <!-- category -->
                    <div class="form-group">
                        <label for="">Category</label><small class="text-danger d-flex justify-content-end"><?php echo empty($cateErr) ? "" : "*" . $cateErr; ?></small>
                        <select name="category" class="form-control" id="">
                            <option value="" disabled name="category">-- Choose a Category Type -- </option>
                            <?php 
                               $cat_stat = $pdo->prepare("SELECT * FROM `categories`");
                               $cat_stat->execute();
                               $tot_category = $cat_stat->fetchAll();
                               foreach($tot_category as $cate_result){
                            ?>
                            <?php if($cate_result['id'] == $result_of_all_product[0]['category_id']): ?>
                                <option value="<?php echo $cate_result['id'];?>" selected><?php echo $cate_result['name'];?></option>
                            <?php else: ?>
                               <option value="<?php echo $cate_result['id'];?>"><?php echo $cate_result['name'];?></option>
                            <?php endif; ?>
                            <?php
                               }
                            ?>
                            
                        </select>
                    </div>
                <!-- quantity -->
                    <div class="form-group">
                        <label for="">Quantity</label><small class="text-danger d-flex justify-content-end"><?php echo empty($qtyErr) ? "" : "*" . $qtyErr; ?></small>
                        <input type="text" name="qty" class="form-control" id="" value="<?php echo $result_of_all_product[0]['quantity']; ?>">
                    </div>
                <!-- price -->
                    <div class="form-group">
                        <label for="">Price MMK</label><small class="text-danger d-flex justify-content-end"><?php echo empty($priceErr) ? "" : "*" . $priceErr; ?></small>
                        <input type="text" name="price" class="form-control" id="" value="<?php echo $result_of_all_product[0]['price']; ?>">
                    </div>
                <!-- image -->
                    <div class="form-group">
                        <label for="">Image</label><small class="text-danger d-flex justify-content-end"><?php echo empty($imageErr) ? "" : "*" . $imageErr; ?></small>
                        <input type="file" name="image" id="" value="<?php echo $result_of_all_product[0]['image']; ?>">
                        <div class="col-6 col-md-4 mt-3">    
                            <div class="card">
                                <img src="images/<?php echo $result_of_all_product[0]['image']; ?>" class="card-img-top" alt="">
                            </div>
                        </div>
                    </div>
                    <input type="submit" name=""  class="btn btn-info" id="">
                    <a href="products.php" class="btn btn-warning">Back</a>
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