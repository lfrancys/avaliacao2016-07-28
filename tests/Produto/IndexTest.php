<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /*
        Atuando como: um usuário deslogado
	    Ao acessar a rota produto.index
	    Espero:
            Ver os 9 últimos produtos.
            Para cada um dos 9 produtos espero ver: O nome, preço e foto
            Espero ver links de paginação
    */
    public function testVisualiarProdutos(){

        try{

            $produtos = \App\Entities\Product::orderBy('id', 'desc')->take(9)->get();

            $this->get(route('produto.index'));
            $this->assertResponseOk();
            $this->seeInElement('.pagination', 'a');
            foreach ($produtos as $produto) {
                $this->seeText($produto->productName);
                $this->seeText($produto->productPrice);
                $this->seeElement('img', ['src' => $produto->productPhoto]);
            }

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }

    /*
        Atuando como: um usuário logado
        Ao acessar a rota produto.index
        Espero:
            Ver os 9 últimos produtos cujo productSpecial = 1 ou 0
            Para cada um dos 9 produtos espero ver: O nome, preço e foto
            Nos produtos que productSpecial=1 espero ver a palavra "(especial)" juntamente com a foto do produto
            Espero ver links de paginação
    */
    public function testVisualizarProdutosLogado(){

        try{

            $user = factory(\App\User::class)->create();
            $produtos = \App\Entities\Product::orderBy('id', 'desc')->take(9)->get();

            $this->actingAs($user)->get(route('produto.index'));
            $this->assertResponseOk();
            $this->seeInElement('.pagination', 'a');

            foreach ($produtos as $produto) {
                if($produto->productSpecial == 1)
                    $this->seeText('especial)');

                $this->seeElement('img', ['src' => $produto->productPhoto]);
                $this->seeText($produto->productName);
                $this->seeText($produto->productPrice);
            }

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }
}
