<?php
require 'config.php';

$pageTitle = "Your Shopping Cart";
include 'header.php';
?>

<div class="cart-container">
    <h1>Your Shopping Cart</h1>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty</p>
        <a href="index.php" class="btn-continue-shopping">Continue Shopping</a>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $cartTotal = 0;
                foreach ($_SESSION['cart'] as $item): 
                    $itemTotal = $item['price'] * $item['quantity'];
                    $cartTotal += $itemTotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>LKR <?= number_format($item['price'], 2) ?></td>
                    <td>
                        <form method="post" action="update_cart.php" class="quantity-form">
                            <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                            <button type="submit" class="btn-update">Update</button>
                        </form>
                    </td>
                    <td>LKR <?= number_format($itemTotal, 2) ?></td>
                    <td>
                        <a href="remove_from_cart.php?id=<?= $item['id'] ?>" class="btn-remove">Remove</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td colspan="2">LKR <?= number_format($cartTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="cart-actions">
            <a href="index.php" class="btn-continue-shopping">Continue Shopping</a>
            <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
        </div>
    <?php endif; ?>
</div>
