<?php

namespace Source\App;

use League\Plates\Engine;

class Admin
{
    private $view;

    public function __construct()
    {
        $this->view = new Engine(__DIR__ . "/../../themes/adm","php");
    }

    public function home ()
    {
        //echo "<h1>Eu sou a Home...</h1>";
        echo $this->view->render("home",[]);
    }

    public function products (): void
    {
        echo $this->view->render("products",[]);
    }

    public function services (): void
    {
        echo $this->view->render("services",[]);
    }

     public function dash (): void
    {
        echo $this->view->render("dash",[]);
    }

}