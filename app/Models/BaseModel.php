<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Libs\AppMessage;
use Carbon\Carbon;

// Query Scopes
use App\Services\Traits\GenericStatusesTrait;
use App\Services\Traits\GenericAttributeAcessorsTrait;

/**
 * Base class for all Models
 *
 * @author Leandro Antonello <lantonello@gmail.com>
 * @version 2.0
 * @copyright (c) 2019, GoUp Digital
 */
class BaseModel extends Model
{
    use GenericStatusesTrait, GenericAttributeAcessorsTrait;

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
     * Mapping from table names to Models class names.
     *
     * @var array
     */
    public static $model_map = [
        'companies' => '\App\Models\Core\Companies',
        'modules'   => '\App\Models\Core\Module',
        'routines'  => '\App\Models\Core\Routine',
    ];

    // GENERIC MODELS OPERATIONS ----------------------------------------------
    /**
     * Returns the Model attributes and values as Array
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
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
            else
            {
				if( ! array_key_exists($key, $data) )
					continue;
				
                if( is_array($data[$key]) )
                    $data[$key] = json_encode( $data[$key] );

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
            dd( $exc );
            DB::rollBack();

            $this->error = $exc->getMessage();

            return false;

            //return new AppMessage( false, $exc->getMessage() );

            //echo $exc->getTraceAsString();
        }

        return true;
    }

    // PUBLIC STATIC METHODS ======================================================================
    /**
     * Returns the model class name by given database table name.
     * @param string $table_name
     * @return string
     */
    public static function getModelClassName( $table_name )
    {
        return self::$model_map[ $table_name ];
    }

    // PROTECTED METHODS ==========================================================================
    protected function validateInput()
    {
        //
    }
}