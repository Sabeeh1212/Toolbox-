<?php
if (!isset($pageTitle)) {
    $pageTitle = "Toolbox Hardware Store";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Toolbox Hardware</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="logo-container">
            <div class="logo">
                <img src="Tool Box Hardware Store logo.jpg" alt="Toolbox Hardware Store" width="110" height="Auto">
            </div>
            <div class="hamburger" id="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
        </div>
        <nav class="nav-links" id="navLinks">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#products">Products</a></li>
                <li><a href="index.php#categories">Categories</a></li>
                <li><a href="index.php#contact">Contact Us</a></li>
            </ul>
        </nav>
        <div class="cart-search">
            <div class="search-container">
                <input type="text" placeholder="Search products..." id="searchInput">
                <button id="searchBtn"><i class="fas fa-search"></i></button>
            </div>
            <button class="login-btn" onclick="window.location.href='login.php'"><i class="fas fa-user"></i></button>
            <div class="cart-icon" onclick="window.location.href='cart.php'">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count"><?= $_SESSION['cart_count'] ?? 0 ?></span>
            </div>
        </div>
    </header>
    <main>