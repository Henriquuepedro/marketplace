<?php
namespace App\Models\Auth;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class PasswordReset extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * The primary key field name
     * @var string
     */
    protected $primaryKey = 'email';

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'email', 'token', 'created_at', 'expires_at', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'email'      => 'bail|required|email|exists:users,username',
        'token'      => 'bail|required|string|max:127',
        'created_at' => 'bail|required|date_format:Y-m-d',
        'expires_at' => 'bail|required|date_format:Y-m-d',
        'status'     > 'string|in:active,expired'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'email'      => 'bail|required|email|exists:users,username',
        'token'      => 'bail|required|string|max:127',
        'created_at' => 'bail|required|date_format:Y-m-d',
        'expires_at' => 'bail|required|date_format:Y-m-d',
        'status'     > 'string|in:active,expired'
    ];

	// RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
