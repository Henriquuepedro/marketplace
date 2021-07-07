<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class OrderOccurrence extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'order_occurrences';

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
        'order_id', 'user_id', 'store_id', 'product_id', 'reason', 'description', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'order_id'    => 'bail|required|integer|exists:orders,id',
        'user_id'     => 'bail|required|integer|exists:users,id',
        'store_id'    => 'bail|required|integer|exists:stores,id',
        'product_id'  => 'bail|required|integer|exists:products,id',
        'reason'      => 'bail|required|string|in:replacement,return,protest,compliment',
        'description' => 'bail|required|string|max:3000',
        'status'      => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'          => 'bail|required|integer|exists:order_occurrences',
        'order_id'    => 'bail|required|integer|exists:orders,id',
        'user_id'     => 'bail|required|integer|exists:users,id',
        'store_id'    => 'bail|required|integer|exists:stores,id',
        'product_id'  => 'bail|required|integer|exists:products,id',
        'reason'      => 'bail|required|string|in:replacement,return,protest,compliment',
        'description' => 'bail|required|string|max:3000',
        'status'      => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Order related to this Occurrence
     *
     * @return App\Models\Store\Order
     */
    public function order()
    {
        return $this->hasOne('App\Models\Store\Order', 'id', 'order_id');
    }

    /**
     * Returns the User associated with this Occurrence
     *
     * @return App\Models\Auth\User
     */
    public function user()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'user_id');
    }

    /**
     * Returns the Store related to this Occurrence
     *
     * @return App\Models\Store\Store
     */
    public function shop()
    {
        return $this->hasOne('App\Models\Store\Store', 'id', 'store_id');
    }

    /**
     * Returns the Product associated with this Occurrence
     *
     * @return App\Models\Store\Product
     */
    public function product()
    {
        return $this->hasOne('App\Models\Store\Product', 'id', 'product_id');
    }

    // PUBLIC METHODS =========================================================

    // STATIC METHODS =========================================================
}
