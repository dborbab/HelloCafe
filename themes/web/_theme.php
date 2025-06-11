<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>..:: Meu Sistema ::..</title>
    <link rel="stylesheet" href="<?= url("assets/css/web/styles.css"); ?>">
    <script type="module" src="<?= url("assets/js/web/theme.js"); ?>" async></script>
<?php if ($this->section("specific-script")): ?>
    <?= $this->section("specific-script"); ?>
<?php endif; ?>
</head>
<body>
<nav id="navbar">
      <a href="<?= url("web/home"); ?>">Home</a>
      <a href="<?= url("web/menu"); ?>">Menu</a>
      <a href="<?= url("web/cart"); ?>">Carrinho</a>
      <a href="<?= url("web/contact"); ?>">Contato</a>
      <a href="<?= url("web/profile"); ?>">Perfil</a>
</nav>
<div class="content">
    <!-- Your content goes here -->
    <?php
        echo $this->section("content");
    ?>
</div>
</body>
</html>