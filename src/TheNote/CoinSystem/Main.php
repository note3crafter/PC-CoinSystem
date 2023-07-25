<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\CoinSystem;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use TheNote\CoinSystem\commands\GiveCoinsCommand;
use TheNote\CoinSystem\commands\MyCoinsCommand;
use TheNote\CoinSystem\commands\PayCoinsCommand;
use TheNote\CoinSystem\commands\SeeCoinsCommand;
use TheNote\CoinSystem\commands\SetCoinsCommand;
use TheNote\CoinSystem\commands\TakeCoinsCommand;
use TheNote\CoinSystem\commands\TopCoinsCommand;
use TheNote\core\CoreAPI;

class Main extends PluginBase implements Listener
{
    public static $instance;

    public static function getInstance()
    {
        return self::$instance;
    }
    public function onLoad(): void
    {
        self::$instance = $this;
        @mkdir($this->getDataFolder() . "Lang");
        $this->saveResource("Lang/LangDEU.json");
        $this->saveResource("Lang/LangENG.json");
        $this->saveResource("Lang/LangESP.json");
        $this->saveResource("Lang/LangCommandPrefix.yml");
        $this->saveResource("Config.yml");
    }
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $cfg = new Config($this->getDataFolder() . "Coins.yml", Config::YAML);
        $cfg->setNested("coins.CONSOLE", 0);
        $cfg->save();
        $this->getServer()->getCommandMap()->register("givecoins", new GiveCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("mycoins", new MyCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("paycoins", new PayCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("seecoins", new SeeCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("setcoins", new SetCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("takecoins", new TakeCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("topcoins", new TopCoinsCommand($this));
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $capi = new CoinAPI();
        $cfg = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
        if ($capi->getCoins($player->getName()) === null) {
            $capi->setCoins($player, $cfg->get("DefaultCoins"));
        }
        var_dump($capi->getCoins($player->getName()));
    }
    public function getLang(string $player, $langkey) {
        $api = new CoreAPI();
        $lang = new Config($this->getDataFolder() . "Lang/Lang" . $api->getUser($player, "language") . ".json", Config::JSON);
        return $lang->get($langkey);
    }
    public function getCommandPrefix($langkey) {
        $lang = new Config($this->getDataFolder() . "Lang/" . "LangCommandPrefix.yml", Config::YAML);
        $lang->get($langkey);
        return $lang->get($langkey);
    }
}