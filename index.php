<?php

require __DIR__ . "/vendor/autoload.php";

use CoffeeCode\Router\Router;

ob_start();

$route = new Router(url(), ":");

$route->namespace("Source\App");
// Rotas amigáveis da área pública
$route->get("/web/home", "Web:home");
$route->get("/web/menu", "Web:menu");
$route->get("/web/contact", "Web:contact");
$route->get("/web/cart","Web:cart");
$route->get("/web/login","Web:login");
$route->get("/web/profile","Web:profile");
$route->get("/web/registerUser","Web:registerUser");
$route->get("/web/registerEnterprise","Web:registerEnterprise");

// Rotas amigáveis da área restrita
$route->group("/app");

$route->get("/app/home", "App:home");
$route->get("/app/profile", "App:profile");
$route->get("/app/cart", "App:cart");

$route->group(null);

$route->group("/admin");

$route->get("/adm/home", "Admin:home");
$route->get("/services", "Admin:services");
$route->get("/products", "Admin:products");
$route->get("/dash", "Admin:dash");

$route->group(null);

$route->get("/ops/{errcode}", "Web:error");

$route->group(null);

$route->dispatch();

if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();