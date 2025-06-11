<?php

echo $this->layout("_theme"); ?>

<link rel="stylesheet" href="/mvc-project-tarde/themes/adm/assets/css/products.css">

  <div class="form-container">
    <h2>Cadastrar Produto</h2>
    <form id="form-produto">
      <label for="nome">Nome do Produto</label>
      <input type="text" id="nome" name="nome" required />

      <label for="descricao">Descrição</label>
      <textarea id="descricao" name="descricao" required></textarea>

      <label for="preco">Preço (R$)</label>
      <input type="number" id="preco" name="preco" step="0.01" min="0" required />

      <label for="quantidade">Quantidade</label>
      <input type="number" id="quantidade" name="quantidade" min="0" required />

      <button type="submit">Cadastrar</button>
    </form>

    <div id="lista-produtos"></div>
  </div>