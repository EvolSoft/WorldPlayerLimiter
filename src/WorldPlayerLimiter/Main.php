<?php

/*
 * WorldPlayerLimiter (v0.4 B2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 03:21 PM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/WorldPlayerLimiter/blob/master/LICENSE)
 */

namespace WorldPlayerLimiter;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{
    
	//About Plugin Const
	const PRODUCER = "EvolSoft";
	const VERSION = "0.4 B2";
	const MAIN_WEBSITE = "http://www.evolsoft.tk";
	//Other Const
	//Prefix
	const PREFIX = "&1[&2World&3Player&4Limiter&1] ";
	
	public $worlds;
	
	public function translateColors($symbol, $message){
	
		$message = str_replace($symbol."0", TextFormat::BLACK, $message);
		$message = str_replace($symbol."1", TextFormat::DARK_BLUE, $message);
		$message = str_replace($symbol."2", TextFormat::DARK_GREEN, $message);
		$message = str_replace($symbol."3", TextFormat::DARK_AQUA, $message);
		$message = str_replace($symbol."4", TextFormat::DARK_RED, $message);
		$message = str_replace($symbol."5", TextFormat::DARK_PURPLE, $message);
		$message = str_replace($symbol."6", TextFormat::GOLD, $message);
		$message = str_replace($symbol."7", TextFormat::GRAY, $message);
		$message = str_replace($symbol."8", TextFormat::DARK_GRAY, $message);
		$message = str_replace($symbol."9", TextFormat::BLUE, $message);
		$message = str_replace($symbol."a", TextFormat::GREEN, $message);
		$message = str_replace($symbol."b", TextFormat::AQUA, $message);
		$message = str_replace($symbol."c", TextFormat::RED, $message);
		$message = str_replace($symbol."d", TextFormat::LIGHT_PURPLE, $message);
		$message = str_replace($symbol."e", TextFormat::YELLOW, $message);
		$message = str_replace($symbol."f", TextFormat::WHITE, $message);
	
		$message = str_replace($symbol."k", TextFormat::OBFUSCATED, $message);
		$message = str_replace($symbol."l", TextFormat::BOLD, $message);
		$message = str_replace($symbol."m", TextFormat::STRIKETHROUGH, $message);
		$message = str_replace($symbol."n", TextFormat::UNDERLINE, $message);
		$message = str_replace($symbol."o", TextFormat::ITALIC, $message);
		$message = str_replace($symbol."r", TextFormat::RESET, $message);
	
		return $message;
	}
	
    public function onEnable(){
    	@mkdir($this->getDataFolder());
    	@mkdir($this->getDataFolder() . "signs/");
    	$this->saveResource("worlds.yml");
    	$this->worlds = new Config($this->getDataFolder() . "worlds.yml", Config::YAML, array());
    	$this->getCommand("worldplayerlimiter")->setExecutor(new Commands\Commands($this));
    	$this->getServer()->getPluginManager()->registerEvents(new Signs($this), $this);
    }

}
?>
