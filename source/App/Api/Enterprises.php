<?php

namespace Source\App\Api;

use Source\Core\TokenJWT;
use Source\Models\Enterprise;
use Source\Support\ImageUploader;

class Enterprises extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getEnterprise()
    {
        $this->auth();

        $enterprise = (new Enterprise())->selectById($this->userAuth->id);

        $this->back([
            "type" => "success",
            "message" => "Empresa autenticada",
            "enterprise" => [
                "id" => $this->userAuth->id,
                "name" => $enterprise->name,
                "email" => $enterprise->email,
                "address" => $enterprise->address,
                "photo" => $enterprise->photo
            ]
        ]);
    }

    public function tokenValidate()
    {
        $this->auth();

        $this->back([
            "type" => "success",
            "message" => "Token válido",
            "enterprise" => [
                "id" => $this->userAuth->id,
                "name" => $this->userAuth->name,
                "email" => $this->userAuth->email
            ]
        ]);
    }

    public function listEnterprises()
    {
        $this->auth();

        $enterprises = (new Enterprise())->selectAll();

        $result = array_map(function ($e) {
            return [
                "id" => $e->id,
                "name" => $e->name,
                "email" => $e->email,
            ];
        }, $enterprises);

        $this->back([
            "type" => "success",
            "message" => "Lista de empresas",
            "enterprises" => $result
        ]);
    }


    public function createEnterprise(array $data): void
{
    header('Content-Type: application/json; charset=UTF-8');

    if (empty($data)) {
        $data = $_POST;
    }

    // DEBUG LOG
    error_log("POST recebido: " . json_encode($data));

    if (
        empty($data["name"]) ||
        empty($data["email"]) ||
        empty($data["password"]) ||
        empty($data["address"])
    ) {
        $this->back([
            "type" => "error",
            "message" => "Preencha todos os campos obrigatórios!"
        ]);
        return;
    }

    $enterprise = new Enterprise(
        null,
        $data["name"],
        $data["email"],
        $data["password"],
        $data["address"]
    );

    if (!$enterprise->insert()) {
        $this->back([
            "type" => "error",
            "message" => $enterprise->getMessage()
        ]);
        return;
    }

    $this->back([
        "type" => "success",
        "message" => "Empresa cadastrada com sucesso!"
    ], 201);
}



    public function updateEnterprise(array $data)
    {
        if (!$this->userAuth) {
            $this->back([
                "type" => "error",
                "message" => "Você não pode estar aqui.."
            ]);
            return;
        }

        $enterprise = new Enterprise(
            $this->userAuth->id,
            $data["name"],
            $data["email"],
            '',
            $data["address"]
        );

        if (!$enterprise->update()) {
            $this->back([
                "type" => "error",
                "message" => $enterprise->getMessage()
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "message" => $enterprise->getMessage(),
            "enterprise" => [
                "id" => $enterprise->getId(),
                "name" => $enterprise->getName(),
                "email" => $enterprise->getEmail()
            ]
        ]);
    }

    public function updatePhoto(array $data)
    {
        $imageUploader = new ImageUploader();
        $photo = (!empty($_FILES["photo"]["name"]) ? $_FILES["photo"] : null);

        $this->auth();

        if (!$photo) {
            $this->back([
                "type" => "error",
                "message" => "Por favor, envie uma foto do tipo JPG ou JPEG"
            ]);
            return;
        }

        $upload = $imageUploader->upload($photo);

        $enterprise = new Enterprise(
            id: $this->userAuth->id,
            photo: $upload
        );

        if (!$enterprise->updatePhoto()) {
            $this->back([
                "type" => "error",
                "message" => $enterprise->getMessage()
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "message" => $enterprise->getMessage(),
            "enterprise" => [
                "id" => $enterprise->getId(),
                "name" => $enterprise->getName(),
                "email" => $enterprise->getEmail(),
                "photo" => $enterprise->getPhoto()
            ]
        ]);
    }

    public function getPhoto(array $data)
    {
        $this->auth();

        $enterprise = new Enterprise();
        $photo = $enterprise->selectById($this->userAuth->id);

        $this->back([
            "type" => "success",
            "message" => "Foto da empresa",
            "photo" => $photo->photo
        ]);
    }

    public function setPassword(array $data)
    {
        if (!$this->userAuth) {
            $this->back([
                "type" => "error",
                "message" => "Você não pode estar aqui.."
            ]);
            return;
        }

        $enterprise = new Enterprise($this->userAuth->id);

        if (!$enterprise->updatePassword($data["password"], $data["newPassword"], $data["confirmNewPassword"])) {
            $this->back([
                "type" => "error",
                "message" => $enterprise->getMessage()
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "message" => $enterprise->getMessage()
        ]);
    }

    public function deleteEnterprise(array $data)
    {
        $this->auth();

        if (empty($data["id"])) {
            $this->back([
                "type" => "error",
                "message" => "ID da empresa não informado"
            ]);
            return;
        }

        $deleted = Enterprise::deleteById((int)$data["id"]);

        if (!$deleted) {
            $this->back([
                "type" => "error",
                "message" => "Empresa não pôde ser deletada"
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "message" => "Empresa deletada com sucesso"
        ]);
    }

    public function loginEnterprise(): void
    {
        $enterprise = new Enterprise();
        
        if ($enterprise->login($_POST['email'] ?? '', $_POST['password'] ?? '')) {
            echo json_encode([
                'type' => 'success',
                'message' => $enterprise->getMessage(),
                'user' => [
                    'id' => $enterprise->getId(),
                    'name' => $enterprise->getName(),
                    'email' => $enterprise->getEmail()
                ]
            ]);
        } else {
            echo json_encode([
                'type' => 'error',
                'message' => $enterprise->getMessage()
            ]);
        }
    }


}
