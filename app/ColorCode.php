<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColorCode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'colors';

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