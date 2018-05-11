<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'colors';

    protected $fillable=
    [
    	'code',
    	'name',
    ];

}
