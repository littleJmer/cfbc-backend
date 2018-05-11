<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flower extends Model
{
    // use \Venturecraft\Revisionable\RevisionableTrait;

    // public static function boot()
    // {
    //     parent::boot();
    // }

    // protected $revisionCreationsEnabled = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'flowers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flower_type_id',
        'variety_color_id'
    ];

    public function getFullNameAttribute()
    {
        $name = trim($this->especie)." ".trim($this->variedad)." ".trim($this->color);
        return strtoupper(trim($name));
    }

}
