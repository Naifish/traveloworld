<?php

session_start();
$startDate='';
$endDate='';
$minAmt='';
$maxAmt='';
$errs=array();


if (isset($_POST['btn-login'])) {
  # code...
  require 'includes/database.php';
  if (empty($_POST['startDate'])) {
    $errs[]="Start Date is required";
  }else{
    $startDate=$_POST['startDate'];
  }

  if (empty($_POST['endDate'])) {
    $errs[]="End Date is required";
  }else{
    $endDate=$_POST['endDate'];
  }

  if (empty($_POST['minAmt'])) {
    $errs[]="Minimum Amount is required";
  }elseif (!(preg_match('/[0-9]+$/', $_POST['minAmt']))) {
        /* end of reference */
        $errors[] = "Only positive numbers are allowed";
    }else{
    $minAmt=$_POST['minAmt'];
  }

  if (empty($_POST['maxAmt'])) {
    $errs[]="Maximum Amount is required";
  }elseif (!(preg_match('/[0-9]+$/', $_POST['maxAmt']))) {
        /* end of reference */
        $errors[] = "Only positive numbers are allowed";
    }else{
    $maxAmt=$_POST['maxAmt'];
  }
}  
?>
<!DOCTYPE html>
<html>
<?php 
require 'includes/header.php';
?>
<center>
	<h1>Welcome to Atlantic Hotel</h1>
	<p>Book a room at a reasonable price</p>
</center>
<div class="container container-registration">
<div class="row">
	
<form action="list-rooms.php?startDate=<?php echo $startDate;?>&endDate=<?php echo $endDate;?>&minAmt=<?php echo $minAmt;?>&maxAmt=<?php echo $maxAmt;?>" method="GET">
  <h3 class="text-center">Search a room</h3>
  <ul>
    <?php if (count($errs)>0){ foreach ($errs as $er) {
    ?>
    <li><?php echo $er; ?></li>
    <?php }} ?>
  </ul>
  <div class="form-group col-md-6">
    <label >Start Date</label>
    <input type="date" class="form-control" name="startDate" placeholder="Pick a date" required>
    <!-- <small>Your email id is safe with us.</small> -->
  </div>
  <div class="form-group col-md-6">
    <label >End Date</label>
    <input type="date" class="form-control" name="endDate" placeholder="Pick a date" required>
  </div>

  <div class="form-group col-md-6">
    <label >Minimum Amount</label>
    <input type="text" class="form-control" name="minAmt" placeholder="Enter minimum amount" required pattern="^[0-9]+" title=" Only positive numbers are allowed">
    <!-- <small>Your email id is safe with us.</small> -->
  </div>
  <div class="form-group col-md-6">
    <label >Maximum Amount</label>
    <input type="text" class="form-control" name="maxAmt" placeholder="Enter maximum amount" required pattern="^[0-9]+" title=" Only positive numbers are allowed">
  </div>  


  <input type="submit" value="Submit" name="btn-login" class="btn btn-success">
</form>
</div>
</div>
<!-- <img src="images/atlantic.jpg" style="width: 100%; height: 520px;"> -->




<?php 
include 'includes/footer.php';
?>

</body>
</html>