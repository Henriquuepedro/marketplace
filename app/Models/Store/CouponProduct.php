<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use App\Models\Store\Product;
use OwenIt\Auditing\Contracts\Auditable;

class CouponProduct extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'coupon_products';

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
        'coupon_id', 'product_id', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'coupon_id'  => 'bail|required|integer|exists:coupons,id',
        'product_id' => 'bail|required|integer|exists:products,id',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:coupon_products',
        'coupon_id'  => 'bail|required|integer|exists:coupons,id',
        'product_id' => 'bail|required|integer|exists:products,id',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // PUBLIC METHODS =========================================================

    // STATIC METHODS =========================================================
}
