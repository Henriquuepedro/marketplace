<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class VariationOption extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'variation_options';

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
     * The model's default values for attributes.
     * @var array
     */
    protected $attributes = [
        'quantity' => 0,
        'status' => 'active'
    ];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [ 'id', 'variation_id', 'name', 'position', 'code', 'quantity', 'price', 'status' ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'variation_id' => 'required|integer|exists:product_variations,id',
        'name'         => 'required|string|max:50',
        'position'     => 'required|integer',
        'code'         => 'nullable',
        'quantity'     => 'nullable',
        'price'        => 'nullable',
        'status'       => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'           => 'bail|required|integer|exists:variation_options',
        'variation_id' => 'required|integer|exists:product_variations,id',
        'name'         => 'required|string|max:50',
        'position'     => 'required|integer',
        'code'         => 'nullable',
        'quantity'     => 'nullable',
        'price'        => 'nullable',
        'status'       => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
