<?php

namespace App\Diligencia\Consulta;

use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL;

use App\Diligencia\Modelo\Diligencia as diligenciaModelo;

final class Diligencia extends Query
{

    protected $attributes = [
        'name' => 'Diligencia',
        'description' => 'Diligancia do Projeto'
    ];

    public function type() : ObjectType
    {
        return GraphQL::type('Diligencia');
    }

    public function args()
    {
        return [
            'idDiligencia' => [
                'type' => Type::int()
            ],

        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $diligencia = diligenciaModelo::find($args['idDiligencia']);

        return $diligencia;
    }
}
