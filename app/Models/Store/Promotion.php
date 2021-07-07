<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use App\Models\Store\Product;
use OwenIt\Auditing\Contracts\Auditable;

class Promotion extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'promotions';

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
        'id', 'store_id', 'product_id', 'price', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'store_id'   => 'bail|required|integer|exists:stores,id',
        'product_id' => 'bail|required|integer|exists:products,id',
        'price'      => 'bail|required|numeric',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:promotions',
        'store_id'   => 'bail|required|integer|exists:stores,id',
        'product_id' => 'bail|required|integer|exists:products,id',
        'price'      => 'bail|required|numeric',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    public function product()
    {
        return $this->hasOne('App\Models\Store\Product', 'id', 'product_id');
    }

    // PUBLIC METHODS =========================================================

    // STATIC METHODS =========================================================
}
