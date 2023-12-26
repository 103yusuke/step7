<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // ...

    public function products()
    {
        return $this->hasMany(Product::class, 'company_name', 'id');
    }
}
