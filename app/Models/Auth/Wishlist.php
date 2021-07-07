<?php
namespace App\Models\Auth;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Wishlist extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'wishlists';

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
        'id', 'user_id', 'product_id', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'user_id'    => 'bail|required|integer|exists:users,id',
        'product_id' => 'bail|required|integer|exists:products,id',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:wishlists',
        'user_id'    => 'bail|required|integer|exists:users,id',
        'product_id' => 'bail|required|integer|exists:products,id',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
