<?php
namespace db2eav\lib\Component\DBHandler;

class MysqlHandler{

    /* attributes */
    private $host="mysql:host=";

    public function __get( $attribute ) {
        return $this->$attribute;
    }
}


