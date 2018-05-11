<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recipes';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=
    [
        'name',
        'customer_id',
        'case_id',
        'sku',
        'type',
        'inmutable',
    ];

    public function getTypeNameAttribute()
    {
        $output = "Buquet";

        if($this->type == 2)
        {
            $output = "Consumer Solido";
        }
        else if($this->type == 3)
        {
            $output = "Consumer Surtido";
        }
        else if($this->type == 4)
        {
            $output = "Bulk";
        }

        return $output;
    }

    public function getInmutableDescAttribute()
    {
        $inmutable = $this->inmutable;
        return ($inmutable == 0) ? 'No' : 'Si';
    }

    /**
     *
     * Get the flowers for the recipe.
     *
     */
    public function flowers()
    {
        return $this->belongsToMany('App\Flower', 'recipes_flowers', 'recipe_id', 'flower_id')
        ->withPivot('quantity');
    }

    /**
     *
     * Get the customer for the recipe.
     *
     */
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    /**
     *
     * Get the case for the recipe.
     *
     */
    public function case()
    {
        return $this->belongsTo('App\Caja');
    }

     /**
     *
     * The materials that belong to the recipe.
     *
     */
    public function material()
    {
        return $this->belongsToMany('App\Item', 'recipes_items', 'recipe_id', 'item_id')
        ->withPivot('quantity', 'price_per_unity');
    }

    /*==================================
    =            Attributes            =
    ==================================*/
    
    public function getItemsObjAttribute()
    {
        $items = array();

        $data = $this->items;

        if($data)
        {
            foreach ($data as $key => $row)
            {
                $items[] = $row->toArray();
            }
        }

        return $items;
    }
    
    /*=====  End of Attributes  ======*/
    
}
