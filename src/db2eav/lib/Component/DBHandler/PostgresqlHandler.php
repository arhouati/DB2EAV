<?php 
namespace db2eav\lib\Component\DBHandler;

class PostgresqlHandler{

	/* attributes */
	private $host="pgsql:host=";

	public function _get(){
	
		return $this->host;
	}

}
