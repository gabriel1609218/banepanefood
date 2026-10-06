interface ItemCarrinho { id:number;
nome:string;
preco:number;
quantidade:number;
}
function totalCarrinho(itens:ItemCarrinho[]):number{return itens.reduce((total,item)=>total+item.preco*item.quantidade,0);
}
function nomesFiltrados(itens:ItemCarrinho[], minimo:number):string[]{return itens.filter(item=>item.quantidade>=minimo).map(item=>item.nome);
}
