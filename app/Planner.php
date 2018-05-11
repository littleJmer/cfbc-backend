<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planner extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'planners';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=
    [
        'name',
        'inventario'
    ];


    /**
     * Get the orders for the planner.
     */
    public function orders()
    {
        return $this->hasMany('App\Order', 'planner_id', 'id');
    }
}
