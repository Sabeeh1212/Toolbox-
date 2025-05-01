<?php
include 'config.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle category deletion
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_categories.php?deleted=1");
    exit();
}

// Fetch all categories
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1>Manage Categories</h1>
                <a href="add_category.php" class="btn btn-primary">+ Add New Category</a>
            </div>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Category deleted successfully!</div>
            <?php endif; ?>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $category['id']; ?></td>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['description']); ?></td>
                        <td>
                            <?php if ($category['image_path']): ?>
                                <img src="<?php echo $category['image_path']; ?>" width="50">
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="edit_category.php?id=<?php echo $category['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="manage_categories.php?delete_id=<?php echo $category['id']; ?>" 
                               class="btn btn-delete" 
                               onclick="return confirm('Are you sure? This will delete all products in this category!')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>