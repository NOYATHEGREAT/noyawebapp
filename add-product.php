<?php 
include 'helpers/functions.php'; 
template('header.php');

use Aries\MiniFrameworkStore\Models\Category;
use Aries\MiniFrameworkStore\Models\Product;
use Carbon\Carbon;

$categoryModel = new Category();
$productModel = new Product();

$categories = $categoryModel->getAll();
$message = '';

if (isset($_POST['submit'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category = $_POST['category'] ?? '';
    $image = $_FILES['image'] ?? null;

    // Validate category ID exists in DB
    $validCategoryIds = array_column($categories, 'id');

    if (!in_array($category, $validCategoryIds)) {
        $message = "Please select a valid category.";
    } else {
        // Validate image upload
        $targetFile = null;
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            // Create directory if not exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            // Sanitize filename
            $filename = basename($image["name"]);
            $targetFile = $targetDir . time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);
            move_uploaded_file($image["tmp_name"], $targetFile);
        }

        // Insert product record
        $productModel->insert([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'image_path' => $targetFile,
            'category_id' => $category,
            'created_at' => Carbon::now('Asia/Manila'),
            'updated_at' => Carbon::now('Asia/Manila')
        ]);

        $message = "Product added successfully!";
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 my-5">
            <h1 class="text-center">Add Product</h1>
            <p class="text-center">Fill in the details below to add a new product.</p>

            <?php if ($message): ?>
                <div class="alert <?php echo ($message === "Product added successfully!") ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="add-product.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product-name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product-name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="product-description" class="form-label">Description</label>
                    <textarea class="form-control" id="product-description" name="description" rows="5"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="product-price" class="form-label">Price</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="product-price" name="price" required value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="product-category" class="form-label">Category</label>
                    <select class="form-select" id="product-category" name="category" required>
                        <option value="" disabled <?php echo empty($_POST['category']) ? 'selected' : ''; ?>>Select category</option>
                        <?php 
                        foreach ($categories as $cat): 
                            $selected = '';
                            if (isset($_POST['category']) && $_POST['category'] == $cat['id']) {
                                $selected = 'selected';
                            } elseif (!isset($_POST['category']) && strtolower($cat['name']) == 'shoes') {
                                $selected = 'selected';
                            }
                        ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $selected; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Image</label>
                    <input class="form-control" type="file" id="formFile" name="image" accept="image/*">
                </div>
                <div class="mb-3 d-grid gap-2">
                    <button class="btn btn-primary" type="submit" name="submit">Add Product</button>
                </div>
            </form>
         </div>
    </div>
</div>

<?php template('footer.php'); ?>
