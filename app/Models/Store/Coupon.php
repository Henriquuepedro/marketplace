<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use App\Models\Store\Product;
use OwenIt\Auditing\Contracts\Auditable;

use App\Models\Store\CouponProduct;

class Coupon extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'coupons';

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
        'store_id', 'code', 'description', 'usage_limit', 'limit', 'products_on_sale',
        'discount_type', 'discount_value', 'min_order_value', 'include_shipping', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'store_id'         => 'bail|required|integer|exists:stores,id',
        'code'             => 'bail|required|string|max:20',
        'description'      => 'nullable|string|max:127',
        'usage_limit'      => 'bail|required|string|in:one_per_customer,many_times,limited_times',
        'limit'            => 'nullable|integer',
        'products_on_sale' => 'bail|required|string|in:include,not_include',
        'discount_type'    => 'bail|required|string|in:none,products_amount,total_amount,shipping_amount,products_percent,total_percent,shipping_pecent',
        'discount_value'   => 'bail|required|numeric|max:999999999.99',
        'min_order_value'  => 'nullable|numeric|max:999999999.99',
        'include_shipping' => 'bail|required|string|in:yes,no',
        'status'           => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'               => 'bail|required|integer|exists:coupons',
        'store_id'         => 'bail|required|integer|exists:stores,id',
        'code'             => 'bail|required|string|max:20',
        'description'      => 'nullable|string|max:127',
        'usage_limit'      => 'bail|required|string|in:one_per_customer,many_times,limited_times',
        'limit'            => 'nullable|integer',
        'products_on_sale' => 'bail|required|string|in:include,not_include',
        'discount_type'    => 'bail|required|string|in:none,products_amount,total_amount,shipping_amount,products_percent,total_percent,shipping_pecent',
        'discount_value'   => 'bail|required|numeric|max:999999999.99',
        'min_order_value'  => 'nullable|numeric|max:999999999.99',
        'include_shipping' => 'bail|required|string|in:yes,no',
        'status'           => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // PUBLIC METHODS =========================================================
    public function products()
    {
        $fields = 'products.*';
        $items  = Product::join('coupon_products', 'products.id', '=', 'coupon_products.product_id')
                ->where('coupon_products.coupon_id', '=', $this->id)
                ->selectRaw( $fields )
                ->get();

        return $items;
    }

    // STATIC METHODS =========================================================
}
