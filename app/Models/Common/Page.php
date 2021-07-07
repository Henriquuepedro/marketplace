<?php
namespace App\Models\Common;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Page extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'pages';

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
        'id', 'title', 'content', 'meta_description', 'meta_keywords', 'slug', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'title'            => 'bail|required|string|max:127',
        'content'          => 'bail|required|string',
        'meta_description' => 'nullable|string|max:255',
        'meta_keywords'    => 'nullable|string|max:255',
        'slug'             => 'required|string|max:127',
        'status'           => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'               => 'bail|required|integer|exists:pages',
        'title'            => 'bail|required|string|max:127',
        'content'          => 'bail|required|string',
        'meta_description' => 'nullable|string|max:255',
        'meta_keywords'    => 'nullable|string|max:255',
        'slug'             => 'required|string|max:127',
        'status'           => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
