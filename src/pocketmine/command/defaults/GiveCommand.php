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
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GiveCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"指定プレイヤーにアイテムを配布します",
			"/give プレイヤー名 アイテムID(:ダメージ値) 数量"
		);
		$this->setPermission("pocketmine.command.give");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}

		if(\count($args) < 2){
			$sender->sendMessage(TextFormat::RED . "使い方: " . $this->usageMessage);

			return \false;
		}

		$player = $sender->getServer()->getPlayer($args[0]);
		$item = Item::fromString($args[1]);

		if(!isset($args[2])){
			$item->setCount($item->getMaxStackSize());
		}else{
			$item->setCount((int) $args[2]);
		}

		if($player instanceof Player){
			if(($player->getGamemode() & 0x01) === 0x01){
				$sender->sendMessage(TextFormat::RED . "指定プレイヤーがクリエイティブです");

				return \true;
			}
			if($item->getId() == 0){
				$sender->sendMessage(TextFormat::RED . "アイテム(" . $args[1] . ")が見つかりませんでした");

				return \true;
			}

			//TODO: overflow
			$player->getInventory()->addItem(clone $item);
		}else{
			$sender->sendMessage(TextFormat::RED . "プレイヤー(" . $args[0] . ")が見つかりませんでした");

			return \true;
		}

		//$sender->sendMessage($player->getName . " に " . $item->getName . "(" . $item->getID . ":" . $item-getDamage . ") を" . $item->getCount . "配布しました");
		//$player->sendMessage($sender->getName() . " から " . $item->getName() . "(" . $item->getID() . ":" . $item-getDamage() . ") を" . $item->getCount . "配布されました");
		Command::broadcastCommandMessage($sender, $player->getName() . " に " . $item->getName() . "(" . $item->getId() . ":" . $item->getDamage() . ") を ".$item->getCount() . "配布しました");

		return \true;
	}
}