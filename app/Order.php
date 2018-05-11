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
        'load_date',
    	'orig_carrier',
    	'sales_rep_name',
    	'sun_valley_order',
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

    public function planner()
    {
        return $this->belongsTo('App\Planner', 'planner_id');
    }

    public function getFlowersAttribute()
    {
        $return = [];

        $products = $this->products;
        if($products)
        {
            foreach ($products as $product)
            {
                $recipe             = $product->recipe;
                $stem_bunch         = $product->stem_bunch;
                $no_cases           = $product->no_cases;
                $bunches_per_box    = $product->bunches_per_box;

                if($recipe)
                {
                    $flowers = $recipe->flowers;
                    if($flowers)
                    {
                        foreach ($flowers as $flower)
                        {
                            if( !isset($return[$flower->id]) )
                            {
                                $return[$flower->id]=
                                [
                                    "name"      => $flower->name_posco,
                                    "especie"   => $flower->especie,
                                    "variedad"  => $flower->variedad,
                                    "color"     => $flower->color,
                                    "qty"       => 0
                                ];
                            }
                            
                            # consolidado surtido
                            if($recipe->type == 2)
                            {
                                $return[$flower->id]["qty"]+= 
                                $stem_bunch * (int)$flower->pivot->quantity * $no_cases;
                            }
                            else
                            {
                                $return[$flower->id]["qty"]+= 
                                (int)$flower->pivot->quantity * $bunches_per_box * $no_cases;
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }

    public function getFntsAttribute()
    {
        $return = ['flowers' => [], "boxes" => []];

        $products = $this->products;
        if($products)
        {
            foreach ($products as $product)
            {
                $recipe             = $product->recipe;
                $stem_bunch         = $product->stem_bunch;
                $no_cases           = $product->no_cases;
                $bunches_per_box    = $product->bunches_per_box;

                $return["boxes"][] = [
                    "qty"   => (int)$product->no_cases,
                    "type"  => $product->box_type
                ];

                if($recipe)
                {
                    $flowers = $recipe->flowers;
                    if($flowers)
                    {
                        foreach ($flowers as $flower)
                        {
                            if( !isset($return['flowers'][$flower->id]) )
                            {
                                $return['flowers'][$flower->id]=
                                [
                                    "name_posco"      => $flower->name_posco,
                                    "especie"   => $flower->especie,
                                    "variedad"  => $flower->variedad,
                                    "color"     => $flower->color,
                                    "qty"       => 0
                                ];
                            }
                            
                            # Consumer Surtido
                            if($recipe->type == 3)
                            {
                                $return['flowers'][$flower->id]["qty"]+= 
                                $stem_bunch * (int)$flower->pivot->quantity * $no_cases;
                            }
                            else
                            {
                                $return['flowers'][$flower->id]["qty"]+= 
                                (int)$flower->pivot->quantity * $bunches_per_box * $no_cases;
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }

}
