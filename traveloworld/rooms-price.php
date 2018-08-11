<?php

session_start();
$minAmt='';
$maxAmt='';
$departDate='';
$returnDate='';
$fID='';$uID='';
$flightPrice='';
$errs=array();



if (empty($_GET['departDate'])) {
    $errs[]="Departure date is required";
}else{
    $departDate=$_GET['departDate'];
}
if (empty($_GET['returnDate'])) {
    $errs[]="Return date is required";
}else{
    $returnDate=$_GET['returnDate'];
}

if (empty($_GET['fID'])) {
    $errs[]="Flight ID is required";
}else{
    $fID=$_GET['fID'];
}
if (empty($_GET['uID'])) {
    $errs[]="Maximum Amount is required";
}else{
    $uID=$_GET['uID'];
}
if (empty($_GET['price'])) {
    $errs[]="Flight price is required";
}else{
    $flightPrice=$_GET['price'];
}

if (isset($_POST['btn-login'])) {
  # code...
  require 'includes/database.php';
  
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
	<h1>Welcome to Traveloworld</h1>
	<p>Book flight and room at a reasonalble price</p>
</center>
<div class="container container-registration">
<div class="row">
	
<form action="list-rooms.php" method="GET">
  <h3 class="text-center">Search</h3>
  <ul>
    <?php if (count($errs)>0){ foreach ($errs as $er) {
    ?>
    <li><?php echo $er; ?></li>
    <?php }} ?>
  </ul>
  
    <input type="hidden" name="departDate" value="<?php echo $departDate; ?>">
    <input type="hidden" name="returnDate" value="<?php echo $returnDate; ?>">
    <input type="hidden" name="fID" value="<?php echo $fID; ?>">
    <input type="hidden" name="uID" value="<?php echo $uID; ?>">
    <input type="hidden" name="flightPrice" value="<?php echo $flightPrice; ?>">
  <div class="form-group col-md-6">
    <label >Minumum Amount</label>
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