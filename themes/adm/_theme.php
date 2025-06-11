<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.: Área Administrativa :.</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= url("assets/css/admin/styles.css"); ?>">
    <?php if ($this->section("specific-script")): ?>
        <?= $this->section("specific-script"); ?>
    <?php endif; ?>
</head>
<body>
    <header>
        <h1>Logo da Empresa</h1>
    </header>
    <nav>
        <ul>
            <li><a href="<?= url("admin"); ?>" <i class="fas fa-home"></i> Home</a></li>
            <li><a href="<?= url("admin/services"); ?>"><i class="fas fa-concierge-bell"></i> Serviços</a></li>
            <li><a href="<?= url("admin/products"); ?>"><i class="fas fa-box"></i> Produtos</a></li>
            <li><a href="<?= url("admin/dash"); ?>"><i class="fas fa-box"></i> Dashboard</a></li>
            <li><a href="<?= url("admin/logout"); ?>"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
        </ul>
    </nav>
    <main>
        <?= $this->section('content') ?>
    </main>
</body>
</html>