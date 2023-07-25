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
use pocketmine\player\Player;
use TheNote\core\CoreAPI;

class GiveCoinsCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("givecoins", $api->getCommandPrefix("Prefix") . $plugin->getCommandPrefix("GiveCoinsDescription"), "/givecoins <player> <coins>");
        $this->setPermission("coins.command.give");
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
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(), "NoPermission"));
            return false;
        }
        if (is_numeric($args[0])) {
            $capi->addCoins($sender, $args[0]);
            $message = str_replace("{coins}", $args[0], $this->plugin->getLang($sender->getName(),"GiveCoinsSucces"));
            $sender->sendMessage($this->plugin->getCommandPrefix("Coins") . $message);
            return true;
        } else {
            if (!is_numeric($args[1])) {
                $sender->sendMessage($api->getCommandPrefix("Error") . $this->plugin->getLang($sender->getName(),"GiveCoinsNumb"));
                return false;
            }
            if (count($args) < 2) {
                $sender->sendMessage($api->getCommandPrefix("Info") . $this->plugin->getLang($sender->getName(),"GiveCoinsNumb"));
                return false;
            }
            $target = $api->findPlayer($sender, $args[0]);
            $capi->addCoins($target, $args[1]);
            $message = str_replace("{player}", $target->getName(), $this->plugin->getLang($sender->getName(),"GiveCoinsPlayerSucces"));
            $message1 = str_replace("{coins}", $args[1], $message);
            $sender->sendMessage($this->plugin->getCommandPrefix("Coins") . $message1);
        }
        return true;
    }
}