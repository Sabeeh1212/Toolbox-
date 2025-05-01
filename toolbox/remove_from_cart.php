<?php
require 'config.php';

if (isset($_GET['id'])) {
    $productId = (int)$_GET['id'];
    
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
    }
}

header('Location: cart.php');
exit();
?>