interface Produto { id:number;
categoria_id:number;
nome:string;
descricao:string;
preco:number;
estoque:number;
imagem:string;
categoria:string;
}
async function buscarProdutos(termo:string):Promise<Produto[]> { const r=await fetch(`../api/produtos.php?busca=${encodeURIComponent(termo)}`);
if(!r.ok) throw new Error('Falha na API');
return await r.json() as Produto[];
}
