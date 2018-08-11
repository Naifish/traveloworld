<?php


//Generate random string function is taken from: https://stackoverflow.com/questions/4356289/php-random-string-generator

function genrtRandStr($lngth) {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chrsLngth = strlen($chars);
    $randStr = '';
    for ($i = 0; $i < $lngth; $i++) {
        $randStr .= $chars[rand(0, $chrsLngth - 1)];
    }
    return $randStr;
}




function base64_URLfriendly($data){


//replace / with - and + with _
	$data = str_replace("/","-",$data);
	return str_replace("+", "_", $data);
}

?>