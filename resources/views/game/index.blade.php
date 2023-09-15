@extends('layouts.app')

@section('content')
    <div style="border: solid 1px black; border-radius:10px;" class="flex flex-col w-[330px] p-[15px]">

            <p class="w-full text-center font-bold mb-[5px]">NEW GAME OPTIONS</p>

            <div class="flex flex-row gap-[5px]">
                <form method="post" action="{{ route('game.randomStats') }}" class="rounded" style="">
                    @csrf<button type="submit" name="random_stats" style=""  class="w-[70px] bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-2 rounded text-xs">Random</button>
                </form>
                <form method="post" action="{{ route('game.easy') }}" class="rounded" style="">
                    @csrf<button type="submit" name="easy" style=""          class="w-[70px] bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-2 rounded text-xs">Easy</button>
                </form>
                <form method="post" action="{{ route('game.medium') }}" class="rounded" style="">
                    @csrf<button type="submit" name="medium" style=""        class="w-[70px] bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-2 rounded text-xs">Medium</button>
                </form>
                <form method="post" action="{{ route('game.hard') }}" class="rounded" style="">
                    @csrf<button type="submit" name="hard" style=""          class="w-[70px] bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-2 rounded text-xs">Hard</button>
                </form>
            </div>
            <p class="text-xs w-full text-center italic mt-1">warning! Your progress will be reset</p>
    </div>

    <div style="border: solid 1px black; border-radius:10px;" class="flex flex-col w-[330px] p-[15px] mt-[10px]">
        <div style="flex-direction: row; display:flex; justify-content: center;">
            <div class="bg-blue-800 px-2 py-1 mx-1 rounded text-white font-medium">Lv:{{$player->level}}</div>
            <div class="bg-red-800 px-2 py-1 mx-1 rounded w-full text-center text-white font-medium">HP: {{$player->hp}} </div>
        </div>
        <div class="flex flex-row justify-center my-2">
            <div class="w-1/3 text-center">STR: {{ $player->STR }}</div>|
            <div class="w-1/3 text-center">DEX: {{ $player->DEX }}</div>|
            <div class="w-1/3 text-center">CON: {{ $player->CON }}</div>
        </div>
        <div>
            <form method="post" action="{{ route('game.play') }}" class="rounded" style="">
                @csrf<button class="w-full bg-gray-200 rounded font-bold p-1 shadow-md" style="border:solid 1px #CCC">PLAY</button>
            </form>
        </div>
    </div>



@endsection
