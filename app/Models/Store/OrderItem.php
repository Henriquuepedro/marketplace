<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class OrderItem extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'order_items';

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
    protected $fillable = [
        'id', 'order_id', 'product_id', 'name', 'quantity', 'unit_price', 'total_price',
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'order_id'    => 'bail|required|integer|exists:orders,id',
        'product_id'  => 'bail|required|integer|exists:products,id',
        'name'        => 'bail|required|string|max:200',
        'quantity'    => 'bail|required|integer|between:1,999',
        'unit_price'  => 'bail|required|numeric',
        'total_price' => 'bail|required|numeric',
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'          => 'bail|required|integer|exists:order_items',
        'order_id'    => 'bail|required|integer|exists:orders,id',
        'product_id'  => 'bail|required|integer|exists:products,id',
        'name'        => 'bail|required|string|max:200',
        'quantity'    => 'bail|required|integer|between:1,999',
        'unit_price'  => 'bail|required|numeric',
        'total_price' => 'bail|required|numeric',
    ];

    // PUBLIC METHODS =========================================================

    // RELATIONSHIPS ==========================================================
    public function product()
    {
        return $this->hasOne('App\Models\Store\Product', 'id', 'product_id');
    }

    // STATIC METHODS =========================================================
}
