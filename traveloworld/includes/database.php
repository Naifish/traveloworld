<?php

class Database
{

/*    private $dbhost = 'localhost';
    private $dbuser = 'root';
    private $dbpass = '';
    private $dbname = 'traveloworld';*/

    private $dbhost = 'traveloworld.mysql.database.azure.com';
    private $dbuser = 'traveloworld@traveloworld';
    private $dbpass = 'profFaisal2018';
    private $dbname = 'traveloworld';

    /* [4] Reference : Create REST API using PHP - https://www.youtube.com/watch?v=DHUxnUX7Y2Y */
    public function connect()
    {
        $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname";
        $dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
    /* End of Reference: Create REST API using PHP */
}

?>