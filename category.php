<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>

<?php

use Aries\MiniFrameworkStore\Models\Category;
use Aries\MiniFrameworkStore\Models\Product;

$categoryModel = new Category();
$productModel = new Product();

$selectedCategory = $_GET['name'] ?? '';

$locale = 'en_PH';
$pesoFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

$allCategories = $categoryModel->getAll();

// Filter categories with "Shoes" in their name (case-insensitive)
$shoeCategories = array_filter($allCategories, function($cat) {
    return stripos($cat['name'], 'Shoes') !== false;
});

?>

<style>
    body {
        background-color: #f9f6fb;
    }

    h1, h2, h4 {
        color: #6a0dad;
    }

    .btn-primary {
        background-color: #6a0dad;
        border-color: #6a0dad;
    }

    .btn-primary:hover {
        background-color: #7e33cc;
        border-color: #7e33cc;
    }

    .btn-outline-primary {
        color: #6a0dad;
        border-color: #6a0dad;
    }

    .btn-outline-primary:hover {
        background-color: #6a0dad;
        color: #fff;
    }

    .btn-success {
        background-color: #a020f0;
        border-color: #a020f0;
    }

    .btn-success:hover {
        background-color: #9400d3;
        border-color: #9400d3;
    }

    .card {
        border: 1px solid #e6ccff;
        box-shadow: 0 4px 8px rgba(160, 32, 240, 0.1);
    }

    .card-title {
        color: #6a0dad;
    }

    .card-subtitle {
        color: #8e44ad;
    }
</style>

<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h1 class="text-center"><?= htmlspecialchars($selectedCategory ?: 'Select a Shoe Category') ?></h1>
        </div>
    </div>

    <!-- Shoe category buttons -->
    <?php if (!empty($shoeCategories)): ?>
        <div class="text-center my-4">
            <h4>Shoe Categories</h4>
            <div class="d-flex justify-content-center flex-wrap gap-2">
                <?php foreach ($shoeCategories as $cat): ?>
                    <a href="category.php?name=<?= urlencode($cat['name']) ?>" class="btn btn-outline-primary <?= ($cat['name'] === $selectedCategory) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <h2>Products</h2>
        <?php 
        $products = $selectedCategory ? $productModel->getByCategory($selectedCategory) : [];
        if (!empty($products)): 
            foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($product['image_path']) ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <h6 class="card-subtitle mb-2">
                                <?= $pesoFormatter->formatCurrency($product['price'], 'PHP') ?>
                            </h6>
                            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-primary">View Product</a>
                            <a href="cart.php?product_id=<?= $product['id'] ?>" class="btn btn-success">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        else: ?>
            <p class="text-center text-muted">No products found for this category.</p>
        <?php endif; ?>
    </div>
</div>

<?php template('footer.php'); ?>
