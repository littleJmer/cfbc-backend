<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
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
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=
    [
    	'customer_name_acct',
    	'customer_po_number',
    	'customer_ship_to',
    	'dest_carrier',
    	'order_ship_date',
    	'orig_carrier',
    	'sales_rep_name',
    	'sun_valley_order'
    ];

	/**
	 *
	 * Get the products for the order.
	 *
	 */
    public function products()
    {
        return $this->hasMany('App\ProductOrder', 'order_id');
    }

}
