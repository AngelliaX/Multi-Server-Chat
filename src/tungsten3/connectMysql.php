<?php

namespace tungsten3;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\server\QueryRegenerateEvent;
class connectMysql{
   
 
    /** @var static string , dont change */
	public $db;
    public $host = "127.0.0.1";
    public $user = "root";
    public $password = "";
    public $dbname = "tungst_multic_DB";
    //public $tbname = "tungsten_tb";
    public $query;
	public $pl;
	
	public function __construct(Main $pl){
	   $this->pl = $pl;
       $this->connectMysqlwithDB();
	}
	
	public function connectMysqlwithDB(){
		$this->db = @new \mysqli($this->host, $this->user, $this->password,$this->dbname);
        if ($this->db->connect_error) {
			$this->pl->getLogger()->info("\n\n              §aDont have database, Going to create one....\n");
           $this->connectMysqlwithoutDB();
        }else{
			$this->pl->getLogger()->info("\n\n               §aMultiChat enabled\n");
			$this->createTB();		
		}
	}
	public function connectMysqlwithoutDB(){
		$this->db = @new \mysqli($this->host, $this->user, $this->password);
        if (!$this->db->connect_error) {
           $this->createDB();
        }else{
			$this->pl->getLogger()->info("\n             §cYou didnt enable a mysql service,so the plugin cannot be enable...\n");
		    $this->pl->getServer()->getPluginManager()->disablePlugin($this->pl);
		}
		
	}
	public function createDB(){
		if (!$this->db->query("CREATE DATABASE IF NOT EXISTS ".$this->dbname)){
			$this->getLogger()->info("Failed on creating: " . $this->db->error);
		}else{
		$this->connectMysqlwithDB();
		}
	}
	
	public function createTB(){
		if (!$this->db->query("CREATE TABLE IF NOT EXISTS ".$this->pl->thisserver." (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,server TEXT,name TEXT,message TEXT);")){
			$this->getLogger()->info("Failed on creating table: " . $this->db->error);
		 }
		for($i = 0;$i<$this->pl->numberofserver -1;$i++){
		 if (!$this->db->query("CREATE TABLE IF NOT EXISTS ".$this->pl->server[$i]." (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,server TEXT,name TEXT,message TEXT);")){
			$this->getLogger()->info("Failed on creating table: " . $this->db->error);
		 }
		}
	}
	
	
	
	
	
}