<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenCaja extends Model {

    use \Venturecraft\Revisionable\RevisionableTrait;

    public static function boot()
    {
        parent::boot();
    }

    protected $revisionCreationsEnabled = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ordenesCajas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'orden_id',
    	'box_type',
    	'description',
        'upc_number',
    	'upc_type',
    	'stem_per_bunches',
    	'bunches_per_box',
    	'number_of_cases',
    	'unit_price',
    	'fob_price',
    	'box_code_sku',
    	'flower_type',
    	'flowers',
        'open',
    ];

    /**
     * Get the flores for the caja.
     */
    public function flores()
    {
        return $this->hasMany('App\CajaFlor', 'ordencaja_id', 'id');
    }
}
?>

