<?php require_once('header.php'); 
    if(empty($_SESSION['name'])){
		header('location:login.php');
	}

?>
  

    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                   <?php if(!empty($_SESSION['cart'])): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $total = 0;
                                    foreach($_SESSION['cart'] as $key => $val):
                                    $id = str_replace('id','',$key);
                                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$id);
                                    $stmt->execute();
                                    $res = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $total += $res['price']*$val;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img src="Admin/images/<?=$res['image'];?>" alt="" width="200px">
                                                </div>
                                                <div class="media-body">
                                                    <p><?= $res['description'];?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5><?= $res['price'];?></h5>
                                        </td>
                                        <td>
                                            <div class="product_count">
                                                <input type="text" name="qty"  value="<?= $val; ?>" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <h5><?= $res['price']*$val;?></h5>
                                        </td>
                                        <td>
                                            <a class="primary-btn" style="border-radius: 10px; line-height: 25px;" href="clear_product.php?id=<?= $res['id']; ?>">Clear</a>
                                        </td>
                                     </tr>
                                <?php endforeach; ?>            
                                <tr>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <h5>Subtotal</h5>
                                    </td>
                                    <td>
                                        <h5><?=$total;  ?></h5>
                                    </td>
                                </tr>
                            
                                <tr class="out_button_area">
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <div class="checkout_btn_inner d-flex align-items-center" style="width: 580px;">
                                            <a class="gray_btn" href="clearAll.php">CLEAR ALL</a>
                                            <a class="primary-btn" href="#">Continue Shopping</a>
                                            <a class="gray_btn" href="sale_order.php">ORDER SUBMIT</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                   <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->

    <!-- start footer Area -->
   <?php require_once('footer.php');?>