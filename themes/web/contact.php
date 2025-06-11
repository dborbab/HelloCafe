<?php
    echo $this->layout("_theme");
?>
<link rel="stylesheet" href="/mvc-project-tarde/themes/web/assets/css/contact.css">

<body>

  <h1>Fale conosco!</h1>

  <div class="contato-container">
    <div class="mapa">Mapa</div>

    <form action="/themes/Web/home/home.php">
      <input type="text" placeholder="Nome completo" required />
      <input type="email" placeholder="Email" required />
      <textarea placeholder="Mensagem" rows="4" required></textarea>
      <button type="submit">Enviar</button>
    </form>
  </div>

  </body>
