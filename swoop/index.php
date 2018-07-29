<?php

session_start();
$departDate='';
$returnDate='';
$minAmt='';
$maxAmt='';
$errs=array();


if (isset($_POST['btn-login'])) {
  # code...
  require 'includes/database.php';
  if (empty($_POST['departDate'])) {
    $errs[]="Departure Date is required";
  }else{
    $departDate=$_POST['departDate'];
  }

  if (empty($_POST['returnDate'])) {
    $errs[]="End Date is required";
  }else{
    $returnDate=$_POST['returnDate'];
  }

  if (empty($_POST['minAmt'])) {
    $errs[]="Minimum Amount is required";
  }else{
    $minAmt=$_POST['minAmt'];
  }

  if (empty($_POST['maxAmt'])) {
    $errs[]="Maximum Amount is required";
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
	<h1>Welcome to Swoop airlines</h1>
	<p>Book a flight at a reasonalble price</p>
</center>
<div class="container container-registration">
<div class="row">
	
<form action="list-flights.php?startDate=<?php echo $startDate;?>&endDate=<?php echo $endDate;?>&minAmt=<?php echo $minAmt;?>&maxAmt=<?php echo $maxAmt;?>" method="GET">
  <h3 class="text-center">Search flights</h3>
  <ul>
    <?php if (count($errs)>0){ foreach ($errs as $er) {
    ?>
    <li><?php echo $er; ?></li>
    <?php }} ?>
  </ul>
  <div class="form-group col-md-6">
    <label >Departure Date</label>
    <input type="date" class="form-control" name="departDate" placeholder="Pick a date" required>
    <!-- <small>Your email id is safe with us.</small> -->
  </div>
  <div class="form-group col-md-6">
    <label >Return Date</label>
    <input type="date" class="form-control" name="returnDate" placeholder="Pick a date" required>
  </div>

  <div class="form-group col-md-6">
    <label >Minumum Amount</label>
    <input type="text" class="form-control" name="minAmt" placeholder="Enter minimum amount" required>
    <!-- <small>Your email id is safe with us.</small> -->
  </div>
  <div class="form-group col-md-6">
    <label >Maximum Amount</label>
    <input type="text" class="form-control" name="maxAmt" placeholder="Enter maximum amount" required>
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