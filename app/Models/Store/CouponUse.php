<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use App\Models\Store\Product;
use OwenIt\Auditing\Contracts\Auditable;

class CouponUse extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'coupon_uses';

    /**
     * The primary key field name
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'coupon_id', 'user_id', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'coupon_id' => 'bail|required|integer|exists:coupons,id',
        'user_id'   => 'bail|required|integer|exists:users,id',
        'status'    => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'       => 'bail|required|integer|exists:coupon_uses',
        'coupon_id' => 'bail|required|integer|exists:coupons,id',
        'user_id'   => 'bail|required|integer|exists:users,id',
        'status'    => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // PUBLIC METHODS =========================================================

    // STATIC METHODS =========================================================
}
