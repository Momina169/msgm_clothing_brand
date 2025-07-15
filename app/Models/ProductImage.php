<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{

    protected $fillable = [
        'file_name',
    ];
    public function images()
    {
    return $this->hasMany(ProductImage::class);
    }
}
