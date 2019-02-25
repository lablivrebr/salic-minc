<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['middleware' => 'jwt'], function () use ($router) {
    $router->get(
        'user',
        [
            'uses' => 'AuthController@user'
        ]
    );
});

$router->get('/check', function () use ($router) {
    echo '<pre> teste';
    $results = DB::select("SELECT top 1 * FROM controledeacesso.dbo.sgcacesso");
    dd($results);
});

$router->get(
    '/avaliacao-resultados/historico',
    ['uses' => 'HistoricoController@index']
);


$router->group(
    [
        'prefix' => 'avaliacao-resultados',
        'namespace' => '\App\AvaliacaoResultados\Http\Controllers'
    ],
    function() use ($router) {
        $router->get(
            'historico/{idpronac}',
            ['uses' => 'HistoricoController@index']
        );

        $router->get(
            'projetos-similares/{idpronac}',
            ['uses' => 'ProjetosSimilaresController@index']
        );
    }
);

