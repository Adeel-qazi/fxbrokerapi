<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scambroker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];


    protected $casts = [
        "country" => "array"
    ];

    public function points()
    {
        return $this->hasMany(Point::class,'scambroker_id');
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
