<?php require_once '../../../db_config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Produto</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="style.css"> 
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
         <div class="produto-container">
            <div class="galeria-produto">
                <div id="produto-thumbnails">
                    </div>
                <div class="produto-imagem-grande">
                    <img id="produto-imagem" src="" alt="Imagem Principal do Produto"> 
                </div>
            </div>
            <div id="produto-info" class="produto-detalhes">
                <h1 id="produto-nome" class="produto-nome-grande">Carregando...</h1> 
                <div class="estrelas-avaliacao" id="produto-estrelas">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star empty"></i>
                </div>
                <div class="preco-container">
                    <span class="preco-antigo" id="preco-antigo"></span>
                    <p id="produto-preco" class="produto-preco-grande">R$ 0,00</p> 
                </div>
                <div class="seletor-tamanho">
                    <p class="label">Tamanho</p>
                    <div class="tamanhos-container" id="tamanhos-container-real">
                        </div>
                </div>
                <div class="botoes-acao">
                    <button class="botao-comprar-grande" id="btn-comprar">Comprar</button>
                </div>
            </div>
        </div>
        <div class="semelhantes-section">
            <div class="caracteristicas-box">
                <h3 class="caracteristicas-titulo">Características</h3>
                <p id="produto-descricao">Carregando...</p> 
            </div>
        </div>
    </main>

    <script>
        <?php
            // Pega o ID da URL
            $produto_id = $_GET['id'] ?? 0;
            $produto_js = null;
            $estoque_js = [];
            $thumbs_js = []; // Array para as imagens

            if ($produto_id) {
                // 1. Busca o produto principal (da tabela 'produtos')
                $stmt_produto = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
                $stmt_produto->execute([$produto_id]);
                $produto = $stmt_produto->fetch();

                if ($produto) {
                    // 2. Busca todos os tamanhos e estoques para esse produto
                    $stmt_estoque = $pdo->prepare("SELECT tamanho, quantidade, preco FROM estoque_tamanhos WHERE produto_id = ? ORDER BY CAST(tamanho AS UNSIGNED)");
                    $stmt_estoque->execute([$produto_id]);
                    $estoque_lista = $stmt_estoque->fetchAll(PDO::FETCH_ASSOC);
                    
                    // --- 3. LÓGICA INTELIGENTE DE IMAGENS (NOVA) ---
                    if (!empty($produto['imagem'])) {
                        // Caminho físico da imagem 1 no servidor
                        // __DIR__ é a pasta .../produto Individual/
                        // realpath vai para .../DU.Hype/
                        $caminho_fisico_base = realpath(__DIR__ . '/../../../') . $produto['imagem'];
                        
                        // URL base da imagem 1
                        $url_base = BASE_URL . $produto['imagem'];

                        // Remove o sufixo _1.ext (ou .ext se não tiver _1)
                        $base_name_url = preg_replace('/(_1)?\.[^.]+$/', '', $url_base);
                        $base_name_path = preg_replace('/(_1)?\.[^.]+$/', '', $caminho_fisico_base);
                        
                        $ext = pathinfo($caminho_fisico_base, PATHINFO_EXTENSION);
                        
                        // Loop de 1 a 4, verificando se o arquivo existe
                        for ($i = 1; $i <= 4; $i++) {
                            // Tenta encontrar o arquivo (ex: tenis_abc_1.jpg, tenis_abc_2.jpg...)
                            $file_to_check = $base_name_path . "_" . $i . "." . $ext;
                            
                            // Se existir, adiciona na lista de thumbs
                            if (file_exists($file_to_check)) {
                                $thumbs_js[] = $base_name_url . "_" . $i . "." . $ext;
                            }
                        }
                        
                        // Fallback: Se o loop falhar (lógica de nome antiga)
                        if(empty($thumbs_js) && file_exists($caminho_fisico_base)) {
                           $thumbs_js[] = $url_base; // Adiciona pelo menos a principal
                        }
                    }
                    // --- FIM DA LÓGICA DE IMAGENS ---
                    
                    // --- Passa os dados para o JavaScript ---
                    $produto_js = [
                        'id' => (int)$produto['id'],
                        'marca' => $produto['marca'],
                        'nome' => $produto['nome'],
                        // Define a imagem principal como a primeira imagem encontrada
                        'imagem_principal' => $thumbs_js[0] ?? (BASE_URL . $produto['imagem']),
                        // Passa a lista de imagens que REALMENTE existem
                        'imagens_thumb' => $thumbs_js, 
                        'descricao' => $produto['descricao']
                    ];
                    $estoque_js = $estoque_lista;
                }
            }
            
            // Envia as variáveis para o JavaScript
            echo "const produto = " . json_encode($produto_js) . ";\n";
            echo "const estoqueDisponivel = " . json_encode($estoque_js) . ";\n";
        ?>
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // As variáveis 'produto' e 'estoqueDisponivel' vêm do PHP
            
            if (!produto) {
                document.querySelector('main').innerHTML = '<h1>Produto não encontrado.</h1>';
                return;
            }

            // ----- Elementos da Página -----
            const nomeEl = document.getElementById('produto-nome');
            const precoEl = document.getElementById('produto-preco');
            const descricaoEl = document.getElementById('produto-descricao');
            const imagemPrincipalEl = document.getElementById('produto-imagem');
            const thumbnailsContainer = document.getElementById('produto-thumbnails');
            const tamanhosContainer = document.getElementById('tamanhos-container-real');
            const btnComprar = document.getElementById('btn-comprar');
            
            let tamanhoSelecionado = null;
            let precoSelecionado = "0.00";

            // ----- 1. POPULAR DADOS BÁSICOS -----
            nomeEl.textContent = produto.nome;
            descricaoEl.textContent = produto.descricao;
            imagemPrincipalEl.src = produto.imagem_principal;

            // ----- 2. CRIAR GALERIA DE IMAGENS (Thumbnails) -----
            // AGORA SÓ CRIA THUMBNAILS PARA IMAGENS QUE EXISTEM
            thumbnailsContainer.innerHTML = '';
            
            // Só mostra a galeria se tiver mais de 1 imagem
            if (produto.imagens_thumb.length > 1) {
                produto.imagens_thumb.forEach((imgSrc, index) => {
                    const img = document.createElement('img');
                    img.src = imgSrc;
                    img.alt = `Thumbnail ${index + 1}`;
                    img.className = 'thumb-item';
                    
                    img.onload = () => {
                        if (index === 0) img.classList.add('active-thumb');
                        img.addEventListener('click', () => {
                            imagemPrincipalEl.src = imgSrc;
                            document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active-thumb'));
                            img.classList.add('active-thumb');
                        });
                        thumbnailsContainer.appendChild(img);
                    };
                    img.onerror = () => {}; // Ignora se uma thumb não carregar
                });
            }

            // ----- 3. CRIAR BOTÕES DE TAMANHO (Dinâmico) -----
            tamanhosContainer.innerHTML = ''; // Limpa os botões falsos
            
            if (estoqueDisponivel.length > 0) {
                let primeiroAtivo = false;
                estoqueDisponivel.forEach((item) => {
                    const btn = document.createElement('button');
                    btn.className = 'tamanho-btn';
                    btn.textContent = item.tamanho;
                    btn.dataset.preco = item.preco; 
                    
                    if (item.quantidade <= 0) {
                        btn.disabled = true; 
                        btn.title = "Fora de estoque";
                    }

                    // Define o primeiro item disponível como ativo
                    if (!primeiroAtivo && item.quantidade > 0) {
                        btn.classList.add('active');
                        tamanhoSelecionado = item.tamanho;
                        precoSelecionado = item.preco;
                        precoEl.textContent = 'R$ ' + parseFloat(precoSelecionado).toFixed(2).replace('.', ',');
                        primeiroAtivo = true;
                    }
                    
                    btn.addEventListener('click', () => {
                        if (btn.disabled) return;
                        
                        document.querySelectorAll('.tamanho-btn').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        
                        tamanhoSelecionado = btn.textContent;
                        precoSelecionado = btn.dataset.preco;
                        precoEl.textContent = 'R$ ' + parseFloat(precoSelecionado).toFixed(2).replace('.', ',');
                    });
                    
                    tamanhosContainer.appendChild(btn);
                });

                // Adiciona o botão do carrinho
                const btnCarrinho = document.createElement('button');
                btnCarrinho.className = 'botao-carrinho-integrado';
                btnCarrinho.setAttribute('aria-label', 'Adicionar ao carrinho');
                btnCarrinho.innerHTML = '<i class="fas fa-shopping-cart"></i>';
                tamanhosContainer.appendChild(btnCarrinho);
                
                btnCarrinho.addEventListener('click', () => {
                    if (!checarSelecao()) return;
                    adicionarAoCarrinho(produto.id, tamanhoSelecionado);
                    alert(`${produto.nome} (Tamanho ${tamanhoSelecionado}) foi adicionado ao carrinho!`);
                });

            } else {
                tamanhosContainer.innerHTML = '<p>Produto indisponível.</p>';
                btnComprar.disabled = true;
                btnComprar.textContent = "Indisponível";
            }
            
            // ----- 4. LÓGICA DOS BOTÕES DE COMPRA -----
            function checarSelecao() {
                if (!tamanhoSelecionado) {
                    alert('Por favor, selecione um tamanho.');
                    return false;
                }
                return true;
            }

            btnComprar.addEventListener('click', () => {
                if (!checarSelecao()) return;
                adicionarAoCarrinho(produto.id, tamanhoSelecionado);
                window.location.href = '<?php echo BASE_URL; ?>/src/pages/carrinho/carrinho.php';
            });
        });

        // Função helper
        function adicionarAoCarrinho(id, tamanho) {
            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
            // Salva o ID e o TAMANHO
            carrinho.push({ id: id, tamanho: tamanho });
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
        }
    </script>

        <?php include '../../../libras/libras.php'; ?>

</body>
</html>