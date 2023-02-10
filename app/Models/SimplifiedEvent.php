<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SimplifiedEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function timestamp(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::create($value),
            set: fn ($value) => Carbon::createFromTimestamp($value/1000),
        );
    }

    // event belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // event belongs to a line group.
    public function group()
    {
        return $this->belongsTo(LineGroup::class, 'line_group_id', 'id');
    }


}
