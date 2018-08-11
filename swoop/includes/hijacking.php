<?php
/*Code Reference: https://www.safaribooksonline.com/library/view/essential-php-security/059600656X/ch04s04.html*/
if (isset($_SESSION['URS_AGNT'])){
    if ($_SESSION['URS_AGNT'] != md5($_SERVER['HTTP_USER_AGENT'])){
        session_unset();
        session_destroy();
        header('location:login.php');
    }
}else{
    $_SESSION['URS_AGNT'] = md5($_SERVER['HTTP_USER_AGENT']);
}
/*End of reference*/
?>