<?php

ob_start();

require  __DIR__ . "/../vendor/autoload.php";

// os headers abaixo são necessários para permitir o acesso a API
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Access-Control-Allow-Credentials: true'); // Permitir credenciais

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

use CoffeeCode\Router\Router;

$route = new Router(url(),":");

$route->namespace("Source\App\Api");

/* USERS */

$route->group("/users");

$route->get("/", "Users:listUsers");
$route->post("/create","Users:createUser");
$route->get("/me","Users:getUser");
$route->post("/login","Users:loginUser");
$route->put("/update","Users:updateUser");
$route->post("/photo","Users:updatePhoto");
$route->get("/photo","Users:getPhoto");
$route->post("/set-password","Users:setPassword");
$route->get("/token-validate", "Users:tokenValidate");
$route->delete("/delete/{id}","Users:deleteUser");

$route->group("null");

/* PRODUTOS */

$route->group("/products");

$route->get("/", "Products:listProducts");           // método para listar todos
$route->get("/{id}", "Products:getById");           // método para buscar por ID
$route->post("/", "Products:createProduct");
$route->delete("/{id}", "Products:deleteById");               // inserir novo (sem insertFood na url)
$route->put("/{id}", "Products:updateProduct");            // atualizar produto (usando PUT e id)
     // deletar por id

$route->group(null);


/* SERVICES */

$route->group("/services");

$route->get("/service/{serviceId}","Services:getById");
$route->post("/service","Services:insert");
$route->delete("/service/{serviceId}","Services:delete");
$route->put("/service/{serviceId}/name/{name}/description/{description}","Services:update");
$route->get("/list-by-category/category/{categoryId}","Services:listByCategory");
//$route->get("/list-by-category/category/{categoryId}/bland/{blandId}","Services:listByCategory");

$route->group("null");

$route->group("/services-categories");
$route->post("/","ServicesCategories:insert");
$route->get("/","ServicesCategories:getCategory");
$route->put("/","ServicesCategories:update");
$route->delete("/","ServicesCategories:remove");
$route->group("null");


$route->dispatch();

/** ERROR REDIRECT */
if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "errors" => [
            "type " => "endpoint_not_found",
            "message" => "Não foi possível processar a requisição"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

ob_end_flush();
