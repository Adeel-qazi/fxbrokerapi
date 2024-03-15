<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeData extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'type',
        'image',
        'lose',
        'country',
        'eursud',
        'usdjpy',
        'gbpusd',
        'usdcad',
        'audusd',
        'nzdusd',
        'eurjpy',
        'gbpjpy',
        'usdchf',
        'eurgbp',
        'nzdjpy',
        'audjpy',
        'gold',
    ];

    protected $casts = [
        'country' => 'array',
        'eursud' => 'array',
        'usdjpy' => 'array',
        'gbpusd' => 'array',
        'usdcad' => 'array',
        'audusd' => 'array',
        'nzdusd' => 'array',
        'eurjpy' => 'array',
        'gbpjpy' => 'array',
        'usdchf' => 'array',
        'eurgbp' => 'array',
        'nzdjpy' => 'array',
        'audjpy' => 'array',
        'gold' => 'array',
    ];


    public function imageFee()
    {
        return $this->hasOne(Image::class, 'filename','broker');
    }
    
}
