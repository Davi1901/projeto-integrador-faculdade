<?php 
require_once '../db_config.php'; 

// Proteção da Página de Admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: " . BASE_URL . "/src/pages/login/login.php");
    exit;
}

// === DADOS DINÂMICOS DO BANCO ===

// 1. Total Faturado
$total_faturado = $pdo->query("SELECT SUM(valor_total) FROM pedidos WHERE status_pagamento = 'Aprovado'")->fetchColumn();

// 2. Pedidos Pendentes
$pedidos_ativos = $pdo->query("SELECT COUNT(id) FROM pedidos WHERE status_pagamento = 'Pendente'")->fetchColumn();

// 3. Total de Pedidos Aprovados
$pedidos_enviados = $pdo->query("SELECT COUNT(id) FROM pedidos WHERE status_pagamento = 'Aprovado'")->fetchColumn();

// 4. Melhores Vendedores
$stmt_best_sellers = $pdo->query("
    SELECT p.nome, p.imagem, SUM(pi.quantidade) as total_vendido, SUM(pi.preco_unitario * pi.quantidade) as faturamento_produto
    FROM pedido_itens pi
    JOIN produtos p ON pi.produto_id = p.id
    GROUP BY pi.produto_id, p.nome, p.imagem
    ORDER BY total_vendido DESC
    LIMIT 3
");
$best_sellers = $stmt_best_sellers->fetchAll();

// 5. Pedidos Recentes
$stmt_recent_orders = $pdo->query("
    SELECT p.*, u.nome as nome_cliente
    FROM pedidos p
    LEFT JOIN usuarios u ON p.usuario_id = u.id
    ORDER BY p.data_pedido DESC
    LIMIT 6
");
$recent_orders = $stmt_recent_orders->fetchAll();

// 6. DADOS PARA O GRÁFICO (Vendas dos últimos 7 dias)
$sql_grafico = "
    SELECT DATE(data_pedido) as dia, SUM(valor_total) as total 
    FROM pedidos 
    WHERE status_pagamento = 'Aprovado' 
    GROUP BY DATE(data_pedido) 
    ORDER BY dia DESC 
    LIMIT 7
";
$stmt_grafico = $pdo->query($sql_grafico);
$dados_grafico = $stmt_grafico->fetchAll(PDO::FETCH_ASSOC);

// Prepara arrays para o JavaScript (garante ordem cronológica)
$labels_grafico = [];
$valores_grafico = [];

// Se não tiver vendas, cria dados zerados para o gráfico não quebrar
if (empty($dados_grafico)) {
    for ($i = 6; $i >= 0; $i--) {
        $labels_grafico[] = date('d/m', strtotime("-$i days"));
        $valores_grafico[] = 0;
    }
} else {
    // Organiza os dados do banco
    $dados_formatados = array_reverse($dados_grafico); // Inverte para ficar cronológico
    foreach ($dados_formatados as $dado) {
        $labels_grafico[] = date('d/m', strtotime($dado['dia']));
        $valores_grafico[] = (float)$dado['total'];
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kicks Dashboard</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/financeiro/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="logo">Du.hype</h1>
            </div>

          <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item active">
                        <a href="<?php echo BASE_URL; ?>/financeiro/financeiro.php">
                            <i class='bx bxs-dashboard'></i>
                            <span>DASHBOARD</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/estoque/estoque.php"> <i class='bx bx-package'></i>
                            <span>Estoque</span>
                        </a>
                    </li>
                    <li class="nav-item">
                         <a href="<?php echo BASE_URL; ?>/add.html/adicionar_deletarProdutos.php"> <i class='bx bx-plus-circle'></i>
                            <span>Adicionar Produto</span>
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
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <h2>Dashboard</h2>
                    <p class="breadcrumb">Home > Dashboard</p>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <i class='bx bx-search'></i>
                        <i class='bx bx-bell'></i>
                        <div class="admin-profile">
                            <span><?php echo htmlspecialchars($_SESSION['user_nome'] ?? 'Admin'); ?></span> <i class='bx bx-chevron-down'></i>
                        </div>
                    </div>
                    <div class="date-range">
                        <i class='bx bx-calendar'></i>
                        <span><?php echo date('d M, Y'); ?></span>
                    </div>
                </div>
            </header>

            <section class="stats-cards">
                <div class="card">
                    <div class="card-header">
                        <h3>Total Faturado</h3>
                        <i class='bx bx-dots-horizontal-rounded'></i>
                    </div>
                    <div class="card-body">
                        <h2>R$ <?php echo number_format($total_faturado ?? 0, 2, ',', '.'); ?></h2>
                    </div>
                    <p class="card-footer">Todos os pedidos aprovados</p>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Pedidos Pendentes</h3>
                        <i class='bx bx-dots-horizontal-rounded'></i>
                    </div>
                    <div class="card-body">
                        <h2><?php echo $pedidos_ativos ?? 0; ?></h2>
                    </div>
                    <p class="card-footer">Aguardando pagamento</p>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Total de Pedidos</h3>
                        <i class='bx bx-dots-horizontal-rounded'></i>
                    </div>
                    <div class="card-body">
                         <h2><?php echo $pedidos_enviados ?? 0; ?></h2>
                    </div>
                    <p class="card-footer">Pedidos aprovados no total</p>
                </div>
            </section>

            <section class="analytics-section">
                <div class="sales-graph-card card">
                    <div class="card-header">
                        <h3>Vendas (Últimos 7 dias)</h3>
                        <div class="graph-toggles">
                            <button class="toggle-btn active">SEMANAL</button>
                        </div>
                    </div>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <div class="best-sellers-card card">
                    <div class="card-header">
                        <h3>Mais Vendidos</h3>
                        <i class='bx bx-dots-horizontal-rounded'></i>
                    </div>
                    <ul class="best-sellers-list">
                        <?php foreach ($best_sellers as $seller): ?>
                            <li class="seller-item">
                                <img src="<?php echo BASE_URL . htmlspecialchars($seller['imagem'] ?: '/src/assets/Images/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($seller['nome']); ?>">
                                <div class="seller-info">
                                    <p><?php echo htmlspecialchars($seller['nome']); ?></p>
                                    <span><?php echo $seller['total_vendido']; ?> vendas</span>
                                </div>
                                <strong class="seller-price">R$ <?php echo number_format($seller['faturamento_produto'], 2, ',', '.'); ?></strong>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($best_sellers)): ?>
                            <p style="padding: 10px; color: #888;">Nenhuma venda registrada ainda.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </section>

            <section class="recent-orders-card card">
                 <div class="card-header">
                    <h3>Pedidos Recentes</h3>
                    <i class='bx bx-dots-horizontal-rounded'></i>
                </div>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Pedido ID</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Status</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($order['data_pedido'])); ?></td>
                                <td><?php echo htmlspecialchars($order['nome_cliente'] ?? 'Visitante'); ?></td>
                                <td>
                                    <?php if ($order['status_pagamento'] == 'Aprovado'): ?>
                                        <span class="status delivered"></span> Aprovado
                                    <?php else: ?>
                                        <span class="status canceled"></span> <?php echo htmlspecialchars($order['status_pagamento']); ?>
                                    <?php endif; ?>
                                </td>
                                <td>R$ <?php echo number_format($order['valor_total'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                         <?php if (empty($recent_orders)): ?>
                            <tr><td colspan="5" style="text-align: center;">Nenhum pedido recente.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <footer class="main-footer-bottom">
                <p>© 2025 - Du.hype DashBoard</p>
            </footer>
        </main>
    </div>
    
    <script src="<?php echo BASE_URL; ?>/financeiro/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            // Dados vindos do PHP
            const labels = <?php echo json_encode($labels_grafico); ?>;
            const dataValues = <?php echo json_encode($valores_grafico); ?>;

            new Chart(ctx, {
                type: 'line', // Tipo do gráfico
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Faturamento (R$)',
                        data: dataValues,
                        backgroundColor: 'rgba(105, 92, 254, 0.2)', // Cor de fundo (roxo transparente)
                        borderColor: '#695cfe', // Cor da linha (Roxo do seu tema)
                        borderWidth: 2,
                        tension: 0.4, // Deixa a linha curva (suave)
                        fill: true,   // Preenche abaixo da linha
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#695cfe',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Esconde a legenda padrão
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f0f0'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>

    <?php include '../libras/libras.php'; ?>

</body>
</html>