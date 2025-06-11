<?php

namespace Source\App\Api;

use Source\Models\Product;

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
       
        if (!$data || in_array("", $data)) {
            $this->back([
                "type" => "error",
                "message" => "Preencha todos os campos"
            ]);
            return;
        }

        $product = new Product(
            null,
            $this->userAuth->id,
            $data["name"],
            $data["description"],
            (float)$data["price"],
            (int)$data["stock"],
            $data["image"],
            (int)$data["category_id"]
        );

        $insert = $product->insert();

        if (!$insert) {
            $this->back([
                "type" => "error",
                "message" => "Erro ao cadastrar produto"
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "message" => "Produto cadastrado com sucesso"
        ]);
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

        $product = new Product(
            (int)$data["id"],
            $this->userAuth->id,
            $data["name"],
            $data["description"],
            (float)$data["price"],
            (int)$data["stock"],
            $data["image"],
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
    }
}
