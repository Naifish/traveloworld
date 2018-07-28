<!DOCTYPE html>
<html>
<?php 
session_start();

require 'includes/header.php';
?>
<div class="container">
	<h3> View all your bookings</h3>
</div>

<!-- Listing Starts -->
<div class="container container-rooms">
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
    url: "http://localhost/traveloworld/atlantic/public/index.php/rooms/bookings/"+id,
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
        var id= data[i].id;
        var price= data[i].price;
        var image = '<img src="https://res.cloudinary.com/drtnvyqg9/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';
        
        $("ol").append("<li><div class='container list-room'><div class='row'><div class='col-md-3'>"+image+"</div><div class='col-md-7'><h3>Room Type"+roomType+"<hr/><p>"+description+"</p><p>"+room+"</p></div><div class='col-md-2'><h2>CAD"+price+"</h2><</div></div></div></li><br />");
    }    
                    
    } 
});
});        
</script>
</body>
</html>