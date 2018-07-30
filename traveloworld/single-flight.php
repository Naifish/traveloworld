<!DOCTYPE html>
<html>
<?php 
session_start();
if(isset($_SESSION) && empty($_SESSION['email'])){ header('location:login.php');}

require 'includes/header.php';
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
    <input type="hidden" name="sendId" id="uID" value="<?php echo $_SESSION['id'];?>">
    <input type="hidden" name="sendId" id="departDate" value="<?php echo $departDate;?>">
    <input type="hidden" name="sendId" id="returnDate" value="<?php echo $returnDate;?>">
</form>

<?php
include 'includes/footer.php';
?>


<script type="text/javascript">
	
$(document).ready(function() {
    var id=0;
     id = document.getElementById('id').value;
     var uID = document.getElementById('uID').value;
     var departDate = document.getElementById('departDate').value; 
     var returnDate = document.getElementById('returnDate').value;  
     console.log(id);  
$.ajax({
    url: "http://localhost/traveloworld/traveloworld/swoop/public/index.php/flights/"+id,
    method: 'GET',
    contentType: 'application/json',
    dataType: 'JSON',
    
    success: function (data) {
/* Append() Reference Reference:
[1] w3schools.com3. "jQuery append() Method". w3schools.com. [Online]. 
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
        
        $("ol").append("<li></center><br/><br/><h2>Flight Name: "+name+"</h2><p>Flight Type: "+flightType+"</p><hr/><p class='flight-description'>Details: "+details+"</p><p class='room-number'>Source: "+source+"</p><p class='room-number'>Destination: "+destination+"</p><div class='row'><div class='col-md-3'><h3>Departure Date  </h3>"+departDate+"</div><div class='col-md-3'><h3> Return Date </h3> "+returnDate+"</div></div><br /><h2>CAD "+price+"</h2><br/><a href='payment.php?fID="+id+"&price="+price+"&uID="+uID+"&departDate="+departDate+"&returnDate="+returnDate+"' class='btn-primary btn-flights'>Book this flight</a></div></li><br />");
    }    
                    
    } 
});
});        
</script>
</body>
</html>