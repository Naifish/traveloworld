<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../includes/database.php';

/* [5] Reference : Create REST API using PHP - https://www.youtube.com/watch?v=DHUxnUX7Y2Y */
/* [6] Reference : Create REST API using Slim framework https://www.slimframework.com/docs/v3/tutorial/first-app.html  */

$app = new \Slim\App;





$app->run();

?>