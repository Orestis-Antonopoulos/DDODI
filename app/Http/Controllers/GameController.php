<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;

class GameController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $player = $user->player;

        return view('game.index', ['player' => $player]);
    }

    public function randomStats(Request $request)
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
        $request->session()->forget('enemy_stats'); // Remove enemy from session
        $request->session()->forget('player_stats'); // Remove player from session
        Equipment::where('player_id', $player->id)->delete();

        return view('game.index', ['player' => $player]);
    }

    public function easy(Request $request)
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
        $request->session()->forget('enemy_stats'); // Remove enemy from session
        $request->session()->forget('player_stats'); // Remove player from session
        Equipment::where('player_id', $player->id)->delete();

        return view('game.index', ['player' => $player]);
    }

    public function medium(Request $request)
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
        $request->session()->forget('enemy_stats'); // Remove enemy from session
        $request->session()->forget('player_stats'); // Remove player from session
        Equipment::where('player_id', $player->id)->delete();

        return view('game.index', ['player' => $player]);
    }

    public function hard(Request $request)
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
        $request->session()->forget('enemy_stats'); // Remove enemy from session
        $request->session()->forget('player_stats'); // Remove player from session
        Equipment::where('player_id', $player->id)->delete();

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
            $armorClass = round(10 + (($DEX-10)/2));
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
                'AC' => $armorClass,
            ];
            $playerDamage = 1+(($player->STR-10)/2); if ($playerDamage <= 0.5) {$playerDamage = 1;}
            $playerAC = round(10+(($player->DEX-10)/2));
            $playerStats = [
                'HP' => $player->hp,
                'totalHP' => $player->hp,
                'AC' => $playerAC,
                'damage' => $playerDamage,
            ];
            $request->session()->put('enemy_stats', $enemyStats);
            $request->session()->put('player_stats', $playerStats);
            if (!$request->session()->has('temp_stats')) {$temp_stats = [$enemyStats['HP'], $playerStats['HP']]; $request->session()->put('temp_stats', $temp_stats);}
        } else {
            $enemyStats = $request->session()->get('enemy_stats');
            $playerStats = $request->session()->get('player_stats');
            if (!$request->session()->has('temp_stats')) {$temp_stats = [$enemyStats['HP'], $playerStats['HP']]; $request->session()->put('temp_stats', $temp_stats);}
            $temp_stats = $request->session()->get('temp_stats');
        }
        return view('game.play', ['player' => $player, 'enemy' => $enemyStats, 'player_stats' => $playerStats, 'temp_stats' => $temp_stats]);
    }

    public function attack(Request $request)
    {
        $user = auth()->user();
        $player = $user->player;
        $enemyStats = $request->session()->get('enemy_stats'); // Initialize or retrieve enemy stats from session
        $playerStats = $request->session()->get('player_stats'); // Initialize or retrieve player stats from session

        $playerCritical = 1; //Damage to Enemy
        $playerD20 = rand(1,20); if ($playerD20 == 1) {$playerCritical--;} elseif ($playerD20 == 20) {$playerCritical++;}
        $playerAttackRoll = $playerD20+round(($player->DEX-10)/2);
        $playerSTRModifier = ($player->STR-10)/2; if ($playerSTRModifier <= 1) {$playerSTRModifier = 1;}
        $tempEnemyHP = $enemyStats['HP']; //saving prior hp for animation
        if ($playerAttackRoll >= $enemyStats['AC']) {$enemyStats['HP'] -= $playerSTRModifier*$playerCritical; }

        $enemyCritical = 1; //Damage to Player
        $enemyD20 = rand(1,20); if ($enemyD20 == 1) {$enemyCritical--;} elseif ($enemyD20 == 20) {$enemyCritical++;}
        $enemyAttackRoll = $enemyD20+round(($enemyStats['DEX']-10)/2);
        $enemySTRModifier = ($enemyStats['STR']-10)/2; if ($enemySTRModifier <= 1) {$enemySTRModifier = 1;}
        $tempPlayerHP = $playerStats['HP']; //saving prior hp for animation
        if ($enemyAttackRoll >= $playerStats['AC']) {$playerStats['HP'] -= $enemySTRModifier*$enemyCritical; }
        $tempHPStats= [$tempEnemyHP, $tempPlayerHP]; //saving prior hp (both player's and enemy's) for animation

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



            //Random loot formula START (type, name, special abilities)
                $dropDecide= rand(20,27); //Default = rand(0,99) //Type of loot
                $specialabilities=[];
                $name= "";
                $droptype="";
                $SAResults = [];

                for ($i=3; $i>0; $i--) {
                    $chance = rand(1,6); //Default = rand(1,100) //Chance for special ability
                    $multiplier = 3*$enemyStats['level'];
                    if ($chance <= $multiplier) {
                        $chance2 = rand(1,100);
                        if ($chance2 <= 75) {
                            array_push($specialabilities, 1);
                        } else {
                            array_push($specialabilities, 2);
                        }
                    } else {
                        array_push($specialabilities, 0);
                    }
                };

                if (($dropDecide >=0) && ($dropDecide <20)) {$droptype="potion";}
                elseif (($dropDecide >=20) && ($dropDecide <28)) {$droptype="weapon";
                    $weaponNameList = json_decode(file_get_contents(public_path('/names/weaponNames.json')), true);
                    if ($specialabilities[0] == 1) { // Weapon Adjective
                        $randomIndex = array_rand($weaponNameList['epicAdjectives']);
                        $name .= $weaponNameList['epicAdjectives'][$randomIndex] . " ";
                    } elseif ($specialabilities[0] == 2) {
                        $randomIndex = array_rand($weaponNameList['funnyAdjectives']);
                        $name .= $weaponNameList['funnyAdjectives'][$randomIndex] . " ";
                    }

                    if ($specialabilities[1] == 0) { //Weapon Type
                        $randomIndex = array_rand($weaponNameList['weapons']);
                        $name .= $weaponNameList['weapons'][$randomIndex] . " ";
                    } elseif ($specialabilities[1] == 1) {
                        $randomIndex = array_rand($weaponNameList['epicWeapons']);
                        $name .= $weaponNameList['epicWeapons'][$randomIndex] . " ";
                    } elseif ($specialabilities[1] == 2) {
                        $randomIndex = array_rand($weaponNameList['funnyWeapons']);
                        $name .= $weaponNameList['funnyWeapons'][$randomIndex] . " ";
                    }

                    if ($specialabilities[2] == 1) { // Weapon Suffixe
                        $randomIndex = array_rand($weaponNameList['epicSuffixes']);
                        $name .= $weaponNameList['epicSuffixes'][$randomIndex];
                    } elseif ($specialabilities[2] == 2) {
                        $randomIndex = array_rand($weaponNameList['funnySuffixes']);
                        $name .= $weaponNameList['funnySuffixes'][$randomIndex];
                    }
                    $name = rtrim($name);
                    foreach ($specialabilities as $sa) {
                        if ($sa == 0) {null;
                    } elseif ($sa == 1) {
                        // array_push($SAResults, array_rand['STR'=>1,'DMG'=>rand(1,10)]);
                    }
                    }
                }
                elseif (($dropDecide >=28) && ($dropDecide <36)) {$droptype="offhand";}
                elseif (($dropDecide >=36) && ($dropDecide <44)) {$droptype="helmet";}
                elseif (($dropDecide >=44) && ($dropDecide <52)) {$droptype="torso";}
                elseif (($dropDecide >=52) && ($dropDecide <60)) {$droptype="legs";}
                elseif (($dropDecide >=60) && ($dropDecide <68)) {$droptype="boots";}
                elseif (($dropDecide >=68) && ($dropDecide <76)) {$droptype="gloves";}
                elseif (($dropDecide >=76) && ($dropDecide <84)) {$droptype="necklace";}
                elseif (($dropDecide >=84) && ($dropDecide <92)) {$droptype="earing";}
                elseif (($dropDecide >=92) && ($dropDecide <100)) {$droptype="ring";}
                // dd($dropDecide, $specialabilities, $droptype, $name, $SAResults);
            //Random loot formula END

            $request->session()->forget('enemy_stats'); // Remove enemy from session
            $request->session()->forget('player_stats'); // Remove player from session
            $request->session()->forget('temp_stats'); // Remove temp stats from session

            return redirect()->action([GameController::class, 'loot']); // Redirect to play to spawn a new enemy
        }
        // Store updated stats back into session
        $request->session()->put('enemy_stats', $enemyStats);
        $request->session()->put('player_stats', $playerStats);
        $request->session()->put('temp_stats', $tempHPStats);

        // Pass data to view
        return view('game.play', ['player' => $player, 'enemy' => $enemyStats, 'player_stats' => $playerStats, 'temp_stats' => $tempHPStats]);
    }

    public function loot(Request $request)
    {
        $user = auth()->user();
        $player = $user->player;
        $experienceBefore = $request->session()->get('experience_before');
        $experienceAfter = $request->session()->get('experience_after');
        $levelup = $request->session()->get('level_up');
        return view('game.loot', ['player' => $player, 'experienceBefore' => $experienceBefore, 'experienceAfter' => $experienceAfter, 'level_up' => $levelup]);

    }

    public function randomXP()
    {
        // Logic to randomize XP goes here
    }
}
