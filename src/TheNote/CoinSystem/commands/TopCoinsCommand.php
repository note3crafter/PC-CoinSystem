<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\CoinSystem\commands;

use TheNote\CoinSystem\CoinAPI;
use TheNote\CoinSystem\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use TheNote\core\CoreAPI;
use TheNote\core\utils\Permissions;

class TopCoinsCommand extends Command implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("topcoins", $api->getCommandPrefix("Prefix") . $plugin->getCommandPrefix("TopCoinsDescription"), "/topcoins");
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        $moneys = new Config(Main::getInstance()->getDataFolder() . "Coins.yml", Config::YAML);
        $moneys = $moneys->get("coins", []);
        $money = [];
        foreach ($moneys as $p => $m) {
            $money[] = ["Coins" => $m, "Player" => $p];
        }
        rsort($money);
        $sender->sendMessage($this->plugin->getLang($sender->getName(), "TopCoins"));
        $money = array_slice($money, 0, 10);
        foreach ($money as $rank => $data) {
            $datas = round($data["Coins"], 2);
            $sender->sendMessage("§d" . ($rank + 1) . "§f. " . $data["Player"] . " §f:§d " . $datas . " Coins");
        }
        return true;
    }
}