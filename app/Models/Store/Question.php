<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Question extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'questions';

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
        'id', 'user_id', 'product_id', 'store_id', 'question', 'answer', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'user_id'    => 'required|integer|exists:users,id',
        'product_id' => 'required|integer|exists:products,id',
        'store_id'   => 'required|integer|exists:stores,id',
        'question'   => 'required|string|max:255',
        'answer'     => 'nullable|string|max:255',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:questions',
        //'user_id'    => 'required|integer|exists:users,id',
        //'product_id' => 'required|integer|exists:products,id',
        //'store_id'   => 'required|integer|exists:stores,id',
        //'question'   => 'required|string|max:255',
        'answer'     => 'nullable|string|max:255',
        //'status'     => 'string|in:active,inactive,deleted'
    ];

    // PUBLIC METHODS =========================================================

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the User that makes this Question
     *
     * @return \App\Models\Auth\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    /**
     * Returns the Product belongs to this Question
     *
     * @return \App\Models\Store\Product
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Store\Product', 'product_id', 'id');
    }

    /**
     * Returns the Store related to this Question
     *
     * @return \App\Models\Store\Store
     */
    public function shop()
    {
        return $this->belongsTo('App\Models\Store\Store', 'store_id', 'id');
    }

    // STATIC METHODS =========================================================
}
