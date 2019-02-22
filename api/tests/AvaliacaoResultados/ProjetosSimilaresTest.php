<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ProjetosSimilaresTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->json(
            'GET',
            '/avaliacao-resultados/projetos-similares/134261'
        )->seeJson([
            'idpronac' => 134261,
        ]);
    }
}
