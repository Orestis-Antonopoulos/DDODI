<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'value', 'slot'];

    public function equipments()
    {
        return $this->hasMany(PlayerEquipment::class);
    }
}
