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
use pocketmine\utils\TextFormat;

class WhitelistCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"ホワイトリストの設定を行います",
			"/whitelist add|remove プレイヤー名 | /whitelist on|off|list|reload"
		);
		$this->setPermission("pocketmine.command.whitelist.reload;pocketmine.command.whitelist.enable;pocketmine.command.whitelist.disable;pocketmine.command.whitelist.list;pocketmine.command.whitelist.add;pocketmine.command.whitelist.remove");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}

		if(\count($args) === 1){
			if($this->badPerm($sender, \strtolower($args[0]))){
				return \true;
			}
			switch(\strtolower($args[0])){
				case "reload":
					$sender->getServer()->reloadWhitelist();
					$sender->sendMessage("Whitelistファイルを再読み込みします");

					return \true;
				case "on":
					$sender->getServer()->setConfigBool("white-list", \true);
					$sender->sendMessage("Whitelistを有効にします");

					return \true;
				case "off":
					$sender->getServer()->setConfigBool("white-list", \false);
					$sender->sendMessage("Whitelistを無効にします");

					return \true;
				case "list":
					$result = "";
					foreach($sender->getServer()->getWhitelisted()->getAll(\true) as $player){
						$result .= $player . ", ";
					}
					$sender->sendMessage("Whitelistに登録されているプレイヤー: " . \substr($result, 0, -2));

					return \true;
			}
		}elseif(\count($args) === 2){
			if($this->badPerm($sender, \strtolower($args[0]))){
				return \true;
			}
			switch(\strtolower($args[0])){
				case "add":
					$sender->getServer()->getOfflinePlayer($args[1])->setWhitelisted(\true);
					$sender->sendMessage($args[1] . " をWhitelistに登録しました");

					return \true;
				case "remove":
					$sender->getServer()->getOfflinePlayer($args[1])->setWhitelisted(\false);
					$sender->sendMessage($args[1] . " をWhitelistから削除しました");

					return \true;
			}
		}

		$sender->sendMessage(TextFormat::RED . "使い方:\n" . $this->usageMessage);

		return \true;
	}

	private function badPerm(CommandSender $sender, $perm){
		if(!$sender->hasPermission("pocketmine.command.whitelist.$perm")){
			$sender->sendMessage(TextFormat::RED . "権限がありません");

			return \true;
		}

		return \false;
	}
}