<?php
require 'config.php';

// Set proper JSON header first
header('Content-Type: application/json');

try {
    // Initialize response
    $response = [
        'success' => false,
        'message' => 'Invalid request',
        'cart_count' => $_SESSION['cart_count'] ?? 0
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $productId = (int)$_POST['product_id'];
        
        // Initialize cart if needed
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Add/update item in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            // Get product from database
            $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $_SESSION['cart'][$productId] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => 1
                ];
            }
        }
        
        // Update cart count
        $_SESSION['cart_count'] = array_sum(array_column($_SESSION['cart'], 'quantity'));
        
        $response = [
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $_SESSION['cart_count']
        ];
    }
} catch (Exception $e) {
    $response['message'] = 'Server error: ' . $e->getMessage();
}

// Ensure no output before this
echo json_encode($response);
exit(); // Prevent any additional output
?>