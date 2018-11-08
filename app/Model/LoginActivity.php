<?php

namespace App\Model;

/**
 * App\Model\LoginActivity
 *
 * @property int $id
 * @property string $user_agent
 * @property string $ip_address
 * @property string $login_at
 * @property string|null $logout_at
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read \App\Model\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereLogoutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\LoginActivity whereUserId($value)
 * @mixin \Eloquent
 */
class LoginActivity extends BaseModel
{
    protected $dates = ['deleted_at', 'login_at', 'logout_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
