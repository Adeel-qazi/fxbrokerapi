<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $fillable = [
        'broker_id',
        'name',
        'point',
    ];

    public function broker()
    {
        return $this->belongsTo(Broker::class);
    }
}
