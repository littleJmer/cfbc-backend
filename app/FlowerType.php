<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlowerType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'flower_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=
    [
    	'code',
    	'name'
    ];

}