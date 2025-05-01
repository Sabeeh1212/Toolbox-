<?php 
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛠️ Toolbox - Your Hardware Solutions</title>
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
                <li><a href="#">Home</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#categories">Categories</a></li>
                <li><a href="#contact">Contact Us</a></li>
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
        <section class="welcome">
            <div class="welcome-content">
                <h2>Welcome to Toolbox</h2>
                <p>Your one-stop shop for all hardware needs - quality tools, building supplies, and expert advice</p>
                <a href="#products" class="btn-shop">Shop Now</a>
            </div>
        </section>

        <section class="categories" id="categories">
            <h2>Our Categories</h2>
            <div class="category-container">
                <?php
                $category_sql = "SELECT * FROM categories LIMIT 3";
                $category_result = $conn->query($category_sql);
                
                if ($category_result->num_rows > 0) {
                    while($category = $category_result->fetch_assoc()) {
                        echo '<div class="category-card">';
                        echo '<div class="category-img">';
                        echo '<img src="'.htmlspecialchars($category['image_path']).'" alt="'.htmlspecialchars($category['name']).'">';
                        echo '</div>';
                        echo '<h3>'.htmlspecialchars($category['name']).'</h3>';
                        echo '<p>'.htmlspecialchars($category['description']).'</p>';
                        echo '<a href="category.php?id='.$category['id'].'" class="btn-category">View All</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No categories found</p>';
                }
                ?>
            </div>
        </section>

        <section class="products" id="products">
            <h2>Featured Products</h2>
            <div class="product-container" id="productContainer">
                <?php
                $product_sql = "SELECT * FROM products WHERE featured = 1 LIMIT 100";
                $product_result = $conn->query($product_sql);

                if ($product_result->num_rows > 0) {
                    while($product = $product_result->fetch_assoc()) {
                        echo '<div class="product-card">';
                        echo '<div class="product-img">';
                        echo '<img src="'.htmlspecialchars($product['image_path']).'" alt="'.htmlspecialchars($product['name']).'">';
                        echo '</div>';
                        echo '<h3>'.htmlspecialchars($product['name']).'</h3>';
                        echo '<p>'.htmlspecialchars($product['description']).'</p>';
                        echo '<div class="price">LKR '.number_format($product['price'], 2).'</div>';
                        echo '<button class="btn-add-to-cart" data-id="'.$product['id'].'">Add to Cart</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No featured products found</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#products">Products</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Customer Service</h3>
                <ul>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Returns & Refunds</a></li>
                    <li><a href="#">Store Locator</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Toolbox Hardware has been serving DIY enthusiasts and professionals since 2005. We're committed to providing quality tools, expert advice, and exceptional customer service.</p>
                <div class="social-icons">
                    <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.twitter.com" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.youtube.com" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h3 class="footer-heading">Contact Us</h3>
                <address>
                    <p><i class="fas fa-map-marker-alt"></i> 124 Main Street, Sainthamaruthu, Sri Lanka</p>
                    <p><i class="fas fa-phone"></i> +94 76 2323 1234</p>
                    <p><i class="fas fa-envelope"></i> info@toolbox.lk</p>
                </address>
                <form method="POST" action="send_message.php">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <input type="text" name="subject" placeholder="Subject">
                    <textarea name="message" placeholder="Your Message" required></textarea>
                    <button type="submit" class="btn-submit">Send Message</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date("Y") ?> Toolbox Hardware. All rights reserved.</p>
            <div class="payment-methods">
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-cc-mastercard"></i>
                <i class="fab fa-cc-amex"></i>
                <i class="fab fa-cc-paypal"></i>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Mobile menu toggle
        $('#hamburger').click(function() {
            $('#navLinks').toggleClass('active');
        });

        // Search functionality
        $('#searchBtn').click(performSearch);
        $('#searchInput').keypress(function(e) {
            if(e.which === 13) performSearch();
        });

        function performSearch() {
            const searchTerm = $('#searchInput').val().trim();
            if(searchTerm) {
                $.get('search_products.php', { term: searchTerm })
                    .done(function(data) {
                        $('#productContainer').html(data);
                        $('html, body').animate({
                            scrollTop: $('#products').offset().top
                        }, 500);
                    })
                    .fail(function() {
                        $('#productContainer').html('<p>Error performing search</p>');
                    });
            }
        }
        
        // Add to cart functionality
        $(document).on('click', '.btn-add-to-cart', function() {
            const productId = $(this).data('id');
            $.post('add_to_cart.php', { product_id: productId })
                .done(function(response) {
                    try {
                        const result = JSON.parse(response);
                        if(result.success) {
                            $('.cart-count').text(result.cart_count);
                            alert('Product added to cart!');
                        } else {
                            alert(result.message || 'Error adding to cart');
                        }
                    } catch (e) {
                        alert('Error processing response');
                    }
                })
                .fail(function() {
                    alert('Failed to connect to server');
                });
        });
    });
    </script>
</body>
</html>