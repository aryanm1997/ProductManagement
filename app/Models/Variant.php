<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Variant extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table='variant';
    protected $fillable = [
        'product_id',
        'size',
        'color'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'id');
    }
}
