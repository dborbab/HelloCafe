<?php   echo $this->layout("_theme");?>

<link rel="stylesheet" href="<?= url("/mvc-project-tarde/themes/adm/assets/css/dash.css"); ?>">
<div class="container">
    <header class="dash-header">
        <h1>Dashboard</h1>
    </header>
    <main class="main-desh">
        <section class="stats">
            <div class="card">
                <h2>Total de Cafeterias</h2>
                <p id="totalCafeterias">0</p>
            </div>
            <div class="card">
                <h2>Total de Produtos</h2>
                <p id="totalProducts">0</p>
            </div>
            <div class="card">
                <h2>Novos Clientes</h2>
                <p id="newClients">0</p>
            </div>
        </section>
        <section class="charts">
            <div class="chart-container">
                <h2>Produtos por Categoria</h2>
                <canvas id="productsByCategoryChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Vendas Recentes</h2>
                <canvas id="recentSalesChart"></canvas>
            </div>
        </section>
        <section class="alerts">
            <h2>Alertas e Notificações</h2>
            <ul id="alertsList">
                <!-- Alertas serão inseridos aqui -->
            </ul>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= url("themes/adm/dash/dash.js") ?>"></script>
</div>
