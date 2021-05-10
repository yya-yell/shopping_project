<?php
    require_once("../config/config.php");
    $stat = $pdo->prepare("DELETE FROM products WHERE id=:id");
    $stat = $stat->execute([':id'=>$_GET['id']]);
    if($stat){
        header("location:products.php");
    }
?>