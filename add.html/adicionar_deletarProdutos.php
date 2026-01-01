<?php 
require_once '../db_config.php'; 

// Proteção da Página de Admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: " . BASE_URL . "/src/pages/login/login.php");
    exit;
}

$form_message = '';
$form_message_estoque = '';
$form_error = ''; // Para erros de upload

// --- CAMINHOS DE UPLOAD ---
define('UPLOAD_DIR_PATH', __DIR__ . '/../src/assets/tenis/'); // Caminho FÍSICO no servidor
define('UPLOAD_DIR_URL', '/src/assets/tenis/');     // Caminho URL para o Banco de Dados

// --- LÓGICA DE CRUD PARA PRODUTOS (COM UPLOAD DE IMAGEM) ---
$editando_produto = false;
$produto_para_editar = [
    'id' => null, 'nome' => '', 'descricao' => '', 'categoria' => 'sneakers',
    'marca' => '', 'imagem' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao_produto'])) {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];
    $marca = $_POST['marca'];
    $imagem_path = $_POST['imagem_atual'] ?? ''; 

    // --- VERIFICAÇÃO DE PERMISSÃO ---
    if (!is_writable(UPLOAD_DIR_PATH)) {
        $form_error = "ERRO GRAVE: A pasta do servidor '.../src/assets/tenis/' não tem permissão de escrita. Verifique as permissões da pasta.";
    }

    // --- LÓGICA DE UPLOAD (MODIFICADA) ---
    // Agora aceita de 1 a 4 imagens
    elseif (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0])) {
        
        $file_count = count($_FILES['imagens']['name']);
        
        // --- REMOVIDA A VALIDAÇÃO DE "EXATAMENTE 4" ---
        // if ($file_count != 4) { ... }
        
        $base_name = uniqid('tenis_') . '_';
        $caminhos_imagens = [];

        for ($i = 0; $i < $file_count; $i++) {
            $file_tmp = $_FILES['imagens']['tmp_name'][$i];
            $file_error = $_FILES['imagens']['error'][$i];
            
            if ($file_error === UPLOAD_ERR_OK) {
                $file_ext = strtolower(pathinfo($_FILES['imagens']['name'][$i], PATHINFO_EXTENSION));
                $new_file_name = $base_name . ($i + 1) . '.' . $file_ext;
                $destino = UPLOAD_DIR_PATH . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $destino)) {
                    $caminhos_imagens[$i] = UPLOAD_DIR_URL . $new_file_name;
                } else {
                    $form_error = "Erro ao mover o arquivo '$new_file_name'. Verifique as permissões da pasta.";
                    break;
                }
            } else {
                $form_error = "Erro no upload do arquivo " . ($i+1) . " (Código: $file_error).";
                break;
            }
        }
        
        if (empty($form_error)) {
            $imagem_path = $caminhos_imagens[0]; // Salva o caminho da IMAGEM_1
        }
    }
    // --- FIM DA LÓGICA DE UPLOAD ---

    // Continua apenas se não houver erro
    if (empty($form_error)) {
        try {
            if ($_POST['acao_produto'] == 'adicionar') {
                $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, categoria, marca, imagem) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nome, $descricao, $categoria, $marca, $imagem_path]);
                $form_message = "Produto adicionado! Agora adicione o estoque abaixo.";
            } 
            elseif ($_POST['acao_produto'] == 'editar' && $id) {
                $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, categoria = ?, marca = ?, imagem = ? WHERE id = ?");
                $stmt->execute([$nome, $descricao, $categoria, $marca, $imagem_path, $id]);
                $form_message = "Produto atualizado com sucesso!";
            }
        } catch (PDOException $e) {
            $form_error = "Erro de Banco de Dados: " . $e->getMessage();
        }
    }
}

// Lógica de carregar para edição (Info)
if (isset($_GET['edit_produto_id'])) {
    $id_para_editar = (int)$_GET['edit_produto_id'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id_para_editar]);
    $produto_para_editar = $stmt->fetch();
    if ($produto_para_editar) $editando_produto = true;
}

// Lógica de deletar produto
if (isset($_GET['delete_produto_id'])) {
    $id_para_deletar = (int)$_GET['delete_produto_id'];
    $stmt_img = $pdo->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt_img->execute([$id_para_deletar]);
    $img_path = $stmt_img->fetchColumn();
    
    if ($img_path) {
        $base_name = basename($img_path); // Ex: 'tenis_654a9b8c12345_1.jpg'
        $base_name_sem_num = preg_replace('/_1\.[^.]+$/', '', $base_name); // Ex: 'tenis_654a9b8c12345'
        $ext = pathinfo($img_path, PATHINFO_EXTENSION); // Ex: 'jpg'
        
        // Tenta deletar as 4 imagens (não dá erro se não achar)
        @unlink(UPLOAD_DIR_PATH . $base_name_sem_num . "_1." . $ext);
        @unlink(UPLOAD_DIR_PATH . $base_name_sem_num . "_2." . $ext);
        @unlink(UPLOAD_DIR_PATH . $base_name_sem_num . "_3." . $ext);
        @unlink(UPLOAD_DIR_PATH . $base_name_sem_num . "_4." . $ext);
    }
    
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?"); // ON DELETE CASCADE cuida do estoque
    $stmt->execute([$id_para_deletar]);
    header("Location: adicionar_deletarProdutos.php"); 
    exit;
}


// --- LÓGICA DE CRUD PARA ESTOQUE (Tamanho/Preço/Qtd) ---
$editando_estoque = false;
$estoque_para_editar = [
    'id' => null, 'produto_id' => '', 'tamanho' => '', 'quantidade' => 0, 'preco' => 0.00
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao_estoque'])) {
    $produto_id = $_POST['produto_id'];
    $tamanho = $_POST['tamanho'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    if ($_POST['acao_estoque'] == 'adicionar') {
        $stmt = $pdo->prepare("INSERT INTO estoque_tamanhos (produto_id, tamanho, quantidade, preco) VALUES (?, ?, ?, ?)");
        $stmt->execute([$produto_id, $tamanho, $quantidade, $preco]);
        $form_message_estoque = "Estoque adicionado com sucesso!";
    } 
    elseif ($_POST['acao_estoque'] == 'editar') {
        $estoque_id = $_POST['estoque_id'];
        $stmt = $pdo->prepare("UPDATE estoque_tamanhos SET produto_id = ?, tamanho = ?, quantidade = ?, preco = ? WHERE id = ?");
        $stmt->execute([$produto_id, $tamanho, $quantidade, $preco, $estoque_id]);
        $form_message_estoque = "Estoque atualizado com sucesso!";
    }
}

// Lógica de carregar para edição (Estoque)
if (isset($_GET['edit_estoque_id'])) {
    $id_para_editar = (int)$_GET['edit_estoque_id'];
    $stmt = $pdo->prepare("SELECT * FROM estoque_tamanhos WHERE id = ?");
    $stmt->execute([$id_para_editar]);
    $estoque_para_editar = $stmt->fetch();
    if ($estoque_para_editar) $editando_estoque = true;
}

// Lógica de deletar (Estoque)
if (isset($_GET['delete_estoque_id'])) {
    $id_para_deletar = (int)$_GET['delete_estoque_id'];
    $stmt = $pdo->prepare("DELETE FROM estoque_tamanhos WHERE id = ?");
    $stmt->execute([$id_para_deletar]);
    header("Location: adicionar_deletarProdutos.php"); 
    exit;
}

// --- BUSCAR DADOS PARA EXIBIÇÃO ---
$lista_produtos = $pdo->query("SELECT id, nome FROM produtos ORDER BY nome ASC")->fetchAll();
$termo_busca = $_GET['buscar'] ?? '';
$sql_estoque = "
    SELECT 
        e.id as estoque_id, e.tamanho, e.quantidade, e.preco,
        p.id as produto_id, p.nome, p.imagem, p.marca
    FROM 
        estoque_tamanhos e
    JOIN 
        produtos p ON e.produto_id = p.id
";
$params_estoque = [];
if ($termo_busca != '') {
    $sql_estoque .= " WHERE p.nome LIKE ? OR p.marca LIKE ?";
    $params_estoque[] = "%$termo_busca%";
    $params_estoque[] = "%$termo_busca%";
}
$sql_estoque .= " ORDER BY p.nome ASC, e.tamanho ASC";
$stmt_estoque = $pdo->prepare($sql_estoque);
$stmt_estoque->execute($params_estoque);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Estoque - KICKS BR</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/add.html/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header"><h1 class="logo">Du.hype</h1></div>
           <nav class="sidebar-nav">
                <ul>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/financeiro/financeiro.php"> <i class='bx bxs-dashboard'></i> <span>Painel</span></a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/estoque/estoque.php"> <i class='bx bx-package'></i> <span>Visão Geral</span></a></li>
                    <li class="nav-item active"><a href="<?php echo BASE_URL; ?>/add.html/adicionar_deletarProdutos.php"> <i class='bx bx-plus-circle'></i> <span>Gerenciar</span></a></li>
                    <li class="nav-item"><a href="#"><i class='bx bx-cog'></i> <span>Configurações</span></a></li>
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
            <div class="sidebar-footer"><p>© 2025 - DuHype Dashboard</p></div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <h2>Gerenciar Produtos e Estoque</h2>
                </div>
                <form method="GET" action="adicionar_deletarProdutos.php" class="header-right">
                    <div class="search-bar">
                        <i class='bx bx-search'></i>
                        <input type="text" id="buscar" name="buscar" placeholder="Buscar por nome ou marca..." value="<?php echo htmlspecialchars($termo_busca); ?>">
                    </div>
                    <button class="btn btn-secondary" id="modo-btn" type="button"><i class='bx bx-moon'></i><span>Modo Escuro</span></button>
                </form>
            </header>

            <section class="content-wrapper">
                
                <div class="form-column">
                    <div class="card">
                        <h3 id="form-title-produto"><?php echo $editando_produto ? 'Editar Produto (Info)' : 'Adicionar Novo Produto (Info)'; ?></h3>
                        
                        <?php if ($form_message): ?><p class="form-message success"><?php echo $form_message; ?></p><?php endif; ?>
                        <?php if ($form_error): ?><p class="form-message error"><?php echo $form_error; ?></p><?php endif; ?>

                        <form id="form-produto" method="POST" action="adicionar_deletarProdutos.php" enctype="multipart/form-data">
                            <input type="hidden" name="acao_produto" value="<?php echo $editando_produto ? 'editar' : 'adicionar'; ?>">
                            <?php if ($editando_produto): ?>
                                <input type="hidden" name="id" value="<?php echo $produto_para_editar['id']; ?>">
                                <input type="hidden" name="imagem_atual" value="<?php echo htmlspecialchars($produto_para_editar['imagem']); ?>" />
                            <?php endif; ?>

                            <label for="nome">Nome do Tênis</label>
                            <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($produto_para_editar['nome']); ?>" />

                            <label for="descricao">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars($produto_para_editar['descricao']); ?></textarea>
                            
                            <label for="categoria">Categoria</label>
                            <select id="categoria" name="categoria">
                                <option value="sneakers" <?php echo ($produto_para_editar['categoria'] ?? '') == 'sneakers' ? 'selected' : ''; ?>>Tênis Casuais</option>
                                <option value="corrida" <?php echo ($produto_para_editar['categoria'] ?? '') == 'corrida' ? 'selected' : ''; ?>>Corrida</option>
                                <option value="futebol" <?php echo ($produto_para_editar['categoria'] ?? '') == 'futebol' ? 'selected' : ''; ?>>Futebol</option>
                                <option value="basquete" <?php echo ($produto_para_editar['categoria'] ?? '') == 'basquete' ? 'selected' : ''; ?>>Basquete</option>
                            </select>

                            <label for="marca">Marca</label>
                            <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($produto_para_editar['marca']); ?>" />

                            <label for="imagens">Imagens do Tênis (1 a 4 arquivos)</label>
                            <input type="file" id="imagens" name="imagens[]" multiple accept="image/jpeg, image/png, image/webp" <?php echo $editando_produto ? '' : 'required'; ?> />
                            
                            <?php if ($editando_produto): ?>
                                <p style="font-size: 0.8em; color: #888;">Deixe em branco para manter as imagens atuais.</p>
                            <?php else: ?>
                                <p style="font-size: 0.8em; color: #888;">Segure 'Ctrl' para selecionar até 4 imagens.</p>
                            <?php endif; ?>
                            <div class="form-buttons">
                                <button type="submit" class="btn btn-primary" id="btn-salvar-produto">
                                    <?php echo $editando_produto ? 'Salvar Produto' : 'Adicionar Produto'; ?>
                                </button>
                                <?php if ($editando_produto): ?>
                                    <a href="adicionar_deletarProdutos.php" class="btn btn-secondary">Cancelar Edição</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <div class="card" style="margin-top: 30px;">
                        <h3 id="form-title-estoque"><?php echo $editando_estoque ? 'Editar Item de Estoque' : 'Adicionar Estoque/Preço'; ?></h3>

                        <?php if ($form_message_estoque): ?><p class="form-message success"><?php echo $form_message_estoque; ?></p><?php endif; ?>

                        <form id="form-estoque" method="POST" action="adicionar_deletarProdutos.php">
                            <input type="hidden" name="acao_estoque" value="<?php echo $editando_estoque ? 'editar' : 'adicionar'; ?>">
                            <?php if ($editando_estoque): ?>
                                <input type="hidden" name="estoque_id" value="<?php echo $estoque_para_editar['id']; ?>">
                            <?php endif; ?>

                            <label for="produto_id">Produto</label>
                            <select id="produto_id" name="produto_id" required <?php echo $editando_estoque ? 'disabled' : ''; ?>>
                                <option value="" disabled <?php echo !$editando_estoque ? 'selected' : ''; ?>>-- Selecione um produto --</option>
                                <?php foreach ($lista_produtos as $prod): ?>
                                    <option value="<?php echo $prod['id']; ?>" <?php echo $estoque_para_editar['produto_id'] == $prod['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($prod['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($editando_estoque): ?>
                                <input type="hidden" name="produto_id" value="<?php echo $estoque_para_editar['produto_id']; ?>">
                            <?php endif; ?>


                            <label for="tamanho">Tamanho</label>
                            <input type="text" id="tamanho" name="tamanho" required value="<?php echo htmlspecialchars($estoque_para_editar['tamanho']); ?>" />

                            <label for="quantidade">Quantidade em estoque</label>
                            <input type="number" id="quantidade" name="quantidade" min="0" required value="<?php echo htmlspecialchars($estoque_para_editar['quantidade']); ?>" />

                            <label for="preco">Preço (R$)</label>
                            <input type="number" id="preco" name="preco" step="0.01" min="0" required value="<?php echo htmlspecialchars($estoque_para_editar['preco']); ?>" />

                            <div class="form-buttons">
                                <button type="submit" class="btn btn-primary" id="btn-salvar-estoque">
                                    <?php echo $editando_estoque ? 'Salvar Estoque' : 'Adicionar Estoque'; ?>
                                </button>
                                <?php if ($editando_estoque): ?>
                                    <a href="adicionar_deletarProdutos.php" class="btn btn-secondary">Cancelar Edição</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="list-column">
                    <div class="card">
                        <h3>Itens de Estoque Cadastrados</h3>
                        <div id="lista-produtos" class="product-list">
                            
                            <?php 
                            $produto_atual = null;
                            while ($item = $stmt_estoque->fetch()): 
                                if ($produto_atual != $item['produto_id']):
                                    $produto_atual = $item['produto_id'];
                            ?>
                                <div class="product-header">
                                    <img src="<?php echo BASE_URL . htmlspecialchars($item['imagem'] ?: '/src/assets/Images/placeholder.png'); ?>" alt="">
                                    <h4><?php echo htmlspecialchars($item['nome']); ?></h4>
                                    <div class="product-header-actions">
                                        <a href="?edit_produto_id=<?php echo $item['produto_id']; ?>" class="btn btn-edit-header">Editar Info</a>
                                        <a href="?delete_produto_id=<?php echo $item['produto_id']; ?>" class="btn btn-delete-header" onclick="return confirm('ATENÇÃO: Deletar este produto vai apagar TODOS os seus estoques e imagens. Deseja continuar?');">Deletar Produto</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                                <div class="product-item-estoque">
                                    <div class="product-info-estoque">
                                        <span>Tamanho: <strong><?php echo htmlspecialchars($item['tamanho']); ?></strong></span>
                                        <span>Qtd: <strong><?php echo htmlspecialchars($item['quantidade']); ?></strong></span>
                                        <span>Preço: <strong>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></strong></span>
                                    </div>
                                    <div class="product-actions-estoque">
                                        <a href="?edit_estoque_id=<?php echo $item['estoque_id']; ?>" class="btn btn-edit"><i class='bx bx-pencil'></i> Editar</a>
                                        <a href="?delete_estoque_id=<?php echo $item['estoque_id']; ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja deletar este item de estoque?');"><i class='bx bx-trash'></i> Deletar</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            
                            <?php if ($stmt_estoque->rowCount() === 0): ?>
                                <p>Nenhum item de estoque encontrado.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modoBtn = document.getElementById('modo-btn');
            if (modoBtn) {
                modoBtn.addEventListener('click', () => {
                    document.body.classList.toggle('dark-mode');
                    const icon = modoBtn.querySelector('i');
                    const text = modoBtn.querySelector('span');
                    if (document.body.classList.contains('dark-mode')) {
                        icon.classList.replace('bx-moon', 'bx-sun');
                        text.innerText = 'Modo Claro';
                    } else {
                        icon.classList.replace('bx-sun', 'bx-moon');
                        text.innerText = 'Modo Escuro';
                    }
                });
            }
            <?php if ($editando_produto): ?>
                document.getElementById('form-produto').scrollIntoView({ behavior: 'smooth' });
            <?php endif; ?>
            <?php if ($editando_estoque): ?>
                document.getElementById('form-estoque').scrollIntoView({ behavior: 'smooth' });
            <?php endif; ?>
        });
    </script>
    <style>
        .form-message.success { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 8px; margin-bottom: 15px; }
        .form-message.error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-weight: 500; }
        .product-header { display: flex; align-items: center; gap: 15px; padding: 10px; background-color: var(--color-main-bg); border-radius: 8px; margin-top: 20px; }
        .product-header img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .product-header h4 { flex-grow: 1; font-size: 1.1em; margin: 0; }
        .product-header-actions { display: flex; gap: 8px; }
        .btn-edit-header, .btn-delete-header { font-size: 12px; padding: 5px 10px; border-radius: 6px; font-weight: 500; text-decoration: none; }
        .btn-edit-header { background-color: var(--color-border); color: var(--color-text-dark); }
        .btn-delete-header { background-color: #e94f37; color: white; }
        
        .product-item-estoque { display: flex; align-items: center; justify-content: space-between; padding: 12px 15px 12px 25px; border-bottom: 1px solid var(--color-border); }
        .product-item-estoque:last-child { border-bottom: none; }
        .product-info-estoque { display: flex; gap: 20px; font-size: 14px; }
        .product-actions-estoque { display: flex; gap: 10px; }
        
        #form-produto input[type="file"] {
            padding: 10px;
            background-color: var(--color-main-bg);
            border: 1px solid var(--color-border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--color-text-dark);
        }
    </style>

        <?php include '../libras/libras.php'; ?>

</body>
</html>