<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression as Expression;

class Orden extends Model {

    use \Venturecraft\Revisionable\RevisionableTrait;

    public static function boot()
    {
        parent::boot();
    }

    protected $revisionCreationsEnabled = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ordenes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name_acct',
        'customer_ship_to',
        'customer_po_number',
        'sales_rep_name',
        'sun_valley_order',
        'guess',
        'dest_carrier',
        'orig_carrier',
        'order_ship_date',
        'production_date',
        'load_date',
        'date_code',
        'status',
    ];

    /**
     * Get the cajas for the orden.
     */
    public function cajas()
    {
        return $this->hasMany('App\OrdenCaja', 'orden_id', 'id');
    }

    public function scopeLikeCsv($query, $params = []) {
        $select = [];
        $select[] = "ordenes.id as orden_id";
        $select[] = "ordenes.*";
        $select[] = "ordenesCajas.id as ordencaja_id";
        $select[] = "ordenesCajas.*";
        $select[] = "cajasFlores.id as cajaflor_id";
        $select[] = "cajasFlores.*";
        $select[] = new Expression("flower_types.name as `flower_text`");
        $select[] = new Expression("colors.name as `variety_color_text`");

        $query->select($select);

        $query->join("ordenesCajas", "ordenesCajas.orden_id", "=", "ordenes.id");
        $query->join("cajasFlores", "cajasFlores.ordencaja_id", "=", "ordenesCajas.id");

        $query->leftjoin("flower_types", "flower_types.code", "=", "cajasFlores.flower_type");
        $query->leftjoin("colors", "colors.code", "=", "cajasFlores.variety_color");

        if( isset($params["ordenid"]) && $params["ordenid"] > 0 ) {
            $query->whereIn("ordenes.id", $params["ordenid"]);
        }

        if( isset($params["status"]) ) {
            $query->where("ordenes.status", $params["status"]);
        }

        if( isset($params["production_date"]) ) {
            $query->where("ordenes.production_date", $params["production_date"]);
        }

        if( isset($params["id"]) ) {
            $query->where("ordenes.id", $params["id"]);
        }

        $query->orderBy("ordenes.id", "ASC");
        $query->orderBy("ordenesCajas.id", "ASC");
        $query->orderBy("cajasFlores.id", "ASC");

        return $query;
    }
}
?>
