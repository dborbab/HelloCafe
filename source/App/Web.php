<?php

namespace Source\App;

use League\Plates\Engine;
use Source\App\Api\Faqs;
use Source\Models\Faq\Question;

class Web
{
    private $view;

    public function __construct()
    {
        $this->view = new Engine(__DIR__ . "/../../themes/web","php");
    }

    public function home ()
    {
        //echo "<h1>Eu sou a Home...</h1>";
        echo $this->view->render("home",[]);
    }

    public function menu ()
    {
        //echo "<h1>Eu sou a Home...</h1>";
        echo $this->view->render("menu",[]);
    }

    public function contact ()
    {
        echo $this->view->render("contact",[]);
        //echo "<h1>Olá, eu sou o Contato...</h1>";
    }

    public function login ()
    {
        echo $this->view->render("login",[]);
    }

    public function cart(): void
    {
        echo $this->view->render("cart", []);
    }

       public function profile(): void
    {
        echo $this->view->render("profile", []);
    }

           public function registerEnterprise(): void
    {
        echo $this->view->render("registerEnterprise", []);
    }

   
           public function registerUser(): void
    {
        echo $this->view->render("registerUser", []);
    }


    /*public function error (array $data)
    {
        var_dump($data);
    }*/

}