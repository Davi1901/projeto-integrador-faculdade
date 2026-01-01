<?php require_once 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DU.HYPE</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/assets/styles/styles.css">
</head>
<body>
    <div class="header-container">
        <header class="header">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>/home.php">
                    <img src="<?php echo BASE_URL; ?>/src/assets/Images/LogoDu.Hype.svg" alt="Logo duhype">
                </a>
            </div>
            <nav class="nav">
                <a href="<?php echo BASE_URL; ?>/home.php">Início</a>
                <a href="<?php echo BASE_URL; ?>/src/pages/marcas/marcas.php">Marcas</a>
                <a href="<?php echo BASE_URL; ?>/src/pages/fedd/feed.php">Feed</a>
                <a href="<?php echo BASE_URL; ?>/src/pages/novidades/novidades.php">Novidades</a>
            </nav>
            <div class="icons">
                <a href="<?php echo BASE_URL; ?>/src/pages/fedd/feed.php"><i class="fas fa-search"></i></a>
                <a href="<?php echo BASE_URL; ?>/src/pages/carrinho/carrinho.php"><i class="fas fa-shopping-cart"></i></a>
                <a href="<?php echo BASE_URL; ?>/src/pages/cadastro/cadastro.php"><i class="fas fa-user"></i></a>
            </div>
        </header>
    </div>

    <main>
        <section class="hero-section">
            <div id="heroCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
                <div class="carousel-inner">
                    <div class="carousel-item active"><img class="d-block w-100" src="<?php echo BASE_URL; ?>/src/assets/Images/Carrossel AirForce.png" alt="Primeiro Slide"></div>
                    <div class="carousel-item"><img class="d-block w-100" src="<?php echo BASE_URL; ?>/src/assets/Images/carrossel1.png" alt="Segundo Slide"></div>
                    <div class="carousel-item"><img class="d-block w-100" src="<?php echo BASE_URL; ?>/src/assets/Images/carrossel3.png" alt="Terceiro Slide"></div>
                </div>
            </div>
            <div class="hero-content">
                <p class="hero-subtitle">DESCONTO DE 20% NA PRIMEIRA COMPRA</p>
                <h2 class="hero-title">Nike Air Force 1</h2>
                <p class="hero-description">O clássico que nunca sai de moda. Estilo urbano, conforto absoluto e atitude em cada passo.</p>
                <div class="hero-buttons">
                    <a href="<?php echo BASE_URL; ?>/src/pages/carrinho/carrinho.php"><button class="btn btn-primary">Comprar agora</button></a>
                    <a href="<?php echo BASE_URL; ?>/src/pages/fedd/feed.php"><button class="btn btn-secondary">Saber mais</button></a>
                </div>
            </div>
             <div class="carousel-indicators-custom">
                <div class="indicator-dot"></div><div class="indicator-dot"></div><div class="indicator-dot active"></div><div class="indicator-dot"></div><div class="indicator-dot"></div>
            </div>
        </section>

        <section class="product-showcase">
            <h2 class="section-title">Mais Vistos</h2>
            <div class="product-grid" id="mais-vistos-grid">
            </div>
        </section>

        <section class="product-showcase">
            <hr>
            <h2 class="section-title">Todos</h2>
            <div class="product-grid" id="todos-grid">
            </div>
        </section>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        <?php
            
            $sql = "
                SELECT 
                    p.*, 
                    MIN(et.preco) as preco 
                FROM 
                    produtos p
                LEFT JOIN 
                    estoque_tamanhos et ON p.id = et.produto_id
                GROUP BY 
                    p.id
            ";
            
            $stmt_produtos = $pdo->query($sql);
            $produtos_do_banco = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);

            $produtos_js = [];
            foreach ($produtos_do_banco as $produto) {
                
                $imagem_url = BASE_URL . htmlspecialchars($produto['imagem']); 

                $produtos_js[] = [
                    'id' => (int)$produto['id'],
                    'marca' => $produto['marca'],
                    'nome' => $produto['nome'],
                    'preco' => $produto['preco'] ? 'R$ ' . number_format($produto['preco'], 2, ',', '.') : 'Indisponível', 
                    'imagem_principal' => $imagem_url, 
                    'descricao' => $produto['descricao']
                ];
            }
            
            
            echo "const produtos = " . json_encode($produtos_js) . ";";
        ?>
    </script>
    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const maisVistosContainer = document.getElementById('mais-vistos-grid');
            const todosContainer = document.getElementById('todos-grid');
            
            
            const maisVistosProdutos = produtos.slice(0, 3);
            const todosProdutos = produtos.slice(3);

            function criarCardProduto(produto) {
                return `
                    <a href="<?php echo BASE_URL; ?>/src/pages/produto Individual/produtoIndividual.php?id=${produto.id}" class="product-card-link">
                        <div class="product-card">
                            <div class="product-image-container">
                                <img src="${produto.imagem_principal}" alt="${produto.nome}">
                            </div>
                            <div class="product-info">
                                <span class="product-brand">${produto.marca}</span>
                                <h3 class="product-name">${produto.nome}</h3>
                                <div class="product-footer">
                                    <span class="product-price">${produto.preco}</span>
                                    <div class="product-actions">
                                        <button class="action-btn cart-btn" data-id="${produto.id}"><i class="fas fa-shopping-cart"></i></button>
                                        <button class="action-btn buy-btn" data-id="${produto.id}">COMPRAR</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                `;
            }
            maisVistosProdutos.forEach(produto => {
                maisVistosContainer.innerHTML += criarCardProduto(produto);
            });
            todosProdutos.forEach(produto => {
                todosContainer.innerHTML += criarCardProduto(produto);
            });
            
            
            document.querySelector('.product-grid').addEventListener('click', function(e) {
                const target = e.target.closest('.action-btn');
                if (!target) return;
                
                e.preventDefault(); 
                const card = target.closest('.product-card-link');
                const produtoId = card.href.split('id=')[1];
                
                
                adicionarAoCarrinho(parseInt(produtoId), '40');
                
                if (target.classList.contains('buy-btn')) {
                    window.location.href = '<?php echo BASE_URL; ?>/src/pages/carrinho/carrinho.php';
                } else {
                    alert('Produto adicionado ao carrinho!');
                }
            });

            function adicionarAoCarrinho(id, tamanho) {
                let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
                carrinho.push({ id: id, tamanho: tamanho });
                localStorage.setItem('carrinho', JSON.stringify(carrinho));
            }
        });
    </script>
                <?php include 'libras/libras.php'; ?>

</body>
</html>