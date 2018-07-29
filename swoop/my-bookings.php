<!DOCTYPE html>
<html>
<?php 
session_start();
if(isset($_SESSION) && empty($_SESSION['email'])){ header('location:login.php');}

require 'includes/header.php';
?>
<div class="container">
	<h3> View all your bookings</h3>
</div>

<!-- Listing Starts -->
<div class="container container-flights">
	<ol></ol>
</div>
<?php
$id = $_GET['id']; 
//$id = $_SESSION['id'];
?>
<form>
    <input type="hidden" name="sendId" id="id" value="<?php echo $id;?>">
    <!-- <input type="hidden" name="sendId" id="email" value="<?php echo $email;?>"> -->
</form>


<?php 
include 'includes/footer.php';
?>


<script type="text/javascript">
	
$(document).ready(function() {
var id=0;
id = document.getElementById('id').value;
/* +"/"+email*/
$.ajax({
    url: "http://localhost/traveloworld/traveloworld/swoop/public/index.php/flights/bookings/"+id,
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
        var departDate= data[i].departDate;
        var returnDate = data[i].returnDate;
        var price= data[i].price;

        //var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';
        
        $("ol").append("<li><div class='container list-flight'><div class='row'><div class='col-md-8'><h2>Flight Name: "+name+"</h2><p>Flight Type: "+flightType+"</p><hr/><p class='flight-description'>"+details+"</p><p class='flight-number'>Source: "+source+"</p><p class='flight-number'>Destination: "+destination+"</p></div><div class='col-md-4'><h2>CAD "+price+"</h2><p>Departure Date: "+departDate+"</p><p>Return Date: "+returnDate+"</p></div></div></div></li><br />");
    }    
                    
    } 
});
});        
</script>
</body>
</html>