<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/database.php';

/* [5] Reference : Create REST API using PHP - https://www.youtube.com/watch?v=DHUxnUX7Y2Y */
/* [6] Reference : Create REST API using Slim framework https://www.slimframework.com/docs/v3/tutorial/first-app.html  */

$app = new \Slim\App;


$app->get('/rooms/all/{startDate}/{endDate}/{minAmt}/{maxAmt}', function (Request $req, Response $res){
    $startDate = $req->getAttribute('startDate');
    $endDate = $req->getAttribute('endDate');
    $minAmt = $req->getAttribute('minAmt');
    $maxAmt = $req->getAttribute('maxAmt');
        

    try{

        $con = new Database();
        $con = $con->connect();
        /*WHERE startDate*/
        $sql = "SELECT * FROM rooms WHERE startDate<= '$startDate' AND endDate>= '$endDate' AND price BETWEEN '$minAmt' AND '$maxAmt'";        


        $stmt = $con->query($sql);
        $rooms =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $con =  null;
        echo json_encode($rooms);

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});


$app->get('/rooms/{id}', function (Request $req, Response $res){
    $id = $req->getAttribute('id');
    try{

        $con = new Database();
        $con = $con->connect();
        $sql = "SELECT * FROM rooms WHERE id=$id";        


        $stmt = $con->query($sql);
        $rooms =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $con =  null;
        echo json_encode($rooms);

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});


$app->get('/rooms/bookings/{id}', function (Request $req, Response $res){
    $id = $req->getAttribute('id');
    /*$email = $req->getAttribute('email');*/
    try{

        $con = new Database();
        $con = $con->connect();
        $sql = "SELECT my_bookings.uID, rID, name, location, roomType, description, startDate, endDate, image, status, paymentStatus, roomNumber, price FROM my_bookings, rooms WHERE my_bookings.uID = '$id' AND rID= rooms.id";        


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