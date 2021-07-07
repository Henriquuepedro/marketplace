<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Cart extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'carts';

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
        'id', 'user_id', 'address_id', 'user_ip', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'user_id'    => 'nullable|integer|exists:users,id',
        'address_id' => 'nullable|integer|exists:addresses,id',
        'user_ip'    => 'required|ip',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:carts',
        'user_id'    => 'nullable|integer|exists:users,id',
        'address_id' => 'nullable|integer|exists:addresses,id',
        'user_ip'    => 'required|ip',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    // PUBLIC METHODS =========================================================
    /**
     * Return Cart Items ordered by Store
     *
     * @return Collection
     */
    public function getItems()
    {
        return $this->items()->orderBy('store_id')->get();
    }

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Items linked with this Cart
     *
     * @return \App\Models\Store\CartItem
     */
    public function items()
    {
        return $this->hasMany('App\Models\Store\CartItem');
    }

    // STATIC METHODS =========================================================
}
