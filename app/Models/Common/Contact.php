<?php
namespace App\Models\Common;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Contact extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'contacts';

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
        'id', 'user_id', 'name', 'email', 'city', 'state', 'message'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'user_id' => 'nullable|integer',
        'name'    => 'bail|required|string|max:127',
        'email'   => 'bail|required|string|max:127',
        'city'    => 'bail|required|string|max:127',
        'state'   => 'bail|required|string',
        'message' => 'bail|required|string'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'      => 'bail|required|integer|exists:contacts',
        'user_id' => 'nullable|integer',
        'name'    => 'bail|required|string|max:127',
        'email'   => 'bail|required|string|max:127',
        'city'    => 'bail|required|string|max:127',
        'state'   => 'bail|required|string',
        'message' => 'bail|required|string'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
