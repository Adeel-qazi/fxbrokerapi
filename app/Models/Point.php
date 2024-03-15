<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'scambroker_id',
        'description',
    ];
    public function broker()
    {
        return $this->belongsTo(ScamBroker::class);
    }
}
