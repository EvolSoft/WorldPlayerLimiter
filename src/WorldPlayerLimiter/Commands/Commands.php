<?php

/*
 * WorldPlayerLimiter (v0.4 B2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 27/12/2014 03:22 PM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/WorldPlayerLimiter/blob/master/LICENSE)
 */

namespace WorldPlayerLimiter\Commands;

use pocketmine\plugin\PluginBase;
use pocketmine\permission\Permission;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use WorldPlayerLimiter\Main;
use WorldPlayerLimiter\Signs;

class Commands extends PluginBase{
	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		$fcmd = strtolower($cmd->getName());
    	switch($fcmd){
    		case "worldplayerlimiter":
    			if(isset($args[0])){
    				$args[0] = strtolower($args[0]);
    				$tmp = new Signs($this->plugin);
    				if($args[0]=="help"){
    					if($sender->hasPermission("worldplayerlimiter.commands.help")){
    						$sender->sendMessage($this->plugin->translateColors("&", "&9<> &2Available Commands &9<>"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl addworld &9<>&2 Add world data"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl delworld &9<>&2 Delete world data"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl info &9<>&2 Show info about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl reload &9<>&2 Reload the config"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl stats &9<>&2 Get world stats"));
    						break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="info"){
    					if($sender->hasPermission("worldplayerlimiter.commands.info")){
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&2WorldPlayerLimiter &9v" . Main::VERSION . " &2developed by&9 " . Main::PRODUCER));
    			   	        $sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&2Website &9" . Main::MAIN_WEBSITE));
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="reload"){
    					if($sender->hasPermission("worldplayerlimiter.commands.reload")){
    						$this->plugin->worlds->reload();
    						$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aConfiguration reloaded."));
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="stats"){
    					if($sender->hasPermission("worldplayerlimiter.commands.stats")){
    						//Player Sender
    						if($sender instanceof Player){
    							if(isset($args[1])){
    								//Check if world exists
    								if(Server::getInstance()->loadLevel($args[1]) != false){
    									$tpl = $tmp->getPlayerLimit($args[1]);
    									if($tpl == false){
    										$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aThere are &c" . $tmp->CountLevelPlayers($args[1]) . "&a Player(s)"));
    									    break;
    									}else{
    										$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aThere are &c" . $tmp->CountLevelPlayers($args[1]) . "&a Player(s) of &c" . $tpl));
    										break;
    									}
    								}else{
    									$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cWorld " . $args[1] . " not found."));
    									break;
    								}
    							}else{
    								$tpl = $tmp->getPlayerLimit($sender->getLevel()->getName());
    								if($tpl == false){
    									$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aThere are &c" . $tmp->CountLevelPlayers($sender->getLevel()->getName()) . "&a Player(s)"));
    									break;
    								}else{
    									$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aThere are &c" .  $tmp->CountLevelPlayers($sender->getLevel()->getName()) . "&a Player(s) of &c" . $tpl));
    									break;
    								}
    							}
    						}
    						//Command Sender
    						if($sender instanceof CommandSender){
    							if(isset($args[1])){
    								//Check if world exists
    								if(Server::getInstance()->loadLevel($args[1]) != false){
    									$tpl = $tmp->getPlayerLimit($args[1]);
    									if($tpl == false){
    										$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aThere are &c" . $tmp->CountLevelPlayers($args[1]) . "&a Player(s)"));
    										break;
    									}else{
    										$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aThere are &c" . $tmp->CountLevelPlayers($args[1]) . "&a Player(s) of &c" . $tpl));
    										break;
    									}
    								}else{
    									$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cWorld " . $args[1] . " not found."));
    									break;
    								}
    							}else{
    								$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cUsage: /wpl stats <worldname>"));
    							}
    						}
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="addworld"){
    					if($sender->hasPermission("worldplayerlimiter.commands.addworld")){
    						if(isset($args[1]) && isset($args[2]) && is_numeric($args[2])){
    							//Check if world exists
    							if($this->plugin->getServer()->getLevelByName($args[1])){
    								$tmp->saveLevelData($args[1], $args[2]);
    								$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aWorld data saved."));
    							}else{
    								$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cWorld " . $args[1] . " not found."));
    							}
    						}else{
    							$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cUsage: /wpl addworld <worldname> <limit>"));
    						}
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}elseif($args[0]=="delworld"){
    					if($sender->hasPermission("worldplayerlimiter.commands.delworld")){
    						if(isset($args[1])){
    							if($tmp->removeLevelData($args[1])){
    								$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&aWorld data deleted."));
    							}else{
    								$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cWorld data not found."));
    							}
    						}else{
    							$sender->sendMessage($this->plugin->translateColors("&", Main::PREFIX . "&cUsage: /wpl delworld <worldname>"));
    						}
    				        break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}
    				}else{
    					if($sender->hasPermission("worldplayerlimiter.commands.help")){
    						$sender->sendMessage($this->plugin->translateColors("&", "&9<> &2Available Commands &9<>"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl addworld &9<>&2 Add world data"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl delworld &9<>&2 Delete world data"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl info &9<>&2 Show info about this plugin"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl reload &9<>&2 Reload the config"));
    						$sender->sendMessage($this->plugin->translateColors("&", "&2/wpl stats &9<>&2 Get world stats"));
    						break;
    					}else{
    						$sender->sendMessage($this->plugin->translateColors("&", "&cYou don't have permissions to use this command"));
    						break;
    					}
    				}
    			}
	  }
}
?>
