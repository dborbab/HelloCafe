<?php   echo $this->layout("_theme");?>

<script type="module" src="<?= url("assets/js/admin/products.js"); ?>"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f9df8a 0%, #a1a112 100%);
            min-height: 100vh;
            padding: 20px;
            line-height: 1.6;
        }

        .page-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: sticky;
            top: 20px;
            transition: all 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .products-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-height: 80vh;
            overflow-y: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 2rem;
            position: relative;
            padding-bottom: 15px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus {
            outline: none;
            border-color: #8B4513;
            box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
            transform: translateY(-2px);
        }

        textarea {
            height: 100px;
            resize: vertical;
            font-family: inherit;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8B4513, #D2691E);
            border: none;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(139, 69, 19, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(-1px);
        }

        /* Lista de produtos */
        #lista-produtos {
            margin-top: 0;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .produto-item {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(248, 249, 250, 0.9));
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 4px solid #8B4513;
            position: relative;
            overflow: hidden;
        }

        .produto-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }

        .produto-item:hover::before {
            left: 100%;
        }

        .produto-item:hover {
            transform: translateX(10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border-left-color: #D2691E;
        }

        .produto-info {
            flex: 1;
            margin-right: 20px;
        }

        .produto-info strong {
            display: block;
            margin-bottom: 10px;
            font-size: 1.3rem;
            color: #333;
            font-weight: 700;
        }

        .produto-info p {
            margin-bottom: 8px;
            color: #666;
            line-height: 1.5;
        }

        .produto-info p:last-child {
            color: #8B4513;
            font-weight: 600;
            font-size: 1rem;
        }

        .btn-excluir {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 100px;
        }

        .btn-excluir:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(231, 76, 60, 0.4);
        }

        .btn-excluir:active {
            transform: translateY(0);
        }

        /* Header com estatísticas */
        .stats-header {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }

        .stat-card {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #8B4513, #D2691E);
            color: white;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Animações */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .produto-item {
            animation: slideInUp 0.5s ease;
        }

        /* Scrollbar personalizada */
        .products-container::-webkit-scrollbar {
            width: 8px;
        }

        .products-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .products-container::-webkit-scrollbar-thumb {
            background: rgba(139, 69, 19, 0.3);
            border-radius: 4px;
        }

        .products-container::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 69, 19, 0.5);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .page-container {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 0 10px;
            }

            .form-container,
            .products-container {
                padding: 25px;
            }

            .produto-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .produto-info {
                margin-right: 0;
            }

            .btn-excluir {
                align-self: flex-end;
            }

            .stats-header {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 20px;
            }
        }

        /* Notification toast */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transform: translateX(400px);
            transition: transform 0.3s ease;
            z-index: 1000;
            font-weight: 600;
        }

        .toast.show {
            transform: translateX(0);
        }

        /* Loading states */
        .form-container.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .form-container.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 30px;
            height: 30px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #8B4513;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
</head>
<body>
<div class="stats-header">
    <div class="stat-card">
        <span class="stat-number" id="total-products">0</span>
        <div class="stat-label">Produtos Cadastrados</div>
    </div>
    <div class="stat-card">
        <span class="stat-number" id="total-value">R$ 0,00</span>
        <div class="stat-label">Valor Total</div>
    </div>
    <div class="stat-card">
        <span class="stat-number" id="total-quantity">0</span>
        <div class="stat-label">Quantidade Total</div>
    </div>
</div>

<div class="page-container">
    <div class="form-container">
        <h2 id="form-title">☕ Cadastrar Produto</h2>
        <form id="form-produto" enctype="multipart/form-data">
            <input type="hidden" id="produto-id" name="id">

            <div class="form-group">
                <label for="nome">Nome do Produto</label>
                <input type="text" id="nome" name="name" required placeholder="Ex: Café Expresso" />
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="description" required placeholder="Descreva o produto..."></textarea>
            </div>

            <div class="form-group">
                <label for="preco">Preço (R$)</label>
                <input type="number" id="preco" name="price" step="0.01" min="0" required placeholder="0.00" />
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade em Estoque</label>
                <input type="number" id="quantidade" name="stock" min="0" required placeholder="0" />
            </div>

            <div class="form-group">
                <label for="imagem">Imagem do Produto</label>
                <input type="file" id="imagem" name="photo" accept="image/*" />
            </div>

            <div class="form-group">
                <label for="categoria">Categoria</label>
                <input type="number" id="categoria" name="category_id" min="1" required placeholder="ID da categoria" />
            </div>

            <button type="submit" id="btn-submit">
                <span id="btn-text">✨ Cadastrar Produto</span>
                <span id="btn-loading" class="loading" style="display: none;"></span>
            </button>

            <button type="button" id="btn-cancel" style="display: none; background: #6c757d; margin-top: 10px;">
                Cancelar Edição
            </button>
        </form>
    </div>

    <div class="products-container">
        <h2>📦 Produtos Cadastrados</h2>
        <div id="lista-produtos">
            <div class="empty-state">
                <div class="empty-state-icon">🛍️</div>
                <p>Nenhum produto cadastrado ainda.<br>Use o formulário ao lado para adicionar produtos!</p>
            </div>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div id="toast-container" class="toast"></div>
