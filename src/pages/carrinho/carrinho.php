<?php require_once '../../../db_config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - DU.HYPE</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/assets/styles/styles.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/pages/carrinho/style.css"> 
</head>
<body>
    
    <div class="header-container">
        <header class="header">
            <div class="logo">
                <a href="<?php echo BASE_URL; ?>/home.php">
                    <img src="<?php echo BASE_URL; ?>/src/assets/Images/LogoDu.Hype.svg" alt="Logo DUHYPE">
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

    <main>
        <div class="carrinho-page-container">
            <div class="carrinho-itens-lista">
                <h1 class="titulo-secao">Seu Carrinho</h1>
                
                <div id="lista-de-itens"></div>
                
                <div id="carrinho-vazio" style="display: none;">
                    <p>Seu carrinho está vazio.</p>
                </div>
            </div>

            <div id="coluna-resumo" class="carrinho-resumo-coluna">
                
                <div class="endereco-box box-sombra">
                    <h2 class="titulo-secao">Endereço de Entrega</h2>
                    <p id="texto-endereco" style="line-height: 1.5; margin-bottom: 15px; font-size: 14px; color: #555;">Endereço não cadastrado</p>
                    <button id="btn-abrir-modal" class="btn-preto">CADASTRAR ENDEREÇO</button>
                </div>

                <div class="resumo-compra-box box-sombra">
                    <h2 class="titulo-secao">Resumo da Compra</h2>
                    <div id="lista-resumo-pequena"></div>
                    
                    <div class="resumo-linha">
                        <span>Produtos (<span id="total-itens">0</span>):</span>
                        <span id="subtotal-valor">R$ 0,00</span>
                    </div>
                    <div class="resumo-linha">
                        <span>Frete:</span>
                        <span>R$ 0,00</span>
                    </div>
                    
                    <input id="cupom-input" type="text" placeholder="DIGITE SEU CUPOM">
                    <a href="<?php echo BASE_URL; ?>/src/pages/pagamento/pagamento.php" class="btn-preto" style="text-align: center; text-decoration: none; display: block; padding: 12px 0;">ESCOLHER MÉTODO DE PAGAMENTO</a>
                </div>
            </div>
        </div>
    </main>

    <div id="modal-endereco" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Endereço de Entrega</h3>
                <span class="close-modal">&times;</span>
            </div>
            <form id="form-endereco">
                <div class="form-group">
                    <label>CEP</label>
                    <input type="text" id="cep" placeholder="00000-000" maxlength="9" required>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label>Rua</label>
                        <input type="text" id="rua" required>
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Número</label>
                        <input type="text" id="numero" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Bairro</label>
                    <input type="text" id="bairro" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" id="cidade" required>
                    </div>
                    <div class="form-group" style="flex: 0.5;">
                        <label>UF</label>
                        <input type="text" id="uf" maxlength="2" required>
                    </div>
                </div>
                <button type="submit" class="btn-preto" style="margin-top: 15px;">SALVAR ENDEREÇO</button>
            </form>
        </div>
    </div>

    <script>
        <?php
            $stmt_produtos = $pdo->query("SELECT id, nome, marca, imagem, descricao FROM produtos");
            $produtos_lista = $stmt_produtos->fetchAll(PDO::FETCH_ASSOC);
            
            $stmt_estoque = $pdo->query("SELECT produto_id, tamanho, preco FROM estoque_tamanhos");
            $estoque_lista = $stmt_estoque->fetchAll(PDO::FETCH_ASSOC);

            $produtos_js = [];
            foreach ($produtos_lista as $produto) {
                $produtos_js[$produto['id']] = [
                    'id' => (int)$produto['id'],
                    'marca' => $produto['marca'],
                    'nome' => $produto['nome'],
                    'imagem_principal' => BASE_URL . $produto['imagem'],
                    'descricao' => $produto['descricao']
                ];
            }
            
            $estoque_js = [];
            foreach ($estoque_lista as $item) {
                if (!isset($estoque_js[$item['produto_id']])) {
                    $estoque_js[$item['produto_id']] = [];
                }
                $estoque_js[$item['produto_id']][$item['tamanho']] = $item['preco'];
            }

            echo "const produtosDB = " . json_encode($produtos_js) . ";\n";
            echo "const estoqueDB = " . json_encode($estoque_js) . ";\n";
        ?>
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- LÓGICA DO CARRINHO ---
            const containerItens = document.getElementById('lista-de-itens');
            const containerResumo = document.getElementById('lista-resumo-pequena');
            const carrinhoVazioEl = document.getElementById('carrinho-vazio');
            const colunaResumoEl = document.getElementById('coluna-resumo');
            const totalItensEl = document.getElementById('total-itens');
            const subtotalEl = document.getElementById('subtotal-valor');

            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

            function renderizarCarrinho() {
                containerItens.innerHTML = '';
                containerResumo.innerHTML = '';
                
                if (carrinho.length === 0) {
                    carrinhoVazioEl.style.display = 'block';
                    colunaResumoEl.style.display = 'none';
                    containerItens.style.display = 'none';
                    return;
                }
                
                carrinhoVazioEl.style.display = 'none';
                colunaResumoEl.style.display = 'block';
                containerItens.style.display = 'block';

                let subtotal = 0;

                carrinho.forEach((item, index) => {
                    const produtoInfo = produtosDB[item.id];
                    const precoTamanho = estoqueDB[item.id] ? estoqueDB[item.id][item.tamanho] : null;

                    if (!produtoInfo || !precoTamanho) {
                        removerItem(index, true); 
                        return;
                    }

                    const precoFloat = parseFloat(precoTamanho);
                    subtotal += precoFloat;
                    const precoFormatado = 'R$ ' + precoFloat.toFixed(2).replace('.', ',');

                    containerItens.innerHTML += `
                        <div class="box-sombra item-carrinho-principal">
                            <img src="${produtoInfo.imagem_principal}" alt="${produtoInfo.nome}" class="item-imagem">
                            <div class="item-detalhes">
                                <span class="item-marca">${produtoInfo.marca}</span>
                                <p class="item-nome">${produtoInfo.nome}</p>
                                <div class="item-tamanho-box">
                                    Tamanho: <span>${item.tamanho}</span>
                                </div>
                                <p class="item-preco">${precoFormatado}</p>
                                <button class="item-remover" data-index="${index}">Remover</button>
                            </div>
                        </div>
                    `;

                    containerResumo.innerHTML += `
                        <div class="resumo-linha" style="align-items: center; gap: 10px;">
                            <img src="${produtoInfo.imagem_principal}" alt="" style="width: 40px; height: 40px; border-radius: 5px; object-fit: cover;">
                            <span style="flex: 1; font-size: 0.9em;">${produtoInfo.nome} (T: ${item.tamanho})</span>
                            <span style="font-size: 0.9em;">${precoFormatado}</span>
                        </div>
                    `;
                });

                totalItensEl.textContent = carrinho.length;
                subtotalEl.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
            }

            function removerItem(index, suprimirRender = false) {
                carrinho.splice(index, 1);
                localStorage.setItem('carrinho', JSON.stringify(carrinho));
                if (!suprimirRender) {
                    renderizarCarrinho();
                }
            }

            containerItens.addEventListener('click', function(e) {
                if (e.target.classList.contains('item-remover')) {
                    const index = e.target.getAttribute('data-index');
                    removerItem(index);
                }
            });

            renderizarCarrinho();

            // --- LÓGICA DO ENDEREÇO (MODAL + VIACEP) ---
            const modal = document.getElementById('modal-endereco');
            const btnAbrir = document.getElementById('btn-abrir-modal');
            const btnFechar = document.querySelector('.close-modal');
            const formEndereco = document.getElementById('form-endereco');
            const textoEndereco = document.getElementById('texto-endereco');
            const cepInput = document.getElementById('cep');

            // 1. Carregar endereço salvo
            const enderecoSalvo = JSON.parse(localStorage.getItem('endereco_usuario'));
            if (enderecoSalvo) {
                atualizarVisualizacao(enderecoSalvo);
            }

            // 2. Abrir/Fechar Modal
            btnAbrir.addEventListener('click', () => modal.style.display = 'flex');
            btnFechar.addEventListener('click', () => modal.style.display = 'none');
            window.addEventListener('click', (e) => {
                if (e.target == modal) modal.style.display = 'none';
            });

            // 3. API de CEP
            cepInput.addEventListener('blur', async () => {
                let cep = cepInput.value.replace(/\D/g, '');
                if (cep.length === 8) {
                    try {
                        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                        const data = await response.json();
                        if (!data.erro) {
                            document.getElementById('rua').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('uf').value = data.uf;
                            document.getElementById('numero').focus();
                        }
                    } catch (error) {
                        console.error('Erro ao buscar CEP');
                    }
                }
            });

            // 4. Salvar Endereço
            formEndereco.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const endereco = {
                    cep: document.getElementById('cep').value,
                    rua: document.getElementById('rua').value,
                    numero: document.getElementById('numero').value,
                    bairro: document.getElementById('bairro').value,
                    cidade: document.getElementById('cidade').value,
                    uf: document.getElementById('uf').value
                };

                localStorage.setItem('endereco_usuario', JSON.stringify(endereco));
                atualizarVisualizacao(endereco);
                modal.style.display = 'none';
            });

            function atualizarVisualizacao(end) {
                textoEndereco.innerHTML = `
                    <strong>${end.rua}, ${end.numero}</strong><br>
                    ${end.bairro} - ${end.cidade}/${end.uf}<br>
                    CEP: ${end.cep}
                `;
                btnAbrir.textContent = "ALTERAR ENDEREÇO";
                btnAbrir.style.backgroundColor = "white";
                btnAbrir.style.color = "#1C1C1C";
                btnAbrir.style.border = "1px solid #1C1C1C";
            }
        });
    </script>

    <?php include '../../../libras/libras.php'; ?>
</body>
</html>