<?php

namespace Source\App\Api;

use Source\Core\TokenJWT;
use Source\Models\User;
use Source\Support\ImageUploader;

class Users extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUser ()
    {
        $this->auth();

        $users = new User();
        $user = $users->selectById($this->userAuth->id);

        $this->back([
            "type" => "success",
            "message" => "Usuário autenticado",
            "user" => [
                "id" => $this->userAuth->id,
                "name" => $user->name,
                "email" => $user->email,
                "address" => $user->address,
                "photo" => $user->photo
            ]
        ]);

    }

    public function tokenValidate ()
    {
        $this->auth();

        $this->back([
            "type" => "success",
            "message" => "Token válido",
            "user" => [
                "id" => $this->userAuth->id,
                "name" => $this->userAuth->name,
                "email" => $this->userAuth->email
            ]
        ]);
    }

 public function listUsers()
{
    $this->auth();

    $usersModel = new User();
    $users = $usersModel->selectAll();

    $usersArray = array_map(function ($user) {
        return [
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
        ];
    }, $users);

    $this->back([
        "type" => "success",
        "message" => "Lista de usuários",
        "users" => $usersArray
    ]);
}


// public function createUser(array $data): void
// {
//     header('Content-Type: application/json; charset=UTF-8');



//     if (!$data) {
//         http_response_code(400);
//         $this->back([
//             "type" => "error",
//             "message" => "JSON inválido ou vazio"
//         ]);
//         return;
//     }

//     if (in_array("", $data)) {
//         $this->back([
//             "type" => "error",
//             "message" => "Preencha todos os campos"
//         ]);
//         return;
//     }


//     $user = new User(
//         null,
//         $data["name"],
//         $data["email"],
//         $data["password"]
//     );

//     $insertUser = $user->insert();

//     if (!$insertUser) {
//         $this->back([
//             "type" => "error",
//             "message" => $user->getMessage()
//         ]);
//         return;
//     }

//     $this->back([
//         "type" => "success",
//         "message" => "Usuário cadastrado com sucesso!"
//     ]);
// }

public function createUser(array $data): void
{
    header('Content-Type: application/json; charset=UTF-8');

    // Se vier vazio, assume $_POST
    if (empty($data)) {
        $data = $_POST;
    }

    if (
        empty($data["name"]) ||
        empty($data["email"]) ||
        empty($data["password"]) ||
        empty($data["adress"])
    ) {
        $this->back([
            "type" => "error",
            "message" => "Preencha todos os campos obrigatórios!"
        ]);
        return;
    }

    // Inserir usuário
    $user = new User(
        null,
        $data["name"],
        $data["email"],
        $data["password"],
        $data["adress"],
    );

    if (!$user->insert()) {
        $this->back([
            "type" => "error",
            "message" => $user->getMessage()
        ]);
        return;
    }

    $this->back([
        "type" => "success",
        "message" => "Usuário cadastrado com sucesso!"
    ], 201);
}



   public function loginUser (array $data) {
       

    if(empty($data["email"]) || empty($data["password"])) {
        $this->back([
            "type" => "error",
            "message" => "Email e senha são obrigatórios!"
        ]);
        return;
    }

    $user = new User();

    if(!$user->login($data["email"], $data["password"])){
        $this->back([
            "type" => "error",
            "message" => $user->getMessage()
        ]);
        return;
    }

    $token = new TokenJWT();

    $this->back([
        "type" => "success",
        "message" => $user->getMessage(),
        "user" => [
            "token" => $token->create([
                "id" => $user->getId(),
                "name" => $user->getName(),
                "email" => $user->getEmail()
            ])
        ]
    ]);
}


    public function updateUser(array $data)
    {

        if(!$this->userAuth){
            $this->back([
                "type" => "error",
                "message" => "Você não pode estar aqui.."
            ]);
            return;
        }

        $user = new User(
            $this->userAuth->id,
            $data["name"],
            $data["email"],
            '',
            $data["address"]
        );

        if(!$user->update()){
            $this->back([
                "type" => "error",
                "message" => $user->getMessage()
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "message" => $user->getMessage(),
            "user" => [
                "id" => $user->getId(),
                "name" => $user->getName(),
                "email" => $user->getEmail()
            ]
        ]);

    }

    // public function updatePhoto(array $data)
    // {

    //     $imageUploader = new ImageUploader();
    //     $photo = (!empty($_FILES["photo"]["name"]) ? $_FILES["photo"] : null);

    //     $this->auth();

    //     if (!$photo) {
    //         $this->back([
    //             "type" => "error",
    //             "message" => "Por favor, envie uma foto do tipo JPG ou JPEG"
    //         ]);
    //         return;
    //     }

    //     $upload = $imageUploader->upload($photo);

    //     $user = new User(
    //         id: $this->userAuth->id,
    //         photo: $upload
    //     );

    //     if (!$user->updatePhoto()) {
    //         $this->back([
    //             "type" => "error",
    //             "message" => $user->getMessage()
    //         ]);
    //         return;
    //     }

    //     $this->back([
    //         "type" => "success",
    //         "message" => $user->getMessage(),
    //         "user" => [
    //             "id" => $user->getId(),
    //             "name" => $user->getName(),
    //             "email" => $user->getEmail(),
    //             "photo" => $user->getPhoto()
    //         ]
    //     ]);

    // }

    public function getPhoto (array $data)
    {
        $this->auth();

        $user = new User();
        $userPhoto = $user->selectById($this->userAuth->id);

        $this->back([
            "type" => "success",
            "message" => "Foto do usuário",
            "photo" => $userPhoto->photo
        ]);
    }

    public function setPassword(array $data)
    {
        if(!$this->userAuth){
            $this->back([
                "type" => "error",
                "message" => "Você não pode estar aqui.."
            ]);
            return;
        }

        $user = new User($this->userAuth->id);

        if(!$user->updatePassword($data["password"],$data["newPassword"],$data["confirmNewPassword"])){
            $this->back([
                "type" => "error",
                "message" => $user->getMessage()
            ]);
            return;
        }

        $this->back([
            "type" => "success",
            "message" => $user->getMessage()
        ]);
    }

    public function deleteUser(array $data)
{
    $this->auth();

    if (empty($data["id"])) {
        $this->back([
            "type" => "error",
            "message" => "ID do usuário não informado"
        ]);
        return;
    }

    $deleted = User::deleteById((int)$data["id"]);

    if (!$deleted) {
        $this->back([
            "type" => "error",
            "message" => "Usuário não pôde ser deletado"
        ]);
        return;
    }

    $this->back([
        "type" => "success",
        "message" => "Usuário deletado com sucesso"
    ]);
}


}
