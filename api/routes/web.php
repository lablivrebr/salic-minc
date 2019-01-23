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

/* $router->get('/v1/graphql', function () use ($router) { */
/* ini_set('display_errors', true); */
/* error_reporting(E_ALL ^E_NOTICE); */
/* echo '<pre>'; */
/* $results = DB::select("SELECT top 1 * FROM controledeacesso.dbo.sgcacesso"); */
/*     print_r($results);die; */
/*     $results = $router->app('db')->select("SELECT * FROM controledeacesso.dbo.sgcacesso"); */
/* echo '<pre>';print_r($results);die; */
/*     return $router->app->version(); */
/* }); */
