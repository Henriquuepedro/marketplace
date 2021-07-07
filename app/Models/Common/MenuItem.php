<?php
namespace App\Models\Common;

use App\Models\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use OwenIt\Auditing\Contracts\Auditable;

class MenuItem extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use NodeTrait;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'menu_items';

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
        'id', 'menu_id', 'name', 'target', 'page_id', 'url', '_lft', '_rgt', 'parent_id', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'menu_id'   => 'bail|required|integer|exists:menus,id',
        'name'      => 'bail|required|string|max:60',
        'target'    => 'bail|required|string|in:page,url,external',
        'page_id'   => 'bail|required_if:target,page',
        'url'       => 'bail|required_if:target,external',
        '_lft'      => 'nullable',
        '_rgt'      => 'nullable',
        'parent_id' => 'nullable',
        'status'    => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'        => 'bail|required|integer|exists:menu_items',
        'menu_id'   => 'bail|required|integer|exists:menus,id',
        'name'      => 'bail|required|string|max:60',
        'target'    => 'bail|required|string|in:page,url,external',
        'page_id'   => 'bail|required_if:target,page',
        'url'       => 'bail|required_if:target,external',
        '_lft'      => 'nullable',
        '_rgt'      => 'nullable',
        'parent_id' => 'nullable',
        'status'    => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Menu that owns this Item
     *
     * @return App\Models\Common\Menu
     */
    public function menu()
    {
        return $this->belongsTo('App\Models\Common\Menu', 'menu_id');
    }

    // STATIC METHODS =========================================================
}
