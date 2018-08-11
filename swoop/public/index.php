<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$swoopPrivateKey='';
require '../vendor/autoload.php';
require '../includes/database.php';
include '../includes/functions.php';

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

// For traveloworld
$app->get('/flights/all/{departDate}/{returnDate}/{minAmt}/{maxAmt}/{clientID}/{pt}/{ct}', function (Request $req, Response $res){
    $departDate = $req->getAttribute('departDate');
    $returnDate = $req->getAttribute('returnDate');
    $minAmt = $req->getAttribute('minAmt');
    $maxAmt = $req->getAttribute('maxAmt');

    $clientID = $req->getAttribute('clientID');
    $pt = $req->getAttribute('pt');
    $ct = $req->getAttribute('ct');

    try{

        $con = new Database();
        $con = $con->connect();

        $checkClient="SELECT * FROM swoop_clients WHERE clientID='$clientID'";

        $checkClientStmt = $con->query($checkClient);
        if ($checkClientStmt->fetchAll(PDO::FETCH_OBJ)) {
            $ct = base64_URLfriendlyReverse($ct);
            $cyperText=base64_decode($ct);
            require '../includes/keys.php';
            openssl_private_decrypt($cyperText, $decrypted, $swoopPrivateKey);

            if ($pt==$decrypted) {
                $sql = "SELECT * FROM flights WHERE departDate <= '$returnDate' AND returnDate >= '$departDate' AND price BETWEEN '$minAmt' AND '$maxAmt' AND status='yes'";

                $stmt = $con->query($sql);
                $flights =  $stmt->fetchAll(PDO::FETCH_OBJ);
                $con =  null;
                echo json_encode($flights);
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


//For traveloworld
$app->get('/flights/{id}/{clientID}/{pt}/{ct}', function (Request $req, Response $res){
    $id = $req->getAttribute('id');
    $clientID = $req->getAttribute('clientID');
    $pt = $req->getAttribute('pt');
    $ct = $req->getAttribute('ct');
    try{

        $con = new Database();
        $con = $con->connect();
        

        
        $checkClient="SELECT * FROM swoop_clients WHERE clientID='$clientID'";

        $checkClientStmt = $con->query($checkClient);
        if ($checkClientStmt->fetchAll(PDO::FETCH_OBJ)) {
            $ct = base64_URLfriendlyReverse($ct);
            $cyperText=base64_decode($ct);
            require '../includes/keys.php';
            openssl_private_decrypt($cyperText, $decrypted, $swoopPrivateKey);

            if ($pt==$decrypted) {
                $sql = "SELECT * FROM flights WHERE id=$id";        


                $stmt = $con->query($sql);
                $flights =  $stmt->fetchAll(PDO::FETCH_OBJ);
                $con =  null;
                echo json_encode($flights);
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
        $sql = "SELECT * FROM users WHERE id='$id'"; 
        $stmt = $con->query($sql);

        
        
        try{
        if ($stmt->fetchAll(PDO::FETCH_OBJ)) {
        $update = "UPDATE users SET name='$name', email='$email' WHERE id='$id'";        
        $stmt = $con->query($update);          
        //echo "here update";
        }else{
       

        $insert = "INSERT INTO users (id,name,email) VALUES ('$id','$name','$email') ";        
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