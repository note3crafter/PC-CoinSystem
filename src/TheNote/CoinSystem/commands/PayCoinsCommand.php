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

class PayCoinsCommand extends Command
{
    private Main $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $api = new CoreAPI();
        parent::__construct("paycoins", $api->getCommandPrefix("Prefix") . $plugin->getCommandPrefix("PayCoinsDescription"), "/paycoins" , );
        $this->setPermission("coins.command.pay");
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
        if (empty($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $this->plugin->getLang($sender->getName(),"PayCoinsUsage"));
            return false;
        }
        if (empty($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Info") . $this->plugin->getLang($sender->getName(),"PayCoinsUsage"));
            return false;
        }
        $target = $api->findPlayer($sender, $args[0]);
        if ($target == null) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $api->getLang($sender->getName(),"PlayernotOnline"));
            return false;
        }
        if (is_numeric($args[0])) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $this->plugin->getLang($sender->getName(),"PayCoinsUsage"));
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $this->plugin->getLang($sender->getName(),"PayCoinsNumb"));
            return false;
        }
        if ($args[1] > $capi->getCoins($sender->getName())) {
            $sender->sendMessage($api->getCommandPrefix("Error") . $this->plugin->getLang($sender->getName(),"PayCoinsNoCoins"));
            return false;
        }
        $capi->addCoins($target, (int)$args[1]);
        $capi->removeCoins($sender, (int)$args[1]);
        $message = str_replace("{victim}", $target, $this->plugin->getLang($sender->getName(),"PayCoinsTarget"));
        $message1 = str_replace("{coins}", $args[1] , $message);
        $sender->sendMessage($this->plugin->getCommandPrefix("Coins") . $message1);
        $message2 = str_replace("{sender}", $sender->getName(), $this->plugin->getLang($target->getName(),"PayCoinsSender"));
        $message3 = str_replace("{coins}", $args[1] , $message2);
        $target->sendMessage($this->plugin->getCommandPrefix("Coins") . $message3);
        return true;
    }
}