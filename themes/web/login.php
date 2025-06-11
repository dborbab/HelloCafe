<?php
    echo $this->layout("_theme");
?>
<?php
  $this->start("specific-script");
?>
<script type="module" src="<?= url("assets/js/web/login.js"); ?>" async></script>
<?php
$this->end();
?>
<link rel="stylesheet" href="/mvc-project-tarde/themes/web/assets/css/login.css">
<body>
    <div class="container">
        <section class="forms" id="registro">
            <form id="loginForm">
                <h2>Faça seu login!</h2>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
                <button id="submit" type="submit">ENTRAR</button>
                <a href="">Esqueceu a senha?</a>
            </form>
        </section>
    </div>
</body>