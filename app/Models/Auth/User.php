<?php
namespace App\Models\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

use Silber\Bouncer\Database\HasRolesAndAbilities;
//use Silber\Bouncer\Bouncer;

// Query Scopes
use App\Services\Traits\GenericStatusesTrait;
use App\Services\Traits\GenericAttributeAcessorsTrait;

use App\Models\Store\Store;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use Notifiable, GenericStatusesTrait, GenericAttributeAcessorsTrait;
    use HasRolesAndAbilities;
    use \OwenIt\Auditing\Auditable;

	/**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'users';

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
     *
     * @var array
     */
    protected $fillable = [
        'id', 'fullname', 'username', 'password', 'validation_token',
        'email_verified_at', 'remember_token', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pivot'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** Indicates the Active Status. */
    const STATUS_ACTIVE   = 'active';

    /** Indicates the Inactive Status. */
    const STATUS_INACTIVE = 'inactive';

    /** Indicates the Deleted status. */
    const STATUS_DELETED  = 'deleted';

    /** @var string Holds the base route for build urls. */
    public $route;

    /** @var string Holds last error message. */
    public $error;

	/**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'fullname' => 'bail|required|string|max:127',
        'username' => 'bail|required|email|unique:users,username',
        'password' => 'bail|required|confirmed|string|min:6',
        'status'   => 'string|in:active,inactive,deleted',
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'       => 'bail|required|integer|exists:users',
        'fullname' => 'bail|required|string|max:127',
        'username' => 'bail|required|email',
        //'password' => 'bail|required|string|min:6',
        'status'   => 'string|in:active,inactive,deleted',
    ];

    /**
     * Returns the Model attributes and values as Array
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Indicates if this User has a Store
     *
     * @return boolean
     */
    public function hasStore()
    {
        $my_store = Store::where('user_id', '=', $this->id)->first();

        if( $my_store )
            return true;

        return false;
    }

    /**
     * Indicates if this User is a Master Admin
     *
     * @return boolean
     */
    public function isMaster()
    {
        return $this->isA('master');
        // Gets the Bouncer instance
        //$bouncer  = Bouncer::create();

        //return $bouncer->is($this)->a('master');
    }

    /**
     * Generic method to validate and save data within a Transaction.
     * @param array $data   Array with input data.
     * @param array $rules  Array with validation rules.
     * @return \App\Libs\AppMessage
     */
    public function store( array $data, array $rules )
    {
        //dd( $data );
        //dd( $rules );

        // Build fields array
        foreach( $rules as $key => $rule )
        {
            // Status special field
            if( ($key === 'status') && (! isset($data[$key]) || empty($data[$key])) )
            {
                $this->{$key} = self::STATUS_ACTIVE;
            }
            else if( ($key === 'password') && ! empty($data[$key]) )
            {
                $this->{$key} = Hash::make( $data[$key] );
            }
            else
            {
                $this->{$key} = $data[$key] ?? null;
            }
        }

        //dd( $this );

        // Validation passes, starts a Transaction
        DB::beginTransaction();

        try
        {
            $this->save();

            DB::commit();

            // Do not return data because of relationships
            //$me = json_decode( json_encode( $this ) );
            //$id = $this->id;

            //return new AppMessage( true, 'Operação concluída com sucesso!', (object)['id' => $id] );
        }
        catch( \Exception $exc )
        {
            DB::rollBack();

            $this->error = $exc->getMessage();

            return false;

            //return new AppMessage( false, $exc->getMessage() );

            //echo $exc->getTraceAsString();
        }

        return true;
    }

	// RELATIONSHIPS ==========================================================
    /**
	 * Returns all Shipping Addresses belongs to this User.
	 * @return \App\Models\Location\Address
	 */
    public function shippingAddresses()
    {
        return $this->addresses()->where('type', '=', 'shipping')->get();
    }

	/**
	 * Returns all Addresses belongs to this User.
	 * @return \App\Models\Location\Address
	 */
	public function addresses()
	{
		return $this->belongsToMany('App\Models\Location\Address', 'user_addresses', 'user_id', 'address_id');
	}

    /**
	 * Returns all Phones belongs to this User.
	 * @return \App\Models\Location\Phone
	 */
	public function phones()
	{
		return $this->belongsToMany('App\Models\Location\Phone', 'user_phones', 'user_id', 'phone_id');
	}
}
