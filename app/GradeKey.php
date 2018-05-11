<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradeKey extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'grade_keys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=
    [
    	'arcata',
    	'oxnard',
        'grade'
    ];

}