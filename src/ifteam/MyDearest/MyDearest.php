<?php

namespace ifteam\MyDearest;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\level\Level;

class MyDearest extends PluginBase implements Listener {
	public $resetTimer;
	public function onEnable() {
		@mkdir ( $this->getDataFolder () );
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
		$this->resetTimer = (new Config ( $this->getDataFolder () . "settings.yml", Config::YAML, [ 
				"resetCycle" => 1800,
				"message-1" => "[안내] 서버가 10초뒤 5~10초간 재부팅됩니다 *자동재부팅*",
				"message-2" => "[안내] 서버가 재부팅됩니다.." 
		] ))->getAll ();
		$this->getServer ()->getScheduler ()->scheduleDelayedTask ( new MyDearestTask ( $this, "reset" ), $this->resetTimer ["resetCycle"] * 20 );
	}
	/**
	 *
	 * @var autoReset Notification
	 */
	public function Reset() {
		$this->getServer ()->broadcastMessage ( TextFormat::DARK_PURPLE . $this->resetTimer ["message-1"] );
		$this->getServer ()->getScheduler ()->scheduleDelayedTask ( new MyDearestTask ( $this, "shutdown" ), 20 * 10 );
	}
	/**
	 *
	 * @var execute autoReset
	 */
	public function Shutdown() {
		$this->getServer ()->broadcastMessage ( TextFormat::DARK_PURPLE . $this->resetTimer ["message-2"] );
		foreach ( $this->getServer ()->getOnlinePlayers () as $player ) {
			$player->save ();
		}
		foreach ( $this->getServer ()->getOnlinePlayers () as $player ) {
			$player->kick ( "서버가 곧 재부팅됩니다" );
		}
		foreach ( $this->getServer ()->getPluginManager ()->getPlugins () as $plugin ) {
			if ($plugin->getName () != "MyDearest")
				if ($plugin->isEnabled ())
					$this->getServer ()->getPluginManager ()->disablePlugin ( $plugin );
		}
		foreach ( $this->getServer ()->getLevels () as $level )
			if ($level instanceof Level)
				$level->unload ();
		$this->getServer ()->shutdown ();
	}
	public function onDisable() {
		foreach ( $this->getServer ()->getOnlinePlayers () as $player ) {
			$player->save ();
		}
		foreach ( $this->getServer ()->getOnlinePlayers () as $player ) {
			$player->kick ( "서버가 곧 재부팅됩니다" );
		}
		foreach ( $this->getServer ()->getPluginManager ()->getPlugins () as $plugin ) {
			if ($plugin->getName () != "MyDearest")
				if ($plugin->isEnabled ())
					$this->getServer ()->getPluginManager ()->disablePlugin ( $plugin );
		}
		foreach ( $this->getServer ()->getLevels () as $level )
			if ($level instanceof Level)
				$level->unload ();
		
		foreach ( $this->getServer ()->getNetwork ()->getInterfaces () as $interface ) {
			$interface->shutdown ();
			$this->getServer ()->getNetwork ()->unregisterInterface ( $interface );
		}
		passthru ( \pocketmine\DATA . "MyDearest.cmd" );
	}
}

?>