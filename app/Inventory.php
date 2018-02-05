<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
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
    protected $table = 'inventory';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=
    [
    	'flower_id',
        'quantity'
    ];

	/**
	 *
	 * Get the flower for the inventory.
	 *
	 */
    public function flower()
    {
        return $this->hasOne('App\Flower', 'id', 'flower_id');
    }

}
