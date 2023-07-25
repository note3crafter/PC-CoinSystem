<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\CoinSystem\commands;

use pocketmine\console\ConsoleCommandSender;
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
        parent::__construct("topcoins", $api->getCommandPrefix("Prefix") . $plugin->getCommandPrefix("TopCoinsDescription"), "/topcoins (side)");
        $this->setPermission(Permissions::$defaultperm);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $api = new CoreAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $this->plugin->getLang($sender->getName(), "TopCoinsUsage"));
            return false;
        }
        $moneys = new Config(Main::getInstance()->getDataFolder() . "Coins.yml", Config::YAML);
        $moneyData = $moneys->get("coins", []);
        $sortedMoneyData = [];
        foreach ($moneyData as $playerName => $moneyAmount) {
            $sortedMoneyData[] = ["Player" => $playerName, "Coins" => $moneyAmount];
        }
        usort($sortedMoneyData, function ($a, $b) {
            return $b["Coins"] - $a["Coins"];
        });
        $pageHeight = ($sender instanceof ConsoleCommandSender) ? 48 : 6;
        $chunkedMoney = array_chunk($sortedMoneyData, $pageHeight);
        $maxPageNumber = count($chunkedMoney);
        if ($args[0] > $maxPageNumber) {
            $sender->sendMessage($api->getCommandPrefix("Error") . str_replace("{maxpage}", $maxPageNumber, $this->plugin->getLang($sender->getName(), "TopCoinsPageError")));
            return false;
        }
        $pageNumber = isset($args[0]) && is_numeric($args[0]) && $args[0] > 0 ? min($args[0], $maxPageNumber) : 1;
        $sender->sendMessage($api->getLang($sender->getName(), "TopCoins"));
        $msg = str_replace("{page}", $pageNumber, $this->plugin->getLang($sender->getName(), "TopCoinsPageList"));
        $sender->sendMessage(str_replace("{maxpage}", $maxPageNumber, $msg));
        if (isset($chunkedMoney[$pageNumber - 1])) {
            $pageData = $chunkedMoney[$pageNumber - 1];
            $rank = $pageNumber === 1 ? 0 : ($pageNumber - 1) * $pageHeight;
            foreach ($pageData as $data) {
                $playerName = $data["Player"];
                $moneyAmount = round(intval($data["Coins"]), 2);
                $sender->sendMessage("§d" . ($rank + 1) . "§f. " . $playerName . " §f:§d " . $moneyAmount . " Coins");
                $rank++;
            }
        }
        return true;
    }
}