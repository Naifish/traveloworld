<!DOCTYPE html>
<html>
<?php
session_start();
require 'includes/database.php';
$flightsBookingIDs = array();
$roomsBookingIDs = array();
if (!isset($_SESSION) || empty($_SESSION['token'])) {
    header('location:google-login.php');
}else{
 $google_id=$_SESSION['id'];
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
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

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
<form>
    <input type="hidden" name="id_token" id="id_token" value="<?php echo $_SESSION['token'];?>">
    <!-- <input type="hidden" name="sendId" id="id" value="<?php echo $id; ?>"> -->
</form>
<form action="#" method="POST" id="myForm">
      
      <input type="hidden" name="uid" id="uid" value="<?php if(isset($_POST['uid'])){ echo $_POST['uid']; } ?>">
</form>

<?php
include 'includes/footer.php';
?>


<script type="text/javascript">

    $(document).ready(function () {
        //id = document.getElementById('id').value;
        var newID;
        var flightsBookingIDs = <?php echo json_encode($flightsBookingIDs); ?>;
        var roomsBookingIDs = <?php echo json_encode($roomsBookingIDs); ?>;
        //var id_token= document.getElementById('id_token').value; 

       /* $.ajax({
    url: "https://traveloworld.azurewebsites.net/public/index.php/user/"+id_token,
    method: 'GET',
    contentType: 'application/json',
    dataType: 'JSON',
    
    success: function (data) {
          newID=data.userID;
         
        if (($('#uid').val().length) > 0) {
          }
          else{
            $('#uid').val(newID);
            $('#myForm').submit();
          } 
  }

});*/

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