<?php
namespace App\Models\Location;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Phone extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'phones';

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
        'id', 'alias', 'ddi_code', 'area_code', 'number', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'alias'     => 'nullable|string|max:127',
        'ddi_code'  => 'bail|required|integer|max:999',
        'area_code' => 'bail|required|integer|max:999',
        'number'    => 'bail|required|string|max:20',
        'status'    => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'        => 'bail|required|integer|exists:phones',
        'alias'     => 'nullable|string|max:127',
        'ddi_code'  => 'bail|required|integer|max:999',
        'area_code' => 'bail|required|integer|max:999',
        'number'    => 'bail|required|string|max:20',
        'status'    => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
