<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use App\Models\Store\Product;
use OwenIt\Auditing\Contracts\Auditable;

use App\Models\Store\StoreRating;

class Store extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'stores';

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
        'id', 'user_id', 'name', 'business_name', 'slogan', 'slug', 'cnpj', 'email', 'phone',
        'address_id', 'domain', 'meta_description', 'meta_keywords', 'analytics_id',
        'logo_id', 'cover_id', 'background_id', 'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'user_id'          => 'bail|required|integer|exists:users,id',
        'name'             => 'bail|required|string|between:6,127',
        'business_name'    => 'bail|required|string|between:6,127',
        'slogan'           => 'nullable',
        'slug'             => 'required|string',
        'cnpj'             => 'bail|required|doc',
        'email'            => 'bail|required|email',
        'phone'            => 'nullable',
        'address_id'       => 'nullable',
        'domain'           => 'nullable',
        'meta_description' => 'nullable',
        'meta_keywords'    => 'nullable',
        'analytics_id'     => 'nullable',
        'logo_id'          => 'nullable',
        'cover_id'         => 'nullable',
        'background_id'    => 'nullable',
        'status'           => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'               => 'required|integer|exists:stores',
        'user_id'          => 'required|integer|exists:users,id',
        'name'             => 'required|string|between:6,127',
        'business_name'    => 'required|string|between:6,127',
        'slogan'           => 'nullable',
        'slug'             => 'required|string',
        'cnpj'             => 'required|doc',
        'email'            => 'required|email',
        'phone'            => 'nullable',
        'address_id'       => 'nullable',
        'domain'           => 'nullable',
        'meta_description' => 'nullable',
        'meta_keywords'    => 'nullable',
        'analytics_id'     => 'nullable',
        'logo_id'          => 'nullable',
        'cover_id'         => 'nullable',
        'background_id'    => 'nullable',
        'status'           => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Store Owner
     *
     * @return \App\Models\Auth\User
     */
    public function owner()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'user_id');
    }

    /**
     * Returns the Address associated with this Store
     *
     * @return \App\Models\Location\Address
     */
    public function address()
    {
        return $this->hasOne('App\Models\Location\Address', 'id', 'address_id');
    }

    /**
     * Returns the Background image
     *
     * @return \App\Models\Common\Image
     */
    public function background()
    {
        return $this->hasOne('App\Models\Common\Image', 'id', 'background_id');
    }

    /**
     * Returns the Cover image
     *
     * @return \App\Models\Common\Image
     */
    public function cover()
    {
        return $this->hasOne('App\Models\Common\Image', 'id', 'cover_id');
    }

    /**
     * Returns the Logo image
     *
     * @return \App\Models\Common\Image
     */
    public function logo()
    {
        return $this->hasOne('App\Models\Common\Image', 'id', 'logo_id');
    }

    /**
     * Returns the Products belongs to this Store
     *
     * @return \App\Models\Store\Product
     */
    public function products()
    {
        return $this->hasMany('App\Models\Store\Product', 'store_id', 'id');
    }

    /**
     * Returns the Store Info
     *
     * @return \App\Models\Store\StoreInfo
     */
    public function info()
    {
        return $this->hasOne('App\Models\Store\StoreInfo');
    }

    // PUBLIC METHODS =========================================================
    /**
     * Returns the average ratings for this Store
     *
     * @return float
     */
    public function averageRatings()
    {
        return StoreRating::where('store_id', '=', $this->id)->avg('average');
    }

    /**
     * Returns the average Shipping ratings for this Store
     *
     * @return float
     */
    public function averageShippingRatings()
    {
        return StoreRating::where('store_id', '=', $this->id)->avg('shipping');
    }

    /**
     * Returns the last 10 Reviews for this Store
     *
     * @return Collection
     */
    public function reviews()
    {
        $reviews = StoreRating::where('store_id', '=', $this->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

        return $reviews;
    }

    public function countProducts()
    {
        //
    }

    // STATIC METHODS =========================================================
}
