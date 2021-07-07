<?php
namespace App\Models\Location;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Address extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'addresses';

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
        'id', 'alias', 'type', 'address', 'number', 'complement', 'neighborhood', 'city',
        'zipcode', 'state_id', 'country_id', 'reference', 'latitude', 'longitude', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'alias'        => 'nullable|string|max:63',
        'type'         => 'bail|required|in:billing,shipping',
        'address'      => 'bail|required|string|max:255',
        'number'       => 'bail|required|string|max:63',
        'complement'   => 'nullable|string|max:127',
        'neighborhood' => 'nullable|string|max:127',
        'city'         => 'bail|required|string|max:127',
        'zipcode'      => 'bail|required|string|max:9',
        'state_id'     => 'bail|required|integer|exists:states,id',
        'country_id'   => 'bail|required|integer|exists:countries,id',
        'reference'    => 'nullable|string|max:255',
        'latitude'     => 'nullable|numeric',
        'longitude'    => 'nullable|numeric',
        'status'       => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'           => 'bail|required|integer|exists:addresses',
        'alias'        => 'nullable|string|max:63',
        'type'         => 'bail|required|in:billing,shipping',
        'address'      => 'bail|required|string|max:255',
        'number'       => 'bail|required|string|max:63',
        'complement'   => 'nullable|string|max:127',
        'neighborhood' => 'nullable|string|max:127',
        'city'         => 'bail|required|string|max:127',
        'zipcode'      => 'bail|required|string|max:9',
        'state_id'     => 'bail|required|integer|exists:states,id',
        'country_id'   => 'bail|required|integer|exists:countries,id',
        'reference'    => 'nullable|string|max:255',
        'latitude'     => 'nullable|numeric',
        'longitude'    => 'nullable|numeric',
        'status'       => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the State associated with this Address
     *
     * @return \App\Models\Location\State
     */
    public function state()
    {
        return $this->hasOne('App\Models\Location\State', 'id', 'state_id');
    }

    /**
     * Returns the Country associated with this Address
     *
     * @return \App\Models\Location\Country
     */
    public function country()
    {
        return $this->hasOne('App\Models\Location\Country', 'id', 'country_id');
    }

    // ACESSORS ==============================================================
    /**
     * Returns the Address and Number attributes joined
     * @param string $value
     * @return string
     */
    public function getAddrNumAttribute( $value )
    {
        return $this->address . ', ' . $this->number;
    }

    // STATIC METHODS =========================================================
}
