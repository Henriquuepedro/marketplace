<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class ProductVariation extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'product_variations';

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
    protected $fillable = [ 'id', 'store_id', 'product_id', 'name', 'code', 'quantity', 'price', 'status' ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'store_id'   => 'bail|required|integer|exists:stores,id',
        'product_id' => 'bail|required|integer|exists:products,id',
        'name'       => 'bail|required|string|max:127',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:product_variations',
        'store_id'   => 'bail|required|integer|exists:stores,id',
        'product_id' => 'bail|required|integer|exists:products,id',
        'name'       => 'bail|required|string|max:127',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    public function product()
    {
        return $this->belongsTo('App\Models\Store\Product', 'product_id', 'id');
    }

    /**
     * Returns all Options of this Variation
     *
     * @return \App\Models\Store\VariationOption
     */
    public function options()
    {
        return $this->hasMany('App\Models\Store\VariationOption', 'variation_id', 'id');
    }

    // STATIC METHODS =========================================================
}
