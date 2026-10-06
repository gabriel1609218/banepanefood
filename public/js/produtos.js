const lista = document.querySelector('#listaProdutos');
const busca = document.querySelector('#busca');
async function carregarProdutos() {
if (!lista) return;
lista.innerHTML = '<div class="text-center">Carregando...</div>';
try {
const termo = encodeURIComponent(busca ? busca.value.trim() : '');
const resposta = await fetch(`../api/produtos.php?busca=${termo}&limite=50`);
if (!resposta.ok) throw new Error('Não foi possível carregar os produtos.');
const produtos = await resposta.json();
lista.innerHTML = '';
if (produtos.length === 0) {
lista.innerHTML = '<div class="alert alert-info">Nenhum produto encontrado.</div>';
return;
}
produtos.forEach((p) => {
const col = document.createElement('div');
col.className = 'col-md-4';
const card = document.createElement('div');
card.className = 'card h-100 shadow-sm';
const img = document.createElement('img');
img.className = 'card-img-top produto-img';
img.src = `../${p.imagem}`;
img.alt = p.nome;
const body = document.createElement('div');
body.className = 'card-body d-flex flex-column';
const h = document.createElement('h4');
h.textContent = p.nome;
const d = document.createElement('p');
d.textContent = p.descricao;
const cat = document.createElement('small');
cat.textContent = `Categoria: ${p.categoria}`;
const price = document.createElement('p');
price.className = 'preco text-warning mt-auto';
price.textContent = moeda(p.preco);
const button = document.createElement('button');
button.className = 'btn btn-warning w-100';
button.textContent = p.estoque > 0 ? 'Adicionar ao carrinho' : 'Sem estoque';
button.disabled = p.estoque <= 0;
button.addEventListener('click', () => adicionarCarrinho(p));
body.append(h, d, cat, price, button);
card.append(img, body);
col.append(card);
lista.append(col);
});
} catch (e) {
lista.innerHTML = `<div class="alert alert-danger">${e instanceof Error ? e.message : 'Erro inesperado.'}</div>`;
}
}
document.querySelector('#btnBuscar')?.addEventListener('click', carregarProdutos);
busca?.addEventListener('keydown', (e) => {
if (e.key === 'Enter') carregarProdutos();
});
carregarProdutos();
