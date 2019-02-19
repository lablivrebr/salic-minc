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
                'description' => 'Identificador Projeto'
            ],
            'idDiligencia' => [
                'type' => Type::int(),
                'description' => 'Identificador'
            ],
            'idTipoDiligencia' => [
                'type' => Type::int(),
                'description' => 'Identificador do tipo'
            ],
            'idSolicitante' => [
                'type' => Type::int(),
                'description' => 'Identificador do Solicitante'
            ],
            'idProponente' => [
                'type' => Type::int(),
                'description' => 'Identificador do Proponente da diligencia'
            ],
            'idPlanoDistribuicao' => [
                'type' => Type::int(),
                'description' => 'Identificador do Plano de distribuição do produto'
            ],
            'DtSolicitacao' => [
                'type' => GraphQL::type('DateTimeType'),
                'description' => 'Data da solicitação'
            ],
            'DtResposta' => [
                'type' => GraphQL::type('DateTimeType'),
                'description' => 'Data da resposta'
            ],
            'Solicitacao' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Texto da solicitação'
            ],
            'Resposta' => [
                'type' => Type::string(),
                'description' => 'Texto da resposta da solicitação'
            ],
            'stEstado' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Estado da Diligencia (Boleano)'
            ],

        ];
    }
}
