<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBase extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'dice', 'many_dices', 'flat', 'slot', 'name'];

    public function equipments()
    {
        return $this->hasMany(PlayerEquipment::class);
    }
}
