<?php
/**
 * Created by IntelliJ IDEA.
 * User: voltAir
 * Date: 18/02/19
 * Time: 16:34
 */

namespace App\Diligencia\Modelo;

use Illuminate\Database\Eloquent\Model;

final class Diligencia extends Model
{
    protected $table = 'SAC.dbo.tbDiligencia';
    protected $primaryKey = 'idPronac';
}
