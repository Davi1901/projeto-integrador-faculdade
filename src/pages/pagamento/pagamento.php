<?php require_once '../../../db_config.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DU.HYPE - Pagamento</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/src/pages/pagamento/pagamento.css">
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

    <main class="container">
        <h1>Método de Pagamento</h1>
        <div class="payment-content-wrapper">
            <section class="payment-method">
                <div class="payment-tabs">
                    <button class="tab-btn active" data-tab="cartao"><i class="fa-solid fa-credit-card"></i><span>Cartão</span></button>
                    <button class="tab-btn" data-tab="pix"><i class="fa-brands fa-pix"></i><span>Pix</span></button>
                    <button class="tab-btn" data-tab="boleto"><i class="fa-solid fa-barcode"></i><span>Boleto</span></button>
                </div>
                
                <div class="payment-details">
                    <div id="cartao" class="tab-pane active">
                        <form class="card-form">
                            <div class="input-group">
                                <input type="text" id="card-number" placeholder="1234 1234 1234 1234" required>
                                <div class="card-icons">
                                    <i class="fa-brands fa-cc-visa"></i>
                                    <i class="fa-brands fa-cc-mastercard"></i>
                                    <i class="fa-brands fa-cc-amex"></i>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="input-group">
                                    <input type="text" id="expiry-date" placeholder="MM / YY" required>
                                </div>
                                <div class="input-group">
                                    <input type="text" id="cvc" placeholder="CVC" required>
                                </div>
                            </div>
                            <div class="input-group">
                                <select id="country" required>
                                    <option value="brasil" selected>Brasil</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div id="pix" class="tab-pane">
                        <div class="pix-info">
                            <p>Para pagar com Pix, aponte a câmera do seu celular para o QR Code que será gerado após finalizar a compra.</p>
                            <i class="fa-solid fa-qrcode fa-5x"></i>
                        </div>
                    </div>

                    <div id="boleto" class="tab-pane">
                        <div class="boleto-info">
                            <p>O boleto bancário será gerado com vencimento para 2 dias úteis.</p>
                            <i class="fa-solid fa-file-invoice fa-5x"></i>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="order-details">
                <div class="details-card">
                    <h3>Endereço de Entrega</h3>
                    <p id="endereco-entrega-texto" style="line-height: 1.6;">Rua Projeto Integrador Senac, 110</p>
                    <button id="btn-calcular-frete" class="btn btn-dark">CALCULAR FRETE</button>
                </div>
                <div class="details-card">
                    <h3>Resumo da Compra</h3>
                    <div id="cart-items-resumo" class="cart-items">
                        </div>
                    <div class="summary-totals">
                        <div class="summary-line"><span>Produtos (<span id="total-itens">0</span>):</span><span id="subtotal-valor">R$0,00</span></div>
                        <div class="summary-line"><span>Frete:</span><span id="valor-frete">R$0,00</span></div>
                        
                        <div class="summary-line" style="margin-top: 15px; border-top: 1px solid #dee2e6; padding-top: 15px;">
                            <span style="font-weight: 700; font-size: 1.2rem;">Total:</span>
                            <span id="valor-total-geral" style="font-weight: 700; font-size: 1.2rem;">R$0,00</span>
                        </div>
                    </div>
                    <div class="coupon-input"><input type="text" placeholder="CUPOM: DIGITE SEU CUPOM"></div>
                    
                    <form id="form-pagamento" action="<?php echo BASE_URL; ?>/src/pages/pagamento/processar_compra.php" method="POST">
                        <input type="hidden" name="carrinho_data" id="carrinho-data-input">
                        <input type="hidden" name="metodo_pagamento" id="metodo-pagamento-input" value="cartao">
                        <button type="submit" class="btn btn-dark" id="btn-finalizar-compra">FINALIZAR COMPRA</button>
                    </form>
                </div>
            </aside>
        </div>
    </main>

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
        document.addEventListener('DOMContentLoaded', () => {
            // --- VARIÁVEIS GLOBAIS ---
            let valorSubtotalGlobal = 0; // Armazena o valor dos produtos
            let valorFreteGlobal = 0;    // Armazena o valor do frete

            // --- LÓGICA DE ENDEREÇO ---
            const enderecoEl = document.getElementById('endereco-entrega-texto');
            const btnCalcularFrete = document.getElementById('btn-calcular-frete');
            const valorFreteEl = document.getElementById('valor-frete');
            const valorTotalGeralEl = document.getElementById('valor-total-geral');
            
            const enderecoSalvo = JSON.parse(localStorage.getItem('endereco_usuario'));

            if (enderecoSalvo && enderecoEl) {
                enderecoEl.innerHTML = `
                    <strong>${enderecoSalvo.rua}, ${enderecoSalvo.numero}</strong><br>
                    ${enderecoSalvo.bairro} - ${enderecoSalvo.cidade}/${enderecoSalvo.uf}<br>
                    CEP: ${enderecoSalvo.cep}
                `;
            }

            // 2. Função para Atualizar o Total Geral
            function atualizarTotalGeral() {
                const totalFinal = valorSubtotalGlobal + valorFreteGlobal;
                valorTotalGeralEl.innerText = 'R$ ' + totalFinal.toFixed(2).replace('.', ',');
            }

            // 3. Lógica do Botão Calcular Frete
            if (btnCalcularFrete) {
                btnCalcularFrete.addEventListener('click', function() {
                    if (!enderecoSalvo) {
                        alert("Por favor, cadastre um endereço no carrinho primeiro.");
                        return;
                    }

                    // Efeito de carregando
                    btnCalcularFrete.innerText = "CALCULANDO...";
                    btnCalcularFrete.disabled = true;
                    btnCalcularFrete.style.opacity = "0.7";

                    setTimeout(() => {
                        // Simulação de cálculo baseada no Estado
                        let freteCalculado = 25.90; 
                        if (enderecoSalvo.uf) {
                            const uf = enderecoSalvo.uf.toUpperCase();
                            if (['SP', 'RJ'].includes(uf)) freteCalculado = 14.90;
                            else if (['MG', 'ES', 'PR', 'SC', 'RS'].includes(uf)) freteCalculado = 22.50;
                            else freteCalculado = 38.90;
                        }

                        // Atualiza variável global e visual
                        valorFreteGlobal = freteCalculado;
                        valorFreteEl.innerText = 'R$ ' + freteCalculado.toFixed(2).replace('.', ',');
                        valorFreteEl.style.color = "#28a745";
                        valorFreteEl.style.fontWeight = "bold";

                        // Chama a soma do total
                        atualizarTotalGeral();

                        btnCalcularFrete.innerText = "FRETE CALCULADO";
                        btnCalcularFrete.style.backgroundColor = "#28a745"; 
                        btnCalcularFrete.style.border = "none";
                        btnCalcularFrete.style.opacity = "1";

                    }, 1200);
                });
            }

            // --- RESTANTE DO CÓDIGO (TABS E CARRINHO) ---
            const tabs = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');
            const containerResumo = document.getElementById('cart-items-resumo');
            const totalItensEl = document.getElementById('total-itens');
            const subtotalEl = document.getElementById('subtotal-valor');
            
            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    document.getElementById(tab.dataset.tab).classList.add('active');
                    document.getElementById('metodo-pagamento-input').value = tab.dataset.tab;
                });
            });

            function renderizarResumo() {
                containerResumo.innerHTML = '';
                let subtotal = 0;

                if(carrinho.length === 0) {
                    containerResumo.innerHTML = '<p>Seu carrinho está vazio.</p>';
                }

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

                    containerResumo.innerHTML += `
                        <div class="cart-item">
                            <img src="${produtoInfo.imagem_principal}" alt="${produtoInfo.nome}">
                            <div class="item-info">
                                <p>${produtoInfo.nome}</p>
                                <span class="item-size">${item.tamanho}</span>
                            </div>
                            <div class="item-price">
                                <p>${precoFormatado}</p>
                                <a href="#" class="btn-remover" data-index="${index}">Remover</a>
                            </div>
                        </div>
                    `;
                });

                // Atualiza o subtotal e a variável global
                valorSubtotalGlobal = subtotal;
                totalItensEl.textContent = carrinho.length;
                subtotalEl.textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
                
                // Atualiza o total geral inicial (sem frete ainda)
                atualizarTotalGeral();
            }

            containerResumo.addEventListener('click', function(e) {
                if(e.target.classList.contains('btn-remover')) {
                    e.preventDefault();
                    const index = e.target.dataset.index;
                    removerItem(index);
                }
            });
            
            function removerItem(index, suprimirRender = false) {
                carrinho.splice(index, 1);
                localStorage.setItem('carrinho', JSON.stringify(carrinho));
                if (!suprimirRender) {
                    renderizarResumo();
                }
            }

            const btnFinalizar = document.getElementById('btn-finalizar-compra');
            btnFinalizar.addEventListener('click', function(event) {
                event.preventDefault(); 
                let carrinhoItens = JSON.parse(localStorage.getItem('carrinho')) || [];
                if (carrinhoItens.length === 0) {
                    alert('Seu carrinho está vazio!');
                    return;
                }
                document.getElementById('carrinho-data-input').value = JSON.stringify(carrinhoItens);
                localStorage.removeItem('carrinho');
                document.getElementById('form-pagamento').submit();
            });

            renderizarResumo();
        });
    </script>

    <?php include '../../../libras/libras.php'; ?>

</body>
</html>