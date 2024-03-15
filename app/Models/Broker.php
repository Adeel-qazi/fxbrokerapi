<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Broker extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'country',
        'url',
        'ratting',
        'lose',
        'path',
        'min_deposit',
        'max_leverage',
        'platform',
        'broker_img',
        'recommended',
    ];

    protected $casts = [
        "country" => "array"
    ];


    public function image()
    {
        return $this->hasOne(Image::class, 'filename','name');
    }

    // public function image()
    // {
    //     return $this->hasOne(Image::class,'filename', 'name');
    // }





}
