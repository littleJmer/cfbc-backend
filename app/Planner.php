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

    public $appends = ['statusName'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'inventario'
    ];

    public function getStatusNameAttribute() {

        $output = "En proceso";

        if ($this->status == 2) $output = "Entregado";
        else if ($this->status == 3) $output = "Cancelado";


        return $output;

    }


    /**
     * Get the orders for the planner.
     */
    public function orders()
    {
        return $this->hasMany('App\Order', 'planner_id', 'id');
    }
}
