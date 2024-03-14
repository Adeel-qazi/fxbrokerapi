<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ScamBroker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function points()
    {
        return $this->hasMany(Point::class,'scam_broker_id');
    }

    public function image()
    {
        return $this->hasOne(Image::class,'filename', 'name');
    }
    public function broker()
    {
        return $this->hasOne(Broker::class,'name', 'name');
    }
}
