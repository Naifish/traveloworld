<!DOCTYPE html>
<html>
<?php 
session_start();

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
	<h3> Select the room of your choice!</h3>
</div>

<!-- Listing Starts -->
<div class="container container-rooms">
	<ol></ol>
</div>

<?php
/*$startDate = $_GET['departDate'];
$endDate = $_GET['returnDate'];*/
$minAmt = $_GET['minAmt']; 
$maxAmt = $_GET['maxAmt']; 
?>
<form>
    <!--<input type="hidden" name="sendId" id="startDate" value="<?php /*echo $startDate;*/?>">
    <input type="hidden" name="sendId" id="endDate" value="<?php /*echo $endDate;*/?>">-->
    <input type="hidden" name="sendId" id="minAmt" value="<?php echo $minAmt;?>">
    <input type="hidden" name="sendId" id="maxAmt" value="<?php echo $maxAmt;?>">

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
var departDate = document.getElementById('departDate').value;
var returnDate = document.getElementById('returnDate').value;
var minAmt = document.getElementById('minAmt').value; 
var maxAmt = document.getElementById('maxAmt').value; 

/*console.log(startDate);
console.log(endDate);
console.log(minAmt);
console.log(maxAmt);*/



$.ajax({
    url: "http://localhost/traveloworld/traveloworld/atlantic/public/index.php/rooms/all/"+departDate+"/"+returnDate+"/"+minAmt+"/"+maxAmt,
    method: 'GET',
    contentType: 'application/json',
    dataType: 'JSON',
    
    success: function (data) {
/* Append() Reference Reference:
[1] w3schools.com3. "jQuery append() Method". w3schools.com. [Online]. 
Available: https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_html_append2. [Accessed On: 23rd June 2018].*/


/*console.log(data);*/
    for (var i=0; i <= data.length-1; i++) {	
        var roomType = data[i].roomType;
        var description = data[i].description;
        var room = data[i].roomNumber;
        var id= data[i].id;
        var price= data[i].price;
        var startDate= data[i].startDate;
        var endDate= data[i].endDate;

        var departDate = $('#departDate').val();
        var returnDate = $('#returnDate').val();
        var fID = $('#fID').val();
        var uID = $('#uID').val();
        var flightPrice = $('#flightPrice').val();
        var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';
        
        $("ol").append("<li><div class='container list-room'><div class='row'><div class='col-md-3'>"+image+"</div><div class='col-md-7'><h3>Room Type: "+roomType+"<hr/><p class='room-description'>"+description+"</p><p class='room-number'>Room Number: "+room+"</p></div><div class='col-md-2'><h2>CAD "+price+"</h2><a href='single-room.php?id="+id+"&startDate="+startDate+"&endDate="+endDate+"&departDate="+departDate+"&returnDate="+returnDate+"&fID="+fID+"&uID="+uID+"&flightPrice="+flightPrice+"' class='btn-primary btn-rooms'>Book this Room</a></div></div></div></li><br />");
    }    
                    
    } 
});
});        
</script>
</body>
</html>