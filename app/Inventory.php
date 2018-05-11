<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression as Expression;

class Inventory extends Model {

    use \Venturecraft\Revisionable\RevisionableTrait;

    public static function boot() {
        parent::boot();
    }

    protected $revisionCreationsEnabled = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventory';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'flower_id',
        'quantity'
    ];

	/**
	 *
	 * Get the flower for the inventory.
	 *
	 */
    public function flower() {
        return $this->hasOne('App\Flower', 'id', 'flower_id');
    }

    // scopes

    public function scopeListJson($query, $params = []) {

        $select = [
            'inventory.id as inventory_id',
            'flowers.id as flower_id',
            'flowers.flower_type_id',
            'flowers.variety_color_id',
            'flower_types.name as flower_name',
            'flower_types.code as flower_code',
            'colors.name as variety_color_name',
            'colors.code as variety_color_code',
            'inventory.quantity'
        ];

        $query->select($select);

        $query->join('flowers', 'flowers.id', '=', 'inventory.flower_id');
        $query->join('flower_types', 'flower_types.id', '=', 'flowers.flower_type_id');
        $query->join('colors', 'colors.id', '=', 'flowers.variety_color_id');

        return $query;
    }

    public function scopeParaPlanificar($query, $params = []) {

        $select = [
            'inventory.id as inventory_id',
            'inventory.flower_id as flower_id',
            'inventory.quantity as quantity',
            // new Expression("1000 as `quantity`"),
            new Expression("0 as `quantity_locked`"),
            new Expression("0 as `quantity_opened`"),
            new Expression("0 as `quantity_after_locked`"),
            new Expression("'M' as `location`"),
            new Expression("CONCAT('M', flower_types.code, colors.code) as `skunumber`"),
            new Expression("CONCAT(flower_types.name, ' ', colors.name) as `skudesc`"),
            'flower_types.code as flower_type',
            'colors.code as variety_color',
            'flower_types.name as flower_text',
            'colors.name as variety_color_text',
        ];

        $query->select($select);

        $query->join('flowers', 'flowers.id', '=', 'inventory.flower_id');
        $query->join('flower_types', 'flower_types.id', '=', 'flowers.flower_type_id');
        $query->join('colors', 'colors.id', '=', 'flowers.variety_color_id');

        return $query;
    }

}
