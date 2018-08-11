<?php

function base64_URLfriendlyReverse($data){


//replace - with / and _ with +
	$data = str_replace("-","/",$data);
	return str_replace("_", "+", $data);
}

?>