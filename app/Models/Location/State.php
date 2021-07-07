<?php
namespace App\Models\Location;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class State extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'states';

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
        'id', 'country_id', 'code', 'name', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'country_id' => 'bail|required|integer|exists:countries,id',
        'code'       => 'bail|required|string|max:4',
        'name'       => 'bail|required|string|max:127',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:states',
        'country_id' => 'bail|required|integer|exists:countries,id',
        'code'       => 'bail|required|string|max:4',
        'name'       => 'bail|required|string|max:127',
        'status'     => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Country associated with this State
     *
     * @return \App\Models\Location\Country
     */
    public function country()
    {
        return $this->hasOne('App\Models\Location\Country', 'id', 'country_id');
    }

    // STATIC METHODS =========================================================
}
