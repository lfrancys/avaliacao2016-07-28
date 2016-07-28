<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    /*
        Atuando como: um usuário logado
        Ao:
            Acessar a rota produto.show de um produto aleatório cujo productSpecial = 1
        Espero:
            Ver o nome do produto
            Ver a foto do produto
            Ver a descrição do produto
            Não ver o id do produto
     */
    public function testVisualizarProduto(){

        try{

            $user = factory(\App\User::class)->create(['password' => bcrypt('12345')]);

            $produto = \App\Entities\Product::create([
                'productName'           => str_random(5),
                'productPrice'          => rand(100,200),
                'productPhoto'          => str_random(5).'jpg',
                'productDescription'    => str_random(10),
                'productStock'          => random_int(0,100),
                'productSpecial'        => 1
            ]);

            $this->actingAs($user)->get(route('produto.show', $produto->id));
            $this->assertResponseOk();
            $this->seeText($produto->productName);
            $this->see($produto->productPhoto);
            $this->seeText($produto->productDescription);

        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }
    /*
        Atuando como: um usuário deslogado
        Ao:
        Acessar a rota produto.show de um produto aleatório cujo productSpecial = 1
        Espero:
        Ser recebido com um erro 403 (forbidden)
    */
    public function testVisualizar403(){

        try {
            $produto = \App\Entities\Product::create([
                'productName' => str_random(5),
                'productPrice' => rand(100, 200),
                'productPhoto' => str_random(5) . 'jpg',
                'productDescription' => str_random(10),
                'productStock' => random_int(0, 100),
                'productSpecial' => 1
            ]);

            $this->get(route('produto.show', $produto->id));
            $this->assertEquals(403, $this->response->getStatusCode());
        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }

    /*
        Atuando como um usuário deslogado
        Ao:
            Acessar a rota produto.show de um produto inexistente no banco de dados
        Espero:
            Ser recebido com um erro 404 (not found)
    */
    public function testVisualizar404(){

        try {
            $produto = \App\Entities\Product::create([
                'productName' => str_random(5),
                'productPrice' => rand(100, 200),
                'productPhoto' => str_random(5) . 'jpg',
                'productDescription' => str_random(10),
                'productStock' => random_int(0, 100),
                'productSpecial' => 1
            ]);

            $this->get(route('produto.show', ($produto->id + 1)));
            $this->assertEquals(404, $this->response->getStatusCode());
        }catch (\Exception $e){
            $this->assertTrue(false, "Exception: {$e->getMessage()} on file {$e->getFile()}, line nº {$e->getLine()}");
        }
    }


}
