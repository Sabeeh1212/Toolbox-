<?php 
include 'config.php';
if (!isset($_SESSION['admin_logged_in'])) header("Location: login.php");

// Delete product if requested
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: manage_products.php?deleted=1");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Same sidebar as dashboard -->
        
        <main class="content">
            <h1>Manage Products</h1>
            <a href="add_product.php" class="button">Add New Product</a>
            
            <table>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
                <?php
                $result = $conn->query("SELECT * FROM products");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><img src="<?php echo $row['image_path']; ?>" width="50"></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>LKR <?php echo $row['price']; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="manage_products.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>
</body>
</html>