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
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\utils\TextFormat;

class TimeCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"ワールドの時間を設定します",
			"/time set 時間 | /time add 数値 | /time start/stop"
		);
		$this->setPermission("pocketmine.command.time.add;pocketmine.command.time.set;pocketmine.command.time.start;pocketmine.command.time.stop");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(\count($args) < 1){
			$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);

			return \false;
		}

		if($args[0] === "start"){
			if(!$sender->hasPermission("pocketmine.command.time.start")){
				$sender->sendMessage(TextFormat::RED . "時間の進行を開始する権限がありません");

				return \true;
			}
			foreach($sender->getServer()->getLevels() as $level){
				$level->checkTime();
				$level->startTime();
				$level->checkTime();
			}
			$sender->sendMessage("時間の進行を開始しました");
			return \true;
		}elseif($args[0] === "stop"){
			if(!$sender->hasPermission("pocketmine.command.time.stop")){
				$sender->sendMessage(TextFormat::RED . "時間の進行を停止する権限がありません");

				return \true;
			}
			foreach($sender->getServer()->getLevels() as $level){
				$level->checkTime();
				$level->stopTime();
				$level->checkTime();
			}
			$sender->sendMessage("時間の進行を停止しました");
			return \true;
		}


		if(\count($args) < 2){
			$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);

			return \false;
		}

		if($args[0] === "set"){
			if(!$sender->hasPermission("pocketmine.command.time.set")){
				$sender->sendMessage(TextFormat::RED . "時間を設定する権限がありません");

				return \true;
			}

			if($args[1] === "day"){
				$value = 0;
			}elseif($args[1] === "night"){
				$value = Level::TIME_NIGHT;
			}else{
				$value = $this->getInteger($sender, $args[1], 0);
			}

			foreach($sender->getServer()->getLevels() as $level){
				$level->checkTime();
				$level->setTime($value);
				$level->checkTime();
			}
			$sender->sendMessage("時間を " . $value . " に設定しました");
		}elseif($args[0] === "add"){
			if(!$sender->hasPermission("pocketmine.command.time.add")){
				$sender->sendMessage(TextFormat::RED . "時間を設定する権限がありません");

				return \true;
			}

			$value = $this->getInteger($sender, $args[1], 0);
			foreach($sender->getServer()->getLevels() as $level){
				$level->checkTime();
				$level->setTime($level->getTime() + $value);
				$level->checkTime();
			}
			$sender->sendMessage("時間を " . $value . " 進めました");
		}else{
			$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);
		}

		return \true;
	}
}