<?php

namespace Source\Models;

use PDOException;
use Source\Core\Connect;
use Source\Core\Model;

class Enterprise extends Model {
    private $id;
    private $name;
    private $email;
    private $password;
    private $address;
    private $photo;
    private $message;

    public function __construct(
        int $id = null,
        string $name = null,
        string $email = null,
        string $password = null,
        string $address = null,
        string $photo = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
        $this->photo = $photo;
        $this->entity = "enterprises";
    }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getName(): ?string { return $this->name; }
    public function setName(?string $name): void { $this->name = $name; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): void { $this->email = $email; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(?string $password): void { $this->password = $password; }

    public function getAddress(): ?string { return $this->address; }
    public function setAddress(?string $address): void { $this->address = $address; }

    public function getPhoto(): ?string { return $this->photo; }
    public function setPhoto(?string $photo): void { $this->photo = $photo; }

    public function getMessage(): ?string { return $this->message; }

    // public function insert(): ?int
    // {
    //     $conn = Connect::getInstance();

    //     if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
    //         $this->message = "E-mail inválido!";
    //         return false;
    //     }

    //     $query = "SELECT * FROM enterprises WHERE email = :email";
    //     $stmt = $conn->prepare($query);
    //     $stmt->bindParam(":email", $this->email);
    //     $stmt->execute();

    //     if ($stmt->rowCount() > 0) {
    //         $this->message = "E-mail já cadastrado!";
    //         return false;
    //     }

    //     $this->password = password_hash($this->password, PASSWORD_DEFAULT);

    //     $query = "INSERT INTO enterprises (name, email, password, address)
    //               VALUES (:name, :email, :password, :address)";

    //     $stmt = $conn->prepare($query);
    //     $stmt->bindParam(":name", $this->name);
    //     $stmt->bindParam(":email", $this->email);
    //     $stmt->bindParam(":password", $this->password);
    //     $stmt->bindParam(":address", $this->address);

    //     try {
    //         $stmt->execute();
    //         return $conn->lastInsertId();
    //     } catch (PDOException $exception) {
    //         $this->message = "Erro ao cadastrar: {$exception->getMessage()}";
    //         return false;
    //     }
    // }


        public function insert(): ?int
{
    $conn = Connect::getInstance();

    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        $this->message = "E-mail inválido!";
        return false;
    }

    $query = "SELECT * FROM enterprises WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $this->email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $this->message = "E-mail já cadastrado!";
        return false;
    }

    $this->password = password_hash($this->password, PASSWORD_DEFAULT);

    // LOG de inserção
    error_log("Inserindo empresa: {$this->name}, {$this->email}, {$this->address}");

    $query = "INSERT INTO enterprises (name, email, password, address) 
              VALUES (:name, :email, :password, :address)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":address", $this->address);

    try {
        $stmt->execute();
        return $conn->lastInsertId();
    } catch (PDOException $exception) {
        $this->message = "Erro ao cadastrar: {$exception->getMessage()}";
        error_log("Erro insert(): " . $this->message);
        return false;
    }
}



    public function login(string $email, string $password): bool
    {
        $query = "SELECT * FROM enterprises WHERE email = :email";
        $conn = Connect::getInstance();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            $this->message = "E-mail não cadastrado!";
            return false;
        }

        if (!password_verify($password, $result['password'])) {
            $this->message = "Senha incorreta!";
            return false;
        }

        $this->setId($result['id']);
        $this->setName($result['name']);
        $this->setEmail($result['email']);

        $this->message = "Empresa logada com sucesso!";
        return true;
    }

    public function update(): bool
    {
        $conn = Connect::getInstance();

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->message = "E-mail inválido!";
            return false;
        }

        $query = "SELECT * FROM enterprises WHERE email LIKE :email AND id != :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $this->message = "E-mail já cadastrado!";
            return false;
        }

        $query = "UPDATE enterprises 
                  SET name = :name, email = :email, address = :address
                  WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Empresa atualizada com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao atualizar: {$exception->getMessage()}";
            return false;
        }
    }

    public function updatePassword(string $password, string $newPassword, string $confirmNewPassword): bool
    {
        $query = "SELECT * FROM enterprises WHERE id = :id";
        $conn = Connect::getInstance();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            $this->message = "Empresa não encontrada!";
            return false;
        }

        if (!password_verify($password, $result['password'])) {
            $this->message = "Senha incorreta!";
            return false;
        }

        if ($newPassword !== $confirmNewPassword) {
            $this->message = "As senhas não conferem!";
            return false;
        }

        $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE enterprises SET password = :password WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":password", $newPasswordHashed);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Senha atualizada com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao atualizar: {$exception->getMessage()}";
            return false;
        }
    }

    public function updatePhoto(): bool
    {
        $query = "SELECT photo FROM enterprises WHERE id = :id";
        $stmt = Connect::getInstance()->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($result['photo'])) {
            @unlink(__DIR__ . "/../../{$result['photo']}");
        }

        $query = "UPDATE enterprises 
                  SET photo = :photo 
                  WHERE id = :id";

        $stmt = Connect::getInstance()->prepare($query);
        $stmt->bindParam(":photo", $this->photo);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Foto atualizada com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao atualizar: {$exception->getMessage()}";
            return false;
        }
    }

    public static function deleteById(int $id): bool
    {
        $conn = Connect::getInstance();

        try {
            $query = "DELETE FROM enterprises WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (\PDOException $exception) {
            return false;
        }
    }

    
}
