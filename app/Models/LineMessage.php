<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'payload' => AsArrayObject::class,
    ];

    // message belongs to bot
    public function bot()
    {
        return $this->belongsTo(LineBot::class);
    }
}
