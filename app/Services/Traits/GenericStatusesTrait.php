<?php
namespace App\Services\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\BaseModel;
use App\Services\AppMessage;

trait GenericStatusesTrait
{
    // QUERY SCOPES -----------------------------------------------------------
    /**
     * Scope a query to only include ACTIVE records.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive( $query )
    {
        return $query->where('status', '=', BaseModel::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include INACTIVE records.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive( $query )
    {
        return $query->where('status', '=', BaseModel::STATUS_INACTIVE);
    }

    /**
     * Scope a query to only include DELETED records.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeleted( $query )
    {
        return $query->where('status', '=', BaseModel::STATUS_DELETED);
    }

    /**
     * Scope a query to only include NOT DELETED (Active and Inactive) records.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotDeleted( $query )
    {
        return $query->where('status', '!=', BaseModel::STATUS_DELETED);
    }

    // STATUS UPDATE ----------------------------------------------------------
    /**
     * Activates the current record.
     * @return $this
     */
    public function activate()
    {
        DB::transaction(function()
        {
            $this->status = BaseModel::STATUS_ACTIVE;
            $this->save();
        });

        return new AppMessage( true );
        //return $this;
    }

    /**
     * Inactivates the current record.
     * @return $this
     */
    public function inactivate()
    {
        DB::transaction(function()
        {
            $this->status = BaseModel::STATUS_INACTIVE;
            $this->save();
        });

        return new AppMessage( true );
        //return $this;
    }

    /**
     * Mark the current record as Deleted.
     * @return $this
     */
    public function remove()
    {
        DB::transaction(function()
        {
            $this->status = BaseModel::STATUS_DELETED;
            $this->save();
        });

        return new AppMessage( true );
        //return $this;
    }
}