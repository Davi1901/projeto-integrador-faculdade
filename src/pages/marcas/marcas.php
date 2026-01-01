<?php 
require_once '../../../db_config.php'; 

// Lógica de filtro do PHP
$marcas_selecionadas = $_GET['marca'] ?? [];

// ATUALIZADO: Query agora busca o preço da tabela estoque_tamanhos
$sql = "
    SELECT 
        p.*, 
        MIN(et.preco) as preco 
    FROM 
        produtos p
    LEFT JOIN 
        estoque_tamanhos et ON p.id = et.produto_id
";
$params = [];

if (!empty($marcas_selecionadas)) {
    $placeholders = implode(',', array_fill(0, count($marcas_selecionadas), '?'));
    $sql .= " WHERE p.marca IN ($placeholders)";
    $params = $marcas_selecionadas;
}

$sql .= " GROUP BY p.id"; // Agrupa para o MIN(preco) funcionar

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos_filtrados = $stmt->fetchAll();

// Para o título
$titulo_marca = "Todas as Marcas";
if (!empty($marcas_selecionadas)) {
    $titulo_marca = implode(", ", $marcas_selecionadas);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DU.HYPE - Marcas</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/assets/styles/styles.css" />
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
        <a href="<?php echo BASE_URL; ?>/src/pages/fedd/feed.php" aria-label="Pesquisar"><i class="fas fa-search"></i></a>
        <a href="<?php echo BASE_URL; ?>/src/pages/carrinho/carrinho.php" aria-label="Carrinho"><i class="fas fa-shopping-cart"></i></a>
        <a href="<?php echo BASE_URL; ?>/src/pages/cadastro/cadastro.php" aria-label="Perfil do usuário"><i class="fas fa-user"></i></a>
      </div>
    </header>
  </div>

  <div class="container">
    <aside class="sidebar">
      <h3>Filtrar Marcas</h3>
      <form id="filtroForm" method="GET" action="marcas.php">
        <label><input type="checkbox" name="marca[]" value="NIKE" <?php echo in_array('NIKE', $marcas_selecionadas) ? 'checked' : ''; ?>> Nike</label>
        <label><input type="checkbox" name="marca[]" value="ADIDAS" <?php echo in_array('ADIDAS', $marcas_selecionadas) ? 'checked' : ''; ?>> Adidas</label>
        <label><input type="checkbox" name="marca[]" value="PUMA" <?php echo in_array('PUMA', $marcas_selecionadas) ? 'checked' : ''; ?>> Puma</label>
        <button type="submit">Filtrar</button>
      </form>
    </aside>

    <main class="produtos">
      <h2 id="tituloMarca"><?php echo htmlspecialchars($titulo_marca); ?></h2>
      <div class="grid" id="produtosGrid">
          
          <?php if (count($produtos_filtrados) > 0): ?>
              <?php foreach ($produtos_filtrados as $p): ?>
                  <div class="card">
                      <img src="<?php echo BASE_URL . htmlspecialchars($p['imagem']); ?>" alt="<?php echo htmlspecialchars($p['nome']); ?>">
                      <span class="marca"><?php echo htmlspecialchars($p['marca']); ?></span>
                      <h3><?php echo htmlspecialchars($p['nome']); ?></h3>
                      <p class="preco">R$ <?php echo number_format($p['preco'] ?? 0, 2, ',', '.'); ?></p>
                      <a href="<?php echo BASE_URL; ?>/src/pages/produto Individual/produtoIndividual.php?id=<?php echo $p['id']; ?>" class="btn">🛒 Comprar</a>
                  </div>
              <?php endforeach; ?>
          <?php else: ?>
              <p>Nenhum produto encontrado para esta seleção.</p>
          <?php endif; ?>

      </div>
    </main>
  </div>

        <?php include '../../../libras/libras.php'; ?>    
            
        

</body>
</html>