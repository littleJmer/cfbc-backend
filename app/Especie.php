<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'especies';

    protected $fillable=
    [
    	'code',
    	'name',
    ];

}
