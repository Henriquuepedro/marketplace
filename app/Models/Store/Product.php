<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Store\Promotion;

class Product extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'products';

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
        'id', 'store_id', 'name', 'code', 'description', 'meta_description', 'meta_keywords',
        'slug', 'featured', 'new', 'new_until',
        'eco_friendly', 'eco_friendly_accepted', 'cruelty_free', 'cruelty_free_accepted',
        'free_shipping', 'quantity',
        'width', 'height', 'length', 'weight', 'price', 'old_price', 'tax', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'store_id'              => 'required|integer|exists:stores,id',
        'name'                  => 'required|string|max:127',
        'code'                  => 'nullable|string|max:127',
        'description'           => 'required|string',
        'meta_description'      => 'nullable|string|max:255',
        'meta_keywords'         => 'nullable|string|max:255',
        'slug'                  => 'required|string|max:127',
        'featured'              => 'required|string|in:yes,no',
        'new'                   => 'required|string|in:yes,no',
        //'new_until'             => 'nullable|required_if:new,yes',
        'new_until'             => 'nullable',
        'eco_friendly'          => 'required|string|in:yes,no',
        'eco_friendly_accepted' => 'accepted_if:eco_friendly,yes',
        'cruelty_free'          => 'required|string|in:yes,no',
        'cruelty_free_accepted' => 'accepted_if:cruelty_free,yes',
        'free_shipping'         => 'required|string|in:yes,no',
        'quantity'              => 'required|integer',
        'width'                 => 'required|numeric',
        'height'                => 'required|numeric',
        'length'                => 'required|numeric',
        'weight'                => 'required|numeric',
        'price'                 => 'required|numeric',
        'old_price'             => 'nullable|numeric',
        'tax'                   => 'nullable|numeric',
        'status'                => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'                    => 'bail|required|integer|exists:products',
        'store_id'              => 'required|integer|exists:stores,id',
        'name'                  => 'required|string|max:127',
        'code'                  => 'nullable|string|max:127',
        'description'           => 'required|string',
        'meta_description'      => 'nullable|string|max:255',
        'meta_keywords'         => 'nullable|string|max:255',
        'slug'                  => 'required|string|max:127',
        'featured'              => 'required|string|in:yes,no',
        'new'                   => 'required|string|in:yes,no',
        //'new_until'             => 'nullable|required_if:new,yes',
        'new_until'             => 'nullable',
        'eco_friendly'          => 'required|string|in:yes,no',
        'eco_friendly_accepted' => 'accepted_if:eco_friendly,yes',
        'cruelty_free'          => 'required|string|in:yes,no',
        'cruelty_free_accepted' => 'accepted_if:cruelty_free,yes',
        'free_shipping'         => 'required|string|in:yes,no',
        'quantity'              => 'required|integer',
        'width'                 => 'required|numeric',
        'height'                => 'required|numeric',
        'length'                => 'required|numeric',
        'weight'                => 'required|numeric',
        'price'                 => 'required|numeric',
        'old_price'             => 'nullable|numeric',
        'tax'                   => 'nullable|numeric',
        'status'                => 'string|in:active,inactive,deleted'
    ];

    // PUBLIC METHODS =========================================================
    /**
     * Return the correct product price
     *
     * @return float
     */
    public function price()
    {
        // Check if this Product is in promotion
        $promo = Promotion::where('product_id', '=', $this->id)->where('status', '=', Promotion::STATUS_ACTIVE)->first();

        if( $promo )
            return $promo->price;

        return $this->price;
    }

    /**
     * Indicates if the Product is Featured
     *
     * @return boolean
     */
    public function isFeatured()
    {
        return ($this->featured === 'yes') ? true : false;
    }

    /**
     * Indicates if the Product is New
     *
     * @return boolean
     */
    public function isNew()
    {
        return ($this->new === 'yes') ? true : false;
    }

    /**
     * Indicates if the Product is Eco Friendly
     *
     * @return boolean
     */
    public function isEcoFriendly()
    {
        return ($this->eco_friendly === 'yes') ? true : false;
    }

    /**
     * Indicates if the Product is Vegan & Cruelty Free
     *
     * @return boolean
     */
    public function isCrueltyFree()
    {
        return ($this->cruelty_free === 'yes') ? true : false;
    }

    /**
     * Indicates if the Product has Free Shipping
     *
     * @return boolean
     */
    public function hasFreeShipping()
    {
        return ($this->free_shipping === 'yes') ? true : false;
    }

    /**
     * Indicates if the Product has correct data to calculate Shipping
     *
     * @return boolean
     */
    public function canCalculateShipping()
    {
        // Check dimensions
        if( is_null($this->width) || is_null($this->height) || is_null($this->length) )
            return false;

        // Check weight
        if( is_null($this->weight) )
            return false;

        return true;
    }

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Store the owns this Product
     *
     * @return \App\Models\Store\Store
     */
    public function shop()
    {
        return $this->belongsTo('App\Models\Store\Store', 'store_id', 'id');
    }

    /**
     * Returns all images belongs to this Product
     *
     * @return \App\Models\Common\Image
     */
    public function images()
    {
        return $this->belongsToMany('App\Models\Common\Image', 'product_images', 'product_id', 'image_id');
    }

    /**
     * Returns the Main Image of the Product
     *
     * @return \App\Models\Common\Image
     */
    public function mainImage()
    {
        return $this->images()->orderBy('position')->first();
    }

    public function mainImageUrl()
    {
        return $this->mainImage()->url ?? 'media/general/product-placeholder.png';
    }

    /**
     * Returns all Categories belongs to this Product
     *
     * @return \App\Models\Store\Category
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Store\Category', 'product_categories', 'product_id', 'category_id');
    }

    /**
     * Returns all Variations of this Product
     *
     * @return \App\Models\Store\ProductVariation
     */
    public function variations()
    {
        return $this->hasMany('App\Models\Store\ProductVariation', 'product_id', 'id');
    }

    // STATIC METHODS =========================================================
}
