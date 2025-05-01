<?php
require 'config.php';

if(isset($_GET['term']) && !empty($_GET['term'])) {
    $term = $conn->real_escape_string($_GET['term']);
    $sql = "SELECT * FROM products 
            WHERE name LIKE '%$term%' 
            OR description LIKE '%$term%'
            LIMIT 100";
    
    $result = $conn->query($sql);
    
    if($result->num_rows > 0) {
        while($product = $result->fetch_assoc()) {
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
        echo '<p>No products found matching "'.htmlspecialchars($_GET['term']).'"</p>';
    }
} else {
    echo '<p>Please enter a search term</p>';
}
?>