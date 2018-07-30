<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/database.php';

/* [5] Reference : Create REST API using PHP - https://www.youtube.com/watch?v=DHUxnUX7Y2Y */
/* [6] Reference : Create REST API using Slim framework https://www.slimframework.com/docs/v3/tutorial/first-app.html  */

$app = new \Slim\App;


$app->get('/flights/all/{departDate}/{returnDate}/{minAmt}/{maxAmt}', function (Request $req, Response $res){
    $departDate = $req->getAttribute('departDate');
    $returnDate = $req->getAttribute('returnDate');
    $minAmt = $req->getAttribute('minAmt');
    $maxAmt = $req->getAttribute('maxAmt');
        

    try{

        $con = new Database();
        $con = $con->connect();
        $sql = "SELECT * FROM flights WHERE departDate <= '$returnDate' AND returnDate >= '$departDate' AND price BETWEEN '$minAmt' AND '$maxAmt' AND status='yes'";

        $stmt = $con->query($sql);
        $flights =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $con =  null;
        echo json_encode($flights);

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});


$app->get('/flights/{id}', function (Request $req, Response $res){
    $id = $req->getAttribute('id');
    try{

        $con = new Database();
        $con = $con->connect();
        $sql = "SELECT * FROM flights WHERE id=$id";        


        $stmt = $con->query($sql);
        $flights =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $con =  null;
        echo json_encode($flights);

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});


$app->get('/flights/bookings/{id}', function (Request $req, Response $res){
    $id = $req->getAttribute('id');
    /*$email = $req->getAttribute('email');*/
    try{

        $con = new Database();
        $con = $con->connect();
        $sql = "SELECT my_bookings.uID, fID, name, source,destination, flightType, details, departDate, returnDate, status, paymentStatus, price FROM my_bookings, flights WHERE my_bookings.uID = '$id' AND fID= flights.id";        


        $stmt = $con->query($sql);
        $flights =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $con =  null;
        echo json_encode($flights);

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});


$app->get('/flights/status/{fID}', function (Request $req, Response $res){
    $id = $req->getAttribute('fID');
    
    try{

        $con = new Database();
        $con = $con->connect();
        $updateStatus = "UPDATE flights SET status='no' WHERE id= '$id'";
        $stmt = $con->query($updateStatus);
        $con =  null;
        echo '{"message": {"updateStatus": true}  }';

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});

$app->run();

?>