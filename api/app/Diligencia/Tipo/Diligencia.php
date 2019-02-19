<?php

namespace App\Diligencia\Tipo;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;


final class Diligencia extends BaseType
{
    protected $attributes = [
        'name' => 'Diligencia',
        'description' => 'Diligencias do Projeto por idPronac'
    ];

    public function fields()
    {
        return [
            'idPronac' => [
                'type' => Type::int(),
                'description' => 'Identificador Projeto.'
            ],
            'idDiligencia' => [
                'type' => Type::int(),
                'description' => 'Identificador'
            ],
            'idTipoDiligencia' => [
                'type' => Type::int(),
                'description' => 'Identificador do tipo Diligência'
            ],
            'DtSolicitacao' => [
                'type' => GraphQL::type('DateTimeType'),
                'description' => 'Data da solicitação'
            ],
            'Solicitacao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Texto da solicitação da Diligência'
            ]

        ];
    }
}
