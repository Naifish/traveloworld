<?php

session_start();
if((isset($_SESSION)) && (empty($_SESSION['email']) || $_SESSION['email']!="macpro@live.com")){ header('location:../login.php');}
if (isset($_GET['login'])){
    unset($_SESSION["email"]);
    unset($_SESSION["id"]);
    session_destroy();
    header('location:../login.php');
}
$name='';$location='';$roomNumber='';$roomType='';$description='';$startDate='';$endDate='';$price='';$status='';$image='';$rommAdded=false;$errs=array();

if (isset($_POST['btn-add-room'])) {
	require '../includes/database.php';
	if (empty($_POST['name'])) {
		$errs[]="Name is required";
	}else{
		$name=$_POST['name'];
	}

  if (empty($_POST['image'])) {
    $errs[]="Please Upload image. It is required";
  }else{
    $image=$_POST['image'];
  }

  if (empty($_POST['price'])) {
    $errs[]="Price is required";
  }else{
    $price=$_POST['price'];
  }

	if (empty($_POST['location'])) {
		$errs[]="Location id is required";
	}else{
		$location=$_POST['location'];
	}

	if (empty($_POST['roomNumber'])) {
		$errs[]="Room number is required";
	}else{
		$roomNumber=$_POST['roomNumber'];
	}

  if (empty($_POST['roomType'])) {
    $errs[]="Type of room is required";
  }else{
    $roomType=$_POST['roomType'];
  }

  if (empty($_POST['description'])) {
    $errs[]="Room description is required";
  }else{
    $description=$_POST['description'];
  }

  if (empty($_POST['startDate'])) {
    $errs[]="Room availability start date is required";
  /* regextester.com "Regex for Date". www.regextester.com [Online]. Available. "https://www.regextester.com/96222".[Accessed On: 3th August 2018]. */
    } elseif (!(preg_match('/^((19|20)\d{2})-((0|1)\d{1})-((0|1|2|3)\d{1})/', $_POST['startDate']))) {
        /* end of reference */
        $errs[] = "Invalid Room availability start date.";
    } else {
        $startDate = $_POST['startDate'];
    }

    if (empty($_POST['endDate'])) {
    $errs[]="Room availability end date is required";
  /* regextester.com "Regex for Date". www.regextester.com [Online]. Available. "https://www.regextester.com/96222".[Accessed On: 3thth August 2018]. */
    } elseif (!(preg_match('/^((19|20)\d{2})-((0|1)\d{1})-((0|1|2|3)\d{1})/', $_POST['endDate']))) {
        /* end of reference */
        $errs[] = "Invalid Room availability end date.";
    } else {
        $endDate = $_POST['endDate'];
    }

	if (empty($_POST['status'])) {
		$errs[]="Availability status is required";
	}else{
		$status=$_POST['status'];
	}


	if (count($errs)<1) {

          $con = new Database();
          $con = $con->connect();
                $exeQry = $con->query("INSERT INTO rooms (name,location,roomNumber,roomType,description,startDate,endDate,image,status,price) VALUES('$name','$location','$roomNumber','$roomType','$description','$startDate','$endDate','$image','$status','$price')");
                if ($exeQry==true){
                  $rommAdded=true;
                }        
	}
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Atlantic Hotel - Admin</title>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<!-- Navbar Refernce: https://w3schools.com/bootstrap/bootstrap_navbar.asp -->  
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="../index.php">Atlantic Hotel</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="../index.php">Home</a></li>
    </ul>
    <?php if(!isset($_SESSION) || empty($_SESSION['email'])){ ?>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="../login.php">Login</a></li>
    </ul>
  <?php } else { ?>
    <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?login=false">Logout</a></li>
    </ul>
  <?php } ?>
    </div>
</nav>
<!-- End of Reference: Navbar -->

<div class="container container-registration container-add-room">
<div class="row">
	<div class="col-md-12">

<h3 class="text-center">Enter room availability</h3>
<ul>
    <?php if (count($errs)>0){ foreach ($errs as $er) {
    ?>
    <li><?php echo $er; ?></li>
    <?php }} ?>
  </ul>
<!-- Reference: Upload Image to Cloudinary - http://jsfiddle.net/fpoca4c8/462/  -->
<form action="" method="post" enctype="multipart/form-data" onsubmit="AJAXUploadImage(this); return false;">
  <input type="hidden" value="j6re61qb" name="upload_preset">
  <div class="form-group">
    <label >Room Image</label>
    <input class="form-control" type="file" name="file" required id="myFile">
  </div>                          
  <input type="submit" class="btn-warning btn" required value="Upload Image"/><br>
  <img height="100" width="100" id="imageSource" style="border-radius: 20px;">
  <div id="resultInsertImage"></div>
</form>
<!-- End of Reference: Upload image to Cloudinary -->
<form action="#" method="post">
	<div class="form-group">
    <input type="hidden" name="image" value="" id="imageURL">
    <label >Hotel Name</label>
    <input type="text" class="form-control" name="name" value="<?php if(isset($name)){ echo $name; } ?>" placeholder="Hotel Name" required>
  </div>
  <div class="form-group">
    <label >Hotel Location</label>
    <input type="text" class="form-control" name="location" value="<?php if(isset($location)){ echo $location; } ?>" placeholder="Hotel Location" required>
  </div>
  <div class="form-group">
    <label >Room Number</label>
    <input type="text" class="form-control" name="roomNumber" value="<?php if(isset($roomNumber)){ echo $roomNumber; } ?>" placeholder="Room Number" required>
  </div>
  <div class="form-group">
    <label >Type of rooms</label>
    <select name="roomType" required>
      <option value="">Select Room type</option>
      <option value="1 Bedroom">1 Bedroom</option>
      <option value="2 Bedrooms">2 Bedrooms</option>
      <option value="3 Bedrooms">3 Bedrooms</option>
    <option value="Luxury">Luxury</option>
  </select>
  </div>
  <div class="form-group">
    <label >Description</label>
    <textarea name="description" required rows="6" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <label >Available from Date:</label>
    <input type="date" class="form-control" name="startDate" required>
  </div>
  <div class="form-group">
    <label >Available Till Date:</label>
    <input type="date" class="form-control" name="endDate" required>
  </div>
  <div class="form-group">
    <label >Price</label>
    <input type="text" class="form-control" name="price" value="<?php if(isset($price)){ echo $price; } ?>" placeholder="Enter Amount" required>
  </div>

  <div class="form-group">
    <label >Available</label>
    <input type="text" class="form-control" name="status" value="<?php if(isset($status)){ echo $status; } ?>" placeholder="yes or no" required>
  </div>
  <input type="submit" value="Add Room" name="btn-add-room" class="btn btn-primary">
</form>
</div>
</div>
</div>


<!-- w3schools.com "Bootstrap Modal". www.w3schools.com [Online]. Available. "https://www.w3schools.com/bootstrap/bootstrap_modal.asp".[Accessed On: 3rd August 2018].-->
    <div id="modalSuccess" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <form action="../index.php" method="post">
                        <button type="submit" class="close">&times;</button>
                    </form>
                    <h4 class="modal-title">Room Availability added</h4>
                </div>
                <div class="modal-body">
                    <p>This room is now available for the customers.</p>
                </div>
                <div class="modal-footer">
                    <form action="../index.php" method="post">
                        <input type="submit" class="btn btn-default" value="Close">
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- End of Bootstrap Modal -->

<?php 
include '../includes/footer.php';
?>


<script type="text/javascript">


<?php if ($rommAdded==true){ $rommAdded=false; ?>
    $("#modalSuccess").modal('show');
    <?php } ?>


  /*Reference: Upload Image to Cloudinary - http://jsfiddle.net/fpoca4c8/462/*/
        window.uploadSuccess = function () {
            response = JSON.parse(this.responseText);
            document.getElementById('imageSource').setAttribute("src", response["secure_url"]);
            var imageID = response.public_id;
            console.log(imageID);
            $('#imageURL').val(imageID);

        }
        window.AJAXUploadImage = function (formElement) {
            if (!formElement.action) {
                return;
            }
            var xhr = new XMLHttpRequest();
            xhr.onload = uploadSuccess;
            xhr.open("post", "https://api.cloudinary.com/v1_1/drp5uq3ng/image/upload");
            xhr.send(new FormData(formElement));
        }
/* End of Reference: Upload image to Cloudinary */
</script>
</body>
</html>