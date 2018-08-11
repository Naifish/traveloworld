<!DOCTYPE html>
<html>
<?php 
session_start();
require 'includes/database.php';
$google_id='';
if(!isset($_SESSION) || empty($_SESSION['token'])){ 
    header('location:google-login.php');
}else{
$curl = curl_init();
// CURL REFERENCE: https://stackoverflow.com/questions/33302442/get-info-from-external-api-url-using-php
  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=".$_SESSION['token'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
/*End of reference*/
$response = json_decode($response, true);
$id=$response['sub'];       
$google_id=$id;
  //echo $_SESSION['token'];
  $con = new Database();
  $con = $con->connect();
  $sql = "SELECT * FROM users WHERE id='$google_id'"; 
  $stmt = $con->query($sql);
        if ($stmt->fetchAll(PDO::FETCH_OBJ)) {
}else{
  header("location: google-login.php");  
}
}
$id='';
require 'includes/header.php';
require 'includes/hijacking.php';
?>
<div class="container">
	<h3> View all your bookings</h3>
</div>

<!-- Listing Starts -->
<div class="container container-flights">
	<ol></ol>
</div>
<?php
//$id = $_SESSION['id']; 
//$id = $_SESSION['id'];
?>
<form>
    <input type="hidden" name="sendId" id="id" value="<?php echo $google_id;?>">
    <!-- <input type="hidden" name="sendId" id="email" value="<?php echo $email;?>"> -->
</form>

<!-- [5] w3schools.com "Bootstrap Modal". www.w3schools.com [Online]. Available. "https://www.w3schools.com/bootstrap/bootstrap_modal.asp".[Accessed On: 19th July 2018].-->
    <div id="modalSuccess" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <form action="my-bookings.php" method="post">
                        <button type="submit" class="close">&times;</button>
                    </form>
                    <h4 class="modal-title">Payment has been received.</h4>
                </div>
                <div class="modal-body">
                    <p>You booking has been confirmed. please visit your booking page to check the booking status</p>
                </div>
                <div class="modal-footer">
                    <form action="my-bookings.php" method="post">
                        <input type="submit" class="btn btn-default" value="Close">
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- End of Bootstrap Modal -->

<?php 
include 'includes/footer.php';
?>


<script type="text/javascript">
<?php if (isset($_GET['successBooking']) && $_GET['successBooking']=='true'){ ?>
        $("#modalSuccess").modal('show');
        <?php } ?>
	
$(document).ready(function() {
var id=0;
id = document.getElementById('id').value;
/* +"/"+email*/

$.ajax({
    url: "https://swoop-airlines.azurewebsites.net/public/index.php/flights/bookings/"+id,
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