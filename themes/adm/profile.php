<?php
echo $this->layout("_theme");
?>
<?php
$this->start("specific-script");
?>
<script type="module" src="<?= url("assets/js/admin/profile.js"); ?>"></script>
<?php
$this->end();
?>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f9df8a 0%, #3b3b00 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .profile-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            padding: 60px;
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #8b6914, #6b4e3d, #d4a574);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .cafe-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #8b6914, #6b4e3d);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .title {
            color: #6b4e3d;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #8b6914;
            font-size: 16px;
            opacity: 0.8;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #6b4e3d;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #8b6914;
            box-shadow: 0 0 0 3px rgba(139, 105, 20, 0.1);
            transform: translateY(-2px);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .profile-image-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #8b6914;
            object-fit: cover;
            margin-bottom: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .image-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px dashed #8b6914;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            background: #f9f9f9;
            color: #8b6914;
            font-size: 14px;
            text-align: center;
        }

        .update-btn {
            background: linear-gradient(135deg, #8b6914, #6b4e3d);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(139, 105, 20, 0.3);
        }

        .update-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(139, 105, 20, 0.4);
        }

        .update-btn:active {
            transform: translateY(-1px);
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
            display: none;
        }

        .row {
            display: flex;
            gap: 15px;
        }

        .row .form-group {
            flex: 1;
        }

        @media (max-width: 480px) {
            .profile-container {
                padding: 25px;
            }
            
            .row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>

</head>
<body>
    <div class="profile-container">
        <div class="header">
            <div class="cafe-logo">HC</div>
            <h1 class="title">Perfil Admin</h1>
            <p class="subtitle">Hello Café - Gerenciamento</p>
        </div>

        <div class="success-message" id="successMessage">
            ✅ Perfil atualizado com sucesso!
        </div>

        <form id="profileForm">
            <div class="profile-image-section">
                <div class="image-placeholder" id="imagePreview">
                    Clique para<br>adicionar foto
                </div>
                <input type="file" id="profileImageInput" accept="image/*" style="display: none;">
            </div>


            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" value="" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="phone">Telefone</label>
                    <input type="tel" id="phone" value="">
                </div>
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" id="name" value="Nome da Empresa">
                </div>
            </div>

            <div class="form-group">
                <label for="address">Endereço</label>
                <input type="text" id="address" value="">
            </div>

            <div class="form-group">
                <label for="bio">Sobre mim</label>
                <textarea id="bio" placeholder="Conte um pouco sobre sua experiência na gestão da cafeteria...">Administradora experiente com mais de 5 anos no ramo alimentício. Apaixonada por café e dedicada a oferecer a melhor experiência aos nossos clientes do Hello Café.</textarea>
            </div>

            <button type="submit" class="update-btn">
                ✏️ Atualizar Perfil
            </button>
        </form>
    </div>
    <div id="toast-container"></div>

    <script>
    

        // Adicionar animações aos inputs
        document.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
