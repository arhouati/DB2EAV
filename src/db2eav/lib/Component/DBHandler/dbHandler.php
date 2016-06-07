<?php 
namespace db2eav\lib\Component\DBHandler;

use db2eav\lib\Component\SettingsFile\SettingsFile;
use db2eav\lib\Component\DBHandler\MysqlHandler;
use db2eav\lib\Component\Debug\debug;

class DbHandler{
	private static $_instance = null;
	public $pdoConnector;
	public $targetHost = null;
	public $hostParam;

	/**
	 * @param null $targetHost
     */
	private function __construct( $targetHost = null ){
		$file = new SettingsFile( __dir__ . "/../../../config/db.config.xml" );

		if( $targetHost ) $this->targetHost = $targetHost;
		
		$this->hostParam = $this->getHostParamFromFile($file);

        $classNameDBHandlers = "db2eav\\lib\\Component\\DBHandler\\".$this->hostParam["DataBaseHandle"] . "Handler";
        $DBHandler = new $classNameDBHandlers();

		try{
            $this->pdoConnector = new \PDO( $DBHandler->host . $this->hostParam["DataBaseHost"]. ';port=' . $this->hostParam["DataBasePort"] . ';dbname=' . $this->hostParam["DataBaseName"], $this->hostParam["DataBaseLogin"], $this->hostParam["DataBasePassWord"]);
        }catch (\Exception $e){
            debug::log( $e->getCode() . " - " . $e->getMessage());
        }

    }
	
	public function __destruct(){
		
	}
	
	public function switchTargetHost( $targetHost ){
		
		if( $this->targetHost == $targetHost ) return false;
		
		$this->targetHost = $targetHost;
		
	}
	
	public static function getInstance( $targetHost = null ){
		
		if( true === is_null( self::$_instance ) )
       {
           self::$_instance = new self( $targetHost );
       }

       return self::$_instance;
	}
	
	public function execute( $sql ){
		
		$pdoStatement = $this->pdoConnector->prepare( $sql );
		
		$pdoStatement->execute();
		
		return $pdoStatement->fetchAll();
	}
	
	private function getHostParamFromFile( $file )
	{
		if( $this->targetHost == null ) {
			$hostParamNode = $file->DefaultHost;
		}else{
			$host = $this->targetHost;
			$hostParamNode = $file->$host;
		}

		//var_dump($hostParamNode);
		
		$hostParam = array();
		foreach($hostParamNode["children"] as $param){
			$hostParam[$param["name"]] = (String)$param["attributes"]["value"];
		}

		return $hostParam;
	}
}