<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineBot extends Model
{
    use HasFactory;

    protected $guarded = [];

    // bot has many messages
    public function messages()
    {
        return $this->hasMany(LineMessage::class);
    }
}
