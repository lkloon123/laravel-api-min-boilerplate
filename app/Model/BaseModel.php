<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 10/18/2018
 * Time: 3:22 PM
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * App\Model\BaseModel
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\BaseModel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\BaseModel withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Model\BaseModel withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 */
class BaseModel extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    protected $hidden = ['pivot'];
}