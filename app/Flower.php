<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flower extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;

    public static function boot()
    {
        parent::boot();
    }

    protected $revisionCreationsEnabled = true;

    public function getFullNameAttribute()
    {
        $name = trim($this->especie)." ".trim($this->variedad)." ".trim($this->color);
        return strtoupper(trim($name));
    }

}
