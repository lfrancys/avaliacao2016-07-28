<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreTest extends TestCase
{

    use WithoutMiddleware;
    use DatabaseTransactions;

    /*
	Atuando como: um usuário deslogado
	Ao:
		Acessar a rota sistema.index
		Digitar no campo do email um email inválido
		Digitar no campo da senha uma senha inválida
		Clicar no Logar
	Espero:
		Ser redirecionado para sistema.index
		Ver "Email e senha não conferem"

 */
    public function testLogandoErrado(){
        try{

            $this->visit(route('sistema.index'))
                ->type(str_random('5').'@gmail.com', 'email')
                ->type(random_int(1000, 9999), 'password')
                ->press('Login')
                ->seePageIs(route('sistema.index'))
                ->assertSessionHasErrors();
                //->seeInSession('', "Email e senha não conferem");
                //->seeInElement("Email e senha não conferem");

            //Email e senha não conferem

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }

    /*
        Atuando como: um usuário deslogado
        Ao:
            Acessar a rota sistema.index
            Digitar no campo do email um email válido de um usuário aleatório que seja diferente a cada vez que eu rodar o teste
            Digitar no campo senha, a senha válida de um usuário aleatório que seja diferente a cada vez que eu rodar o teste
            Clicar no Logar
        Espero:
            Ser redirecionado para produto.index
            Ver o nome do usuário com o qual loguei nessa página
     */
    public function testLogandoCerto(){
        try{

            $senha = rand(1000, 9999);
            $user = factory(\App\User::class)->create(['password' => bcrypt($senha)]);

            $this->visit(route('sistema.index'))
                ->type($user->email, 'email')
                ->type($senha, 'password')
                ->press('Login')
                ->seePageIs(route('produto.index'))
                ->see($user->name);

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }
}
