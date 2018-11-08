<?php

namespace App\Model;

/**
 * App\Model\UserProfile
 *
 * @property int $id
 * @property string|null $full_name
 * @property string|null $gender
 * @property string|null $birthday
 * @property string|null $mobile
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Model\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\UserProfile whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 */
class UserProfile extends BaseModel
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
