<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'STR', 'DEX', 'CON', 'level', 'experience', 'bosses'];

    public function equipments()
    {
        return $this->hasMany(PlayerEquipment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
