<?php

namespace Aries\MiniFrameworkStore\Models;

use Aries\MiniFrameworkStore\Includes\Database;

class Product extends Database
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->getConnection();
    }

    public function insert($data)
    {
        $sql = "INSERT INTO products (name, description, price, slug, image_path, category_id, created_at, updated_at)
                VALUES (:name, :description, :price, :slug, :image_path, :category_id, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function update($data)
    {
        $sql = "UPDATE products SET name = :name, description = :description, price = :price, image_path = :image_path WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return true;
    }

    public function getAll()
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByName($name)
    {
        $sql = "SELECT * FROM products WHERE name = :name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['name' => $name]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByPrice($price)
    {
        $sql = "SELECT * FROM products WHERE price = :price";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['price' => $price]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getByCategory($categoryName)
{
    $sql = "SELECT products.id, products.name, products.price, products.description, products.image_path
            FROM products
            JOIN product_categories ON products.category_id = product_categories.id
            WHERE product_categories.name = :category_name";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['category_name' => $categoryName]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

}
