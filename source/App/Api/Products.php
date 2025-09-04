<?php

namespace Source\App\Api;

use Source\Models\Product;
use Source\Support\ImageUploader;

class Products extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listProducts()
    {
        $this->auth();

        $products = Product::selectAll();

        $productsArray = array_map(function ($product) {
            return [
                "id" => $product->getId(),
                "vendorId" => $product->vendorId,
                "name" => $product->name,
                "description" => $product->description,
                "price" => $product->price,
                "stock" => $product->stock,
                "image" => $product->image,
                "category_id" => $product->category_id
            ];
        }, $products);

        $this->back([
            "type" => "success",
            "message" => "Lista de produtos",
            "products" => $productsArray
        ]);
    }

    public function createProduct(array $data): void
    {
        $this->auth();

        if (!$data || !is_array($data) || empty($data)) {
            $this->back([
                "type" => "error",
                "message" => "Dados não fornecidos"
            ]);
            return;
        }

        $requiredFields = ['name', 'description', 'price', 'stock', 'category_id'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $this->back([
                    "type" => "error",
                    "message" => "Campo obrigatório faltando: {$field}"
                ]);
                return;
            }
        }

        try {
            $product = new Product(
                null,
                $this->userAuth->id,
                trim($data["name"]),
                trim($data["description"]),
                (float)$data["price"],
                (int)$data["stock"],
                "",
                (int)$data["category_id"]
            );

            $insert = $product->insert();

            if (!$insert) {
                $this->back([
                    "type" => "error",
                    "message" => "Erro ao cadastrar produto no banco de dados"
                ]);
                return;
            }

            $this->back([
                "type" => "success",
                "message" => "Produto cadastrado com sucesso",
                "product_id" => $insert
            ]);

        } catch (\Exception $e) {
            $this->back([
                "type" => "error",
                "message" => "Erro interno: " . $e->getMessage()
            ]);
        }
    }

    public function getById(array $data)
    {
        $this->auth();

        if (empty($data["id"])) {
            $this->back([
                "type" => "error",
                "message" => "ID do produto não informado"
            ]);
            return;
        }

        $product = Product::getById((int)$data["id"]);

        if (!$product) {
            $this->back([
                "type" => "error",
                "message" => "Produto não encontrado"
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "product" => [
                "id" => $product->getId(),
                "vendorId" => $product->vendorId,
                "name" => $product->name,
                "description" => $product->description,
                "price" => $product->price,
                "stock" => $product->stock,
                "image" => $product->image,
                "category_id" => $product->category_id
            ]
        ]);
    }

    public function deleteById(array $data)
    {
        $this->auth();

        if (empty($data["id"])) {
            $this->back([
                "type" => "error",
                "message" => "ID do produto não informado"
            ]);
            return;
        }

        $deleted = Product::delete((int)$data["id"]);

        if (!$deleted) {
            $this->back([
                "type" => "error",
                "message" => "Produto não pôde ser deletado"
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "message" => "Produto deletado com sucesso"
        ]);
    }

    public function updateProduct(array $data)
    {
        $this->auth();

        if (empty($data["id"])) {
            $this->back([
                "type" => "error",
                "message" => "ID do produto não informado"
            ]);
            return;
        }

        $requiredFields = ['name', 'description', 'price', 'stock', 'category_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '' || $data[$field] === null) {
                $this->back([
                    "type" => "error",
                    "message" => "Campo obrigatório faltando: {$field}"
                ]);
                return;
            }
        }

        try {
            $product = new Product(
                (int)$data["id"],
                $this->userAuth->id,
                trim($data["name"]),
                trim($data["description"]),
                (float)$data["price"],
                (int)$data["stock"],
                "",
                (int)$data["category_id"]
            );

            if (!$product->update()) {
                $this->back([
                    "type" => "error",
                    "message" => "Erro ao atualizar produto"
                ]);
                return;
            }

            $this->back([
                "type" => "success",
                "message" => "Produto atualizado com sucesso"
            ]);

        } catch (\Exception $e) {
            $this->back([
                "type" => "error",
                "message" => "Erro interno: " . $e->getMessage()
            ]);
        }
    }

    public function updatePhoto(array $data): void
    {
        $this->auth();

        if (empty($_FILES["photo"])) {
            $this->back([
                "type" => "error",
                "message" => "Nenhuma imagem enviada"
            ]);
            return;
        }

        try {
            $uploader = new ImageUploader();
            $uploaded = $uploader->upload($_FILES["photo"], "products");

            if (!$uploaded) {
                $this->back([
                    "type" => "error",
                    "message" => "Falha no upload da imagem"
                ]);
                return;
            }

            $productId = (int)($data["id"] ?? 0);
            if ($productId <= 0) {
                $this->back([
                    "type" => "error",
                    "message" => "ID do produto inválido"
                ]);
                return;
            }

            $product = Product::getById($productId);
            if (!$product) {
                $this->back([
                    "type" => "error",
                    "message" => "Produto não encontrado"
                ]);
                return;
            }

            $product->image = $uploaded;
            if (!$product->updatePhoto()) {
                $this->back([
                    "type" => "error",
                    "message" => "Não foi possível atualizar a imagem do produto"
                ]);
                return;
            }

            $this->back([
                "type" => "success",
                "message" => "Imagem do produto atualizada com sucesso",
                "image" => $uploaded
            ]);

        } catch (\Exception $e) {
            $this->back([
                "type" => "error",
                "message" => "Erro no upload: " . $e->getMessage()
            ]);
        }
    }
}

