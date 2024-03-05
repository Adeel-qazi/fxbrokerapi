<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comparebroker extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'country',
        'lose',
        'url',
        'score',
        'available',
        'popularity',
        'updated',
        'img',
        'tradingfees',
        'nontradingfees',
        'safety',
        'depositandwithdrawal',
        'platformandexperience',
    ];

    protected $casts = [
        "country" => "array",
        "tradingfees" => "array",
        "nontradingfees" => "array",
        "safety" => "array",
        "depositandwithdrawal" => "array",
        "platformandexperience" => "array",
    ];
    
}
