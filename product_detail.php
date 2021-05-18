<?php include('header.php');
  if(empty($_SESSION['name'])){
		header('location:login.php');
	}
  if($_GET['id']){
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=:id");
    $stmt->execute([':id'=>$_GET['id']]);
    $proRes = $stmt->fetchAll();
  }
  $stmt1 = $pdo->prepare("SELECT * FROM categories WHERE id=:cat_id");
  $stmt1->execute(['cat_id'=>$proRes[0]['category_id']]);
  $catName = $stmt1->fetchAll();
?>
<!--================Single Product Area =================-->
<div class="product_image_area" style="padding-top: 0;">
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6">
        <img class="" src="Admin/images/<?= $proRes[0]['image'];?>" alt="" width="500px">  
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <h3><?= $proRes[0]['name']; ?></h3>
          <h2><?= $proRes[0]['price']; ?> ကျပ်</h2>
          <ul class="list">
            <li><a class="active" href="#"><span>Category</span> : <?= $catName[0]['name'];?></a></li>
            <li><a href="#"><span>Quantity</span> : <?= $proRes[0]['quantity']; ?></a></li>
          </ul>
          <p><?= $proRes[0]['description']; ?></p>
          <form action="addTocart.php" method="post">
            <input name="_token" type="hidden" value="<?php echo empty($_SESSION['_token']) ? '' : $_SESSION['_token']; ?>">
            <input type="hidden" name="id" value="<?= $proRes[0]['id'] ?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
              class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
              class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>
            <div class="card_area d-flex align-items-center">
              <button class="primary-btn" type="submit" href="#" style="border: 0px;">Add to Cart</button>
              <a class="primary-btn" href="index.php">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div><br>

<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include('footer.php');?>
