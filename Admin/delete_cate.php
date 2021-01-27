<?php
    require_once("../config/config.php");
    $stat = $pdo->prepare("DELETE FROM `categories` WHERE id=".$_GET['id']);
    $stat->execute();
    header("location: Category_mana.php");
?>