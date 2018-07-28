<!DOCTYPE html>
<html>
<?php 
session_start();

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
    <input type="hidden" name="sendId" id="startDate" value="<?php echo $startDate;?>">
    <input type="hidden" name="sendId" id="endDate" value="<?php echo $endDate;?>">
</form>

<?php
include 'includes/footer.php';
?>


<script type="text/javascript">
	
$(document).ready(function() {
    var id=0;
     id = document.getElementById('id').value;
     uID = document.getElementById('id').value;
     var startDate = document.getElementById('startDate').value; 
     var endDate = document.getElementById('endDate').value;  
     console.log(id);  
$.ajax({
    url: "http://localhost/traveloworld/atlantic/public/index.php/rooms/"+id,
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
        var image = '<img src="https://res.cloudinary.com/drtnvyqg9/image/upload/w_490,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';
        
        $("ol").append("<li><div class='container'><center><div class='room-img'>"+image+"</div></center><br/><br/><h2>Room Type "+roomType+"</h2><hr/><h3>Description</h3><p>"+description+"</p><h3>Room Number </h3><p>"+room+"</p><div class='row'><div class='col-md-3'><h3>Start Date  </h3>"+startDate+"</div><div class='col-md-3'><h3> End Date </h3> "+endDate+"</div></div><br /><h2>CAD "+price+"</h2><br/><a href='payment.php?uID="+id+"&price="+price+"&uID="+uID+"' class='btn-primary btn-rooms'>Book this Room</a></div></li><br />");
    }    
                    
    } 
});
});        
</script>
</body>
</html>