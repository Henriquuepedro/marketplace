<?php
namespace App\Models\Common;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Bank extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'banks';

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
        'id', 'code', 'name', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'code'   => 'bail|required|string|max:7',
        'name'   => 'bail|required|string|max:127',
        'status' => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'     => 'bail|required|integer|exists:banks',
        'code'   => 'bail|required|string|max:7',
        'name'   => 'bail|required|string|max:127',
        'status' => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
