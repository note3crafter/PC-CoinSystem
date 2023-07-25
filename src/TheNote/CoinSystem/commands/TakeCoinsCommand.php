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
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\CoinSystem\CoinAPI;
use TheNote\CoinSystem\Main;
use TheNote\core\CoreAPI;

class TakeCoinsCommand extends Command implements Listener
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("takecoins", $api->getCommandPrefix("Prefix") . $plugin->getCommandPrefix("TakeCoinsDescription"), "/takecoins {player} {value}");
        $this->setPermission("coins.command.take");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $api = new CoreAPI();
        $capi = new CoinAPI();
        if (!$sender instanceof Player) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getCommandPrefix("CommandIngame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"NoPermission"));
            return false;
        }
        if (!isset($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("info") . $this->plugin->getLang($sender->getName(),"TakeCoinsUsage"));
            return false;
        }
        if (!isset($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("info") . $this->plugin->getLang($sender->getName(),"TakeCoinsUsage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $this->plugin->getLang($sender->getName(),"TakeCoinsNumb"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if($target === $sender){
            $sender->sendMessage($api->getCommandPrefix("Error") . $this->plugin->getLang($sender->getName(),"TakeCoinsNotYourSelf"));
            return false;
        }

        if ($target == null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
            return false;
        }
        $api->removeMoney($target, (int)$args[1]);
        $message = str_replace("{target}", $target->getName(), $this->plugin->getLang($sender->getName(),"TakeCoinsSender"));
        $message1 = str_replace("{coins}", $args[1] , $message);
        $sender->sendMessage($this->plugin->getCommandPrefix("Coins") . $message1);
        $message2 = str_replace("{coins}", $args[1], $this->plugin->getLang($target->getName(),"TakeCoinsTarget"));
        $message3 = str_replace("{sender}", $sender->getName() , $message2);
        $target->sendMessage($this->plugin->getCommandPrefix("Coins") . $message3);
        return true;
    }
}
