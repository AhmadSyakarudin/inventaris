<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'borrower_name',
        'keterangan',
        'borrow_date',
        'return_date',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lendingItems()
    {
        return $this->hasMany(LendingItem::class);
    }

    public function isReturned()
    {
        return $this->return_date !== null;
    }
}