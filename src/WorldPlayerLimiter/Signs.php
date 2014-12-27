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

use pocketmine\event\Listener;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Sign;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;


class Signs extends PluginBase implements Listener{
	
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function Update(EntityLevelChangeEvent $event){
		$entity = $event->getEntity();
		$origin = $event->getOrigin();
		$dest = $event->getTarget();
		if($entity instanceof Player){
			$origcount = $origin->getName();
			$destcount = $dest->getName();
			if($this->getPlayerLimit($destcount)!=false){
				if($this->CountLevelPlayers($destcount)+1>$this->getPlayerLimit($destcount)){
					if($this->getPlayerLimit($origcount)!=false){
						if($this->CountLevelPlayers($origcount)+1>$this->getPlayerLimit($origcount)){
							$entity->kick($this->plugin->translateColors("&", Main::PREFIX . "&cPlayer limit reached"));
						}else{
							$entity->teleport(Server::getInstance()->getLevelByName($origcount)->getSafeSpawn());
						}
					}else{
						$entity->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cPlayer limit reached"));
						$event->setCancelled(true);
					}
				}
			}
		}
	}
	
	public function onPlayerJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$level = $player->getLevel();
		if($this->getPlayerLimit($level->getName())!=false){
			if($this->CountLevelPlayers($level->getName())>$this->getPlayerLimit($level->getName())){
				$player->kick($this->plugin->translateColors("&", Main::PREFIX . "&cPlayer limit reached"));
			}
		}
	}
	
	public function SignClick(PlayerInteractEvent $event){
		//Checking Permissions
		if($event->getPlayer()->hasPermission("worldplayerlimiter.use-sign") == true){
			if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
				$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
				if($sign instanceof Sign){
					//Initialize vars
					$txtsign = $sign->getText();
					$lvl = $event->getPlayer()->getLevel()->getName();
					$sx = $sign->x;
					$sy = $sign->y;
					$sz = $sign->z;
					if($this->isRegistered($lvl, $sx, $sy, $sz)){
						$dest = $this->getSignDest($lvl, $sx, $sy, $sz);
						if(Server::getInstance()->loadLevel($dest) != false){
							$event->getPlayer()->teleport(Server::getInstance()->getLevelByName($dest)->getSafeSpawn());
						}else{
							Server::getInstance()->loadLevel($dest);
							$event->getPlayer()->teleport(Server::getInstance()->getLevelByName($dest)->getSafeSpawn());
						}
						//Update Sign
						//Checking Player Limit
						$tmp = $this->getPlayerLimit($dest);
						if($tmp != false){
							$sign->setText($txtsign[0], $this->CountLevelPlayers($dest) . "/" . $tmp, $txtsign[2], $txtsign[3]);
						}
					}
				}
			}
		}
	}
	
	public function SignCreate(SignChangeEvent $event){
		//Checking Permissions
		if($event->getPlayer()->hasPermission("worldplayerlimiter.create-sign") == true){
			if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
				$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
				if($sign instanceof Sign){
					$line0 = $event->getLine(0);
					$line1 = $event->getLine(1);
					$line2 = $event->getLine(2);
					if($line0=='[WPL]'){
						if(empty($line1) !== true){
							if(Server::getInstance()->loadLevel($line1) != false){
								//Initialize vars
								$level = $line1;
							    $this->saveLevelSign($event->getPlayer()->getLevel()->getName(), $sign->x, $sign->y, $sign->z, $line1);
								//Checking Custom Name
								if(empty($line2) == true){
									$event->setLine(0, $line1);
								}else{
									$event->setLine(0, $line2);
								}
								//Checking Player Limit
								$tmp = $this->getPlayerLimit($line1);
								if($tmp == false){
									$event->setLine(1, "Click to join.");
									$event->setLine(2, "");
									$event->setLine(3, "");
								}else{
									$event->setLine(1, $this->CountLevelPlayers($line1) . "/" . $tmp);
									$event->setLine(2, "Click to join.");
									$event->setLine(3, "");
								}
							}else{
								$event->getPlayer()->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cCan't create sign: World not found."));
							}
						}
					}
				}
			}
		}
	}
	
	public function removeLevelData($level){
		$level = strtolower($level);
		$this->plugin->worlds->remove($level);
		$this->plugin->worlds->save();
	}
	
	public function saveLevelData($level, $limit){
		$level = strtolower($level);
		$tmp = $this->plugin->worlds->getAll();
		$tmp[$level]["limit"] = $limit;
		$this->plugin->worlds->setAll($tmp);
		$this->plugin->worlds->save();
	}
	
	public function deleteLevelSign($level, $x, $y, $z, $dest){
		if(file_exists($this->plugin->getDataFolder() . "signs/" . strtolower($level . "/") . strtolower($x."-".$y."-".$z.".yml"))){
			unlink($this->plugin->getDataFolder() . "signs/" . strtolower($level . "/") . strtolower($x."-".$y."-".$z.".yml"));
			return true; //Success!
		}else{
			return false; //Failed: Sign not found.
		}
	}
	
	public function saveLevelSign($level, $x, $y, $z, $dest){
		@mkdir($this->plugin->getDataFolder() . "signs/" . strtolower($level . "/"));
			$chest = new Config($this->plugin->getDataFolder() . "signs/" . strtolower($level . "/") . strtolower($x."-".$y."-".$z.".yml"), Config::YAML);
			$chest->set("world", $dest);
			$chest->save();
	}
	
	public function getSignDest($level, $x, $y, $z){
		if($this->isRegistered($level, $x, $y, $z)){
			$sign = new Config($this->plugin->getDataFolder() . "signs/" . strtolower($level . "/") . strtolower($x."-".$y."-".$z.".yml"), Config::YAML);
			$tmp = $sign->get("world");
			return $tmp; //Success!
		}else{
			return false; //Failed: Sign not found.
		}
	}
	
	public function isRegistered($level, $x, $y, $z){
		return file_exists($this->plugin->getDataFolder() . "signs/" . strtolower($level . "/") . strtolower($x."-".$y."-".$z.".yml"));
	}
	
	public function getPlayerLimit($level){
		$level = strtolower($level);
		$tmp = $this->plugin->worlds->getAll();
		if(isset($tmp[$level]["limit"])){
			return $tmp[$level]["limit"];
		}else{
			return false;
		}
	}
	
	public function CountLevelPlayers($level){
		$level = strtolower($level);
		if($this->plugin->getServer()->getLevelByName($level)){
			$count = $this->plugin->getServer()->getLevelByName($level)->getPlayers();
			$num = 0;
			for($i=1; $i<=count($count); $i++){
				$num = $num + 1;
			}
			return $num;
		}else{
			return false;
		}
	}

}
?>
