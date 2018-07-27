<!DOCTYPE html>
<html>
<?php 
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
include 'includes/footer.php';
?>


<script type="text/javascript">
	
$(document).ready(function() {
$.ajax({
    url: "http://localhost/traveloworld/atlantic/public/index.php/rooms/all",
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
        var image = '<img src="https://res.cloudinary.com/drtnvyqg9/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';
        
        $("ol").append("<li><div class='container list-room'><div class='row'><div class='col-md-3'>"+image+"</div><div class='col-md-7'><h3>Room Type"+roomType+"<hr/><p>"+description+"</p><p>"+room+"</p></div><div class='col-md-2'><h2>CAD 300</h2><a href='#' class='btn-primary btn-rooms'>Book this Room</a></div></div></div></li><br />");
    }    
                    
    } 
});
});        
</script>
</body>
</html>