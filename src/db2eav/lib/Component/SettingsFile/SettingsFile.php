<?php
namespace db2eav\lib\Component\SettingsFile;

class SettingsFile{
	
	private $_configurationArray = array();
	
	public function __construct( $file ){
		
		$configurationArray = array();
				
		if( is_file($file) ){

			$xml = simplexml_load_file($file);
			
			// level 1
			$nodes = $xml->xpath("/Configuration/*");
			
			foreach($nodes as $node){
	
				$this->_configurationArray[ $node->getName() ] = array( "attributes" => $node->attributes() );
				
				// level 2
				if( count( $node->children() ) ){
					foreach( $node->children() as $child){
						$this->_configurationArray[$node->getName()]["children"][]= array( "name" => $child->getName(), "attributes" => $child->attributes() );
					}
				}
				
			}
		}
	}
	
	public function __destruct(){
		unset( $this->_configurationArray );
	}
	
	public function __get( $attributeName ){
		return $this->_configurationArray[$attributeName];
	}
}