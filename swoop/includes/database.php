<?php

class Database
{

/*    private $dbhost = 'localhost';
    private $dbuser = 'root';
    private $dbpass = '';
    private $dbname = 'swoop_airlines';*/


    private $dbhost = 'swoop-airlines.mysql.database.azure.com';
    private $dbuser = 'traveloworld@swoop-airlines';
    private $dbpass = 'profFaisal2018';
    private $dbname = 'swoop_airlines';


/*
  private $dbhost = 'db.cs.dal.ca';
    private $dbuser = 'dhuka';
    private $dbpass = 'B00784039';
    private $dbname = 'dhuka';*/

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