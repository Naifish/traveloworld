<!DOCTYPE html>
<html>
<?php 
session_start();
require 'includes/keys.php';
include 'includes/functions.php';
require 'includes/database.php';
$pt = genrtRandStr(200);
openssl_public_encrypt($pt, $ct, $swoopPublicKey);
$ct =base64_encode($ct);
$ct=base64_URLfriendly($ct);
$selfID='ffb7d672f69630a691d978bd9dd8d234';
if(!isset($_SESSION) || empty($_SESSION['token'])){

header('location:google-login.php');
}

if (isset($_POST['id_token']) && !empty($_POST['id_token'])) {
  $google_id=$_POST['uid'];
  $con = new Database();
  $con = $con->connect();
        
  echo $id;        
  $sql = "SELECT * FROM users WHERE id='$google_id'"; 
  $stmt = $con->query($sql);
  
        if ($stmt->fetchAll(PDO::FETCH_OBJ)) {
        echo "done";       
}else{

  header("location: google-login.php");  
}
}

require 'includes/header.php';
require 'includes/hijacking.php';
?>
<div class="container">
	<h3> Flight Details</h3>
</div>

<!-- Listing Starts -->
<div class="container container-flights">
	<ol></ol>
</div>


<?php
$id = $_GET['id']; 
$departDate = $_GET['departDate']; 
$returnDate = $_GET['returnDate']; 
?>
<form>
    <input type="hidden" name="sendId" id="id" value="<?php echo $id;?>">
    <input type="hidden" name="sendId" id="departDate" value="<?php echo $departDate;?>">
    <input type="hidden" name="sendId" id="returnDate" value="<?php echo $returnDate;?>">
    <input type="hidden" name="id_token" id="id_token" value="<?php echo $_SESSION['token']?>">


    <input type="hidden" name="pt" id="pt" value="<?php echo $pt;?>">
    <input type="hidden" name="ct" id="ct" value="<?php echo $ct;?>">
    <input type="hidden" name="selfID" id="selfID" value="<?php echo $selfID;?>">
</form>

    <form action="#" method="POST" id="myForm">
          
          <input type="hidden" name="uid" id="uid" value="<?php if(isset($_POST['uid'])){ echo $_POST['uid']; } ?>">
    </form>

<?php
include 'includes/footer.php';
?>


<script type="text/javascript">
	
$(document).ready(function() {
    var id=0;
     id = document.getElementById('id').value;
     //var uID = document.getElementById('uID').value;
     var id_token= document.getElementById('id_token').value; 
     var departDate = document.getElementById('departDate').value; 
     var returnDate = document.getElementById('returnDate').value;
     var newID;
     
     var pt = document.getElementById('pt').value; 
     var ct = document.getElementById('ct').value; 
     var selfID = document.getElementById('selfID').value;   
$.ajax({
    url: "https://traveloworld.azurewebsites.net/public/index.php/user/"+id_token,
    method: 'GET',
    contentType: 'application/json',
    dataType: 'JSON',
    
    success: function (data) {
          newID=data.userID;
         
          if (($('#uid').val().length) > 0) {
          }
          else{
            $('#uid').val(newID);
            $('#myForm').submit();
          }    
     }

});
$.ajax({
    url: "https://swoop-airlines.azurewebsites.net/public/index.php/flights/"+id+"/"+selfID+"/"+pt+"/"+ct,
    method: 'GET',
    contentType: 'application/json',
    dataType: 'JSON',
    
    success: function (data) {
/* Append() Reference Reference:
Reference: w3schools.com3. "jQuery append() Method". w3schools.com. [Online]. 
Available: https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_html_append2. [Accessed On: 23rd June 2018].*/
    
    for (var i=0; i <= data.length-1; i++) {    
        var flightType = data[i].flightType;
        var details = data[i].details;
        var source = data[i].source;
        var destination = data[i].destination;
        var name = data[i].name;
        var id= data[i].id;
        var price= data[i].price;
        //var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';
        
        $("ol").append("<li></center><br/><br/><h2>Flight Name: "+name+"</h2><p>Flight Type: "+flightType+"</p><hr/><p class='flight-description'>Details: "+details+"</p><p class='room-number'>Source: "+source+"</p><p class='room-number'>Destination: "+destination+"</p><div class='row'><div class='col-md-3'><h3>Departure Date  </h3>"+departDate+"</div><div class='col-md-3'><h3> Return Date </h3> "+returnDate+"</div></div><br /><h2>CAD "+price+"</h2><br/><a href='payment.php?fID="+id+"&price="+price+"&departDate="+departDate+"&returnDate="+returnDate+"' class='btn-primary btn-flights'>Book this flight</a></div></li><br />");
    }    
                    
    } 
});        

});        
</script>
</body>
</html>