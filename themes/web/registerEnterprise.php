<?php $this->layout("_theme"); ?>

<link rel="stylesheet" href="/mvc-project-tarde/themes/web/assets/css/registerEnterprise.css">

<body>

  <h1>Cadastro de Empresa</h1>

  <form>
    <label for="name">Nome da Empresa</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email Empresarial</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Senha</label>
    <input type="password" id="password" name="password" required>


    <label for="adress">Endereço da Empresa</label>
    <input type="text" id="adress" name="adress" required>

    <label for="cnpj">CNPJ</label>
    <input type="text" id="cnpj" name="cnpj" placeholder="00.000.000/0001-00" required>

    <label for="photo">Logo da Empresa</label>
    <input type="file" id="photo" name="photo" accept="image/*">

     <a href="<?= url("adm/home"); ?>"><button type="submit">Cadastrar Empresa</button></a>
  </form>

</body>