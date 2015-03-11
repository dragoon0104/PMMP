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

class TeleportCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"指定プレイヤーをテレポートします",
			"/tp プレイヤー ターゲット / x y z"
		);
		$this->setPermission("pocketmine.command.teleport");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}

		if(\count($args) < 1 or \count($args) > 4){
			$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);

			return \true;
		}

		$target = \null;
		$origin = $sender;

		if(\count($args) === 1 or \count($args) === 3){
			if($sender instanceof Player){
				$target = $sender;
			}else{
				$sender->sendMessage(TextFormat::RED . "プレイヤーを指定してください");

				return \true;
			}
			if(\count($args) === 1){
				$target = $sender->getServer()->getPlayer($args[0]);
				if($target === \null){
					$sender->sendMessage(TextFormat::RED . "プレイヤー(" . $args[0] . ")は見つかりませんでした");

					return \true;
				}
			}
		}else{
			$target = $sender->getServer()->getPlayer($args[0]);
			if($target === \null){
				$sender->sendMessage(TextFormat::RED . "プレイヤー(" . $args[0] . ")は見つかりませんでした");

				return \true;
			}
			if(\count($args) === 2){
				$origin = $target;
				$target = $sender->getServer()->getPlayer($args[1]);
				if($target === \null){
					$sender->sendMessage(TextFormat::RED . "プレイヤー(" . $args[1] . ")は見つかりませんでした");

					return \true;
				}
			}
		}

		if(\count($args) < 3){
			$origin->teleport($target);
			$sender->sendMessage($origin->getDisplayName() . " は " . $target->getDisplayName() . " にテレポートしました");

			return \true;
		}elseif($target->getLevel() !== \null){
			$pos = \count($args) === 4 ? 1 : 0;
			$x = $this->getRelativeDouble($target->x, $sender, $args[$pos++]);
			$y = $this->getRelativeDouble($target->y, $sender, $args[$pos++], 0, 128);
			$z = $this->getRelativeDouble($target->z, $sender, $args[$pos]);
			$target->teleport(new Vector3($x, $y, $z));
			$sender->sendMessage($target->getDisplayName() . " は " . \round($x, 2) . " " . \round($y, 2) . " " . \round($z, 2) . " にテレポートしました");

			return \true;
		}

		$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);

		return \true;
	}
}
