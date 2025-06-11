<?php

namespace Source\Models;

use Source\Core\Connect;

class Product {
    public int $id;
    public int $vendorId;
    public string $name;
    public string $description;
    public float $price;
    public int $stock;
    public string $image;
    public int $category_id;

    public function __construct(
        ?int $id,
        int $vendorId,
        string $name,
        string $description,
        float $price,
        int $stock,
        string $image,
        int $category_id
    ) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        $this->vendorId = $vendorId;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->image = $image;
        $this->category_id = $category_id;
    }

    public function getId(): ?int {
        return $this->id ?? null;
    }

    public function insert(): ?int {
        $conn = Connect::getInstance();

        $query = "INSERT INTO products (vendor_id, name, description, price, stock, image, category_id) 
                  VALUES (:vendor_id, :name, :description, :price, :stock, :image, :category_id)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":vendor_id", $this->vendorId);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":category_id", $this->category_id);

        try {
            $stmt->execute();
            $this->id = $conn->lastInsertId();
            return $this->id;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function update(): bool {
        if (empty($this->id)) {
            return false;
        }

        $conn = Connect::getInstance();

        $query = "UPDATE products SET 
                    vendor_id = :vendor_id, 
                    name = :name, 
                    description = :description, 
                    price = :price, 
                    stock = :stock, 
                    image = :image,
                    category_id = :category_id
                  WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":vendor_id", $this->vendorId);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":category_id", $this->category_id);
        

        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public static function delete(int $id): bool {
        $conn = Connect::getInstance();

        try {
            $query = "DELETE FROM products WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public static function getById(int $id): ?self {
        $conn = Connect::getInstance();

        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return new self(
                (int)$data['id'],
                (int)$data['vendor_id'],
                $data['name'],
                $data['description'],
                (float)$data['price'],
                (int)$data['stock'],
                $data['image'],
                (int)$data['category_id']
            );
        }

        return null;
    }

    public static function selectAll(): array {
        $conn = Connect::getInstance();

        $query = "SELECT * FROM products";
        $stmt = $conn->query($query);

        $products = [];

        while ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = new self(
                (int)$data['id'],
                (int)$data['vendor_id'],
                $data['name'],
                $data['description'],
                (float)$data['price'],
                (int)$data['stock'],
                $data['image'],
                (int)$data['category_id']
            );
        }

        return $products;
    }
}
