<?php

class DataBase extends PDO {
    private $_DB_HOST = 'localhost';
    private $_DB_USER = 'root';
    private $_DB_PASS = 'root';
    private $_DB_NAME = 'fpview';

    function __construct() {
        try {
            parent::__construct('mysql:host=' . $this->_DB_HOST . ';dbname=' . $this->_DB_NAME, $this->_DB_USER, $this->_DB_PASS);
        } catch (PDOException $exception) {
            echo 'Erreur de connexion : ' . $exception->getMessage();
        }
    }
}

?>