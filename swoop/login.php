<?php

session_start();
if(isset($_SESSION) && !empty($_SESSION['email'])){ header('location:index.php');}
;$email='';$pass='';$errs=array();


if (isset($_POST['btn-login'])) {
  # code...
  require 'includes/database.php';
  if (empty($_POST['email'])) {
    $errs[]="Email id is required";
  }else{
    $email=$_POST['email'];
  }

  if (empty($_POST['pass'])) {
    $errs[]="password is required";
  }else{
    $pass=$_POST['pass'];
  }



  if (count($errs<1)) {
    $con = new Database();
    $con = $con->connect();
                $checkUerExistence= $con->prepare("SELECT * FROM users WHERE email= :email AND pass= :pass");
                $checkUerExistence->execute(array(
                    "email"=>$email,
                    "pass"=>$pass
        ));

        $userExist=$checkUerExistence->rowCount();
            if($userExist>0){
                $res = $checkUerExistence->fetch(PDO::FETCH_ASSOC);
                $_SESSION['name']=$res['name'];
                $_SESSION['email'] = $email;
                $_SESSION['id']= $res['id'];
                


                header('location:index.php');
            }
            else{
                    $errs[]="Login credentials are not valid";
            }
  }

}

?>

<!DOCTYPE html>
<html>
<?php 
require 'includes/header.php';
?>

<div class="container container-registration">
<div class="row">
	<div class="col-md-12">
<form action="#" method="post">
  <h3 class="text-center">Login</h3>
  <ul>
    <?php if (count($errs)>0){ foreach ($errs as $er) {
    ?>
    <li><?php echo $er; ?></li>
    <?php }} ?>
  </ul>
  <div class="form-group">
    <label >Email address</label>
    <input type="email" class="form-control" name="email" placeholder="Enter email" required>
    <!-- <small>Your email id is safe with us.</small> -->
  </div>
  <div class="form-group">
    <label >Password</label>
    <input type="password" class="form-control" name="pass" placeholder="Password" required>
  </div>
  <input type="submit" value="Submit" name="btn-login" class="btn btn-success">
</form>
</div>
</div>
</div>
<?php 
include 'includes/footer.php';
?>

</body>
</html>