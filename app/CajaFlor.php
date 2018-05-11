<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CajaFlor extends Model {

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
    protected $table = 'cajasFlores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ordencaja_id',
    	'skunumber',
    	'bunch_qty',
    	'skudesc',
    	'location',
    	'flower_type',
    	'variety_color',
    	'grade',
    	'stem_count' 
    ];
}
?>

