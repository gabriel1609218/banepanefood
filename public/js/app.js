function moeda(valor){return Number(valor||0).toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
}
function lerCarrinho(){try{return JSON.parse(localStorage.getItem('carrinho')||'[]');
}catch{return [];
}}
function salvarCarrinho(c){localStorage.setItem('carrinho',JSON.stringify(c));
atualizarContador();
}
function atualizarContador(){const el=document.querySelector('#cartCount');
if(el)el.textContent=String(lerCarrinho().reduce((total,item)=>total+Number(item.quantidade||0),0));
}
function adicionarCarrinho(produto){const c=lerCarrinho();
const atual=c.find(i=>i.id===produto.id);
if(atual)atual.quantidade+=1;
else c.push({...produto,quantidade:1});
salvarCarrinho(c);
alert('Produto adicionado ao carrinho!');
}
document.addEventListener('DOMContentLoaded',atualizarContador);
