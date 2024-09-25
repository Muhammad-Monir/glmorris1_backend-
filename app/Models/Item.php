<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'room_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
