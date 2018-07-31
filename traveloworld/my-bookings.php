<!DOCTYPE html>
<html>
<?php
session_start();
$flightsBookingIDs = array();
$roomsBookingIDs = array();
if (isset($_SESSION) && empty($_SESSION['email'])) {
    header('location:login.php');
}
$id = '';
if (isset($_GET['id'])) {
    require 'includes/database.php';
    $id = $_GET['id'];


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
?>
<div class="container">
    <h3> View all your bookings</h3>
</div>

<!-- Listing Starts -->
<div class="container container-flights">
    <ol></ol>
</div>
<form>
    <input type="hidden" name="sendId" id="id" value="<?php echo $id; ?>">
</form>


<?php
include 'includes/footer.php';
?>


<script type="text/javascript">

    $(document).ready(function () {
        id = document.getElementById('id').value;

        var flightsBookingIDs = <?php echo json_encode($flightsBookingIDs); ?>;
        var roomsBookingIDs = <?php echo json_encode($roomsBookingIDs); ?>;


        for (var i = 0; i < flightsBookingIDs.length; i++) {
            $.ajax({
                url: "http://localhost/traveloworld/traveloworld/swoop/public/index.php/flights/" + flightsBookingIDs[i],
                method: 'GET',
                async: false,
                contentType: 'application/json',
                dataType: 'JSON',

                success: function (data) {
                    /* Append() Reference Reference:
                    [1] w3schools.com3. "jQuery append() Method". w3schools.com. [Online].
                    Available: https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_html_append2. [Accessed On: 23rd June 2018].*/

                    var flightType = data[i].flightType;
                    var details = data[i].details;
                    var source = data[i].source;
                    var destination = data[i].destination;
                    var name = data[i].name;
                    var id = data[i].id;
                    var departDate = data[i].departDate;
                    var returnDate = data[i].returnDate;
                    var price = data[i].price;

                    //var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';

                    $("ol").append("<li><div class='container list-flight'><div class='row'><div class='col-md-8'><h2>Flight Name: " + name + "</h2><p>Flight Type: " + flightType + "</p><hr/><p class='flight-description'>" + details + "</p><p class='flight-number'>Source: " + source + "</p><p class='flight-number'>Destination: " + destination + "</p></div><div class='col-md-4'><h2>CAD " + price + "</h2><p>Departure Date: " + departDate + "</p><p>Return Date: " + returnDate + "</p></div></div></div></li><br />");
                }
            });
        }


        for (var i = 0; i < roomsBookingIDs.length; i++) {
            $.ajax({
                url: "http://localhost/traveloworld/traveloworld/atlantic/public/index.php/rooms/" + roomsBookingIDs[i],
                method: 'GET',
                async: false,
                contentType: 'application/json',
                dataType: 'JSON',

                success: function (data) {
                    /* Append() Reference Reference:
                    [1] w3schools.com3. "jQuery append() Method". w3schools.com. [Online].
                    Available: https://www.w3schools.com/jquery/tryit.asp?filename=tryjquery_html_append2. [Accessed On: 23rd June 2018].*/

                    var roomType = data[i].roomType;
                    var description = data[i].description;
                    var room = data[i].roomNumber;
                    var id= data[i].id;
                    var price= data[i].price;
                    var startDate = data[i].startDate;
                    var endDate = data[i].endDate;

                    var image = '<img src="https://res.cloudinary.com/drp5uq3ng/image/upload/w_250,bo_1px_solid_rgb:00390b,f_png,c_fill/'+data[i].image+'">';

                    $("ol").append("<li><div class='container list-room'><div class='row'><div class='col-md-3'>"+image+"</div><div class='col-md-7'><h3>Room Type: "+roomType+"<hr/><p class='room-description'>"+description+"</p><p class='room-number'>Room Number: "+room+"</p></div><div class='col-md-2'><h2>CAD "+price+"</h2><p>Start Date  "+startDate+"</p><p>End Date  "+endDate+"</p></div></div></div></li><br />");
                }
            });
        }
    });
</script>
</body>
</html>