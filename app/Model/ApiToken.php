<?php

namespace App\Model;

use Auth;
use Carbon\Carbon;
use Hash;
use Log;
use Token;

/**
 * App\Model\ApiToken
 *
 * @property int $id
 * @property string $token
 * @property string $type
 * @property int $claimed
 * @property string|null $claimed_at
 * @property int $is_blacklisted
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Model\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereClaimed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereClaimedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereIsBlacklisted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Model\ApiToken whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 */
class ApiToken extends BaseModel
{
    #region variables/keys
    protected $dates = ['deleted_at', 'claimed_at'];
    public static $resetPassword = 'reset_password';
    public static $confirmEmail = 'confirm_email';
    #endregion

    #region attributes
    public function setTokenAttribute($value)
    {
        if ($value === null) {
            $this->attributes['token'] = null;
        } else {
            Log::debug('last generated token : ' . $value);
            $this->attributes['token'] = Hash::make($value);
        }
    }
    #endregion

    #region relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    #endregion

    #region methods
    public static function generate($type, $user = null, $shouldBlacklistLastToken = true)
    {
        $user = $user ?? Auth::user();
        $token = Token::random(40);

        if ($shouldBlacklistLastToken) {
            $lastToken = $user->apiTokens
                ->where('type', '=', ApiToken::$confirmEmail)
                ->where('claimed', '=', false)
                ->where('is_blacklisted', '=', false)
                ->sortByDesc('updated_at')
                ->first();
            if ($lastToken !== null) {
                $lastToken->update(['is_blacklisted' => true]);
            }
        }

        $user->apiTokens()->create([
            'type' => $type,
            'token' => $token
        ]);

        return $token;
    }

    public function validate($inputToken)
    {
        if (!$this->is_blacklisted && !$this->claimed && Hash::check($inputToken, $this->token))
            return true;

        return false;
    }

    public function claim()
    {
        $this->update([
            'claimed' => true,
            'claimed_at' => Carbon::now()
        ]);
        $this->delete();
    }

    public function validateAndClaim($inputToken)
    {
        if ($this->validate($inputToken)) {
            $this->claim();
            return true;
        }

        return false;
    }
    #endregion
}
