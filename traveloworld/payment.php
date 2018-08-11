<?php
session_start();
require 'includes/database.php';
$id='';
if(!isset($_SESSION) || empty($_SESSION['token'])){ 
    header('location:google-login.php');
}else{
  $curl = curl_init();
// CURL REFERENCE: https://stackoverflow.com/questions/33302442/get-info-from-external-api-url-using-php
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$_SESSION['token'],
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
        
  //echo $id;        
  $sql = "SELECT * FROM users WHERE id='$google_id'"; 
  $stmt = $con->query($sql);
  
if ($stmt->fetchAll(PDO::FETCH_OBJ)) {
        //echo "success";       
}else{

  header("location: google-login.php");  
}
}

$uID='';$paymentSuccessFlight=false;$paymentSuccessRoom=false;$departDate='';$returnDate='';$price='';$rID='';$user_Id='';$bookingIDs='';
if (isset($_GET['departDate'])){
    $departDate=$_GET['departDate'];
}
if (isset($_GET['returnDate'])){
    $returnDate=$_GET['returnDate'];
}
if (isset($_GET['price'])){
    $price=$_GET['price'];
}
if (isset($_GET['rID'])){
    $rID=$_GET['rID'];
}
$fID=$_GET['fID'];
//$uID=$_GET['uID'];
$totalAmount=0;$discountedAmount=0; $item_name='';
$paypalURL = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; 
$paypalID = 'dal@gmail.com';

$bookingIDs=explode('_', $_GET['item_name']);
$bookingCount=count($bookingIDs);
if (isset($_GET['st']) && $_GET['st']=='Completed') {
  //$user_Id=$_SESSION['id'];
  //$user_Id=$_GET['user_Id']
  if ($bookingCount>1) {
    $user_Id=$google_id;
    $fID=$bookingIDs[0];
    $rID=$bookingIDs[1];
    $con = new Database();
    $con = $con->connect();
    $exeQry = $con->query(" INSERT INTO my_bookings (uID,fID,rID) VALUES('$user_Id','$fID','$rID')");
    if ($exeQry==true){
        $paymentSuccessFlight=true;
        $paymentSuccessRoom=true;
    }
  }
  else{
  $user_Id=$google_id;
  $fID=$bookingIDs[0];
  $con = new Database();
  $con = $con->connect();
  $exeQry = $con->query(" INSERT INTO my_bookings (uID,fID) VALUES('$user_Id','$fID')");
                    if ($exeQry==true){
                        $paymentSuccessFlight=true;
                    }
  }    

}

?>

<!DOCTYPE html>
<html>
<?php 
require 'includes/header.php';
require 'includes/hijacking.php';
?>

<div class="container container-registration container-add-room">
<div class="row">
	<div class="col-md-12">
      
<form action="#" method="post" id="form-payment">
	<h3 class="text-center">Payment</h3>
  <input type="hidden" name="departDate" id="departDate" value="<?php echo $_GET['departDate'];?>">
  <input type="hidden" name="returnDate" id="returnDate" value="<?php echo $_GET['returnDate'];?>">
  <input type="hidden" name="fID" id="fID" value="<?php if(isset($_GET['fID'])){ echo $_GET['fID']; }else if($bookingIDs[0]!=''){ echo $bookingIDs[0]; } ?>">
  <input type="hidden" name="rID" id="rID" value="<?php if(isset($_GET['rID'])){ echo $_GET['rID']; } else if($bookingIDs[1]!=''){ echo $bookingIDs[1]; } ?>">
  
  
    <?php if (isset($_GET['fID']) && isset($_GET['rID'])){
        $totalAmount=$_GET['flightPrice']+$_GET['price'];
        $discountedAmount= $totalAmount- ($totalAmount*0.05);
        $item_name=$_GET['fID'].'_'.$_GET['rID'];
    }else{
        $discountedAmount=$_GET['price'];
        $item_name=$_GET['fID'];
    }
    ?>
  <div class="form-group">
    <label >Amount <small>(in CAD)</small></label>
    <input type="text" class="form-control" name="amount" id="amount" disabled value="<?php echo $discountedAmount;  ?>" required>
     <input type="hidden" id="bookingRoom" value="<?php if (isset($_GET['fID']) && isset($_GET['rID'])){ echo 'yes'; } else{ echo 'no'; } ?>">
  </div>
    <?php if (isset($_GET['fID']) && isset($_GET['rID'])){ ?>
        <p>Flight ticket Amount: <span class="text-info"><?php echo $_GET['flightPrice'] ?></span><br>
           Hotel Booking Amount: <span class="text-info"><?php echo $_GET['price'] ?></span><br>
           Total Amount: <span class="text-info"><?php echo $totalAmount; ?></span><br>
           After 5% Discount. Amount is: <strong><span class="font-weight-bold text-success"><?php echo $discountedAmount; ?></span></strong><br>
        </p>

    <?php } ?>
    <?php if (isset($_GET['fID']) && !isset($_GET['rID'])){ ?>
  <small>Book hotel rooms for your trip and get discount.</small><br>
    <?php } ?>
  
    <?php if (isset($_GET['fID']) && !isset($_GET['rID'])){ ?>
        <a href="rooms-price.php?fID=<?php echo $fID; ?>&uID=<?php echo $uID; ?>&departDate=<?php echo $departDate; ?>&returnDate=<?php echo $returnDate; ?>&price=<?php echo $price; ?>" class="btn btn-info">Find hotel for your trip</a>
    <?php } ?>
</form>
<hr/>
<!--Paypal Reference: https://codexworld.com/paypal-standard-payment-gateway-integration-php/  -->
 <form action="<?php echo $paypalURL; ?>" method="post">
        <!-- Identify your business so that you can collect the payments. -->
        <input type="hidden" name="business" value="<?php echo $paypalID; ?>">
        
        <!-- Specify a Buy Now button. -->
        <input type="hidden" name="cmd" value="_xclick">
        
        <!-- Specify details about the item that buyers will purchase. -->
        <input type="hidden" name="amount" value="<?php echo $discountedAmount; ?>">
        <input type="hidden" name="item_name" value="<?php echo $item_name; ?>">
        <input type="hidden" name="currency_code" value="CAD">
        
        <!-- Specify URLs -->
        <input type='hidden' name='cancel_return' value='https://traveloworld.azurewebsites.net/index.php'>
        <input type='hidden' name='return' value='https://traveloworld.azurewebsites.net/payment.php'>
        
        <!-- Display the payment button. -->
        <input type="submit" name="submit" class="btn btn-primary" value="Proceed to Paypal Payment"> 
    </form>
<!-- End of Paypal Reference -->
</div>
</div>
</div>




<?php 
include 'includes/footer.php';
?>

<script type="text/javascript">
  
$(document).ready(function () {
var resultPayment = $('#resultPayment');
var amountPayed=false;var flightStautsUpdated=false;
var roomStautsUpdated=false;
var bookingRoom = $('#bookingRoom').val();
var fID=$('#fID').val();
var rID=$('#rID').val();


        $("#form-payment").submit(function(e){
            e.preventDefault();
        });

            
<?php if ($paymentSuccessFlight==true && $paymentSuccessRoom==false) { ?>
                $.ajax({
                    url: 'https://swoop-airlines.azurewebsites.net/public/index.php/flights/status/' + fID,
                    method: 'GET',
                    async: false,
                    contentType: 'application/json',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.message.updateStatus==true) {
                            flightStautsUpdated=true;
                            window.location.href = "https://traveloworld.azurewebsites.net/my-bookings.php?successBooking=true";
                        }
                    }
                });
            <?php 
          } elseif ($paymentSuccessFlight==true && $paymentSuccessRoom==true) { ?>
                $.ajax({
                    url: 'https://swoop-airlines.azurewebsites.net/public/index.php/flights/status/' + fID,
                    method: 'GET',
                    async: false,
                    contentType: 'application/json',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.message.updateStatus==true) {
                            flightStautsUpdated=true;
                            window.location.href = "https://traveloworld.azurewebsites.net/my-bookings.php?successBooking=true";
                        }
                    }
                });

                $.ajax({
                    url: 'https://atlantic-hotel.azurewebsites.net/public/index.php/rooms/status/' + rID,
                    method: 'GET',
                    async: false,
                    contentType: 'application/json',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.message.updateStatus==true) {
                            roomStautsUpdated=true;
                            window.location.href = "https://traveloworld.azurewebsites.net/my-bookings.php?successBooking=true";
                        }
                    }
                });
            <?php 
          } ?>
  });

</script>
</body>
</html>