<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'invoice_type' => 'array', // Cast JSON data to an array
        'payment_type' => 'array', 
    ];

    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

}