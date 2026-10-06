"use strict";
function numeroSeguro(valor) {
    const numero = Number(valor);
    return Number.isFinite(numero) ? numero : 0;
}
function textoSeguro(valor) {
    return typeof valor === 'string' ? valor : String(valor ?? '');
}
function moedaDashboard(valor) {
    return numeroSeguro(valor).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}
async function buscarDashboard() {
    try {
        const resposta = await fetch('../api/dashboard.php');
        if (!resposta.ok) {
            throw new Error(`Erro HTTP ${resposta.status}`);
        }
        const dados = await resposta.json();
        if (!dados || typeof dados !== 'object') {
            throw new Error('Resposta inválida da API.');
        }
        const objeto = dados;
        const resumoBruto = objeto.resumo;
        if (!resumoBruto || typeof resumoBruto !== 'object') {
            throw new Error('Resumo do dashboard não encontrado.');
        }
        const resumo = resumoBruto;
        const estoque = Array.isArray(objeto.estoque)
            ? objeto.estoque
            : [];
        const diario = Array.isArray(objeto.diario)
            ? objeto.diario
            : [];
        const ranking = Array.isArray(objeto.ranking)
            ? objeto.ranking
            : [];
        return {
            resumo: {
                pedidos: numeroSeguro(resumo.pedidos),
                faturamento: numeroSeguro(resumo.faturamento),
                clientes: numeroSeguro(resumo.clientes),
                produtos: numeroSeguro(resumo.produtos)
            },
            estoque,
            diario,
            ranking
        };
    }
    catch (erro) {
        const mensagem = erro instanceof Error
            ? erro.message
            : 'Não foi possível carregar o dashboard.';
        throw new Error(mensagem);
    }
}
function calcularIndicadores(dados) {
    // reduce(): soma os dados diários para demonstrar uma agregação financeira.
    const faturamentoCalculado = dados.diario.reduce((total, venda) => total + numeroSeguro(venda.faturamento), 0);
    const pedidosCalculados = dados.diario.reduce((total, venda) => total + numeroSeguro(venda.pedidos), 0);
    // filter(): separa produtos com estoque crítico para uma regra de negócio.
    const estoqueCritico = dados.estoque.filter((produto) => numeroSeguro(produto.estoque) <= 10);
    // sort(): cria o ranking pelo número de unidades vendidas.
    const rankingOrdenado = [...dados.ranking].sort((a, b) => numeroSeguro(b.quantidade_vendida) - numeroSeguro(a.quantidade_vendida));
    const produtoMaisVendido = rankingOrdenado[0]
        ? textoSeguro(rankingOrdenado[0].nome)
        : 'Nenhum produto vendido';
    return {
        faturamentoTotal: dados.resumo.faturamento > 0
            ? dados.resumo.faturamento
            : faturamentoCalculado,
        pedidosTotal: dados.resumo.pedidos > 0
            ? dados.resumo.pedidos
            : pedidosCalculados,
        produtoMaisVendido,
        estoqueCritico
    };
}
function criarLinha(nome, valor) {
    const linha = document.createElement('li');
    linha.className = 'list-group-item d-flex justify-content-between align-items-center';
    const nomeElemento = document.createElement('span');
    nomeElemento.textContent = nome;
    const valorElemento = document.createElement('strong');
    valorElemento.textContent = valor;
    linha.append(nomeElemento, valorElemento);
    return linha;
}
function preencherIndicadores(dados, indicadores) {
    const faturamento = document.querySelector('#totalFaturamento');
    const pedidos = document.querySelector('#totalPedidos');
    const clientes = document.querySelector('#totalClientes');
    const produtos = document.querySelector('#totalProdutos');
    const maisVendido = document.querySelector('#produtoMaisVendido');
    if (faturamento) {
        faturamento.textContent = moedaDashboard(indicadores.faturamentoTotal);
    }
    if (pedidos) {
        pedidos.textContent = String(indicadores.pedidosTotal);
    }
    if (clientes) {
        clientes.textContent = String(dados.resumo.clientes);
    }
    if (produtos) {
        produtos.textContent = String(dados.resumo.produtos);
    }
    if (maisVendido) {
        maisVendido.textContent = indicadores.produtoMaisVendido;
    }
    const ranking = document.querySelector('#ranking');
    if (ranking) {
        ranking.replaceChildren();
        if (dados.ranking.length === 0) {
            const vazio = document.createElement('p');
            vazio.className = 'text-muted mb-0';
            vazio.textContent = 'Nenhuma venda registrada.';
            ranking.append(vazio);
        }
        else {
            const lista = document.createElement('ul');
            lista.className = 'list-group list-group-flush';
            const rankingOrdenado = [...dados.ranking].sort((a, b) => numeroSeguro(b.quantidade_vendida) - numeroSeguro(a.quantidade_vendida));
            rankingOrdenado.forEach((produto) => {
                lista.append(criarLinha(textoSeguro(produto.nome), `${numeroSeguro(produto.quantidade_vendida)} un.`));
            });
            ranking.append(lista);
        }
    }
    const estoque = document.querySelector('#estoque');
    if (estoque) {
        estoque.replaceChildren();
        if (dados.estoque.length === 0) {
            const vazio = document.createElement('p');
            vazio.className = 'text-muted mb-0';
            vazio.textContent = 'Nenhum produto em estoque.';
            estoque.append(vazio);
        }
        else {
            const lista = document.createElement('ul');
            lista.className = 'list-group list-group-flush';
            // map(): transforma cada registro do banco em um elemento visual.
            const linhas = dados.estoque.map((produto) => criarLinha(`${textoSeguro(produto.nome)} (${textoSeguro(produto.categoria)})`, `${numeroSeguro(produto.estoque)} un.`));
            lista.append(...linhas);
            estoque.append(lista);
        }
    }
    const alerta = document.querySelector('#estoqueCritico');
    if (alerta) {
        alerta.textContent = indicadores.estoqueCritico.length > 0
            ? `${indicadores.estoqueCritico.length} produto(s) com estoque crítico (até 10 unidades).`
            : 'Nenhum produto com estoque crítico.';
        alerta.className = indicadores.estoqueCritico.length > 0
            ? 'alert alert-warning mt-3 mb-0'
            : 'alert alert-success mt-3 mb-0';
    }
}
async function iniciarDashboard() {
    try {
        const dados = await buscarDashboard();
        const indicadores = calcularIndicadores(dados);
        preencherIndicadores(dados, indicadores);
    }
    catch (erro) {
        const mensagem = erro instanceof Error
            ? erro.message
            : 'Não foi possível carregar os dados.';
        const area = document.querySelector('#dashboardErro');
        if (area) {
            area.textContent = mensagem;
            area.className = 'alert alert-danger mt-3';
        }
        else {
            console.error(mensagem);
        }
    }
}
document.addEventListener('DOMContentLoaded', () => {
    void iniciarDashboard();
});
