<?php 
include 'config.php';

// Check authentication
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get statistics
$products = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$categories = $conn->query("SELECT COUNT(*) FROM categories")->fetch_row()[0];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h2>Toolbox Admin</h2>
            <nav>
                <a href="admin_dashboard.php" class="active">Dashboard</a>
                <a href="manage_products.php">Products</a>
                <a href="manage_categories.php">Categories</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>

        <main class="content">
            <h1>Dashboard Overview</h1>
            
            <div class="stats">
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <p><?php echo $products; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Categories</h3>
                    <p><?php echo $categories; ?></p>
                </div>
            </div>

            <section class="recent-products">
                <h2>Recent Products</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </section>
        </main>
    </div>
</body>
</html>