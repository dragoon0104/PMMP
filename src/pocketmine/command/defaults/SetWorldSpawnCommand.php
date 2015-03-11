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
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetWorldSpawnCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"初期スポーン地点を設定します",
			"/setworldspawn | /setworldspawn x y z"
		);
		$this->setPermission("pocketmine.command.setworldspawn");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}

		if(\count($args) === 0){
			if($sender instanceof Player){
				$level = $sender->getLevel();
				$pos = (new Vector3($sender->x, $sender->y, $sender->z))->round();
			}else{
				$sender->sendMessage(TextFormat::RED . "座標を指定してください");

				return \true;
			}
		}elseif(\count($args) === 3){
			$level = $sender->getServer()->getDefaultLevel();
			$pos = new Vector3($this->getInteger($sender, $args[0]), $this->getInteger($sender, $args[1]), $this->getInteger($sender, $args[2]));
		}else{
			$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);

			return \true;
		}

		$level->setSpawnLocation($pos);

		$sender->sendMessage("ワールド(" . $level->getName() . ")の初期スポーン地点を " . $pos->x . " " . $pos->y . " " . $pos->z . " に設定しました");

		return \true;
	}
}
