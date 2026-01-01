-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/11/2025 às 05:48
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `du_hype_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque_tamanhos`
--

CREATE TABLE `estoque_tamanhos` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `tamanho` varchar(10) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque_tamanhos`
--

INSERT INTO `estoque_tamanhos` (`id`, `produto_id`, `tamanho`, `quantidade`, `preco`) VALUES
(1, 1, '39', 5, 750.99),
(2, 1, '40', 3, 750.99),
(3, 1, '41', 2, 750.99),
(4, 2, '38', 10, 350.99),
(5, 2, '39', 5, 350.99),
(6, 3, '40', 4, 599.99),
(7, 3, '41', 4, 599.99),
(8, 4, '37', 6, 699.99),
(9, 4, '38', 6, 699.99),
(10, 5, '39', 7, 597.90),
(11, 6, '38', 5, 650.90),
(12, 6, '39', 5, 650.90),
(13, 7, '39', 10, 490.99),
(14, 7, '40', 10, 490.99),
(15, 8, '36', 1, 799.99),
(16, 8, '37', 4, 799.99),
(17, 9, '40', 15, 699.99),
(18, 9, '41', 10, 699.99),
(22, 13, '38', 15, 29000.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `valor_total` decimal(10,2) NOT NULL,
  `status_pagamento` varchar(50) DEFAULT 'Pendente',
  `metodo_pagamento` varchar(50) DEFAULT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_itens`
--

CREATE TABLE `pedido_itens` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `tamanho` varchar(10) DEFAULT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `imagem` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `categoria`, `marca`, `imagem`) VALUES
(1, 'Tênis LaFrancé Moment Unissex', 'Apresentamos o tênis de basquete LaFracé Moment, que traz o estilo \'Not From Here\' do Melo para nossa nova silhueta fora da quadra. Inspirado nas linguagens do amor, o esquema de cores em dois tons representa as diferentes emoções e vibrações de Melo. Esse drop mostra o frescor de Melo, mostrandoque ele está sempre pronto, dentro ou fora da quadra!', 'basquete', 'PUMA', '/src/assets/tenis/Tenis_LaFrance_moment_Unissex1.jpg'),
(2, 'Tênis Nike Court Vision Low', 'Adora o look clássico do basquete dos anos 80, mas tem uma queda pela cultura de ritmo acelerado dos jogos atuais? Conheça o Court Vision Low. Ele mantém a alma do original com material sintético impecável e camadas costuradas, enquanto o colarinho macio o mantém elegante e confortável para o seu mundo.', 'sneakers', 'NIKE', '/src/assets/tenis/TenisNike_CourtVIsion_low1.jpg'),
(3, 'Tênis Adidas Initiation', 'O tênis adidas Initiation foi criado para ajudar você a encontrar sua fluidez na quadra. A entressola Dreamstrike+ proporciona maior conforto em cada passada e corte, enquanto o forro têxtil mantém seus pés confortáveis até o apito final. O solado de borracha oferece a tração que você precisa para fazer seus melhores movimentos.', 'basquete', 'ADIDAS', '/src/assets/tenis/Tenis_adidas_Initiation_Azul_1.jpg'),
(4, 'Tênis Campus 00s Beta', 'O tênis Campus 00s Beta acompanha você aonde for. O suede premium envolve o cabedal, acentuado por detalhes perfurados e língua em malha elástica para maior fluxo de ar. Inspirado nos arquivos adidas do fim dos anos 90c este tênis de lifestule tem uma vibe old-school que está de volta.', 'sneakers', 'ADIDAS', '/src/assets/tenis/Tenis_Campus_00s_Beta_Marrom_1.jpg'),
(5, 'Tênis Tekkira Cup', 'Uma nova silhueta para skate que combina a estética de corrida do final dos anos 90 com o estilo inspirado nas quadras do início dos anos, o Tekkira Cup combina o conforto do cupsole com o desempenho.', 'sneakers', 'ADIDAS', '/src/assets/tenis/Tekkira_Cup_Branco_1.jpg'),
(6, 'Tênis Campus 00s Preto', 'O tênis Campus pode ter nascido para as quadras, mas seu maior impacto já foi sentido longe delas. Este par não é execeção. Detalhes icônicos, como as Três Listras serrilhadas e o cupsole de borracha, são complementados por um cabedal têxtil de malha complexa.', 'sneakers', 'ADIDAS', '/src/assets/tenis/Tenis_Campus_00s_Preto_1.jpg'),
(7, 'Air Max 90', 'Nada tão legal, nada tão confortável, nada tão comprovado. O Nike Air Max 90 permanece fiel às suas raízes de corrida do OG, com a emblemática sola Waffle, sobreposições costuradas e detalhes clássicos em TPU.', 'corrida', 'NIKE', '/src/assets/tenis/AirMax90_1.jpg'),
(8, 'Nike Dunk Low', 'Você sempre pode contar com um clássico. O Dunk Low Retro combina um visual monocromático com materiais premium e estofamento de pelúcia para um conforto revolucionário que dura.', 'sneakers', 'NIKE', '/src/assets/tenis/DunkLow_1.jpg'),
(9, 'Tênis Nike Air Force 1', 'Confortável, durável e atemporal: não é à toa que ele é o número 1. A construção clássica dos anos 80 combina com detalhes arrojados para um estilo que acompanha você na quadra ou em qualquer lugar', 'sneakers', 'NIKE', '/src/assets/tenis/Tenis_airForce_1.jpg'),
(11, 'Air jodan', 'tenis confortável e barato e do negão ', 'basquete', 'Nike', ''),
(13, 'P-6000', 'KKKK', 'sneakers', 'Nike', '/src/assets/tenis/tenis_690d74a287bf0_1.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha_hash`, `is_admin`, `criado_em`) VALUES
(1, 'Administrador', 'admin@duhype.com', '$2y$10$f.o.LhN.S.4.W/Nl8X.8XuC.d6.s2E.01zH0l2GjX.4k6J0Cj5H/m', 1, '2025-11-04 22:31:50');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `estoque_tamanhos`
--
ALTER TABLE `estoque_tamanhos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `estoque_tamanhos`
--
ALTER TABLE `estoque_tamanhos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido_itens`
--
ALTER TABLE `pedido_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `estoque_tamanhos`
--
ALTER TABLE `estoque_tamanhos`
  ADD CONSTRAINT `estoque_tamanhos_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `pedido_itens`
--
ALTER TABLE `pedido_itens`
  ADD CONSTRAINT `pedido_itens_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedido_itens_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
