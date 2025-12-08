<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    protected $fillable = ['code', 'product_name', 'quantity', 'user_id', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
