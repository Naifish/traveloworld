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
	<div class="form-group">
    <label >Name</label>
    <input type="text" class="form-control" name="name" placeholder="Name" required>
  </div>
  <div class="form-group">
    <label >Email address</label>
    <input type="email" class="form-control" name="email" placeholder="Enter email" required>
    <small>Your email id is safe with us.</small>
  </div>
  <div class="form-group">
    <label >Password</label>
    <input type="password" class="form-control" name="pass" placeholder="Password" required>
  </div>
  <div class="form-group">
    <label >Re-Password</label>
    <input type="password" class="form-control" name="rePsass" placeholder="Retype Password" required>
  </div>
  <div class="form-group">
    <label >Contact</label>
    <input type="text" class="form-control" name="contact" placeholder="xxx xxx-xxxx" required>
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