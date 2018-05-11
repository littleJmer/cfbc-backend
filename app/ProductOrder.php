<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;

    public static function boot()
    {
        parent::boot();
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=
    [
    	'order_id',
    	'box_code_sku',
    	'box_type',
    	'bunches_per_box',
    	'date_code',
    	'description',
    	'fob_price',
    	'no_cases',
    	'product_category',
    	'stem_bunch',
    	'unit_price',
    	'upc_no',
    	'upc_type',
        'sku',
        'skuDesc',
        'bunchQty'
    ];

    /**
     *
     * Get the recipe record associated with the productorder.
     *
     */
    public function recipe()
    {
        return $this->hasOne('App\Recipe', 'name', 'description');
    }
}
