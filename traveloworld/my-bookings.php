<!DOCTYPE html>
<html>
<?php
session_start();
$google_id='';
require 'includes/database.php';
$flightsBookingIDs = array();
$roomsBookingIDs = array();
if (!isset($_SESSION) || empty($_SESSION['token'])) {
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
        //echo "done";       
}else{
  header("location: google-login.php");  
}
}

$id = '';
if (isset($_SESSION['token'])) {
    $id = $google_id;

    $con = new Database();
    $con = $con->connect();
    $getUserBookings = $con->prepare("SELECT * FROM my_bookings WHERE uID= :id");
    $getUserBookings->execute(array(
        "id" => $id
    ));

    $userBookings = $getUserBookings->rowCount();
    if ($userBookings > 0) {
        while ($booking = $getUserBookings->fetch(PDO::FETCH_OBJ)) {

            if (!empty($booking->fID)) {
                $flightsBookingIDs[] = $booking->fID;
            }
            if (!empty($booking->rID)) {
                $roomsBookingIDs[] = $booking->rID;
            }
        }
    }
}

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
include 'includes/footer.php';
?>
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

<script type="text/javascript">

    $(document).ready(function () {
        //id = document.getElementById('id').value;
        var newID;
        var flightsBookingIDs = <?php echo json_encode($flightsBookingIDs); ?>;
        var roomsBookingIDs = <?php echo json_encode($roomsBookingIDs); ?>;
        
       <?php if (isset($_GET['successBooking']) && $_GET['successBooking']=='true'){ ?>
        $("#modalSuccess").modal('show');
        <?php } ?>
     

        var flightType, details, source,destination, name, id, departDate, returnDate,price; 
        for (var i = 0; i < flightsBookingIDs.length; i++) {
            $.ajax({
                url: "https://swoop-airlines.azurewebsites.net/public/index.php/flights/" + flightsBookingIDs[i],
                method: 'GET',
                async: false,
                contentType: 'application/json',
                dataType: 'JSON',


                success: function (data) {
                    /* Append() Reference Reference:
                    [1] w3schools.com3. "jQuery append() Method". w3schools.com. [Online].
                    Available: https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_html_append2. [Accessed On: 23rd June 2018].*/
                    console.log(data);    
                    flightType = data[0].flightType;
                    details = data[0].details;
                    source = data[0].source;
                    destination = data[0].destination;
                    name = data[0].name;
                    id = data[0].id;
                    departDate = data[0].departDate;
                    returnDate = data[0].returnDate;
                    price = data[0].price;

                    //var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';

                    $("ol").append("<li><div class='container list-flight'><div class='row'><div class='col-md-8'><h2>Flight Name: " + name + "</h2><p>Flight Type: " + flightType + "</p><hr/><p class='flight-description'>" + details + "</p><p class='flight-number'>Source: " + source + "</p><p class='flight-number'>Destination: " + destination + "</p></div><div class='col-md-4'><h2>CAD " + price + "</h2><p>Departure Date: " + departDate + "</p><p>Return Date: " + returnDate + "</p></div></div></div></li><br />");
                }
            });
        }

            
        for (var i = 0; i < roomsBookingIDs.length; i++) {
            $.ajax({
                url: "https://atlantic-hotel.azurewebsites.net/public/index.php/rooms/" + roomsBookingIDs[i],
                method: 'GET',
                async: false,
                contentType: 'application/json',
                dataType: 'JSON',

                success: function (data) {
                    /* Append() Reference Reference:
                    [1] w3schools.com3. "jQuery append() Method". w3schools.com. [Online].
                    Available: https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_html_append2. [Accessed On: 23rd June 2018].*/

                    var roomType = data[0].roomType;
                    var description = data[0].description;
                    var room = data[0].roomNumber;
                    var id= data[0].id;
                    var price= data[0].price;
                    var startDate = data[0].startDate;
                    var endDate = data[0].endDate;

                    var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[0].image+'">';

                    $("ol").append("<li><div class='container list-room'><div class='row'><div class='col-md-3'>"+image+"</div><div class='col-md-7'><h3>Room Type: "+roomType+"<hr/><p class='room-description'>"+description+"</p><p class='room-number'>Room Number: "+room+"</p></div><div class='col-md-2'><h2>CAD "+price+"</h2><p>Start Date  "+startDate+"</p><p>End Date  "+endDate+"</p></div></div></div></li><br />");
                }
            });
        }
    });
</script>
</body>
</html>