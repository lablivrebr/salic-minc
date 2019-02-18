<?php
/**
 * Created by IntelliJ IDEA.
 * User: voltAir
 * Date: 18/02/19
 * Time: 17:00
 */

namespace App\Diligencia\Consulta;

use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

final class Diligencia extends Query
{

    protected $attributes = [
        'name' => 'Diligencia',
        'description' => 'Diligancia do Projeto'
    ];

    public function type()
    {
        return GraphQL::type('Diligencia');
    }

    public function args()
    {
        return [
            'idPronac' => [
                'type' => Type::id()
            ],

        ];
    }
}
