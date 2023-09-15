<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['player_id', 'item_base_id', 'slot', 'modifier_id'];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function itemBase()
    {
        return $this->belongsTo(ItemBase::class);
    }

    public function modifier()
    {
        return $this->belongsTo(Modifier::class);
    }
}
