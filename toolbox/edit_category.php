<?php
include 'config.php';

// Check admin authentication
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get category ID
if (!isset($_GET['id'])) {
    header("Location: manage_categories.php");
    exit();
}

$id = (int)$_GET['id'];

// Fetch category data
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

if (!$category) {
    header("Location: manage_categories.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    
    // Handle file upload
    $target_dir = __DIR__ . "/images/categories/";
    $image_path = $category['image_path'];
    
    if (!empty($_FILES['image']['name'])) {
        // Delete old image if exists
        if ($image_path && file_exists(__DIR__ . '/' . $image_path)) {
            unlink(__DIR__ . '/' . $image_path);
        }
        
        // Upload new image
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = "images/categories/" . $new_filename;
        }
    }
    
    // Update database
    $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ?, image_path = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $description, $image_path, $id);
    
    if ($stmt->execute()) {
        header("Location: manage_categories.php?updated=1");
        exit();
    } else {
        $error = "Error updating category: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Category - Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="main-content">
            <div class="content-header">
                <h1>Edit Category</h1>
                <a href="manage_categories.php" class="btn btn-back">← Back to Categories</a>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="category-form">
                <div class="form-group">
                    <label for="name">Category Name*</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($category['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Current Image</label>
                    <?php if ($category['image_path']): ?>
                        <img src="<?php echo $category['image_path']; ?>" width="100" style="display: block; margin-bottom: 10px;">
                    <?php else: ?>
                        <p>No image uploaded</p>
                    <?php endif; ?>
                    
                    <label for="image">New Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <small>Leave blank to keep current image</small>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Category</button>
            </form>
        </main>
    </div>
</body>
</html>