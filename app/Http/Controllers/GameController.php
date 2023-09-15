<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $player = $user->player;

        return view('game.index', ['player' => $player]);
    }

    public function randomStats()
    {
        $user = auth()->user();
        $player = $user->player;

        $player->STR = rand(1, 20);
        $player->DEX = rand(1, 20);
        $player->CON = rand(1, 20);
        $player->level = 1;
        $player->experience = 0;
        $player->hp = round(10+(($player->CON -10)/2)+(($player->level -1)*(6+(($player->CON -10)/2))));
        $player->bosses = 0;
        $player->save();

        return view('game.index', ['player' => $player]);
    }

    public function easy()
    {
        $user = auth()->user();
        $player = $user->player;

        do {
        $player->STR = rand(1, 20);
        $player->DEX = rand(1, 20);
        $player->CON = rand(1, 20);
        } while (($player->STR)+($player->DEX)+($player->CON)<=44);
        $player->level = 1;
        $player->experience = 0;
        $player->hp = round(10+(($player->CON -10)/2)+(($player->level -1)*(6+(($player->CON -10)/2))));
        $player->bosses = 0;
        $player->save();

        return view('game.index', ['player' => $player]);
    }

    public function medium()
    {
        $user = auth()->user();
        $player = $user->player;

        do {
            $player->STR = rand(1, 20);
            $player->DEX = rand(1, 20);
            $player->CON = rand(1, 20);
            } while (($player->STR)+($player->DEX)+($player->CON)>44 || ($player->STR)+($player->DEX)+($player->CON)<35);
        $player->level = 1;
        $player->experience = 0;
        $player->hp = round(10+(($player->CON -10)/2)+(($player->level -1)*(6+(($player->CON -10)/2))));
        $player->bosses = 0;
        $player->save();

        return view('game.index', ['player' => $player]);
    }

    public function hard()
    {
        $user = auth()->user();
        $player = $user->player;

        do {
            $player->STR = rand(1, 20);
            $player->DEX = rand(1, 20);
            $player->CON = rand(1, 20);
            } while (($player->STR)+($player->DEX)+($player->CON)>34);
        $player->level = 1;
        $player->experience = 0;
        $player->hp = round(10+(($player->CON -10)/2)+(($player->level -1)*(6+(($player->CON -10)/2))));
        $player->bosses = 0;
        $player->save();

        return view('game.index', ['player' => $player]);
    }

    public function play(Request $request)
    {
        $user = auth()->user();
        $player = $user->player;

        // Initialize or retrieve enemy stats from session
        if (!$request->session()->has('enemy_stats')) {
            $STR = rand(1, 10)+round($player->level/2);
            $DEX = rand(1, 10)+round($player->level/2);
            $CON = rand(1, 10)+round($player->level/2);
            $minLevel = max(1, $player->level - 3); // enemy level can't go below 1
            $maxLevel = min($player->level + 3, floor(1.5 * $player->level)); // enemy level can't be more than 150% of player's level
            $enemyLevel = rand($minLevel, $maxLevel);
            $ExpHpMultiplier = 1;
                if ($enemyLevel > 4 && $enemyLevel < 10) {$temp = rand(1, 100); if ($temp > 75) {$ExpHpMultiplier = 2;}}
                elseif ($enemyLevel >= 10 && $enemyLevel < 15) {$temp = rand(1, 100); if ($temp > 91) {$ExpHpMultiplier = 3;} elseif ($temp > 75) {$ExpHpMultiplier = 2;}}
                elseif ($enemyLevel >=15) {$temp = rand(1, 100); if ($temp > 97) {$ExpHpMultiplier = 4;} elseif ($temp > 91) {$ExpHpMultiplier = 3;} elseif ($temp > 75) {$ExpHpMultiplier = 2;}}
            $enemyHP = $ExpHpMultiplier * (intval(round(10 + (($CON - 10) / 2) + (($enemyLevel - 1) * (6 + (($CON - 10) / 2))))));
            $exp = ($enemyLevel*3)*(($STR + $CON + $DEX)/30)*$ExpHpMultiplier;
            $enemyStats = [
                'name' => 'Goblin',
                'STR'  => $STR,
                'DEX'  => $DEX,
                'CON'  => $CON,
                'level' => $enemyLevel,
                'HP' => $enemyHP,
                'totalHP' => $enemyHP,
                'exp' => $exp,
                'multiplier' => $ExpHpMultiplier,
            ];
            $playerStats = [
                'HP' => $player->hp,
                'totalHP' => $player->hp,
                'attack_roll' => ($player->Dex-10)/2,
            ];
            $request->session()->put('enemy_stats', $enemyStats);
            $request->session()->put('player_stats', $playerStats);
        } else {
            $enemyStats = $request->session()->get('enemy_stats');
            $playerStats = $request->session()->get('player_stats');
        }
        return view('game.play', ['player' => $player, 'enemy' => $enemyStats, 'player_stats' => $playerStats]);
    }

    public function attack(Request $request)
    {
        $user = auth()->user();
        $player = $user->player;
        $enemyStats = $request->session()->get('enemy_stats'); // Initialize or retrieve enemy stats from session
        $playerStats = $request->session()->get('player_stats'); // Initialize or retrieve player stats from session
        // Perform battle logic here

        $enemyStats['HP'] -= 3; // Here we simply reduce the enemy's HP by 5
        $playerStats['HP'] -= 1;

        if ($enemyStats['HP'] <= 0) { // Check if enemy is dead
            $experienceBefore = $player->experience;
            $player->experience += $enemyStats['exp']; // Add experience here
            $experienceAfter = $player->experience;
            $levelup = 0;
            if ($player->experience >= (3*pow(($player->level +1), 2))){
                $player->experience = 0;
                $player->level++;
                $player->hp = round(10+(($player->CON -10)/2)+(($player->level -1)*(6+(($player->CON -10)/2))));
                $levelup = 1;
            };
            $player->save();
            $request->session()->put('experience_before', $experienceBefore);
            $request->session()->put('experience_after', $experienceAfter);
            $request->session()->put('level_up', $levelup);

            $request->session()->forget('enemy_stats'); // Remove enemy from session
            $request->session()->forget('player_stats'); // Remove player from session
            return redirect()->action([GameController::class, 'loot']); // Redirect to play to spawn a new enemy
        }
        // Store updated stats back into session
        $request->session()->put('enemy_stats', $enemyStats);
        $request->session()->put('player_stats', $playerStats);

        // Pass data to view
        return view('game.play', ['player' => $player, 'enemy' => $enemyStats, 'player_stats' => $playerStats]);
    }

    public function loot(Request $request)
    {
        $user = auth()->user();
        $player = $user->player;
        $experienceBefore = $request->session()->get('experience_before');
        $experienceAfter = $request->session()->get('experience_after');
        return view('game.loot', ['player' => $player, 'experienceBefore' => $experienceBefore, 'experienceAfter' => $experienceAfter]);

    }

    public function randomXP()
    {
        // Logic to randomize XP goes here
    }
}
