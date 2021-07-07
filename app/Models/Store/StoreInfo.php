<?php
namespace App\Models\Store;

use App\Models\BaseModel;
use OwenIt\Auditing\Contracts\Auditable;

class StoreInfo extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'store_info';

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
        'id', 'store_id', 'bank_id', 'bank_branch', 'bank_branch_dv', 'bank_account', 'bank_account_dv',
        'bank_account_type', 'account_holder_name', 'account_holder_doc',
        'bank_account_id', 'recipient_id', 'recipient_status', 'status_reason',
        'status'
    ];

    /**
     * @var array RULE_STORE Constant that defines the Store rules.
     */
    const RULES_STORE = [
        'store_id'            => 'bail|required|integer|exists:stores,id',
        'bank_id'             => 'bail|required|integer|exists:banks,id',
        'bank_branch'         => 'bail|required|string|max:7',
        'bank_branch_dv'      => 'nullable|string|max:3',
        'bank_account'        => 'bail|required|string|max:11',
        'bank_account_dv'     => 'bail|required|string|max:3',
        'bank_account_type'   => 'bail|required|string|in:conta_corrente,conta_poupanca,conta_corrente_conjunta,conta_poupanca_conjunta',
        'account_holder_name' => 'bail|required|string|max:127',
        'account_holder_doc'  => 'bail|required|string|cpf',
        'bank_account_id'     => 'nullable|string|max:127',
        'recipient_id'        => 'nullable|string|max:127',
        'recipient_status'    => 'nullable|string|max:63',
        'status_reason'       => 'nullable|string|max:255',
        'status'              => 'string|in:active,inactive,deleted'
    ];

    /**
     * @var array RULE_UPDATE Constant that defines the Update rules.
     */
    const RULES_UPDATE = [
        'id'                  => 'bail|required|integer|exists:store_info',
        'store_id'            => 'bail|required|integer|exists:stores,id',
        'bank_id'             => 'bail|required|integer|exists:banks,id',
        'bank_branch'         => 'bail|required|string|max:7',
        'bank_branch_dv'      => 'nullable|string|max:3',
        'bank_account'        => 'bail|required|string|max:11',
        'bank_account_dv'     => 'bail|required|string|max:3',
        'bank_account_type'   => 'bail|required|string|in:conta_corrente,conta_poupanca,conta_corrente_conjunta,conta_poupanca_conjunta',
        'account_holder_name' => 'bail|required|string|max:127',
        'account_holder_doc'  => 'bail|required|string|cpf',
        'bank_account_id'     => 'nullable|string|max:127',
        'recipient_id'        => 'nullable|string|max:127',
        'recipient_status'    => 'nullable|string|max:63',
        'status_reason'       => 'nullable|string|max:255',
        'status'              => 'string|in:active,inactive,deleted'
    ];

    // RELATIONSHIPS ==========================================================
    /**
     * Returns the Bank associated with this Store Info
     *
     * @return \App\Models\Common\Bank
     */
    public function bank()
    {
        return $this->hasOne('App\Models\Common\Bank', 'id', 'bank_id');
    }

    // STATIC METHODS =========================================================
}
