# PC-CoinSystem
A CoinSystem for Minecraft 

#Important
This Plugin needs [ProjectCore](https://github.com/note3crafter/ProjectCore) to run!

# How to Use the API

```php
$c = new CoinAPI;
#Add here coins to a Player
$c->addCoins($player, $amount);
#Remove here Coins from a Player
$c->removeCoins($player, $amount);
#Get here the Coins from a Player. its a Strin you need the playername!
$c->getCoins($player->getName); 
```

