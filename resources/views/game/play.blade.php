@extends('layouts.app')

@section('content')

@php
    $tempEnemyHP = $temp_stats[0];
    $tempPlayerHP = $temp_stats[1];
    $tempEnemyHpPercentage = $tempEnemyHP/$enemy['totalHP']*100;
    $tempPlayerHpPercentage = $tempPlayerHP/$player_stats['totalHP']*100;
    $enemyHpPercentage = $enemy['HP']/$enemy['totalHP']*100;
    $playerHpPercentage = $player_stats['HP']/$player_stats['totalHP']*100;
    $playerXpPercentage = $player->experience/(3*pow(($player->level +1), 2))*100;
@endphp

<style>
    .health_bar, .xp_bar {
        color:white;
        display:flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-weight:bold;
        padding:0px;
        width:100%;
        max-width:300px;
        height:25px;
        border:solid 1px #ccc;
        border-radius:15px;
        font-size:14px;
    }
    @keyframes PlayerHealthBar {
    0%      {width: {{$tempPlayerHpPercentage}}%;}
    100%    {width: {{$playerHpPercentage}}%;}}
    @keyframes EnemyHealthBar {
    0%      {width: {{$tempEnemyHpPercentage}}%;}
    100%    {width: {{$enemyHpPercentage}}%;}}
</style>
<div class="flex flex-col">
    <div>Player Lv: {{$player->level}} | HP: {{$player_stats['HP'];}} | XP: {{$player->experience}}</div>
    <p>Player AC: {{ $player_stats['AC']}} | Enemy AC: {{ $enemy['AC']}} </p>
    <div id="playerxp" class="xp_bar" style="background-image: linear-gradient(90deg, #555 <?=$playerXpPercentage?>%, #222 <?=$playerXpPercentage?>%);">
        <div>XP: {{ round($playerXpPercentage, 2) }}% </div>
    </div>
    <div id="playerhp" class="health_bar" style="position: relative; z-index:-1">
        <div style="position: relative;background-color:#222; width:100%; height:100%;border-radius:15px;"></div>
        <div style="position: absolute;background-color:#922; width:{{ $playerHpPercentage }}%; height:100%;border-radius:15px; left:0;
        animation: PlayerHealthBar 0.3s linear;"></div>
        <div style="position: absolute;">Player HP: {{ $player_stats['HP']; }} / {{ $player_stats['totalHP']; }} </div>
    </div>
    <div class="mt-[40px]">Enemy: Name | level: {{$enemy['level']}} | multiplier: x{{$enemy['multiplier']}}</div>
    <div id="enemyhp" class="health_bar" style="position: relative; z-index:-1">
        <div style="position: relative;background-color:#222; width:100%; height:100%;border-radius:15px;"></div>
        <div style="position: absolute;background-color:#922; width:{{ $enemyHpPercentage }}%; height:100%;border-radius:15px; left:0;
        animation: EnemyHealthBar 0.3s linear;"></div>
        <div style="position: absolute;">Enemy HP: {{ $enemy['HP'] }} / {{ $enemy['totalHP'] }} </div>
</div>
CON: {{ $enemy['CON']}} | STR: {{ $enemy['STR']}} | DEX: {{ $enemy['DEX']}}

<form action="{{ route('game.attack') }}" method="POST">
    @csrf
    <button type="submit">Attack</button>
</form>
@endsection
