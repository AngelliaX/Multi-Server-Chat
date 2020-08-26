<?php

namespace tungsten3;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\{Command,CommandSender, CommandExecutor, ConsoleCommandSender};

use pocketmine\Player;
use pocketmine\event\player\PlayerChatEvent as Chat;

class Main extends PluginBase implements Listener{
 

    private $task;

    //Edit your message in line 59
	
	public $numberofserver = 2;
	public $thisserver = "Sky";
	
    public $server = ["Sur","Minigame"];
	
	public $connect;
	
	public $list = array();
    public function onEnable()
    {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->task = new PopupTask($this);
	    $this->getScheduler()->scheduleRepeatingTask($this->task,0);
		$this->connect = new connectMysql($this);
        
    }
    public function PlayerChatMessage(Chat $e){
     $p = $e->getPlayer();
     $n = $p->getName();
     $msg = $e->getMessage();
	 for($i = 0;$i<$this->numberofserver-1;$i++){
      $insert = "INSERT INTO ".$this->server[$i]." (server,name,message) VALUES ('$this->thisserver','$n','$msg')";
      if (!$this->connect->db->query($insert)) {
         $this->getLogger()->info("Error: " . $this->connect->db->error);
	  }
	 }
    }

    public function ShowChat(){
       $result = $this->connect->db->query("SELECT * FROM ".$this->thisserver);
        if (!$result) {
            print($this->connect->db->error);
        }else{     
            while ($row = $result->fetch_assoc()){		
			  foreach ($this->getServer()->getOnlinePlayers() as $player){
			   $name = $player->getName();				   
			   if(in_array($name,$this->list)){			   
			   }else{
				 //print("2[".$row["server"]."] ".$row["name"]." -> ".$row["msg"]);	
                 $player->sendMessage("[".$row["server"]."] ".$row["name"]." -> ".$row["message"]);
			   }
			  }
			  $del =$this->connect->db->query("DELETE FROM ".$this->thisserver." WHERE id = ".$row["id"]);	
		      if (!$del){
              print($this->connect->db->error);}			 		     
			}
	    }
    }
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
	  switch(strtolower($cmd->getName())){
          case "chaton":
		   if(in_array($sender->getName(),$this->list)){
			   unset($this->list[array_search($sender->getName(), $this->list)]);
			   $sender->sendMessage("Turn on success");
		   }else{
			   $sender->sendMessage("you already turn on");
		   }
		  break;
          case "chatoff":
		  if(in_array($sender->getName(),$this->list)){
			$sender->sendMessage("you already turn off ");
		  }else{
		   array_push($this->list,$sender->getName());
		   $sender->sendMessage("Turn off success");
		  }
		  break;
		  
		  
          case "stopmultichat": 
		   /*
           for emersency
		   */				  
           $this->getScheduler()->cancelTask($this->task->getTaskId());
           break;
	  }

	 return true;
	}
   
}