<?php
    echo $this->layout("_theme");
?>

 <link rel="stylesheet" href="/mvc-project-tarde/themes/web/assets/css/profile.css">

<body>

  <h1>Perfil</h1>

  <div class="perfil-layout">
    <!-- Lado esquerdo - Dados do usuário -->
    <div class="dados-usuario">
      <div class="dados-bloco">
        <h3>Dados de Cadastro</h3>
        <p><span>Nome:</span> </p>
        <p><span>Email:</span> </p>
        <p><span>Telefone:</span> </p>
      </div>

      <div class="dados-bloco">
        <h3>Endereço</h3>
        <p><span>Rua:</span> </p>
        <p><span>Cidade:</span> </p>
        <p><span>Estado:</span> </p>
        <p><span>CEP:</span> </p>
      </div>

      <div class="dados-bloco">
        <h3>Cartão</h3>
        <p><span>Cartão:</span> </p>
        <p><span>Validade:</span> </p>
        <p><span>Nome no cartão:</span> </p>
      </div>
    </div>

    <!-- Lado direito - Foto e botão -->
    <div class="perfil-lado-direito">
      <div class="foto-perfil"> </div>
      <button class="btn-editar">Editar perfil</button>
    </div>
  </div>

</body>