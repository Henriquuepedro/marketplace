<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class ProductCategory extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'product_categories';

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
    protected $fillable = [ 'id', 'product_id', 'category_id' ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'product_id'  => 'required|integer|exists:products,id',
        'category_id' => 'required|integer|exists:categories,id'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'          => 'bail|required|integer|exists:product_categories',
        'product_id'  => 'required|integer|exists:products,id',
        'category_id' => 'required|integer|exists:categories,id'
    ];

    // RELATIONSHIPS ==========================================================

    // STATIC METHODS =========================================================
}
