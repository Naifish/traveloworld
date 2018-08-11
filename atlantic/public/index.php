<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/database.php';
include '../includes/functions.php';
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
        $sql = "SELECT * FROM rooms WHERE startDate <= '$endDate' AND endDate >= '$startDate' AND price BETWEEN '$minAmt' AND '$maxAmt' AND status='yes'";

        $stmt = $con->query($sql);
        $rooms =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $con =  null;
        echo json_encode($rooms);

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});

$app->get('/rooms/all/{startDate}/{endDate}/{minAmt}/{maxAmt}/{clientID}/{pt}/{ct}', function (Request $req, Response $res){
    $startDate = $req->getAttribute('startDate');
    $endDate = $req->getAttribute('endDate');
    $minAmt = $req->getAttribute('minAmt');
    $maxAmt = $req->getAttribute('maxAmt');
        
    $clientID = $req->getAttribute('clientID');
    $pt = $req->getAttribute('pt');
    $ct = $req->getAttribute('ct');
    try{
        
        $con = new Database();
        $con = $con->connect();

        
        $checkClient="SELECT * FROM atlantic_clients WHERE clientID='$clientID'";

        
        $checkClientStmt = $con->query($checkClient);
        if ($checkClientStmt->fetchAll(PDO::FETCH_OBJ)) {
            $ct = base64_URLfriendlyReverse($ct);
            $cyperText=base64_decode($ct);
            require '../includes/keys.php';
            openssl_private_decrypt($cyperText, $decrypted, $swoopPrivateKey);

            if ($pt==$decrypted) {
                $sql = "SELECT * FROM rooms WHERE startDate <= '$endDate' AND endDate >= '$startDate' AND price BETWEEN '$minAmt' AND '$maxAmt' AND status='yes'";

                $stmt = $con->query($sql);
                $rooms =  $stmt->fetchAll(PDO::FETCH_OBJ);
                $con =  null;
                echo json_encode($rooms);
            }
            else{
                echo '{"message": {"text": "User not authorized. Does not meet requirements"}  }';
            }
        }else{
           echo '{"message": {"text": "User not authorized"}  }';
        }
 
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

//For Traveloworld

$app->get('/rooms/{id}/{clientID}/{pt}/{ct}', function (Request $req, Response $res){
    $id = $req->getAttribute('id');
    $clientID = $req->getAttribute('clientID');
    $pt = $req->getAttribute('pt');
    $ct = $req->getAttribute('ct');
    try{

        $con = new Database();
        $con = $con->connect();
        
        $checkClient="SELECT * FROM atlantic_clients WHERE clientID='$clientID'";

        
        $checkClientStmt = $con->query($checkClient);
        if ($checkClientStmt->fetchAll(PDO::FETCH_OBJ)) {
            $ct = base64_URLfriendlyReverse($ct);
            $cyperText=base64_decode($ct);
            require '../includes/keys.php';
            openssl_private_decrypt($cyperText, $decrypted, $swoopPrivateKey);

            if ($pt==$decrypted) {
                $sql = "SELECT * FROM rooms WHERE id=$id";        


                $stmt = $con->query($sql);
                $rooms =  $stmt->fetchAll(PDO::FETCH_OBJ);
                $con =  null;
                echo json_encode($rooms);
            }
            else{
                echo '{"message": {"text": "User not authorized. Does not meet requirements"}  }';
            }
        }else{
           echo '{"message": {"text": "User not authorized"}  }';
        }
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
        $sql = "SELECT my_bookings_atlantic.uID, rID, name, location, roomType, description, startDate, endDate, image, status, paymentStatus, roomNumber, price FROM my_bookings_atlantic, rooms WHERE my_bookings_atlantic.uID = '$id' AND rID= rooms.id";        


        $stmt = $con->query($sql);
        $rooms =  $stmt->fetchAll(PDO::FETCH_OBJ);
        $con =  null;
        echo json_encode($rooms);

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});

$app->get('/rooms/status/{rID}', function (Request $req, Response $res){
    $id = $req->getAttribute('rID');
    
    try{

        $con = new Database();
        $con = $con->connect();
        $updateStatus = "UPDATE rooms SET status='no' WHERE id= '$id'";
        $stmt = $con->query($updateStatus);
        $con =  null;
        echo '{"message": {"updateStatus": true}  }';
    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});



$app->get('/signin/{id_token}', function (Request $req, Response $res){
    $id_token = $req->getAttribute('id_token');
    
 $curl = curl_init();
// CURL REFERENCE: https://stackoverflow.com/questions/33302442/get-info-from-external-api-url-using-php
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$id_token,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
/*End of reference*/
$response = json_decode($response, true);
$id=$response['sub'];
$name=$response['name'];
$email=$response['email'];
//$email=$response['email'];
 
try{
        $con = new Database();
        $con = $con->connect();
        
        echo $id;        
        $sql = "SELECT * FROM users_atlantic WHERE id='$id'"; 
        $stmt = $con->query($sql);

        
        
        try{
        if ($stmt->fetchAll(PDO::FETCH_OBJ)) {
        $update = "UPDATE users_atlantic SET name='$name', email='$email' WHERE id='$id'";        
        $stmt = $con->query($update);          
        //echo "here update";
        }else{
       

        $insert = "INSERT INTO users_atlantic (id,name,email) VALUES ('$id','$name','$email') ";        
        $stmt = $con->query($insert);
        //echo "here insert";
        }

      }catch( PDOException $e){
        //echo "here 1st"; 
        echo '{"message": {"text": "Exists'.$e->getMessage().'"}  }';
      }
        $con =  null;
        //echo "Success";
    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
        //echo "here 2";
    }

    
});


$app->get('/user/{uID}', function (Request $req, Response $res){
 $uID = $req->getAttribute('uID');
 $curl = curl_init();
// CURL REFERENCE: https://stackoverflow.com/questions/33302442/get-info-from-external-api-url-using-php
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$uID,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
/*End of reference*/
$response = json_decode($response, true);
$id=$response['sub'];

echo '{"userID":"'.$id.'"}';

    
});

$app->run();

?>