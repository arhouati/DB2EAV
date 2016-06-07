<?php 
namespace db2eav\Classes\Base;

use db2eav\lib\Component\SettingsFile\SettingsFile;
use db2eav\lib\Component\DBHandler\dbHandler;

use db2eav\Classes\Entity;
use db2eav\Classes\Attribute;
use db2eav\Classes\Value;

class ContentBase {
	
	/* attributes */
	protected $entity;
	protected $id;
	
	// TODO : analyse if identifier and attributes must be public or private
	public $identifier;
	public $attributes;
	
	/* database */
	public static $db = null;
	
	/* param's files */
	public static $fileDBConfig = null;
	public static $file = null;
	
	protected static $sqlAllContentByEntity = "select %id%, %identifier% from %table% where %idEntity% = '%entity%'";
	protected static $sqlContentByIdAndEntity = "select %id%, %identifier% from %table% where %idEntity% = '%entity%' and %id% = '%content%' limit 1";
	protected static $sqlAttributeValueByContentAndEntity = "select %value% from %table% where %idContent% = '%content%' and %id% = '%attribute%' limit 1";
	protected static $sqlInsertContent="insert into %table% (%nameContent%,%id_entity%) values ('%name_content%','%idEntity%') ";
	protected static $sqlInsertValue="insert into %table% (%idAttribute%,%value%,%idContent%) values ('%id_attribute%','%_value%','%id_content%') ";
	protected static $sqlDeleteContent="delete from %table% where %idContent%='%id_content%'";
	protected static $sqlDeleteContentValue="delete from %table% where %idContentValue%='%id_content_value%'";
	protected static $sqlIdContentByIdentifierAndEntity="select %id% from %table% where %idEntity% = '%entity%' AND %identifier%='%_identifier%' limit 1";
	protected static $sqlUpdateValue="update %table% set %value%='%_value%'  where %idContent%=%id_content% AND  %idAttribute%=%id_attribute%";
	protected static $sqlUpdateContentIdentifier="update %table% set %nameContent%= '%name_content%'  where %id_entity%=%idEntity% AND  %idContent%=%id_content%";
	
	
	public function __construct( $id, $identifier, $idEntity ){
		if( static::$db == null ) static::__init();
	
		$this->id = $id;
		$this->identifier = $identifier;
		$this->entity = new Entity( $idEntity );
	
		$this->loadContentAttributeValues();
	}
	
	public function __destruct(){
	
	}
	
	public function __get( $attribute ){
			
		return $this->$attribute;
	}
	
	public function __set( $attribute, $value){
	
		$this->$attribute = $value;
	}
	
	public function __toString(){
		return  $this->entity . ' - ' . $this->id . ' '. $this->identifier;
	}
	
	public static function __init(){
		static::$db = dbHandler::getInstance();
		static::$file = new SettingsFile(__dir__  .  "/../../config/eav-" . static::$db->hostParam["DataBaseCMS"] . ".config.xml");
	}
	
	public static function getAllContentByEntity( $idEntity ){
	
		if( static::$db == null ) static::__init();
		
		$contentParams = static::$file->ContentTable["attributes"];

		while( strpos( static::$sqlAllContentByEntity , "%" ) ){
			static::$sqlAllContentByEntity = str_replace( array( '%id%', '%identifier%', '%table%', '%idEntity%', '%entity%' ),
					array( (String) $contentParams["id"], (String) $contentParams["identifier"], (String) $contentParams["name"], (String) $contentParams["idEntity"], $idEntity ),
					static::$sqlAllContentByEntity );
		}

		$allContentByEntity = array();
		foreach(static::$db->execute( static::$sqlAllContentByEntity ) as $element){
			array_push( $allContentByEntity, new static( $element[0], $element[1], $idEntity ) );
		}

		return $allContentByEntity;
	}
	
	public static function getContentByIdAndEntity( $id, $idEntity){
	
		if( static::$db == null ) static::__init();
		
		$contentParams = static::$file->ContentTable["attributes"];
	
		while(  strpos( static::$sqlContentByIdAndEntity , "%" ) ){
			static::$sqlContentByIdAndEntity = str_replace( array( '%id%', '%identifier%', '%table%', '%idEntity%', '%entity%', '%content%' ),
					array( (String) $contentParams["id"], (String) $contentParams["identifier"], (String) $contentParams["name"], (String) $contentParams["idEntity"], $idEntity, $id ),
					static::$sqlContentByIdAndEntity );
				
		}
	
		foreach(static::$db->execute( static::$sqlContentByIdAndEntity ) as $element){
			return new static( $element[0], $element[1], $idEntity);
		}
	}
	
	private function loadContentAttributeValues(){
	
		foreach( $this->entity->attributes as $attribute ){
			// TODO : analyse if the method getAttributeValue can be moved to attribute class
			$this->attributes[$attribute->identifier] = $this->getAttributeValue( $attribute->id, $attribute->type );
		}
	}
	
	private function getAttributeValue( $idAttribute, $typeAttribute ){
	
		$attributeParam = static::$file->ValueTable["attributes"];
	
		$children = static::$file->ValueTable["children"];
	
		foreach( $children as $child){
				
			if( $child["attributes"]["type"] == "default" ){
				$attributeParam = $child["attributes"];
			}elseif ( $child["attributes"]["type"] == $typeAttribute ){
				$attributeParam = $child["attributes"];
				break;
			}
			}
	
	
		static::$sqlAttributeValueByContentAndEntity = "select %value% from %table% where %idContent% = '%content%' and %id% = '%attribute%' limit 1";
	
		while(  strpos( static::$sqlAttributeValueByContentAndEntity , "%" ) ){
			// TODO : initialise request sql  from variable static, not use a sql string
			static::$sqlAttributeValueByContentAndEntity = str_replace( array( '%value%', '%table%','%idContent%', '%content%', '%id%', '%attribute%', '%type%', '%identifier%'  ),
					array( (String) $attributeParam["value"], (String) $attributeParam["name"], (String) $attributeParam["idContent"], $this->id,  (String) $attributeParam["id"], $idAttribute, $typeAttribute, $idAttribute  ),
					static::$sqlAttributeValueByContentAndEntity );
		}
	
		foreach(static::$db->execute( static::$sqlAttributeValueByContentAndEntity ) as $element){
			return $element[0];
		}
	}
	 
	 //Add Content 
	 public function addContentToEntity($idEntity,$nameContent){
	 	$idExistedContent = static::getIdContentByIdentifierAndEntity( $nameContent, $idEntity);
	 	if (isset($idExistedContent)){
	 		echo "<SCRIPT LANGUAGE=\"JavaScript\">
						alert('The name of the content already exists, please choose an other name');
						document.location.href=\"addContentToEntity.php?id=".$idEntity."\"
					</SCRIPT>";
	 	} else{
			 if( static::$db == null ) static::__init();
			$contentParams = static::$file->ContentTable["attributes"];
	
			static::$sqlInsertContent = str_replace( array( '%table%','%nameContent%', '%name_content%' ,'%idEntity%','%id_entity%'),
					array( (String) $contentParams["name"],(String) $contentParams["identifier"], $nameContent, $idEntity,(String) $contentParams["idEntity"]),
					static::$sqlInsertContent );
	
			static::$db->execute( static::$sqlInsertContent );
	    	static::$sqlInsertContent;
		
	 	}
	 }
	 
	  public function updateContentIdentifier($idEntity,$nameContent, $idContent){
	 		$idExistedContent=static::getIdContentByIdentifierAndEntity( $nameContent, $idEntity);
			 if (isset($idExistedContent) && $idExistedContent != $idContent ){
	 				echo "<SCRIPT LANGUAGE=\"JavaScript\">
							alert('The name of the content already exists, please choose an other name');
							document.location.href=\"listcontent.php?id=".$idEntity."\"
							</SCRIPT>";
	 
	 
			 } else{
				 if( static::$db == null ) static::__init();
				 $contentParams = static::$file->ContentTable["attributes"];
	
				static::$sqlUpdateContentIdentifier = str_replace( array( '%table%','%nameContent%', '%name_content%' ,'%idEntity%','%id_entity%' , '%idContent%' , '%id_content%'),
																array( (String) $contentParams["name"],(String) $contentParams["identifier"],$nameContent, $idEntity,(String) $contentParams["idEntity"], (String) $contentParams["id"], $idContent),
																static::$sqlUpdateContentIdentifier );
	
				static::$db->execute( static::$sqlUpdateContentIdentifier );
			 }
	  }
	 
	  public function addValueToContent($idAttribute, $value, $idContent, $typeAttribute){

		  $sql=static::$sqlInsertValue;

		  if( static::$db == null ) static::__init();
		  $attributeParam = static::$file->ValueTable["attributes"];

		  $children = static::$file->ValueTable["children"];
		  foreach( $children as $child){
				if( $child["attributes"]["type"] == "default" ){
					$attributeParam = $child["attributes"];
				}elseif ( $child["attributes"]["type"] == $typeAttribute ){
					$attributeParam = $child["attributes"];
					break;
				}
		  }

		  $sql= str_replace( array('%table%', '%idAttribute%','%value%', '%idContent%','%id_attribute%' , '%_value%', '%id_content%' ),
			  				array( (String) $attributeParam["name"],(String) $attributeParam["id"],(String) $attributeParam["value"], $attributeParam["idContent"] , $idAttribute, $value , $idContent ),
							$sql);

		  static::$db->execute( $sql );
	  }
								  
	 
	 public function updateValues($idAttribute, $value, $idContent, $typeAttribute){

		 $sql=static::$sqlUpdateValue;

		 if( static::$db == null ) static::__init();

		 $attributeParam = static::$file->ValueTable["attributes"];
	
		 $children = static::$file->ValueTable["children"];
		 foreach( $children as $child){
			 if( $child["attributes"]["type"] == "default" ){
					$attributeParam = $child["attributes"];
			 }elseif ( $child["attributes"]["type"] == $typeAttribute ) {
					$attributeParam = $child["attributes"];
					break;
			 }
		 }
			
		 $sql= str_replace( array('%table%', '%idAttribute%','%value%', '%idContent%','%id_attribute%' , '%_value%', '%id_content%' ),
								array( (String) $attributeParam["name"],(String) $attributeParam["id"],(String) $attributeParam["value"], $attributeParam["idContent"] , $idAttribute, $value , $idContent ),
							$sql);
			
		 static::$db->execute( $sql );
	 }
	 
	 
	public static function getIdContentByIdentifierAndEntity( $_identifier, $idEntity){
		if( static::$db == null ) static::__init();
		
		$contentParams = static::$file->ContentTable["attributes"];
		static::$sqlIdContentByIdentifierAndEntity = str_replace( array( '%id%',  '%table%', '%idEntity%','%entity%', '%identifier%','%_identifier%' ),
															array( (String) $contentParams["id"], (String) $contentParams["name"], (String) $contentParams["idEntity"],$idEntity, (String) $contentParams["identifier"] , $_identifier ),
															static::$sqlIdContentByIdentifierAndEntity);

		foreach(static::$db->execute( static::$sqlIdContentByIdentifierAndEntity) as $element ){
			return $element[0];
		}
	} 
	
	
	 //To delete content we must delete all values of this content
	 public function deleteContent($idContent){
	 	if( static::$db == null ) static::__init();
	
		$valueParams = static::$file->ValueTable["attributes"];
		$children = static::$file->ValueTable["children"];
		foreach($children as $child){
			static::$sqlDeleteContentValue = str_replace( array( '%table%','%idContentValue%', '%id_content_value%'),
														array( (String) $child["attributes"]["name"],(String) $child["attributes"]["idContent"],$idContent),
														static::$sqlDeleteContentValue );
			static::$db->execute( static::$sqlDeleteContentValue );
		}
		
		
		$contentParams = static::$file->ContentTable["attributes"];
		static::$sqlDeleteContent = str_replace( array( '%table%','%idContent%', '%id_content%'),
												array( (String) $contentParams["name"],(String) $contentParams["id"],$idContent),
												static::$sqlDeleteContent );
		static::$db->execute( static::$sqlDeleteContent );
	 }
}