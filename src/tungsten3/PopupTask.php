<?php

namespace Tungsten3;

use pocketmine\plugin\Plugin;
use pocketmine\scheduler\Task;


class PopupTask extends Task{

	private $owner;

	public function __construct(Plugin $owner){
		$this->owner = $owner;
	}


	public function onRun($tick){
		$this->owner->ShowChat();
	}
}