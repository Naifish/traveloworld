<?php
session_start();
if(isset($_SESSION) && empty($_SESSION['email'])){ header('location:login.php');}
$uID='';$paymentSuccess=false;
if (isset($_GET['payment']) && $_GET['payment']=='success' && isset($_GET['rID']) && isset($_GET['uID'])) {
  $uID=$_GET['uID'];$rID=$_GET['rID'];

  require 'includes/database.php';
  $con = new Database();
  $con = $con->connect();
  $exeQry = $con->query(" INSERT INTO my_bookings (uID,rID) VALUES('$uID','$rID')");
                    if ($exeQry==true){
                        $paymentSuccess=true;
                        
                        //$updateRoomStatus = $con->query("UPDATE rooms SET status='no' WHERE id='$rID");
                        $updateRoomStatus = $con->prepare("UPDATE rooms SET status='no' WHERE id= :id");
            $updateRoomStatus->execute(array("id" => $rID));
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
  <div class="form-group">
    <label >Amount <small>(in CAD)</small></label>
    <input type="text" class="form-control" name="amount" id="amount" disabled value="<?php if(isset($_GET['price'])){ echo $_GET['price']; } ?>" required>
  </div>
  <div id="resultPayment" class="text-danger" style="margin: 20px 0px;"></div>
  <input type="submit" value="Pay for booking" id="btn-pay" class="btn btn-success">
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

        <?php if ($paymentSuccess==true){ $paymentSuccess=false; ?>
        $("#modalSuccess").modal('show');
        <?php } ?>

        $("#form-payment").submit(function(e){
            e.preventDefault();
        });


        $('#btn-pay').click(function () {
            var rID = $('#rID').val();
            var uID = $('#uID').val();
            var type = $('#type').val();
            var cardNumber = $('#cardNumber').val();
            var cvv = $('#cvv').val();
            var holderName = $('#holderName').val();
            holderName=holderName.replace(" ","-");
            var amount = $('#amount').val();
            $.ajax({
                url: 'http://localhost/traveloworld/traveloworld/payment-gateway/index.php/card/pay',
                method: 'POST',
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
                  console.log(data);
                    if (data.message.status=='success') {
                      window.location.href = "http://localhost/traveloworld/traveloworld/atlantic/payment.php?payment=success&rID="+rID+"&uID="+uID;
                    }else{
                      resultPayment.html(data.message.status);
                    }
                }
            });
        });
  });

</script>
</body>
</html>