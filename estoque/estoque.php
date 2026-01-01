<?php 
require_once '../db_config.php'; 

// Proteção da Página de Admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // CAMINHO DE REDIRECT CORRIGIDO
    header("Location: " . BASE_URL . "/src/pages/login/login.php");
    exit;
}

// Lógica para buscar os produtos do banco (QUERY CORRIGIDA)
$termo_busca = $_GET['buscar'] ?? '';
$categoria_filtro = $_GET['categoria'] ?? 'todos'; 

// QUERY ATUALIZADA para buscar da tabela 'produtos' e 'estoque_tamanhos'
$sql = "
    SELECT 
        p.id, p.nome, p.categoria, p.marca,
        SUM(et.quantidade) as total_quantidade, 
        MIN(et.preco) as preco_minimo
    FROM 
        produtos p
    LEFT JOIN 
        estoque_tamanhos et ON p.id = et.produto_id
";
$params = [];
$where_clauses = [];

if ($termo_busca) {
    $where_clauses[] = "(p.nome LIKE ? OR p.marca LIKE ?)";
    $params[] = "%$termo_busca%";
    $params[] = "%$termo_busca%";
}

// (Lógica de categoria não implementada no original, mantido assim)

if (count($where_clauses) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
}

$sql .= " GROUP BY p.id, p.nome, p.categoria, p.marca";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kicks BR - Produtos</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="logo">Du.hype</h1>
            </div>

            <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/financeiro/financeiro.php"> <i class='bx bxs-dashboard'></i> <span>Painel</span>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="<?php echo BASE_URL; ?>/estoque/estoque.php"> <i class='bx bx-package'></i> <span>Estoque</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/add.html/adicionar_deletarProdutos.php"> <i class='bx bx-plus-circle'></i>
                            <span>Adicionar Produto</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <i class='bx bx-cog'></i>
                            <span>Configurações</span>
                        </a>
                    </li>

                    <li class="nav-item" style="border-top: 1px solid #3a3b3c; margin-top: 15px; padding-top: 15px;">
                        <a href="<?php echo BASE_URL; ?>/home.php" target="_blank">
                            <i class='bx bx-home'></i> <span>Ver Loja</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/logout.php">
                            <i class='bx bx-log-out'></i> <span>Sair</span>
                        </a>
                    </li>
                    </ul>
            </nav>

            <div class="sidebar-footer">
                <p>© 2025 - DuHype Dashboard</p>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <h2>Todos os Produtos</h2>
                </div>
                <div class="header-right">
                    <form method="GET" action="estoque.php" class="search-bar">
                        <i class='bx bx-search'></i>
                        <input type="text" name="buscar" placeholder="Buscar produto..." value="<?php echo htmlspecialchars($termo_busca); ?>">
                    </form>
                    <button class="btn btn-secondary" id="darkModeToggle" type="button">
                        <i class='bx bx-moon'></i>
                        <span>Modo Escuro</span>
                    </button>
                    <a href="<?php echo BASE_URL; ?>/add.html/adicionar_deletarProdutos.php" class="btn btn-primary"> <i class='bx bx-plus'></i>
                      <span>Novo Produto</span>
                  </a>
                </div>
            </header>

            <section class="filter-section">
                <h3>Categorias</h3>
                <div class="filter-tags">
                    <button class="tag active">Tênis Casuais</button>
                    <button class="tag">Corrida</button>
                    <button class="tag">Futebol</button>
                    <button class="tag">Basquete</button>
                </div>
            </section>

            <section class="product-grid">
                <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="product-card card">
                            <h4><?php echo htmlspecialchars($produto['nome']); ?></h4>
                            <p class="product-category">Categoria: <?php echo htmlspecialchars($produto['categoria']); ?></p>
                            <p class="product-price">A partir de R$ <?php echo number_format($produto['preco_minimo'] ?? 0, 2, ',', '.'); ?></p>
                            <p style="margin-bottom: 15px; font-weight: 500;">Estoque Total: <strong style="font-size: 1.1em; color: <?php echo ($produto['total_quantidade'] ?? 0) < 5 ? 'red' : 'green'; ?>;"><?php echo $produto['total_quantidade'] ?? 0; ?></strong></p>
                            
                            <a href="<?php echo BASE_URL; ?>/add.html/adicionar_deletarProdutos.php?edit_id=<?php echo $produto['id']; ?>" class="btn-edit" style="text-decoration: none; text-align: center;">Editar Detalhes</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum produto encontrado.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <script src="script.js"></script>

    <?php include '../libras/libras.php'; ?>

</body>
</html>