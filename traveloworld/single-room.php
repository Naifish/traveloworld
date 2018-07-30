<!DOCTYPE html>
<html>
<?php 
session_start();
if(isset($_SESSION) && empty($_SESSION['email'])){ header('location:login.php');}

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
    <input type="hidden" name="sendId" id="uID" value="<?php echo $_SESSION['id'];?>">
    <input type="hidden" name="sendId" id="startDate" value="<?php echo $startDate;?>">
    <input type="hidden" name="sendId" id="endDate" value="<?php echo $endDate;?>">

    <input type="hidden" name="departDate" id="departDate" value="<?php echo $departDate;?>">
    <input type="hidden" name="returnDate" id="returnDate" value="<?php echo $returnDate;?>">
    <input type="hidden" name="fID" id="fID" value="<?php echo $fID;?>">
    <input type="hidden" name="uID" id="uID" value="<?php echo $uID;?>">
    <input type="hidden" name="flightPrice" id="flightPrice" value="<?php echo $flightPrice;?>">
</form>

<?php
include 'includes/footer.php';
?>


<script type="text/javascript">
	
$(document).ready(function() {
    var id=0;
     id = document.getElementById('id').value;
     uID = document.getElementById('uID').value;
     var startDate = document.getElementById('startDate').value; 
     var endDate = document.getElementById('endDate').value;

    var departDate = $('#departDate').val();
    var returnDate = $('#returnDate').val();
    var fID = $('#fID').val();
    var uID = $('#uID').val();
    var flightPrice = $('#flightPrice').val();


     console.log(id);  
$.ajax({
    url: "http://localhost/traveloworld/traveloworld/atlantic/public/index.php/rooms/"+id,
    method: 'GET',
    contentType: 'application/json',
    dataType: 'JSON',
    
    success: function (data) {
/* Append() Reference Reference:
[1] w3schools.com3. "jQuery append() Method". w3schools.com. [Online]. 
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