<?php require_once '../../../db_config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DU.HYPE - Novidades</title>
    
    <link rel="stylesheet" href="style.css"> 
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <div class="header-container">
    <header class="header">
      <div class="logo">
        <img src="<?php echo BASE_URL; ?>/src/assets/Images/LogoDu.Hype.svg" alt="Logo duhype">
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

    <main class="content">
        <h1>Novidades</h1>

        <section class="promo-section">
            <article class="promo-banner">
                <div class="promo-text">
                    <p class="discount-tag">DESCONTO DE 20% NA PRIMEIRA COMPRA</p>
                    <h2>Nike Air Force 1</h2>
                    <hr>
                    <p class="description">
                        O clássico que nunca sai de moda. Estilo urbano,
                        conforto absoluto e atitude em cada passo.
                    </p>
                    <div class="promo-buttons">
                        <a href="<?php echo BASE_URL; ?>/src/pages/carrinho/carrinho.php" class="btn btn-primary">Comprar agora</a>
                        <a href="<?php echo BASE_URL; ?>/src/pages/marcas/marcas.php" class="btn btn-secondary">Saber mais</a>
                    </div>
                </div>
                <img src="<?php echo BASE_URL; ?>/src/assets/Images/novidaeTenis1-removebg-preview.png" alt="Tênis Nike Air Force 1 Branco" class="promo-shoe-img">
            </article>

            <article class="promo-banner banner--reversed">
                <div class="promo-text">
                    <p class="discount-tag">DESCONTO DE 20% NA PRIMEIRA COMPRA</p>
                    <h2>Nike Air Force 1</h2>
                    <hr>
                    <p class="description">
                        O clássico que nunca sai de moda. Estilo urbano,
                        conforto absoluto e atitude em cada passo.
                    </p>
                    <div class="promo-buttons">
                        <a href="<?php echo BASE_URL; ?>/src/pages/carrinho/carrinho.php" class="btn btn-primary">Comprar agora</a>
                        <a href="<?php echo BASE_URL; ?>/src/pages/marcas/marcas.php" class="btn btn-secondary">Saber mais</a>
                    </div>
                </div>
                <img src="<?php echo BASE_URL; ?>/src/assets/Images/tenissemfundo_direita.png" alt="Tênis Nike Air Force 1 Branco" class="promo-shoe-img">
            </article>
        </section>
    </main>

    <footer class="site-footer">
        <div class="footer-container">
            <p>&copy; 2025 DU.HYPE. Todos os direitos reservados.</p>
            <div class="footer-links">
                <a href="#">Sobre Nós</a>
                <a href="#">Contato</a>
                <a href="#">Política de Privacidade</a>
            </div>
        </div>
    </footer>
    <?php include '../../../libras/libras.php'; ?>
</body>
</html>