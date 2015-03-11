<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team

 * 
 *
*/

namespace pocketmine;

use pocketmine\utils\TextFormat;

/**
 * Handles the achievement list and a bit more
 */
abstract class Achievement{
	/**
	 * @var array[]
	 */
	public static $list = [
		/*"openInventory" => array(
			"name" => "Taking Inventory",
			"requires" => [],
		),*/
		"mineWood" => [
			"name" => "木を手に入れる",
			"requires" => [ //"openInventory",
			],
		],
		"buildWorkBench" => [
			"name" => "土台作り",
			"requires" => [
				"mineWood",
			],
		],
		"buildPickaxe" => [
			"name" => "いざ発掘 ！",
			"requires" => [
				"buildWorkBench",
			],
		],
		"buildFurnace" => [
			"name" => "ホット トピック",
			"requires" => [
				"buildPickaxe",
			],
		],
		"acquireIron" => [
			"name" => "金属を手に入れる",
			"requires" => [
				"buildFurnace",
			],
		],
		"buildHoe" => [
			"name" => "いざ農業 ！",
			"requires" => [
				"buildWorkBench",
			],
		],
		"makeBread" => [
			"name" => "パンを焼こう",
			"requires" => [
				"buildHoe",
			],
		],
		"bakeCake" => [
			"name" => "ケーキなんて嘘だ",
			"requires" => [
				"buildHoe",
			],
		],
		"buildBetterPickaxe" => [
			"name" => "アップグレード",
			"requires" => [
				"buildPickaxe",
			],
		],
		"buildSword" => [
			"name" => "いざ突撃 ！",
			"requires" => [
				"buildWorkBench",
			],
		],
		"diamonds" => [
			"name" => "ダイヤモンド ！",
			"requires" => [
				"acquireIron",
			],
		],

	];


	public static function broadcast(Player $player, $achievementId){
		if(isset(Achievement::$list[$achievementId])){
			if(Server::getInstance()->getConfigString("announce-player-achievements", \true) === \true){
				Server::getInstance()->broadcastMessage($player->getDisplayName() . " は " . TextFormat::GREEN . Achievement::$list[$achievementId]["name"] . " の実績を取得した");
			}else{
				$player->sendMessage(TextFormat::GREEN . Achievement::$list[$achievementId]["name"] . TextFormat::WHITE . " の実績を取得しました！");
			}

			return \true;
		}

		return \false;
	}

	public static function add($achievementId, $achievementName, array $requires = []){
		if(!isset(Achievement::$list[$achievementId])){
			Achievement::$list[$achievementId] = [
				"name" => $achievementName,
				"requires" => $requires,
			];

			return \true;
		}

		return \false;
	}


}
