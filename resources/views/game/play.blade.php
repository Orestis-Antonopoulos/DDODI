@extends('layouts.app')

@section('content')
<style>
    .health_bar, .xp_bar {
        color:white;
        display:flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-weight:bold;
        padding:5px;
        width:100%;
        max-width:300px;
        height:25px;
        border:solid 1px #ccc;
        border-radius:15px;
        font-size:14px;
    }
</style>
{{-- @dd($player_stats) --}}
@php
    $enemyHpPercentage = $enemy['HP']/$enemy['totalHP']*100;
    $playerHpPercentage = $player_stats['HP']/$player_stats['totalHP']*100;
    $playerXpPercentage = $player->experience/(3*pow(($player->level +1), 2))*100;
@endphp

<div class="flex flex-col">
    <div>Player Lv: {{$player->level}} | HP: {{$player_stats['HP'];}} | XP: {{$player->experience}}</div>
    <div id="playerxp" class="xp_bar" style="background-image: linear-gradient(90deg, #555 <?=$playerXpPercentage?>%, #222 <?=$playerXpPercentage?>%);">
        <div>XP: {{ round($playerXpPercentage, 2) }}% </div>
    </div>
    <div id="playerhp" class="health_bar" style="background-image: linear-gradient(90deg, #922 <?=$playerHpPercentage?>%, #222 <?=$playerHpPercentage?>%);">
        <div>Player HP: {{ $player_stats['HP']; }} / {{ $player_stats['totalHP']; }} </div>
    </div>
    <div class="mt-[40px]">Enemy: Name | level: {{$enemy['level']}} | multiplier: x{{$enemy['multiplier']}}</div>
    <div id="enemyhp" class="health_bar" style="background-image: linear-gradient(90deg, #922 <?=$enemyHpPercentage?>%, #222 <?=$enemyHpPercentage?>%);">
        <div>Enemy HP: {{ $enemy['HP'] }} / {{ $enemy['totalHP'] }} </div>
    </div>
    CON: {{ $enemy['CON']}} | STR: {{ $enemy['STR']}} | DEX: {{ $enemy['DEX']}}
</div>

<form action="{{ route('game.attack') }}" method="POST">
    @csrf
    <button type="submit">Attack</button>
</form>
@endsection
