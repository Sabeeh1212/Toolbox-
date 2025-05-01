<?php 
include 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_products.php?error=Invalid product ID");
    exit();
}

$product_id = $_GET['id'];

// Get product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: manage_products.php?error=Product not found");
    exit();
}

$product = $result->fetch_assoc();

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
    
    // Check if new image was uploaded
    if (!empty($_FILES['image']['name'])) {
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
                // Delete old image if it exists and is not a default image
                if (!empty($product['image_path']) && file_exists($product['image_path']) && strpos($product['image_path'], 'default.jpg') === false) {
                    unlink($product['image_path']);
                }
                
                // Update database with new image
                $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_path = ?, category_id = ?, featured = ?, stock_quantity = ? WHERE id = ?");
                $stmt->bind_param("ssdsiiii", $name, $description, $price, $target_file, $category_id, $featured, $stock, $product_id);
            } else {
                $error = "Error uploading file.";
            }
        }
    } else {
        // Update database without changing image
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, featured = ?, stock_quantity = ? WHERE id = ?");
        $stmt->bind_param("ssdiiii", $name, $description, $price, $category_id, $featured, $stock, $product_id);
    }
    
    // Execute the query if no error occurred
    if (!isset($error) && $stmt->execute()) {
        header("Location: manage_products.php?success=Product updated successfully");
        exit();
    } else if (!isset($error)) {
        $error = "Error updating product: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="content">
            <h1>Edit Product</h1>
            <a href="manage_products.php" class="btn-back">← Back to Products</a>
            
            <?php if (isset($error)): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="product-form">
                <div class="form-group">
                    <label>Product Name*</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Description*</label>
                    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price* (Lkr)</label>
                        <input type="number" name="price" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Stock Quantity*</label>
                        <input type="number" name="stock_quantity" min="0" value="<?php echo $product['stock_quantity']; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Category*</label>
                    <select name="category_id" required>
                        <option value="">Select Category</option>
                        <?php 
                        // Reset categories result pointer
                        $categories->data_seek(0);
                        while($cat = $categories->fetch_assoc()): 
                            $selected = ($cat['id'] == $product['category_id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $selected; ?>><?php echo $cat['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group checkbox">
                    <input type="checkbox" id="featured" name="featured" <?php echo ($product['featured'] == 1) ? 'checked' : ''; ?>>
                    <label for="featured">Featured Product</label>
                </div>
                
                <div class="form-group">
                    <label>Current Image</label>
                    <div class="current-image">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 200px; max-height: 200px;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Update Product Image (optional)</label>
                    <input type="file" name="image" accept="image/*">
                    <small>Leave empty to keep current image. Max 2MB (JPG, PNG, JPEG, GIF)</small>
                </div>
                
                <button type="submit" class="btn-submit">Update Product</button>
            </form>
        </main>
    </div>
</body>
</html>