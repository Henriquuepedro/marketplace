<?php
namespace App\Models\Common;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Menu extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'menus';

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
        'id', 'name', 'position', 'type', 'order', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'name'     => 'bail|required|string|max:127',
        'position' => 'bail|required|string|in:header,footer',
        'type'     => 'bail|required|string|in:items,categories',
        'order'    => 'bail|required|integer',
        'status'   => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'       => 'bail|required|integer|exists:menus',
        'name'     => 'bail|required|string|max:127',
        'position' => 'bail|required|string|in:header,footer',
        'type'     => 'bail|required|string|in:items,categories',
        'order'    => 'bail|required|integer',
        'status'   => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the items of this Menu
     *
     * @return App\Models\Common\MenuItem
     */
    public function items()
    {
        return $this->hasMany('App\Models\Common\MenuItem', 'menu_id');
    }

    // STATIC METHODS =========================================================
}
