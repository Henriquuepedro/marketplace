<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class StoreRating extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'store_ratings';

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
        'id', 'store_id', 'user_id', 'service', 'products', 'shipping', 'after_sales', 'average', 'review', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'store_id'    => 'required|integer|exists:stores,id',
        'user_id'     => 'required|integer|exists:users,id',
        'service'     => 'required|integer',
        'products'    => 'required|integer',
        'shipping'    => 'required|integer',
        'after_sales' => 'required|integer',
        'average'     => 'required|integer',
        'review'      => 'nullable|string|max:500',
        'status'      => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'          => 'bail|required|integer|exists:store_ratings',
        'store_id'    => 'required|integer|exists:stores,id',
        'user_id'     => 'required|integer|exists:users,id',
        'service'     => 'required|integer',
        'products'    => 'required|integer',
        'shipping'    => 'required|integer',
        'after_sales' => 'required|integer',
        'average'     => 'required|integer',
        'review'      => 'nullable|string|max:500',
        'status'      => 'string|in:active,inactive,deleted'
    ];

    // PUBLIC METHODS =========================================================

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the User that makes this rating
     *
     * @return \App\Models\Auth\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }

    // STATIC METHODS =========================================================
}
