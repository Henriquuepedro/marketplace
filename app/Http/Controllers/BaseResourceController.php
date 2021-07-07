<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\BaseModel;
use App\Models\Store\Store;
use App\Services\AppMessage;

class BaseResourceController extends Controller
{
    /** @var string Holds the Model name. */
    protected $model_name;

    /** @var BaseModel Holds the primary model instance. */
    protected $model;

    /** @var string Holds the Module name. */
    protected $module;

    /** @var string Holds the base route for current resource. */
    protected $route;

    /** @var array Holds the array with list columns. */
    protected $list_columns;

    /** @var array Holds the array with column headers. */
    protected $list_header;

    /** @var int Holds the number of rows per page in list table. */
    protected $list_size = 20;

    /** @var Collection Holds the data for table listing. */
    protected $list_data;

    /** @var array Holds the array with relationships methods. */
    protected $relationships;

    /** @var int Holds the number of current page. */
    protected $page = 1;

    /** @var array Holds the columns that will be searchable. */
    protected $searchable;

    /** @var AppMessage The result message for operations. */
    protected $result;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        // Resolves the model
        $this->resolveModel();

		// Get parameters
        $params = $request->all();

		// Columns
		$columns = $this->list_columns;

		// Check if all fields are required
		if( isset($params['cols']) )
		{
			if( $params['cols'] === 'all' )
				$columns = '*';
		}

        // Start building the list
        $query = $this->model->select( $columns );

        if( is_array($params) && ( count($params) > 0 ) )
        {
            foreach( $params as $field => $value )
            {
				// Skip columns special param
				if( $field === 'cols' )
					continue;

                // Pagination
                if( $field === 'page' )
                    continue;

                // Search ?
                if( $field === 'q' )
                {
                    $this->parseSearchFields( $query, $value );
                    continue;
                }

				// Exceptional queries
				if( $field === 'ex' )
				{
					$parts = explode(':', $value);
					$operator = $this->getOperator( $parts[1] );

					$query->where($parts[0], $operator, $parts[2]);
					continue;
				}

                // Filtering
				if( ! empty($value) )
					$query->where($field, '=', $value);
            }
        }

		// Paginate the results
        $list = $query->paginate( $this->list_size );

		//dd( $query->toSql() );

        // Data to be returned
        $data = json_decode( json_encode( $list ) );

        // Data specific to KTDataTable frontend component
        $data->meta = (object) [
            'page' => $data->current_page,
            'pages' => $data->last_page,
            'perpage' => $data->per_page,
            'total' => $data->total,
            //'sort' => 'asc',
            //'field' => 'ShipDate'
        ];

        //dd( $data );

		// Log this activity
        $this->logAction( 'index' );

		if( $this->model_name !== 'OwenIt\Auditing\Models\Audit' )
			$this->retrievedAudit( $this->model_name, 0, 'index' );

        return $this->responseSuccess( null, $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        // Resolve model
        $this->resolveModel();

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'store' );

        // Return
        $data = [
            'data' => json_decode( json_encode( $this->model ) ),
            'next_page' => url( $this->route )
        ];

        return $this->responseSuccess( 'Operação concluída com sucesso!', $data );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request, $id )
    {
        // Resolve model
        $this->resolveModel($id);

        $data = [
            'data' => json_decode( json_encode( $this->model ) )
        ];

		if( $this->model_name !== 'OwenIt\Auditing\Models\Audit' )
			$this->retrievedAudit( $this->model_name, $id, 'showed' );

        return $this->responseSuccess( null, $data );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Request $request, $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        // Resolve model
        $this->resolveModel($id);

        // Validates input and store data
        $this->validateAndSave( $request->all(), 'update' );

        // Return
        $data = ['data' => json_decode( json_encode( $this->model ) )];

        return $this->responseSuccess( 'Operação concluída com sucesso!', $data );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Request $request, $id )
    {
        // Resolve model
        $this->resolveModel($id);

        $res = $this->model->remove();
        $res->setMessage( 'Operação concluída com sucesso!' );

        // Return
        return $this->responseSuccess( 'O item foi excluído com sucesso!' );
    }

    // PROTECTED METHODS ================================================================
	/**
     * Parses the Search fields
     *
     * @param QueryBuilder $query
     * @param string $term
     * @return void
     */
    protected function parseSearchFields( &$query, $term )
    {
        $i = 0;

        foreach( $this->searchable as $col )
        {
            if( $i === 0 )
            {
                $query->where( $col, 'like', '%'. $term .'%' );
            }
            else
            {
                $query->orWhere( $col, 'like', '%'. $term .'%' );
            }

            $i++;
        }
    }

	protected function getOperator( $op )
	{
		switch( $op )
		{
			case 'eq': return '=';
			case 'no': return '!=';
			case 'lt': return '<';
			case 'lte': return '<=';
			case 'gt': return '>';
			case 'gte': return '>=';
		}
	}

    /**
     * Returns an array with status options
     *
     * @return array
     */
    protected function statusOptions()
    {
        return [
            BaseModel::STATUS_ACTIVE   => __('interface.status.' . BaseModel::STATUS_ACTIVE),
            BaseModel::STATUS_INACTIVE => __('interface.status.' . BaseModel::STATUS_INACTIVE),
            BaseModel::STATUS_DELETED  => __('interface.status.' . BaseModel::STATUS_DELETED),
        ];
    }

    /**
     * Resolves the Model class
     *
     * @param int $id
     * @return void
     */
    protected function resolveModel( $id = null )
    {
        // Resolves the model
        $this->model = resolve( $this->model_name );

        if( ! is_null($id) )
            $this->model = $this->model->find($id);
    }

    /**
     * Resets the Model Name and Class
     *
     * @param string $model_name
     * @return void
     */
    protected function resetModel( $model_name = null )
    {
        $this->model = null;

        if( ! is_null($model_name) )
            $this->model_name = $model_name;
    }

    /**
     * Validates input data and saves to database
     *
     * @param array $input
     * @param string $action
     * @return void
     */
    protected function validateAndSave( array $input, string $action )
    {
        // Get rules by action
        $rules = $this->getRules($action);

		// Special Password case
		if( $action === 'update' )
		{
			if( isset( $input['password'] ) )
				$rules['password'] = 'bail|required|string|min:6';
		}

		//dd( $input );
        //dd( $rules );

		// Validates input
        $this->validateInput( $input, $rules );

        // Store data
        $saved = $this->model->store( $input, $rules );

        if( ! $saved )
        {
            // Build the response message
            $this->result = new AppMessage( false, $this->model->error );

            abort( $this->responseJson( $this->result ) );
        }
    }

    /**
     * Validates user input
     *
     * @param array $input
     * @param array $rules
     * @return bool
     */
    protected function validateInput( array $input, array $rules )
    {
        // Validate rules
        $validator = Validator::make( $input, $rules );

        //dd( $input );
        //dd( $rules );

        if( $validator->fails() )
        {
            // Get first error message
            $errors = $validator->errors();

            //dd( $errors );

            // Build the response message
            $this->result = new AppMessage( false, $errors->first() );

            abort( $this->responseJson( $this->result ) );
        }
    }

    /**
     * Returns validation rules for given action
     *
     * @param string $action
     * @return array
     */
    protected function getRules( string $action )
    {
        if( ! $this->model )
            $this->resolveModel();

        // Get rules by action
        switch( $action )
        {
            case 'store': return $this->model::RULES_STORE;
            case 'update': return $this->model::RULES_UPDATE;
        }
    }

    //
    /**
     * Validates User Store
     *
     * @param int $store_id
     * @return bool|Store
     */
    protected function checkStore( int $store_id = null )
    {
        // Get User data
        $user = auth()->user();

        // Get User's Store
        $store = Store::where('user_id', '=', $user->id)->first();

        if( ! $store )
            return false;

        if( ! is_null($store_id) && ($store_id !== $store->id) )
            return false;

        return $store;
    }
}
