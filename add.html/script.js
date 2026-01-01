

// ----- Helpers -----
const LS_KEY = "kicks_estoque_v1";

// Recupera do localStorage ou inicializa com exemplos
function carregarDados() {
    const raw = localStorage.getItem(LS_KEY);
    if (raw) return JSON.parse(raw);
    
    // Produtos iniciais com mais variedade de imagens
    const iniciais = [
        { id: id(), nome:"Adidas Ultra Boost", descricao:"Tênis confortável para corrida.", categoria:"sneakers", marca:"Adidas", quantidade:12, preco:899.90, imagem:"https://i.imgur.com/t4pE2fN.png" },
        { id: id(), nome:"Nike Air Zoom", descricao:"Velocidade e estabilidade.", categoria:"corrida", marca:"Nike", quantidade:8, preco:749.99, imagem:"https://i.imgur.com/gKPRf9y.png" },
        { id: id(), nome:"Puma Future", descricao:"Controle e precisão.", categoria:"futebol", marca:"Puma", quantidade:15, preco:679.90, imagem:"https://i.imgur.com/OdZzYfT.png" },
        { id: id(), nome:"Asics Gel Nimbus", descricao:"Amortecimento premium.", categoria:"corrida", marca:"Asics", quantidade:6, preco:820.50, imagem:"https://i.imgur.com/4gX5g8b.png" },
        { id: id(), nome:"Under Armour Flow", descricao:"Leve e responsivo.", categoria:"basquete", marca:"Under Armour", quantidade:9, preco:710.00, imagem:"https://i.imgur.com/M6L5L3L.png" },
        { id: id(), nome:"Nike Air Force 1", descricao:"Clássico casual.", categoria:"sneakers", marca:"Nike", quantidade:20, preco:699.90, imagem:"https://i.imgur.com/jE1Y5t7.png" }
    ];
    localStorage.setItem(LS_KEY, JSON.stringify(iniciais));
    return iniciais;
}

function id() { return '_' + Math.random().toString(36).substr(2, 9); }
function salvarDados(lista) { localStorage.setItem(LS_KEY, JSON.stringify(lista)); }

// ----- Elementos -----
const form = document.getElementById('form-produto');
const listaElm = document.getElementById('lista-produtos');
const buscar = document.getElementById('buscar');
const filtroBtns = document.querySelectorAll('.filter-tags .tag');
const modoBtn = document.getElementById('modo-btn');

let produtos = carregarDados();
let editandoId = null;
let filtroAtual = 'todos';

// ----- Renderização (MODIFICADA PARA O NOVO LAYOUT) -----
function renderizar(lista) {
    listaElm.innerHTML = '';
    if (lista.length === 0) {
        listaElm.innerHTML = '<p>Nenhum produto encontrado.</p>';
        return;
    }
    lista.forEach(p => {
        const item = document.createElement('div');
        item.className = 'product-item';
        item.innerHTML = `
            <img class="product-image" src="${p.imagem || 'https://via.placeholder.com/150?text=Sem+Img'}" alt="${p.nome}">
            <div class="product-info">
                <h4>${p.nome}</h4>
                <p>${p.marca} • ${categoriaTexto(p.categoria)}</p>
                <p><strong>Quantidade:</strong> ${p.quantidade} • <strong>Preço:</strong> R$ ${p.preco.toFixed(2)}</p>
            </div>
            <div class="product-actions">
                <button class="btn btn-edit" data-id="${p.id}"><i class='bx bx-pencil'></i> Editar</button>
                <button class="btn btn-delete" data-id="${p.id}"><i class='bx bx-trash'></i> Deletar</button>
            </div>
        `;
        listaElm.appendChild(item);
    });

    // Adicionar eventos dinâmicos (lógica original)
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.currentTarget.dataset.id;
            if (confirm('Tem certeza que deseja deletar este produto?')) {
                produtos = produtos.filter(x => x.id !== id);
                salvarDados(produtos);
                aplicarFiltros();
            }
        });
    });

    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.currentTarget.dataset.id;
            carregarParaEdicao(id);
        });
    });
}

function categoriaTexto(cat) {
    const map = { sneakers: 'Casual', corrida: 'Corrida', futebol: 'Futebol', golfe: 'Golfe', basquete: 'Basquete' };
    return map[cat] || cat;
}

// ----- Formulário (LÓGICA ORIGINAL) -----
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const novo = {
        id: editandoId || id(),
        nome: document.getElementById('nome').value.trim(),
        descricao: document.getElementById('descricao').value.trim(),
        categoria: document.getElementById('categoria').value,
        marca: document.getElementById('marca').value.trim(),
        quantidade: Number(document.getElementById('quantidade').value),
        preco: Number(document.getElementById('preco').value) || 0,
        imagem: document.getElementById('imagem').value.trim()
    };

    if (editandoId) {
        produtos = produtos.map(p => p.id === editandoId ? novo : p);
    } else {
        produtos.unshift(novo);
    }
    resetarFormulario();
    salvarDados(produtos);
    aplicarFiltros();
});

document.getElementById('btn-cancelar').addEventListener('click', resetarFormulario);

function resetarFormulario() {
    editandoId = null;
    form.reset();
    document.getElementById('form-title').textContent = 'Adicionar novo tênis';
    document.getElementById('btn-salvar').textContent = 'Adicionar';
    document.getElementById('nome').focus();
}

function carregarParaEdicao(idProd) {
    const p = produtos.find(x => x.id === idProd);
    if (!p) return;
    editandoId = p.id;
    document.getElementById('nome').value = p.nome;
    document.getElementById('descricao').value = p.descricao;
    document.getElementById('categoria').value = p.categoria;
    document.getElementById('marca').value = p.marca;
    document.getElementById('quantidade').value = p.quantidade;
    document.getElementById('preco').value = p.preco;
    document.getElementById('imagem').value = p.imagem || '';
    document.getElementById('form-title').textContent = 'Editar tênis';
    document.getElementById('btn-salvar').textContent = 'Salvar Alterações';
    window.scrollTo(0, 0); 
    document.getElementById('nome').focus();
}

// ----- Busca e filtros (LÓGICA ORIGINAL) -----
buscar.addEventListener('input', aplicarFiltros);
filtroBtns.forEach(b => b.addEventListener('click', (e) => {
    filtroAtual = e.target.dataset.cat;
    filtroBtns.forEach(btn => btn.classList.remove('active'));
    e.target.classList.add('active');
    aplicarFiltros();
}));

function aplicarFiltros() {
    const termo = buscar.value.trim().toLowerCase();
    let lista = produtos.slice();

    if (filtroAtual !== 'todos') {
        lista = lista.filter(p => p.categoria === filtroAtual);
    }

    if (termo) {
        lista = lista.filter(p => p.nome.toLowerCase().includes(termo) || (p.marca && p.marca.toLowerCase().includes(termo)));
    }
    renderizar(lista);
}

// ----- Modo escuro (MODIFICADO PARA O NOVO LAYOUT) -----
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

// ----- Inicialização -----
document.addEventListener('DOMContentLoaded', () => {
    aplicarFiltros();
});