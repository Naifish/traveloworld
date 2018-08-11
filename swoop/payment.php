<?php
session_start();
require 'includes/database.php';
$uID='';$paymentSuccess=false;$google_id='';
if(!isset($_SESSION) || empty($_SESSION['token'])){ 
    header('location:google-login.php');
}else{
$id_token = $_SESSION['token'];
$curl = curl_init();
// CURL REFERENCE: https://stackoverflow.com/questions/33302442/get-info-from-external-api-url-using-php
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$id_token,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
/*End of reference*/
$response = json_decode($response, true);
$id=$response['sub'];

$google_id=$id;
  //echo $_SESSION['token'];
  $con = new Database();
  $con = $con->connect();
        
  $sql = "SELECT * FROM users_atlantic WHERE id='$google_id'"; 
  $stmt = $con->query($sql);
  
        if ($stmt->fetchAll(PDO::FETCH_OBJ)) {       
}else{

  header("location: google-login.php");  
}
}
$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypalID = 'dal@gmail.com';


if (isset($_GET['st']) && $_GET['st']=='Completed' && isset($_GET['item_name']) ) {
  $uID=$google_id;
  $fID=$_GET['item_name'];

  
  $con = new Database();
  $con = $con->connect();
  $exeQry = $con->query(" INSERT INTO my_bookings (uID,fID) VALUES('$uID','$fID')");
            if ($exeQry==true){
            $paymentSuccess=true;
            $updateFlightStatus = $con->prepare("UPDATE flights SET status='no' WHERE id= :id");
            $updateFlightStatus->execute(array("id" => $fID));
            }

header("location: my-bookings.php?successBooking=true");
}

?>

<!DOCTYPE html>
<html>
<?php 
require 'includes/header.php';
require 'includes/hijacking.php';
?>

<div class="container container-registration container-add-flight">
<div class="row">
<div class="col-md-12">
<h3 class="text-center">Payment</h3>
  <input type="hidden" name="fID" id="fID" value="<?php if(isset($_GET['fID'])){ echo $_GET['fID']; } ?>">
  
  <div class="form-group">
    <label >Flight <small>Swoop Airlines</small></label>
  </div>

  <div class="form-group">
    <label >Amount <small>(in CAD)</small></label>
    <input type="text" class="form-control" name="amount" id="amount" disabled value="<?php if(isset($_GET['price'])){ echo $_GET['price']; } ?>" required>
  </div>
<!--Paypal Reference: https://codexworld.com/paypal-standard-payment-gateway-integration-php/  -->  
  <form action="<?php echo $paypalURL; ?>" method="post">
        <!-- Identify your business so that you can collect the payments. -->
        <input type="hidden" name="business" value="<?php echo $paypalID; ?>">
        
        <!-- Specify a Buy Now button. -->
        <input type="hidden" name="cmd" value="_xclick">
        
        <!-- Specify details about the item that buyers will purchase. -->
        <input type="hidden" name="amount" value="<?php echo $_GET['price']; ?>">
        <input type="hidden" name="item_name" value="<?php echo $_GET['fID']; ?>">
        <input type="hidden" name="currency_code" value="CAD">
        
        <!-- Specify URLs -->
        <input type='hidden' name='cancel_return' value='https://swoop-airlines.azurewebsites.net/index.php'>
        <input type='hidden' name='return' value='https://swoop-airlines.azurewebsites.net/payment.php'>
        
        <!-- Display the payment button. -->
        <input type="image" name="submit" border="0"
        src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online"> 
    </form>  
<!-- End of Reference -->

</div>
</div>
</div>




<?php 
include 'includes/footer.php';
?>


</body>
</html>