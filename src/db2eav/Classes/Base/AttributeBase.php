<?php
namespace db2eav\Classes\Base;

use db2eav\lib\Component\SettingsFile\SettingsFile;
use db2eav\lib\Component\DBHandler\dbHandler;
use db2eav\Classes\Content;

class AttributeBase {

	/* attribute */
	private $id;
	private $identifier;
	private $type;
	private $value;
	
	/* database driver */
	protected static $db = null;
	
	/* param's files */
	protected static $fileDBConfig = null;
	protected static $file = null;
	
	/* SQL Request */
	protected static $sqlSelectAttributes = "select %id_attribute%, %name_attribute%, %type_attribute% from %table% where %id_entity% = '%id%'";
	protected static $sqlInsertAttributes = "insert into %table% (%name_attribute%, %type_attribute%, %id_entity%) values ('%nameAttribute%', '%TypeAttribute%' , '%idEntity%')";
	protected static $sqlDeleteAttributes="delete from %table% where %idAtt% = '%id_attribute%'";
	protected static $sqlDeleteAttributeValues=" delete from %table% where %idAttribute% = '%id_attribute%'";
	protected static $sqlSelectAttributeDBType=" select %typeDB_attribute% from %table% where %idAttribute% = '%id_attribute%'";
	
	
	public function __construct($id, $identifier, $type){
	
		$this->id = $id;
		$this->identifier = $identifier;
		$this->type = $type;
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

		return $this->identifier . ' = ' . $this->value;
	}
	
	public static function __init(){
		static::$db = dbHandler::getInstance();
		static::$file = new SettingsFile(__dir__  .  "/../../config/eav-" . static::$db->hostParam["DataBaseCMS"] . ".config.xml");
	}
	
	public static function getAllAttributeByEntity( $idEntity ){
		
		if( static::$db == null ) static::__init();
		
		$attributeParams = static::$file->AttributeTable["attributes"];

		$sqlRequest = str_replace( array('%id_attribute%', '%name_attribute%', '%type_attribute%', '%table%', '%id_entity%', '%id%'),
				array( (String) $attributeParams["id"], (String) $attributeParams["identifier"], (String) $attributeParams["type"], (String) $attributeParams["name"], (String) $attributeParams["idEntity"], $idEntity),
				static::$sqlSelectAttributes );
		
		$allAttribtues = array();
		foreach( static::$db->execute( $sqlRequest ) as $element){
		 	array_push($allAttribtues, new self($element[0], $element[1], $element[2]) );
		}
		
		return $allAttribtues;
	}
	
	public function addAttributeToEntity($idEntity, $nameAtribute, $typeAttribute){
		if( static::$db == null ) static::__init();

		$attributeParams = static::$file->AttributeTable["attributes"];
		static::$sqlInsertAttributes = str_replace( array('%nameAttribute%', '%TypeAttribute%', '%table%', '%idEntity%', '%name_attribute%', '%type_attribute%', '%id_entity%' ),
					array($nameAtribute , $typeAttribute, (String) $attributeParams["name"], $idEntity, (String) $attributeParams["identifier"], (String) $attributeParams["type"],(String) $attributeParams["idEntity"]),
					static::$sqlInsertAttributes);

		static::$db->execute( static::$sqlInsertAttributes );
	}
	
	//function to delete attribute values
	public static function deleteAttributeValues($id){
		if( static::$db == null ) static::__init();
		$contentParams = static::$file->ValueTable["attributes"];
		$children = static::$file->ValueTable["children"];
		foreach($children as $child){
			static::$sqlDeleteAttributeValues = str_replace(array('%table%','%idAttribute%','%id_attribute%'),
					array((String) $child["attributes"]["name"],(String) $child["attributes"]["id"],$id),
					static::$sqlDeleteAttributeValues );
			static::$db->execute( static::$sqlDeleteAttributeValues );
		}
	}
	
	//function to delete attributes
	public static function deleteAttribute($id,$idEntity){		
		if( static::$db == null ) static::__init();
		$listContent = Content::getAllContentByEntity( $idEntity);
		foreach($listContent as $content){
			foreach($content->entity->attributes as $attribute){
				if($attribute->id ==$id){
					static::deleteAttributeValues($attribute->id);
				}
			}
		}
		
		$attributeParams = static::$file->AttributeTable["attributes"];
		static::$sqlDeleteAttributes = str_replace(array('%table%','%idAtt%','%id_attribute%'),
													array((String) $attributeParams["name"],(String) $attributeParams["id"],$id),
													static::$sqlDeleteAttributes );
		static::$db->execute( static::$sqlDeleteAttributes );
	
	}
	
	//Get Types of Attributes From Xml File:
	public function getTypesAttributes(){
		$type=array();
		if( static::$db == null ) static::__init();
	    $typesParams = static::$file-> TypeAttributes["attributes"];	
	    $children = static::$file->TypeAttributes["children"];
		foreach($children as $child){
			$typeAttribute=$child["attributes"]["name"];
			array_push($type,$typeAttribute);
		}
		return $type;
	}
}