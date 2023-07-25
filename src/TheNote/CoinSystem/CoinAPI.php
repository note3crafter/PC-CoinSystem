<?php

//  ╔═════╗ ╔═════╗ ╔═════╗     ╔═╗ ╔═════╗ ╔═════╗ ╔═════╗      ╔═════╗ ╔═════╗ ╔═════╗ ╔═════╗
//  ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═╗ ║     ║ ║ ║ ╔═══╝ ║ ╔═══╝ ╚═╗ ╔═╝      ║ ╔═══╝ ║ ╔═╗ ║ ║ ╔═╗ ║ ║ ╔═══╝
//  ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ║ ║ ║     ║ ║ ║ ╚══╗  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╚═╝ ║ ║ ╚══╗
//  ║ ╔═══╝ ║ ╔╗ ╔╝ ║ ║ ║ ║ ╔═╗ ║ ║ ║ ╔══╝  ║ ║       ║ ║        ║ ║     ║ ║ ║ ║ ║ ╔╗ ╔╝ ║ ╔══╝
//  ║ ║     ║ ║╚╗╚╗ ║ ╚═╝ ║ ║ ╚═╝ ║ ║ ╚═══╗ ║ ╚═══╗   ║ ║        ║ ╚═══╗ ║ ╚═╝ ║ ║ ║╚╗╚╗ ║ ╚═══╗
//  ╚═╝     ╚═╝ ╚═╝ ╚═════╝ ╚═════╝ ╚═════╝ ╚═════╝   ╚═╝        ╚═════╝ ╚═════╝ ╚═╝ ╚═╝ ╚═════╝
//  Easy to Use! Written in Love! Project Core by TheNote\RetroRolf\Rudolf2000\note3crafter

namespace TheNote\CoinSystem;

use pocketmine\player\Player;
use pocketmine\utils\Config;

class CoinAPI
{
    public function addCoins(Player $player, $amount)
    {
        $coins = new Config(Main::getInstance()->getDataFolder() . "Coins.yml", Config::YAML);
        $coins->setNested("coins." . $player->getName(), $coins->getNested("coins." . $player->getName()) + $amount);
        $coins->save();
    }

    public function removeCoins(Player $player, $amount)
    {
        $coins = new Config(Main::getInstance()->getDataFolder() . "Coins.yml", Config::YAML);
        $coins->setNested("coins." . $player->getName(), $coins->getNested("coins." . $player->getName()) - $amount);
        $coins->save();
    }

    public function getCoins(string $player)
    {
        $coins = new Config(Main::getInstance()->getDataFolder() . "Coins.yml", Config::YAML);
        $coins->getNested("coins." . $player);
        return $coins->getNested("coins." . $player);
    }

    public function setCoins(Player $player, $amount)
    {
        $coins = new Config(Main::getInstance()->getDataFolder() . "Coins.yml", Config::YAML);
        $coins->setNested("coins." . $player->getName(), $amount);
        $coins->save();
    }
}