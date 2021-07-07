<?php
namespace App\Models\Auth;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class UserAddress extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'user_addresses';

    /**
     * The primary key field name
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [ 'id', 'user_id', 'address_id' ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'user_id'    => 'bail|required|integer|exists:users,id',
        'address_id' => 'bail|required|integer|exists:addresses,id',
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:user_addresses',
        'user_id'    => 'bail|required|integer|exists:users,id',
        'address_id' => 'bail|required|integer|exists:addresses,id',
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
