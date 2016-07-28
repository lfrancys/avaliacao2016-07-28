<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnitTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;


    /*
    public function testInserindoProdutoViaIndex(){
        try{


        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }
    */

    /*
        Atuando como um usuário logado
        Ao:
            Solicitar ao laravel uma instancia de um CarrinhoService
            Adicionar 3 produtos aleatórios no carrinho cujo stockProduct > 0, utilizando o método add();
        Espero:
            Ver no banco de dados na tabela chart os 3 produtos ligados ao usuário com o qual estou atuando
     */
    public function testeComUsuarioLogado(){
        try{
            $user = factory(\App\User::class)->create();

            $produtos = \App\Entities\Product::where('productStock', '>', 0)->take(3)->get();
            $carrinhoService = app(\App\Services\CarrinhoService::class);

            $this->actingAs($user)->get(route('produto.index'));
            foreach ($produtos as $produto) {
                $carrinhoService->add($produto);
                $this->seeInDatabase('chart', ['userId' => $user->id, 'productId' => $produto->id]);
            }

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }

    /*
        Atuando como um usuário deslogado
        Ao:
            Solicitar ao laravel uma instancia de um CarrinhoService
            Adicionar 3 produtos aleatórios no carrinho cujo stockProduct > 0, utilizando o método add();
        Espero:
            Ver na session a variavel 'charts'
    */
    public function testeSemUsuarioLogado(){
        try{

            $produtos = \App\Entities\Product::where('productStock', '>', 0)->where('productSpecial', '=', 0)->take(3)->get();
            $carrinhoService = app(\App\Services\CarrinhoService::class);

            $this->get(route('produto.index'));
            foreach ($produtos as $produto) {
                $carrinhoService->add($produto);
                $this->seeInSession('charts');
            }

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }

    /*
        Ao:
            Solicitar ao laravel uma instancia de um CarrinhoService
            Adicionar 3 produtos aleatórios no carrinho cujo stockProduct > 0, utilizando o método add();
        Espero:
            Ter uma collection com 3 ítens como retorno do método getItems();
            Para cada ítem da collection:
                O ítem deve ser uma instancia de um produto
                O id deve estar de acordo com os produtos adicionados
    */
    /*public function testComEstoque(){
        try{
            $user = factory(\App\User::class)->create();
            $produtos = \App\Entities\Product::where('productStock', '>', 0)->where('productSpecial', '=', 0)->take(3)->get();
            $carrinhoService = app(\App\Services\CarrinhoService::class);


            $this->actingAs($user)->get(route('produto.index'));
            foreach ($produtos as $produto) {
                $carrinhoService->add($produtos);
            }
            $produtoCollection = $carrinhoService->getItems();
            foreach ($produtoCollection as $item) {
                //$this->assertInternalType('instance', $item);
            }


        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }*/

    /*
        Ao:
            Solicitar ao laravel uma instancia de um CarrinhoService
            Adicionar 1 produto cujo stockProduct <= 0, utilizando o método add();
        Espero:
            Uma exceção CarrinhoException ser lançada
     */
    /*public function testEstoqueZerado(){
        //try{
            $user = factory(\App\User::class)->create();

            $produto = \App\Entities\Product::first();
            \App\Entities\Product::find($produto->id)->update(['productStock' => 0]);
            $produto = \App\Entities\Product::find($produto->id)->first();

            $carrinhoService = app(\App\Services\CarrinhoService::class);

            $this->actingAs($user)->get(route('produto.index'));
            $carrinhoService->add($produto);

            echo $this->response->getStatusCode();

            //$this->assertEquals('App\Exceptions\CarrinhoException: Produto sem estoque no momento!', $this->response->getContent());

        /*}catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }*/
    //}
}
