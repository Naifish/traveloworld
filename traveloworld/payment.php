<?php
session_start();
if(isset($_SESSION) && empty($_SESSION['email'])){ header('location:login.php');}
require 'includes/database.php';
$uID='';$paymentSuccess=false;$departDate='';$returnDate='';$price='';$rID='';
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
$uID=$_GET['uID'];
$totalAmount=0;$discountedAmount=0;
if (isset($_GET['payment']) && $_GET['payment']=='success' && isset($_GET['fID']) && isset($_GET['uID']) && !isset($_GET['rID'])) {
  $uID=$_GET['uID'];$fID=$_GET['fID'];
  $con = new Database();
  $con = $con->connect();
  $exeQry = $con->query(" INSERT INTO my_bookings (uID,fID) VALUES('$uID','$fID')");
                    if ($exeQry==true){
                        $paymentSuccess=true;
                    }
}
else if (isset($_GET['payment']) && $_GET['payment']=='success' && isset($_GET['fID']) && isset($_GET['uID']) && isset($_GET['rID'])){
    $uID=$_GET['uID'];$fID=$_GET['fID'];$rID=$_GET['rID'];
    $con = new Database();
    $con = $con->connect();
    $exeQry = $con->query(" INSERT INTO my_bookings (uID,fID,rID) VALUES('$uID','$fID','$rID')");
    if ($exeQry==true){
        $paymentSuccess=true;
    }
}

?>

<!DOCTYPE html>
<html>
<?php 
require 'includes/header.php';
?>

<div class="container container-registration container-add-room">
<div class="row">
	<div class="col-md-12">
<form action="#" method="post" id="form-payment">
	<h3 class="text-center">Payment</h3>
  <input type="hidden" name="departDate" id="departDate" value="<?php echo $_GET['departDate'];?>">
  <input type="hidden" name="returnDate" id="returnDate" value="<?php echo $_GET['returnDate'];?>">
  <input type="hidden" name="fID" id="fID" value="<?php if(isset($_GET['fID'])){ echo $_GET['fID']; } ?>">
  <input type="hidden" name="rID" id="rID" value="<?php if(isset($_GET['rID'])){ echo $_GET['rID']; } ?>">
  <input type="hidden" name="uID" id="uID" value="<?php if(isset($_GET['uID'])){ echo $_GET['uID']; } ?>">
  <div class="form-group">
    <label >Type of card</label>
    <select name="type" id="type" required>
      <option value="">Select Card type</option>
      <option value="Master">Master</option>
      <option value="Visa">Visa</option>
    </select>
  </div>
  <div class="form-group">
    <label >Card Number</label>
    <input type="text" class="form-control" name="cardNumber" id="cardNumber" minlength="9" maxlength="11" placeholder="Card Number" required>
  </div>
  <div class="form-group">
    <label >CVV Number</label>
    <input type="text" class="form-control" name="cvv" id="cvv" minlength="4" maxlength="4" required>
  </div>
	<div class="form-group">
    <label >Card holder name</label>
    <input type="text" class="form-control" name="holderName" id="holderName" placeholder="Name of card holder" required>
  </div>
    <?php if (isset($_GET['fID']) && isset($_GET['rID'])){
        $totalAmount=$_GET['flightPrice']+$_GET['price'];
        $discountedAmount= $totalAmount- ($totalAmount*0.05);
    }else{
        $discountedAmount=$_GET['price'];

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
  <div id="resultPayment" class="text-danger" style="margin: 20px 0px;"></div>
    <?php if (isset($_GET['fID']) && !isset($_GET['rID'])){ ?>
  <small>Book hotel rooms for your trip and get discount.</small><br>
    <?php } ?>
  <input type="submit" value="Pay for booking" id="btn-pay" class="btn btn-success">
    <?php if (isset($_GET['fID']) && !isset($_GET['rID'])){ ?>
        <a href="rooms-price.php?fID=<?php echo $fID; ?>&uID=<?php echo $uID; ?>&departDate=<?php echo $departDate; ?>&returnDate=<?php echo $returnDate; ?>&price=<?php echo $price; ?>" class="btn btn-info">Find hotel for your trip</a>
    <?php } ?>
</form>
</div>
</div>
</div>


<!-- [5] w3schools.com "Bootstrap Modal". www.w3schools.com [Online]. Available. "https://www.w3schools.com/bootstrap/bootstrap_modal.asp".[Accessed On: 19th July 2018].-->
    <div id="modalSuccess" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <form action="my-bookings.php?id=<?php echo $uID; ?>" method="post">
                        <button type="submit" class="close">&times;</button>
                    </form>
                    <h4 class="modal-title">Payment has been received.</h4>
                </div>
                <div class="modal-body">
                    <p>You booking has been confirmed. please visit your booking page to check the booking status</p>
                </div>
                <div class="modal-footer">
                    <form action="my-bookings.php?id=<?php echo $uID; ?>" method="post">
                        <input type="submit" class="btn btn-default" value="Close">
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- End of Bootstrap Modal -->

<?php 
include 'includes/footer.php';
?>

<script type="text/javascript">
  
$(document).ready(function () {
var resultPayment = $('#resultPayment');
var amountPayed=false;var flightStautsUpdated=false;
var roomStautsUpdated=false;
var bookingRoom = $('#bookingRoom').val();


        <?php if ($paymentSuccess==true){ $paymentSuccess=false; ?>
        $("#modalSuccess").modal('show');
        <?php } ?>

        $("#form-payment").submit(function(e){
            e.preventDefault();
        });


        $('#btn-pay').click(function () {
            var fID = $('#fID').val();
            var uID = $('#uID').val();
            var rID = $('#rID').val();
            var type = $('#type').val();
            var cardNumber = $('#cardNumber').val();
            var cvv = $('#cvv').val();
            var holderName = $('#holderName').val();
            holderName=holderName.replace(" ","-");
            var amount = $('#amount').val();
            $.ajax({
                url: 'http://localhost/traveloworld/traveloworld/payment-gateway/index.php/card/pay',
                method: 'POST',
                async: false,
                contentType: 'application/json',
                data: JSON.stringify({
                    type: type,
                    cardNumber: cardNumber,
                    cvv: cvv,
                    holderName: holderName,
                    payAmount: amount
                }),
                dataType: 'JSON',
                success: function (data) {
                    if (data.message.status=='success') {
                      /*window.location.href = "http://localhost/traveloworld/traveloworld/swoop/payment.php?payment=success&fID="+fID+"&uID="+uID;*/
                        amountPayed=true;
                    }else{
                      resultPayment.html(data.message.status);
                    }
                }
            });


            if(amountPayed==true){
                $.ajax({
                    url: 'http://localhost/traveloworld/traveloworld/swoop/public/index.php/flights/status/' + fID,
                    method: 'GET',
                    async: false,
                    contentType: 'application/json',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.message.updateStatus==true) {
                            flightStautsUpdated=true;
                        }
                    }
                });
            }
            if(amountPayed==true && bookingRoom=='yes'){
                $.ajax({
                    url: 'http://localhost/traveloworld/traveloworld/atlantic/public/index.php/rooms/status/' + rID,
                    method: 'GET',
                    async: false,
                    contentType: 'application/json',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.message.updateStatus==true) {
                            roomStautsUpdated=true;
                        }
                    }
                });
            }

            //if paying only for flight
            if(flightStautsUpdated==true && bookingRoom=='no'){
                //$("#modalSuccess").modal('show');
                window.location.href = "http://localhost/traveloworld/traveloworld/traveloworld/payment.php?payment=success&fID="+fID+"&uID="+uID;
            }
            //if paying for flight and rooms
            else if(flightStautsUpdated==true && roomStautsUpdated==true){
                //$("#modalSuccess").modal('show');
                window.location.href = "http://localhost/traveloworld/traveloworld/traveloworld/payment.php?payment=success&fID="+fID+"&uID="+uID+"&rID="+rID;
            }
        });
  });

</script>
</body>
</html>