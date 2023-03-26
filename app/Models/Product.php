<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table='product';
    protected $fillable = [
        'title',
        'description',
        'image'
    ];
    public function variants(){
        return $this->hasMany(Variant::class,'product_id');

    }
}
