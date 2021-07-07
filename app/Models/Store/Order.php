<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class Order extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'orders';

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
        'id', 'user_id', 'transaction_id', 'payment_method',
        'amount', 'shipping', 'authorized_amount', 'paid_amount', 'refunded_amount', 'installments',
        'boleto_url', 'boleto_barcode', 'boleto_expiration_date',
        'acquirer_name', 'acquirer_id', 'acquirer_response_code', 'authorization_code', 'tid', 'nsu',
        'request_data', 'response_data', 'tracking_code', 'shipping_date', 'delivery_date',
        'status', 'refuse_reason', 'status_reason',
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'user_id'                => 'bail|required|integer|exists:users,id',
        'transaction_id'         => 'nullable|string|max:127',
        'payment_method'         => 'bail|required|string|max:127',
        'amount'                 => 'bail|required|numeric',
        'shipping'               => 'bail|required|numeric',
        'authorized_amount'      => 'nullable|numeric',
        'paid_amount'            => 'nullable|numeric',
        'refunded_amount'        => 'nullable|numeric',
        'installments'           => 'bail|required|integer|max:12',
        //
        'boleto_url'             => 'nullable|string|max:255',
        'boleto_barcode'         => 'nullable|string|max:255',
        'boleto_expiration_date' => 'nullable|string|max:127',
        //
        'acquirer_name'          => 'nullable|string|max:127',
        'acquirer_id'            => 'nullable|string|max:127',
        'acquirer_response_code' => 'nullable|string|max:127',
        'authorization_code'     => 'nullable|string|max:127',
        'tid'                    => 'nullable|string|max:127',
        'nsu'                    => 'nullable|string|max:127',
        //
        'request_data'           => 'bail|required|string',
        'response_data'          => 'nullable|string',
        'tracking_code'          => 'nullable|string',
        'shipping_date'          => 'nullable',
        'delivery_date'          => 'nullable',
        //
        'status'                 => 'bail|required|string|in:processing,authorized,paid,refunded,waiting_payment,pending_refund,refused,in_transit,returned,delivered',
        'refuse_reason'          => 'nullable|string',
        'status_reason'          => 'nullable|string',
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'                     => 'bail|required|integer|exists:orders',
        'user_id'                => 'bail|required|integer|exists:users,id',
        'transaction_id'         => 'nullable|string|max:127',
        'payment_method'         => 'bail|required|string|max:127',
        'amount'                 => 'bail|required|numeric',
        'shipping'               => 'bail|required|numeric',
        'authorized_amount'      => 'nullable|numeric',
        'paid_amount'            => 'nullable|numeric',
        'refunded_amount'        => 'nullable|numeric',
        'installments'           => 'bail|required|integer|max:12',
        //
        'boleto_url'             => 'nullable|string|max:255',
        'boleto_barcode'         => 'nullable|string|max:255',
        'boleto_expiration_date' => 'nullable|string|max:127',
        //
        'acquirer_name'          => 'nullable|string|max:127',
        'acquirer_id'            => 'nullable|string|max:127',
        'acquirer_response_code' => 'nullable|string|max:127',
        'authorization_code'     => 'nullable|string|max:127',
        'tid'                    => 'nullable|string|max:127',
        'nsu'                    => 'nullable|string|max:127',
        //
        'request_data'           => 'bail|required|string',
        'response_data'          => 'nullable|string',
        'tracking_code'          => 'nullable|string',
        'shipping_date'          => 'nullable',
        'delivery_date'          => 'nullable',
        //
        'status'                 => 'bail|required|string|in:processing,authorized,paid,refunded,waiting_payment,pending_refund,refused,in_transit,returned,delivered',
        'refuse_reason'          => 'nullable|string',
        'status_reason'          => 'nullable|string',
    ];

    // PUBLIC METHODS =========================================================
    public function countItems()
    {
        return $this->items()->sum('quantity');
    }

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Items linked with this Order
     *
     * @return \App\Models\Store\OrderItem
     */
    public function items()
    {
        return $this->hasMany('App\Models\Store\OrderItem');
    }

    /**
     * Returns the Customer that place this Order
     *
     * @return \App\Models\Auth\User
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    // STATIC METHODS =========================================================
}
