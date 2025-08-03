<?php

namespace Source\App;

use League\Plates\Engine;

class App
{
    private $view;

    public function __construct()
    {
        $this->view = new Engine(__DIR__ . "/../../themes/app","php");
    }

    public function profile ()
    {
        echo $this->view->render("profile",[]);
    }

    public function cart (array $data)
    {
        echo $this->view->render("cart", []);
    }

    public function messages ()
    {
        echo $this->view->render("messages", []);
    }
    public function orders ()
    {
        echo $this->view->render("orders", []);
    }

        public function faqs ()
    {
        echo $this->view->render("faqs", []);
    }

}  
