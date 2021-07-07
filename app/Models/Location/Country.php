<?php
namespace App\Models\Location;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Country extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'countries';

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
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'code_a3', 'code_n3', 'region', 'name', 'latitude', 'longitude', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'code'      => 'bail|required|string|max:4',
        'code_a3'   => 'nullable|string|max:5',
        'code_n3'   => 'nullable|string|max:5',
        'region'    => 'nullable|string|max:3',
        'name'      => 'bail|required|string|max:127',
        'latitude'  => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'status'    => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'        => 'bail|required|integer|exists:countries',
        'code'      => 'bail|required|string|max:4',
        'code_a3'   => 'nullable|string|max:5',
        'code_n3'   => 'nullable|string|max:5',
        'region'    => 'nullable|string|max:3',
        'name'      => 'bail|required|string|max:127',
        'latitude'  => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'status'    => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
