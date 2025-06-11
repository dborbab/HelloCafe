# Documentação da API - Web Service

Este Web Service fornece uma API RESTful para gestão de **usuários**, **produtos** e **serviços**. Todas as requisições devem ser feitas para a base URL:

```
http://localhost/mvc-project-tarde/api
```

> Todas as rotas (exceto login e cadastro) exigem autenticação via token JWT.

## Autenticação

### POST `/users/login`

* **Descrição**: Realiza o login do usuário e retorna um token JWT.
* **Body**:

```json
{
  "email": "usuario@email.com",
  "password": "123456"
}
```

* **Resposta** (200 OK):

```json
{
  "type": "success",
  "user": { ... },
  "token": "JWT_TOKEN"
}
```

---

## Produtos

### GET `/products/`

* **Descrição**: Lista todos os produtos.
* **Headers**: Authorization: Bearer {token}
* **Resposta** (200 OK):

```json
{
  "type": "success",
  "message": "Lista de produtos",
  "products": [ {...}, {...} ]
}
```

### GET `/products/{id}`

* **Descrição**: Retorna um produto pelo ID.
* **Resposta** (200 OK):

```json
{
  "type": "success",
  "product": { ... }
}
```

* **Resposta** (404):

```json
{
  "type": "error",
  "message": "Produto não encontrado"
}
```

### POST `/products/`

* **Descrição**: Cria um novo produto.
* **Body**:

```json
{
  "name": "Produto X",
  "description": "Detalhes do produto",
  "price": 59.99,
  "stock": 10,
  "image": "produto.jpg",
  "category_id": 1
}
```

* **Resposta** (201 Created):

```json
{
  "type": "success",
  "message": "Produto cadastrado com sucesso"
}
```

### PUT `/products/{id}`

* **Descrição**: Atualiza um produto existente.
* **Body**:

```json
{
  "id": 7,
  "name": "Produto Atualizado",
  "description": "Nova descrição",
  "price": 79.90,
  "stock": 20,
  "image": "nova-imagem.jpg",
  "category_id": 2
}
```

* **Resposta** (200 OK):

```json
{
  "type": "success",
  "message": "Produto atualizado com sucesso"
}
```

### DELETE `/products/{id}`

* **Descrição**: Deleta um produto pelo ID.
* **Resposta** (200 OK):

```json
{
  "type": "success",
  "message": "Produto deletado com sucesso"
}
```

---

## Códigos de status HTTP

| Código | Descrição                      |
| ------ | ------------------------------ |
| 200    | OK (requisição bem sucedida)   |
| 201    | Created (recurso criado)       |
| 400    | Bad Request (dados inválidos)  |
| 401    | Unauthorized (token inválido)  |
| 404    | Not Found (recurso não existe) |
| 500    | Internal Server Error          |

---

## GitHub

O projeto está versionado no GitHub. Se for um repositório privado, conceda acesso ao usuário `fabio3268`.

**Caminho do arquivo**: `api/README.md`
