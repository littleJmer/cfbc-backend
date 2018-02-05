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
        'client',
        'cases',
        'sku',
    ];

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
