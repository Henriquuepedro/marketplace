<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class CartItem extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'cart_items';

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
    protected $fillable = [ 'id', 'cart_id', 'store_id', 'product_id', 'quantity', 'shipping' ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'cart_id'    => 'required|integer|exists:carts,id',
        'store_id'   => 'required|integer|exists:stores,id',
        'product_id' => 'required|integer|exists:products,id',
        'quantity'   => 'required|integer',
        'shipping'   => 'nullable'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:cart_items',
        'cart_id'    => 'required|integer|exists:carts,id',
        'store_id'   => 'required|integer|exists:stores,id',
        'product_id' => 'required|integer|exists:products,id',
        'quantity'   => 'required|integer',
        'shipping'   => 'nullable'
    ];

    // PUBLIC METHODS =========================================================

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Cart the owns this Item
     *
     * @return \App\Models\Store\Cart
     */
    public function cart()
    {
        return $this->belongsTo('App\Models\Store\Cart', 'cart_id', 'id');
    }

    // STATIC METHODS =========================================================
}
