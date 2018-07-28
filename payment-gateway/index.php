<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'includes/database.php';

/* [5] Reference : Create REST API using PHP - https://www.youtube.com/watch?v=DHUxnUX7Y2Y */
/* [6] Reference : Create REST API using Slim framework https://www.slimframework.com/docs/v3/tutorial/first-app.html  */

$app = new \Slim\App;


$app->post('/card/pay', function (Request $req, Response $res){
    
    $type = '';$cardNumber='';$cvv='';$holderName='';$payAmount='';$errs = '';

    if (empty($req->getParam('type'))) {
        $errs = $errs . " - Card Type is required";
    }else {
        $type = $req->getParam('type');
    }

    if (empty($req->getParam('cardNumber'))) {
        $errs = $errs . " - Card number is required";
    }else {
        $cardNumber = $req->getParam('cardNumber');
    }

    if (empty($req->getParam('cvv'))) {
        $errs = $errs . " - CVV number is required";
    }else {
        $cvv = $req->getParam('cvv');
    }

    if (empty($req->getParam('holderName'))) {
        $errs = $errs . " - Card holder name is required";
    }else {
        $holderName = $req->getParam('holderName');
    }

    if (empty($req->getParam('payAmount'))) {
        $errs = $errs . " - Payment amount is required";
    }else {
        $payAmount = $req->getParam('payAmount');
    }

    try{

        $con = new Database();
        $con = $con->connect();
        $sql = "SELECT * FROM cards WHERE type='$type' AND cardNumber='$cardNumber' AND cvv='$cvv' AND holderName='$holderName'";        


        $stmt = $con->query($sql);
        $cardResult =  $stmt->fetchAll(PDO::FETCH_OBJ);
        if($cardResult){

            if ($cardResult[0]->remainingAmount>=$payAmount) {

                $newAmount=$cardResult[0]->remainingAmount-$payAmount;
                $updateAmount = $con->prepare("UPDATE cards SET remainingAmount='$newAmount' WHERE cardNumber= :cardNumber AND cvv= :cvv");
                $updateAmount->execute(array(
                    "cardNumber" => $cardNumber,
                    "cvv" => $cvv
                ));

                echo '{"message": {"status": "success"}  }';

            }else{
                echo '{"message": {"status": "You have not sufficient amount to make this transection"}  }';
            }

        }
        else{
            echo '{"message": {"status": "Invalid card information"}  }';
        }
        $con =  null;

    }catch(PDOException $e){
        echo '{"message": {"text": '.$e->getMessage().'}  }';
    }
});


$app->run();

?>