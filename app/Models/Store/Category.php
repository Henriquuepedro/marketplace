<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;
use Kalnoy\Nestedset\NodeTrait;

class Category extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use NodeTrait;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'categories';

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
        'id', 'name', 'slug', 'description', 'cover_id', '_lft', '_rgt', 'parent_id', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'name'        => 'bail|required|string|between:6,127',
        'slug'        => 'bail|required|string|between:6,255',
        'description' => 'nullable',
        'cover_id'    => 'nullable',
        '_lft'        => 'nullable',
        '_rgt'        => 'nullable',
        'parent_id'   => 'nullable',
        'status'      => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'          => 'bail|required|integer|exists:categories',
        'name'        => 'bail|required|string|between:6,127',
        'slug'        => 'bail|required|string|between:6,255',
        'description' => 'nullable',
        'cover_id'    => 'nullable',
        '_lft'        => 'nullable',
        '_rgt'        => 'nullable',
        'parent_id'   => 'nullable',
        'status'      => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Cover Image associated with this Category
     *
     * @return \App\Models\Common\Image
     */
    public function cover()
    {
        return $this->hasOne('App\Models\Common\Image', 'id', 'cover_id');
    }

    // STATIC METHODS =========================================================
}
