<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/connection.php';

/* [5] Reference : Create REST API using PHP - https://www.youtube.com/watch?v=DHUxnUX7Y2Y */
/* [6] Reference : Create REST API using Slim framework https://www.slimframework.com/docs/v3/tutorial/first-app.html  */

$app = new \Slim\App;
/* Store Employer info */
$app->post('/employer/info', function (Request $req, Response $resp) {
    $emplrPosi = '';
    $empYearsOfServ = '';
    $emplrYrSlry = '';
    $applicationID = '';
    $errs = '';
    if (empty($req->getParam('emplrPosi'))) {
        $errs = $errs . " - Employer position is required";
    } else {
        $emplrPosi = $req->getParam('emplrPosi');
    }
    if (empty($req->getParam('empYearsOfServ'))) {
        $errs = $errs . " - Employer years of services is required";
    } else {
        $empYearsOfServ = $req->getParam('empYearsOfServ');
    }
    if (empty($req->getParam('emplrYrSlry'))) {
        $errs = $errs . " - Employer salary is required";
    } else {
        $emplrYrSlry = $req->getParam('emplrYrSlry');
    }
    if (empty($req->getParam('applicationID'))) {
        $errs = $errs . " - Broker ID is required";
    } else {
        $applicationID = $req->getParam('applicationID');
    }
    if ($errs == '') {
        try {
            $myDB = new Database();
            $myDB = $myDB->connect();
            $updateEmply = $myDB->prepare("UPDATE mbr SET emplrPosi='$emplrPosi', empYearsOfServ='$empYearsOfServ', emplrYrSlry='$emplrYrSlry' WHERE applicationID= :id");
            $updateEmply->execute(array(
                "id" => $applicationID
            ));

            if ($updateEmply->rowCount() > 0) {

                //echo "Employer Data Updated. Please visit the Mortgage portal to verify the status of your application";
                echo '{"Response": {"Employer Data Updated. Please visit the Mortgage portal to verify the status of your application"}}';


                $getCallbackURL = $myDB->prepare("SELECT * FROM mbr WHERE applicationID= :id");
                $getCallbackURL->execute(array(
                    "id" => $applicationID
                ));

                if ($getCallbackURL->rowCount() > 0) {
                    $result = $getCallbackURL->fetch(PDO::FETCH_ASSOC);
                    $callbackURL = $result['emplCallBack'];

                    /* End workflow of Employer by Callback function */
                    /* [7] Reference: http://codular.com/curl-with-php */
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => $callbackURL,
                        CURLOPT_RETURNTRANSFER => 1,
                    ));
                    $resp = curl_exec($curl);
                    curl_close($curl);
                    /* End of reference */
                }

                $myDB = null;
            } else {
                echo '{"Response": {"Message":"No Record updated. Please check all the fields."}}';
            }

        } catch (PDOException $e) {
            echo '{"Response": {"Message":"' . $e->getMessage() . '"}}';
        }
    } else {
        echo '{"Response": {"Message":"' . $errs . '"}}';
    }
});

/* Store Client info */
$app->post('/insurance/info', function (Request $req, Response $resp) {
    $insurNum = '';
    $insurValue = '';
    $applicationID = '';
    $errs = '';
    if (empty($req->getParam('insurNum'))) {
        $errs = $errs . " - Insurance number is required";
    } else {
        $insurNum = $req->getParam('insurNum');
    }
    if (empty($req->getParam('insurValue'))) {
        $errs = $errs . " - Insurance value is required";
    } else {
        $insurValue = $req->getParam('insurValue');
    }
    if (empty($req->getParam('applicationID'))) {
        $errs = $errs . " - Broker ID is required";
    } else {
        $applicationID = $req->getParam('applicationID');
    }
    if ($errs == '') {
        try {
            $myDB = new Database();
            $myDB = $myDB->connect();
            $updateEmply = $myDB->prepare("UPDATE mbr SET insurNum='$insurNum', insurValue='$insurValue' WHERE applicationID= :id");
            $updateEmply->execute(array(
                "id" => $applicationID
            ));

            if ($updateEmply->rowCount() > 0) {

                //echo "Employer Data Updated. Please visit the Mortgage portal to verify the status of your application";
                echo '{"Response": {"Insurance Data Updated. Please visit the Mortgage portal to verify the status of your application"}}';


                $getCallbackURL = $myDB->prepare("SELECT * FROM mbr WHERE applicationID= :id");
                $getCallbackURL->execute(array(
                    "id" => $applicationID
                ));

                if ($getCallbackURL->rowCount() > 0) {
                    $result = $getCallbackURL->fetch(PDO::FETCH_ASSOC);
                    $callbackURL = $result['licCallBack'];

                    /* End workflow of Insurance by Callback function */
                    /* Reference: http://codular.com/curl-with-php */
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => $callbackURL,
                        CURLOPT_RETURNTRANSFER => 1,
                    ));
                    $resp = curl_exec($curl);
                    curl_close($curl);
                    /* End of reference */
                }

                $myDB = null;
            } else {
                echo '{"Response": {"Message":"No Record updated. Please check all the fields."}}';
            }

        } catch (PDOException $e) {
            echo '{"Response": {"Message":"' . $e->getMessage() . '"}}';
        }
    } else {
        echo '{"Response": {"Message":"' . $errs . '"}}';
    }
});


/* Get Callback URL of Employer workflow */
$app->post('/employer/subscribe', function (Request $req, Response $resp) {
    $emplCallBack = '';
    $applicationID = '';
    $errs = '';

    $emplCallBack = $req->getParam('emplCallBack');
    $applicationID = $req->getParam('applicationID');


    if ($errs == '') {
        try {
            $myDB = new Database();
            $myDB = $myDB->connect();
            $updateEmplyCallback = $myDB->prepare("UPDATE mbr SET emplCallBack='$emplCallBack'WHERE applicationID= :id");
            $updateEmplyCallback->execute(array(
                "id" => $applicationID
            ));

            if ($updateEmplyCallback->rowCount() > 0) {

                echo "Employer Data Updatedd";

                $myDB = null;
            } else {
                echo '{"Response": {"Message":"No Record updated. Please check all the fields."}}';
            }

        } catch (PDOException $e) {
            echo '{"Response": {"Message":"' . $e->getMessage() . '"}}';
        }
    } else {
        echo '{"Response": {"Message":"' . $errs . '"}}';
    }
});

/* Get Callback URL of Insurance workflow */
$app->post('/insurance/subscribe', function (Request $req, Response $resp) {
    $licCallBack = '';
    $applicationID = '';
    $errs = '';

    $licCallBack = $req->getParam('licCallBack');
    $applicationID = $req->getParam('applicationID');


    if ($errs == '') {
        try {
            $myDB = new Database();
            $myDB = $myDB->connect();
            $updateInsuranceCallback = $myDB->prepare("UPDATE mbr SET licCallBack='$licCallBack'WHERE applicationID= :id");
            $updateInsuranceCallback->execute(array(
                "id" => $applicationID
            ));

            if ($updateInsuranceCallback->rowCount() > 0) {

                echo "Insurance Data Updatedd";

                $myDB = null;
            } else {
                echo '{"Response": {"Message":"No Record updated. Please check all the fields."}}';
            }

        } catch (PDOException $e) {
            echo '{"Response": {"Message":"' . $e->getMessage() . '"}}';
        }
    } else {
        echo '{"Response": {"Message":"' . $errs . '"}}';
    }
});

$app->run();

?>