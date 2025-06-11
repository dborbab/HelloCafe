<?php $this->layout("_theme"); ?>

<link rel="stylesheet" href="/mvc-project-tarde/themes/web/assets/css/registerUser.css">

<body>

  <h1>Cadastro</h1>

  <form id="registerForm" enctype="multipart/form-data">
    <label for="name">Nome</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Senha</label>
    <input type="password" id="password" name="password" required>

    <label for="adress">Endereço</label>
    <input type="text" id="adress" name="adress" required>

    <label for="photo">Foto de perfil</label>
    <input type="file" id="photo" name="photo" accept="image/*">

    <button type="submit">Cadastrar</button>
  </form>
</body>