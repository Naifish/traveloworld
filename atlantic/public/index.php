<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/database.php';

/* [5] Reference : Create REST API using PHP - https://www.youtube.com/watch?v=DHUxnUX7Y2Y */
/* [6] Reference : Create REST API using Slim framework https://www.slimframework.com/docs/v3/tutorial/first-app.html  */

$app = new \Slim\App;


$app->get('/rooms/all', function (Request $req, Response $res){
    
    try{

        $con = new Database();
        $con = $con->connect();
        $sql = "SELECT * FROM rooms";        


        $stmt = $con->query($sql);
        $rooms =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $con =  null;
        echo json_encode($rooms);

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});


$app->run();

?>