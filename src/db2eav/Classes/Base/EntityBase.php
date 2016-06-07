<?php 
namespace db2eav\Classes\Base;

use db2eav\lib\Component\SettingsFile\SettingsFile;
use db2eav\lib\Component\DBHandler\dbHandler;
use db2eav\Classes\Attribute;

// TODO : EntityBase handle errors using exception

class EntityBase {

	/* attributes */
	protected $id;
	protected $name;
	protected $other = array();
	protected $attributes = array();
	
	/* database driver */
	protected static $db = null;
	
	/* param's files */
	protected static $fileDBConfig = null;
	protected static $file = null;
	
	/* Generic SQL Requests */
	protected static $sqlSelectAll = "select %id%, %identifier% %other% from %table%";
	protected static $sqlSelectById = "select %id%, %identifier% %other% from %table% where %identifier% = %id%";
	protected static $sqlInsert="insert into %table% (%identifier%) values ('%entityName%') ";
	protected static $sqlDelete="delete from %table% where %idEntity%='%id_entity%' ";
	protected static $sqlDeleteAttributesToEntity="delete from %table% where %idEntity%='%id_entity%' ";
	protected static $sqlDeleteContentToEntity="delete from %table% where %idEntity%='%id_entity%' ";
	
	public function __construct( $id = null ){
	
		if( static::$db == null ) static::__init();
	
		$this->id = $id;
		
		$this->getEntityByID();
	
		$this->attributes = $this->getListAttributes();
	}
	
	public function __destruct(){
	
	}
	
	public function __get( $attribute ){
	
		return $this->$attribute;
	}
	
	public function __set( $attribute, $value){
	
		$this->$attribute = $value;
	}
	
	public function __toString()
	{
		return 'Entity Id = ' . $this->id;
	}
	
	public static function __init( $targetHost = null  ){
		static::$db = dbHandler::getInstance( $targetHost );
		static::$file = new SettingsFile(__dir__  .  "/../../config/eav-" . static::$db->hostParam["DataBaseCMS"] . ".config.xml");
	}
	
	private function getListAttributes(){
		return Attribute::getAllAttributeByEntity( $this->id );
	}
	
	private function getEntityByID(){
		
		$entityParams = static::$file->EntityTable["attributes"];
		
		static::$sqlSelectAll = str_replace( array('%id%', '%identifier%', '%table%', '%other%', '%id%'),
				array(  (String) $entityParams["id"],
						(String) $entityParams["identifier"],
						(String) $entityParams["name"],
						isset($entityParams["other"]) ? ', ' . $entityParams["other"]  : '',
						$this->id
				),
				static::$sqlSelectAll );
		
		$otherArray = array();
		if( isset($entityParams["other"]))
			$otherArray = explode( ',', $entityParams["other"] );
		
		foreach( static::$db->execute( static::$sqlSelectAll ) as $element ){ 
			$this->name = $element[1];
				
			foreach($otherArray as $chmp)
				$this->other[$chmp] = $element[$chmp];
		
		}	
		
	}
	
	public static function getAllEntities( $targetHost = null ){
	
		if( static::$db == null ) static::__init( $targetHost );
		
		$entityParams = static::$file->EntityTable["attributes"];
	
		static::$sqlSelectAll = str_replace( array('%id%', '%identifier%', '%table%', '%other%'),
				array(  (String) $entityParams["id"], 
						(String) $entityParams["identifier"], 
						(String) $entityParams["name"], 
						isset($entityParams["other"]) ? ', ' . $entityParams["other"]  : '' 
					),
				static::$sqlSelectAll );
		
		$otherArray = array();
		if( isset($entityParams["other"]))
			$otherArray = explode( ',', $entityParams["other"] );

		$allEntities = array();
		foreach( static::$db->execute( static::$sqlSelectAll ) as $element ){
			$entity = new self( $element[0] );
			$entity->name = $element[1];
			
			foreach($otherArray as $chmp)
				$entity->other[$chmp] = $element[$chmp];

			array_push($allEntities, $entity);
		}
	
		return $allEntities;
	
	}
	public static function addEntity($entityName, $targetHost = null){
		if( static::$db == null ) static::__init( $targetHost );
		$entityParams = static::$file->EntityTable["attributes"];
		
		static::$sqlInsert = str_replace( array('%identifier%',  '%table%', '%entityName%'),
				array(  
				(String) $entityParams["identifier"],
				(String) $entityParams["name"],
				$entityName
				),
				static::$sqlInsert );	
		static::$db->execute( static::$sqlInsert );
	}
	
	
	public static function deleteEntity( $idEntity, $targetHost = null){
	  
		if( static::$db == null ) static::__init($targetHost);

		//Delete Content of Entity :
		$contentParams = static::$file->ContentTable["attributes"];
		static::$sqlDeleteContentToEntity = str_replace(array('%table%','%idEntity%','%id_entity%'),
														array((String) $contentParams["name"],(String) $contentParams["idEntity"],$idEntity),
														static::$sqlDeleteContentToEntity );
		static::$db->execute( static::$sqlDeleteContentToEntity );
				
		//Delete Attributes of Entity :
		$attributeParams = static::$file->AttributeTable["attributes"];
		static::$sqlDeleteAttributesToEntity = str_replace(array('%table%','%idEntity%','%id_entity%'),
															array((String) $attributeParams["name"],(String) $attributeParams["idEntity"],$idEntity),
															static::$sqlDeleteAttributesToEntity );
		static::$db->execute( static::$sqlDeleteAttributesToEntity );
	
		//Delete Entity :
		$entityParams = static::$file->EntityTable["attributes"];
		static::$sqlDelete = str_replace(array('%table%','%idEntity%','%id_entity%'),
											array((String) $entityParams["name"],(String) $entityParams["id"],$idEntity),
											static::$sqlDelete );
		static::$db->execute( static::$sqlDelete );
	}
}