<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LendingItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'lending_id',
        'item_id',
        'quantity',
    ];

    // Relasi ke lending
    public function lending()
    {
        return $this->belongsTo(Lending::class);
    }

    // Relasi ke item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}