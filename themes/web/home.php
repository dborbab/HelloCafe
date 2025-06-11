<?php
    echo $this->layout("_theme");
?>
<head><link rel="stylesheet" href="/mvc-project-tarde/themes/web/assets/css/home.css"></head>
 
<body>
     <section class="inicio">
        <div class="container">
            <div class="logo">
                <img src="/mvc-project-tarde/themes/web/assets/images/HelloLogo.png" alt="Hello! Café & Colab">
            </div>
            <a href="/mvc-project-tarde/themes/web/login.php">
            <a href="<?= url("web/login"); ?>"><button class="button">ENTRAR</button></a>
            </a>
        </div>
    </section>

    <section class="sobre" >
        <div class="imagens">
            <img src="/mvc-project-tarde/themes/web/assets/images/strawberryjuice.png" alt="Bebida 1" id="img1">
            <img src="/mvc-project-tarde/themes/web/assets/images/limonpie.png" alt="Torta" id="img2">
            <img src="/mvc-project-tarde/themes/web/assets/images/coffee1.png" alt="Café com chantilly" id="img3">
            <img src="/mvc-project-tarde/themes/web/assets/images/pie.png" alt="Sobremesa Hello" id="img4">
        </div>
        <div class="texto">
            <h2>Quem somos nós?</h2>
            <p>
                O sistema Hello Café é uma plataforma digital voltada para otimizar o atendimento da cafeteria Hello Café,
                oferecendo funcionalidades como cardápio online, pedidos via site, agendamento de retirada, personalização de
                bebidas e gerenciamento administrativo. Seu objetivo é modernizar o atendimento, reduzir filas, minimizar erros
                nos pedidos e aumentar a eficiência operacional da cafeteria.
            </p>
        </div>
    </section> 
    <div class="logo2">
            <img src="/mvc-project-tarde/themes/web/assets/images/HelloLogo.png" class="logo2" alt="">
    </div>
    <section class="cadastro-opcoes" id="register">
        
    <div class="box">
        <h2>Cadastro empresarial</h2>
        <p>Quer fazer parte da nossa rede de cafeterias?<br>
        Cadastre sua empresa para divulgar seus produtos, gerenciar pedidos e alcançar ainda mais clientes apaixonados por café!</p>
        <ul>
            <li>Crie seu perfil empresarial</li>
            <li>Adicione seus produtos e cardápio</li>
            <li>Gerencie pedidos e promoções</li>
            <li>Aumente sua visibilidade com a nossa plataforma</li>
        </ul>
        <p>Comece agora e traga mais aroma e sabor ao seu negócio!</p>
       <a href="<?= url("web/registerEnterprise"); ?>"> <button class="button">Cadastrar-se!</button></a>
    </div>

    <div class="box">
       
        <h2>Cadastro pessoal</h2>
        <p>Bem-vindo à experiência completa do café!<br>
        Cadastre-se como cliente para explorar cafeterias incríveis, montar seu pedido favorito e receber tudo com comodidade.</p>
        <ul>
            <li>Acesse cardápios exclusivos</li>
            <li>Monte seu pedido com praticidade</li>
            <li>Salve suas cafeterias preferidas</li>
            <li>Receba novidades e promoções</li>
        </ul>
        <p>Faça seu cadastro e descubra um novo jeito de curtir café!</p><br>
        <a href="<?= url("web/registerUser"); ?>"> <button class="button">Cadastrar-se!</button></a>
    </div>
</section>


</body>