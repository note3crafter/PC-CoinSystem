<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\CoinSystem\commands;

use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\CoinSystem\CoinAPI;
use TheNote\CoinSystem\Main;
use TheNote\core\CoreAPI;

class SetCoinsCommand extends Command implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("setcoins", $api->getCommandPrefix("Prefix") . $plugin->getCommandPrefix("SetCoinsDesctiption"), "/setcoins {player} {value}");
        $this->setPermission("coins.command.set");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $capi = new CoinAPI();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if(!isset($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("info") . $this->plugin->getLang($sender->getName(),"SetCoinsUsage"));
            return false;
        }
        if(!isset($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("info") . $this->plugin->getLang($sender->getName(),"SetCoinsUsage"));
            return false;
        }
        if(!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $this->plugin->getLang($sender->getName(),"SetCoinsNumb"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
            return false;
        }
        $capi->setCoins($target, (int)$args[1]);
        $message = str_replace("{target}", $target->getName(), $this->plugin->getLang($sender->getName(),("SetCoinsSender")));
        $message1 = str_replace("{coins}", $args[1] , $message);
        $sender->sendMessage($this->plugin->getCommandPrefix("Coins") . $message1);
        $message2 = str_replace("{coins}", $args[1], $this->plugin->getLang($target->getName(),"SetCoinsTarget"));
        $target->sendMessage($this->plugin->getCommandPrefix("Coins") . $message2);
        return true;
    }

}