<?php
include 'config.php';

if(isset($_GET['q']) && !empty($_GET['q'])) {
    $searchTerm = $conn->real_escape_string($_GET['q']);
    
    // Use only columns that exist in your table
    $sql = "SELECT * FROM products 
            WHERE name LIKE '%$searchTerm%' 
            OR description LIKE '%$searchTerm%'";
            
    $result = $conn->query($sql);
    
    if($result->num_rows > 0) {
        while($product = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<div class="product-img">';
            echo '<img src="'.$product['image_path'].'" alt="'.$product['name'].'">';
            echo '</div>';
            echo '<h3>'.$product['name'].'</h3>';
            echo '<p>'.$product['description'].'</p>';
            echo '<div class="price">LKR '.number_format($product['price'], 2).'</div>';
            echo '<button class="btn-add-to-cart" data-id="'.$product['id'].'">Add to Cart</button>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found matching "'.htmlspecialchars($_GET['q']).'"</p>';
    }
} else {
    echo '<p>Please enter a search term.</p>';
}
?>