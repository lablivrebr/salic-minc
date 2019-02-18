<?php
/**
 * Created by IntelliJ IDEA.
 * User: voltAir
 * Date: 18/02/19
 * Time: 16:55
 */

namespace App\Diligencia\Tipo;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;

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
                'type' => Type::id(),
                'description' => 'Identificador Projeto.'
            ],
        ];
    }
}
