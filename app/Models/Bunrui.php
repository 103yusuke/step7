<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bunrui extends Model
{
    public function getLists()
    {
        $bunruis = Bunrui::pluck('str', 'id');

        return $bunruis;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    use HasFactory;
}
