<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PutTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;
    /*
        Atuando como: um usuário logado
        Ao:
            Acessar a rota produto.index
            Clicar no botão Adicionar de 3 produtos aleatórios que aparecem nessa página
        Espero:
            Para cada clique, um redirect para a mesma página
            Ver a mensagem: Produto adicionado ao carrinho
    */
    public function testInserindoProdutoViaIndex(){
        try{

            $user = factory(\App\User::class)->create();

            /*$response = $this->actingAs($user)->get(route('produto.index'));
            echo $response->response->getContent();

            +Carrinho

            */
            /*$this->actingAs($user)->visit(route('produto.index'))
                ->press('+Carrinho')
                ->assertRedirectedToRoute('produto.index');

            , 'Produto adicionado ao carrinho'*/

            $response = $this->actingAs($user)->get(route('produto.index'));
            $responseContent = $response->response->getContent();


            foreach ($responseContent as $resp){
                $resp->press('+Carrinho');
            }



        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }
}
