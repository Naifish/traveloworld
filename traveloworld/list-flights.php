<!DOCTYPE html>
<html>
<?php 
session_start();

require 'includes/header.php';
require 'includes/keys.php';
include 'includes/functions.php';

$pt = genrtRandStr(200);
openssl_public_encrypt($pt, $ct, $swoopPublicKey);
$ct =base64_encode($ct);
$ct=base64_URLfriendly($ct);
$selfID='ffb7d672f69630a691d978bd9dd8d234';

?>
<div class="container">
	<h3> Select the flight of your choice!</h3>
</div>

<!-- Listing Starts -->
<div class="container container-flights">
	<ol></ol>
</div>

<?php
$departDate = $_GET['departDate']; 
$returnDate = $_GET['returnDate']; 
$minAmt = $_GET['minAmt']; 
$maxAmt = $_GET['maxAmt'];

?>
<form>
    <input type="hidden" name="sendId" id="departDate" value="<?php echo $departDate;?>">
    <input type="hidden" name="sendId" id="returnDate" value="<?php echo $returnDate;?>">
    <input type="hidden" name="sendId" id="minAmt" value="<?php echo $minAmt;?>">
    <input type="hidden" name="sendId" id="maxAmt" value="<?php echo $maxAmt;?>">

    <input type="hidden" name="pt" id="pt" value="<?php echo $pt;?>">
    <input type="hidden" name="ct" id="ct" value="<?php echo $ct;?>">
    <input type="hidden" name="selfID" id="selfID" value="<?php echo $selfID;?>">

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

var pt = document.getElementById('pt').value; 
var ct = document.getElementById('ct').value; 
var selfID = document.getElementById('selfID').value;

$.ajax({
    url: "https://swoop-airlines.azurewebsites.net/public/index.php/flights/all/"+departDate+"/"+returnDate+"/"+minAmt+"/"+maxAmt+"/"+selfID+"/"+pt+"/"+ct,
    method: 'GET',
    contentType: 'application/json',
    dataType: 'JSON',
    
    success: function (data) {
/* Append() Reference Reference:
Reference: w3schools.com3. "jQuery append() Method". w3schools.com. [Online]. 
Available: https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_html_append2. [Accessed On: 6th August 2018].*/

//console.log(data);

    for (var i=0; i <= data.length-1; i++) {	
        var flightType = data[i].flightType;
        var details = data[i].details;
        var source = data[i].source;
        var destination = data[i].destination;
        var name = data[i].name;
        var id= data[i].id;
        var price= data[i].price;
        departDate=data[i].departDate;
        returnDate=data[i].returnDate;
        //var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';
        
        $("ol").append("<li><div class='container list-flight'><div class='row'><div class='col-md-8'><h2>Flight Name: "+name+"</h2><p>Flight Type: "+flightType+"</p><hr/><p class='flight-description'>"+details+"</p><p class='flight-number'>Source: "+source+"</p><p class='flight-number'>Destination: "+destination+"</p></div><div class='col-md-4'><h2>CAD "+price+"</h2><a href='single-flight.php?id="+id+"&departDate="+departDate+"&returnDate="+returnDate+"' class='btn-primary btn-flights'>Book this Flight</a></div></div></div></li><br />");
    }    
                    
    } 
});
});        
</script>
</body>
</html>