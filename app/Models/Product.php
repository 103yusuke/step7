<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'img_path',
    ];

    public function bunrui()
    {
        return $this->belongsTo(Bunrui::class);
    }
}
