<?php

session_start();
if(isset($_SESSION) && !empty($_SESSION['email'])){ header('location:index.php');}
$name='';$email='';$pass='';$rePsass='';$contact='';$errs=array();

if (isset($_POST['btn-registration'])) {
	require 'includes/database.php';
	if (empty($_POST['name'])) {
		$errs[]="Name is required";
	}else{
		$name=$_POST['name'];
	}

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

	if (empty($_POST['rePass'])) {
		$errs[]="Please eneter your password again in Re-Password field";
	}elseif($_POST['pass'] != $_POST['rePass']){
		$errs[]="Password did not matched";
	}

	if (empty($_POST['contact'])) {
		$errs[]="Contact number is required";
	}else{
		$contact=$_POST['contact'];
	}


	if (count($errs)<1) {

          $con = new Database();
          $con = $con->connect();
                $checkUerExistence= $con->prepare("SELECT * FROM users WHERE email= :email");
                $checkUerExistence->execute(array(
                    "email"=>$email
                ));
            $userExist=$checkUerExistence->rowCount();
            if($userExist>0){
                $errs[]="Please use different email id";
            }
            else{
                    $exeQry = $con->query(" INSERT INTO users (contact,email,name,pass) VALUES('$contact','$email','$name','$pass')");
                    if ($exeQry==true){

                      $checkUerExistence= $con->prepare("SELECT * FROM users WHERE email= '$email'");
                      $checkUerExistence->execute();
                      $userExist=$checkUerExistence->rowCount();
                      if($userExist>0){
                        $res = $checkUerExistence->fetch(PDO::FETCH_ASSOC);
                
                          $_SESSION['id']= $res['id'];
                      }

                    	$_SESSION['name']=$name;
                      $_SESSION['email'] = $email;
                      header('location:index.php');
                    }
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
	<h3 class="text-center">Registration</h3>
	<ul>
		<?php if (count($errs)>0){ foreach ($errs as $er) {
		?>
		<li><?php echo $er; ?></li>
		<?php }} ?>
	</ul>
	<div class="form-group">
    <label >Name</label>
    <input type="text" class="form-control" name="name" value="<?php if(isset($name)){ echo $name; } ?>" placeholder="Name" required>
  </div>
  <div class="form-group">
    <label >Email address</label>
    <input type="email" class="form-control" name="email" value="<?php if(isset($email)){ echo $email; } ?>" placeholder="Enter email" required>
    <small>Your email id is safe with us.</small>
  </div>
  <div class="form-group">
    <label >Password</label>
    <input type="password" class="form-control" name="pass" placeholder="Password" required>
  </div>
  <div class="form-group">
    <label >Re-Password</label>
    <input type="password" class="form-control" name="rePass" placeholder="Retype Password" required>
  </div>
  <div class="form-group">
    <label >Contact</label>
    <input type="text" class="form-control" name="contact" value="<?php if(isset($contact)){ echo $contact; } ?>" placeholder="xxx xxx-xxxx" required>
  </div>
  <input type="submit" value="Submit" name="btn-registration" class="btn btn-success">
</form>
</div>
</div>
</div>
<?php 
include 'includes/footer.php';
?>

</body>
</html>