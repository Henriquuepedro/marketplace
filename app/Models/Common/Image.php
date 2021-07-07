<?php
namespace App\Models\Common;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Image extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'images';

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
        'id', 'store_id', 'user_id', 'original_name', 'original_extension', 'mime_type',
        'size', 'name', 'path', 'url', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'store_id'           => 'nullable|integer|exists:stores,id',
        'user_id'            => 'bail|required|integer|exists:users,id',
        'original_name'      => 'bail|required|string|max:255',
        'original_extension' => 'bail|required|string|max:7',
        'mime_type'          => 'nullable',
        'size'               => 'nullable',
        'name'               => 'bail|required|string|max:127',
        'path'               => 'bail|required|string|max:255',
        'url'                => 'bail|required|string|max:255',
        'status'             => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'                 => 'bail|required|integer|exists:images',
        'store_id'           => 'nullable|integer|exists:stores,id',
        'user_id'            => 'bail|required|integer|exists:users,id',
        'original_name'      => 'bail|required|string|max:255',
        'original_extension' => 'bail|required|string|max:7',
        'mime_type'          => 'nullable',
        'size'               => 'nullable',
        'name'               => 'bail|required|string|max:127',
        'path'               => 'bail|required|string|max:255',
        'url'                => 'bail|required|string|max:255',
        'status'             => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
