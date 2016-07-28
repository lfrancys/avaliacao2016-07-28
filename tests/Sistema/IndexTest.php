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
    Ao acessar a rota sistema.index
    Espero:
        Ver um formulário de login
        Ver o campo para informar o email
        Ver o campo para informar a senha
     */
    public function testVisualizaFormulario(){
        try{

            $this->get(route('sistema.index'));
            $this->assertResponseOk();
            $this->seeElement('.form');
            $this->seeInElement('.form', 'email');
            $this->seeInElement('.form', 'password');

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }

   /*
    Atuando como: um usuário logado
	ao acessar a rota sistema.index
	Espero:
		Ser redirecionado para produto.index
   */
    public function testAcessoLogado(){
        try{

            $user = factory(\App\User::class)->create();
            $this->actingAs($user)->get(route('sistema.index'));
            $this->assertRedirectedToRoute('produto.index');

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }


}
