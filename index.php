<?php include 'helpers/functions.php'; ?>
<?php template('header.php'); ?>

<?php

use Aries\MiniFrameworkStore\Models\Product;

$products = new Product();

// Set the locale and check for the intl extension
$amounLocale = 'en_PH';
$pesoFormatter = null;

if (class_exists('NumberFormatter')) {
    $pesoFormatter = new NumberFormatter($amounLocale, NumberFormatter::CURRENCY);
} else {
    echo "<div style='color:red; text-align:center;'>Error: PHP Intl extension is not enabled. Currency formatting won't work.</div>";
}

?>

<div class="container my-5">
    <div class="row align-items-center">
        <div class="col-md-12">
            <h1 class="text-center">HUSTLE STEPS</h1>
            <p class="text-center">FIND YOUR PEACE IN EVERY STEP!</p>
        </div>
    </div>

    <div class="row">
        <h2>Products</h2>
        <?php foreach ($products->getAll() as $product): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="<?php echo $product['image_path']; ?>" class="card-img-top" alt="Product Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">
                        <?php 
                        if ($pesoFormatter) {
                            echo $pesoFormatter->formatCurrency($product['price'], 'PHP');
                        } else {
                            echo '₱' . number_format($product['price'], 2);
                        }
                        ?>
                    </h6>
                    <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">View Product</a>
                    <a href="#" class="btn btn-success add-to-cart" data-productid="<?php echo $product['id']; ?>" data-quantity="1">Add to Cart</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php template('footer.php'); ?>
