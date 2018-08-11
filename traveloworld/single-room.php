<!DOCTYPE html>
<html>
<?php 
session_start();
require 'includes/database.php';
require 'includes/keys.php';
include 'includes/functions.php';
$pt = genrtRandStr(200);
openssl_public_encrypt($pt, $ct, $swoopPublicKey);
$ct =base64_encode($ct);
$ct=base64_URLfriendly($ct);
$selfID='2e58bab4a92cfad411d52e0f090b26b7';


if(!isset($_SESSION) || empty($_SESSION['token'])){ 
  header('location:google-login.php');
}else{
  $id_token=$_SESSION['token'];
}
if (isset($_POST['id_token']) && !empty($_POST['id_token'])) {
  $google_id=$_POST['uid'];
  //echo $_SESSION['token'];
  $con = new Database();
  $con = $con->connect();
        
  //echo $id;        
  $sql = "SELECT * FROM users WHERE id='$google_id'"; 
  $stmt = $con->query($sql);
  
        if ($stmt->fetchAll(PDO::FETCH_OBJ)) {
        //echo "done";       
}else{

  header("location: google-login.php");  
}
}

$departDate='';
$returnDate='';
$fID='';$uID='';
$flightPrice='';


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
if (empty($_GET['flightPrice'])) {
    $errs[]="Flight price is required";
}else{
    $flightPrice=$_GET['flightPrice'];
}



require 'includes/header.php';
require 'includes/hijacking.php';
?>
<div class="container">
	<h3> Room Details</h3>
</div>

<!-- Listing Starts -->
<div class="container container-rooms">
	<ol></ol>
</div>


<?php
$id = $_GET['id']; 
$startDate = $_GET['startDate']; 
$endDate = $_GET['endDate']; 
?>
<form>
    <input type="hidden" name="sendId" id="id" value="<?php echo $id;?>">
    <!-- <input type="hidden" name="sendId" id="uID" value="<?php echo $_SESSION['id'];?>"> -->
    <input type="hidden" name="sendId" id="startDate" value="<?php echo $startDate;?>">
    <input type="hidden" name="sendId" id="endDate" value="<?php echo $endDate;?>">
    <input type="hidden" name="id_token" id="id_token" value="<?php echo $id_token;?>">
    <input type="hidden" name="departDate" id="departDate" value="<?php echo $departDate;?>">
    <input type="hidden" name="returnDate" id="returnDate" value="<?php echo $returnDate;?>">
    <input type="hidden" name="fID" id="fID" value="<?php echo $fID;?>">
    <!-- <input type="hidden" name="uID" id="uID" value="<?php echo $uID;?>"> -->
    <input type="hidden" name="flightPrice" id="flightPrice" value="<?php echo $flightPrice;?>">

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
     //uID = document.getElementById('uID').value;
     var id_token= document.getElementById('id_token').value; 
     var startDate = document.getElementById('startDate').value; 
     var endDate = document.getElementById('endDate').value;

    var departDate = $('#departDate').val();
    var returnDate = $('#returnDate').val();
    var fID = $('#fID').val();
    var uID = $('#uID').val();
    var flightPrice = $('#flightPrice').val();
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
    url: "https://atlantic-hotel.azurewebsites.net/public/index.php/rooms/"+id+"/"+selfID+"/"+pt+"/"+ct,
    method: 'GET',
    contentType: 'application/json',
    dataType: 'JSON',
    
    success: function (data) {
/* Append() Reference Reference:
Reference: w3schools.com3. "jQuery append() Method". w3schools.com. [Online]. 
Available: https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_html_append2. [Accessed On: 23rd June 2018].*/
    
    for (var i=0; i <= data.length-1; i++) {	
        var roomType = data[i].roomType;
        var description = data[i].description;
        var room = data[i].roomNumber;
        var price= data[i].price;
        var id= data[i].id;

        var departDate = $('#departDate').val();
        var returnDate = $('#returnDate').val();
        var fID = $('#fID').val();
        var uID = $('#uID').val();
        var flightPrice = $('#flightPrice').val();

        var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';
        
        $("ol").append("<li><div class='container'><center><div class='room-img'>"+image+"</div></center><br/><br/><h2>Room Type "+roomType+"</h2><hr/><p class='room-description'>Description: "+description+"</p><p class='room-number'>Room Number <p class='room-number'>"+room+"</p><div class='row'><div class='col-md-3'><h3>Start Date  </h3>"+startDate+"</div><div class='col-md-3'><h3> End Date </h3> "+endDate+"</div></div><br /><h2>CAD "+price+"</h2><br/><a href='payment.php?rID="+id+"&price="+price+"&startDate="+startDate+"&endDate="+endDate+"&departDate="+departDate+"&returnDate="+returnDate+"&fID="+fID+"&uID="+uID+"&flightPrice="+flightPrice+"' class='btn-primary btn-rooms'>Book this Room</a></div></li><br />");
    }    
                    
    } 
});
});        
</script>
</body>
</html>