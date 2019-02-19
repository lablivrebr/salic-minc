<?php
/**
 * Created by IntelliJ IDEA.
 * User: leonardo
 * Date: 19/02/19
 * Time: 15:09
 */

namespace App\Diligencia\Consulta;


use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ListOfType;
use GraphQL;
use Illuminate\Database\Eloquent\Collection;

use App\Diligencia\Modelo\Diligencia as diligenciaModelo;

class Diligencias extends Query
{

    protected $attributes = [
        'name' => 'Diligencia',
        'description' => 'Lista de diligancias por Projeto'
    ];

    public function type() : ListOfType
    {
        return Type::listOf(GraphQL::type('Diligencia'));
    }

    public function args() : array
    {
        return [
            'idPronac' => [
                'type' => Type::int()
            ],
            'idDiligencia' => [
                'type' => Type::int()
            ],

        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info) : Collection
    {
        $consulta = diligenciaModelo::query();

        if (isset($args['idPronac'])) {
            $consulta->where('idPronac', $args['idPronac']);
        }
        if (isset($args['idDiligencia'])) {
            $consulta->where('idDiligencia', $args['idDiligencia']);
        }

        return $consulta->get();
    }

}
