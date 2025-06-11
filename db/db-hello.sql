-- Banco de dados: `hello_cafe`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Estrutura da tabela `categories`
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Inserindo categorias de produtos
INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Cafés'),
(2, 'Chás'),
(3, 'Doces'),
(4, 'Salgados'),
(5, 'Bebidas Frias'),
(6, 'Tortas');

-- Estrutura da tabela `products`
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `vendor_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL,
  `image` VARCHAR(255),
  `category_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_products_category_idx` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Inserindo produtos de exemplo
INSERT INTO `products` (`id`, `vendor_id`, `name`, `description`, `price`, `stock`, `image`, `category_id`) VALUES
(1, 1, 'Café Espresso', 'Café espresso intenso e encorpado.', 4.50, 100, 'espresso.jpg', 1),
(2, 1, 'Cappuccino', 'Café com leite vaporizado e espuma.', 6.00, 80, 'cappuccino.jpg', 1),
(3, 2, 'Bolo de Cenoura', 'Bolo de cenoura com cobertura de chocolate.', 8.00, 30, 'bolo_cenoura.jpg', 3),
(4, 2, 'Croissant', 'Croissant folhado e amanteigado.', 7.00, 50, 'croissant.jpg', 4),
(5, 1, 'Chá de Camomila', 'Chá calmante de camomila.', 5.00, 60, 'cha_camomila.jpg', 2);

-- Estrutura da tabela `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Inserindo usuários de exemplo
INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Ana Silva', 'ana.silva@hellocafe.com', '$2y$10$1234567890abcdef', '2025-01-01 08:00:00', NULL),
(2, 'Carlos Souza', 'carlos.souza@hellocafe.com', '$2y$10$abcdef1234567890', '2025-01-02 09:00:00', NULL);

-- Restrições de chave estrangeira
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

COMMIT;
