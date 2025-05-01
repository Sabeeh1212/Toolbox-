<?php 
include 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get categories for dropdown
$categories = $conn->query("SELECT * FROM categories");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $stock = $_POST['stock_quantity'];
    
    // Handle file upload
    $target_dir = "images/products/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Validate image
    $uploadOk = 1;
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        $error = "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check file size (max 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        $error = "Image must be less than 2MB";
        $uploadOk = 0;
    }
    
    // Allow certain file formats
    if(!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    
    // Upload if no errors
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_path, category_id, featured, stock_quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsiii", $name, $description, $price, $target_file, $category_id, $featured, $stock);
            
            if ($stmt->execute()) {
                header("Location: manage_products.php?success=Product added successfully");
                exit();
            } else {
                $error = "Error: " . $conn->error;
            }
        } else {
            $error = "Error uploading file.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Product</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="content">
            <h1>Add New Product</h1>
            <a href="manage_products.php" class="btn-back">← Back to Products</a>
            
            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-group">
                    <label>Product Name*</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Description*</label>
                    <textarea name="description" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price* (Lkr)</label>
                        <input type="number" name="price" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Stock Quantity*</label>
                        <input type="number" name="stock_quantity" min="0" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Category*</label>
                    <select name="category_id" required>
                        <option value="">Select Category</option>
                        <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group checkbox">
                    <input type="checkbox" id="featured" name="featured">
                    <label for="featured">Featured Product</label>
                </div>
                
                <div class="form-group">
                    <label>Product Image*</label>
                    <input type="file" name="image" accept="image/*" required>
                    <small>Max 2MB (JPG, PNG, JPEG, GIF)</small>
                </div>
                
                <button type="submit" class="btn-submit">Add Product</button>
            </form>
        </main>
    </div>
</body>
</html>