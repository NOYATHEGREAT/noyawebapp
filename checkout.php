<?php
require 'vendor/autoload.php';
session_start();

use Aries\MiniFrameworkStore\Models\Product;

header('Content-Type: application/json');

try {
    if (!isset($_POST['productId']) || empty($_POST['productId'])) {
        throw new Exception('Product ID is missing');
    }

    $product_id = intval($_POST['productId']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($quantity < 1) {
        $quantity = 1;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $product = new Product();
    $productDetails = $product->getById($product_id);

    if (!$productDetails) {
        throw new Exception('Product not found');
    }

    // If product already in cart, increase quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        $_SESSION['cart'][$product_id]['total'] = $_SESSION['cart'][$product_id]['price'] * $_SESSION['cart'][$product_id]['quantity'];
    } else {
        $_SESSION['cart'][$product_id] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'name' => $productDetails['name'],
            'price' => $productDetails['price'],
            'image_path' => $productDetails['image_path'],
            'total' => $productDetails['price'] * $quantity,
        ];
    }

    echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
