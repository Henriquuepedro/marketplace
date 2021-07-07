<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class ProductImage extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'product_images';

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
    protected $fillable = [ 'id', 'product_id', 'image_id', 'position' ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'product_id' => 'required|integer|exists:products,id',
        'image_id'   => 'required|integer|exists:images,id',
        'position'   => 'required|integer'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'         => 'bail|required|integer|exists:product_images',
        'product_id' => 'required|integer|exists:products,id',
        'image_id'   => 'required|integer|exists:images,id',
        'position'   => 'required|integer'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
