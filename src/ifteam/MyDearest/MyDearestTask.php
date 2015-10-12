<?php

namespace ifteam\MyDearest;

use pocketmine\scheduler\PluginTask;
use pocketmine\plugin\Plugin;

class MyDearestTask extends PluginTask {
	public $type;
	public function __construct(Plugin $owner, $type) {
		parent::__construct ( $owner );
		$this->type = $type;
	}
	public function onRun($currentTick) {
		switch ($type) {
			case "reset" :
				$this->getOwner ()->Reset ();
				break;
			case "shutdown" :
				$this->getOwner ()->Shutdown ();
				break;
		}
	}
}
