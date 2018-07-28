<?php
session_start();

if (isset($_GET['payment']) && $_GET['payment']=='success' && isset($_GET['rID']) && isset($_GET['uID'])) {
  echo "now insert in to my booking table";
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
<form action="#" method="post">
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
<?php 
include 'includes/footer.php';
?>

<script type="text/javascript">
  
$(document).ready(function () {
var resultPayment = $('#resultPayment');



        $("form").submit(function(e){
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