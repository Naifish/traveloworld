<?php

session_start();
if((isset($_SESSION)) && (empty($_SESSION['email']) || $_SESSION['email']!="macpro@live.com")){ header('location:../login.php');}
if (isset($_GET['login'])){
    unset($_SESSION["email"]);
    unset($_SESSION["id"]);
    session_destroy();
    header('location:../login.php');
}
$name='';$source='';$destination='';$flightType='';$details='';$departDate='';$returnDate='';$price='';$status='';$image='';$flightAdded=false;$errs=array();

if (isset($_POST['btn-add-flight'])) {
	require '../includes/database.php';
	if (empty($_POST['name'])) {
		$errs[]="Name is required";
	}else{
		$name=$_POST['name'];
	}

  if (empty($_POST['price'])) {
    $errs[]="Price is required";
  }else{
    $price=$_POST['price'];
  }

	if (empty($_POST['source'])) {
		$errs[]="source id is required";
	}else{
		$source=$_POST['source'];
	}

	if (empty($_POST['destination'])) {
		$errs[]="Destination is required";
	}else{
		$destination=$_POST['destination'];
	}

  if (empty($_POST['flightType'])) {
    $errs[]="Type of flight is required";
  }else{
    $flightType=$_POST['flightType'];
  }

  if (empty($_POST['details'])) {
    $errs[]="Flight details is required";
  }else{
    $details=$_POST['details'];
  }

  if (empty($_POST['departDate'])) {
    $errs[]="Departure date is required";
  /* [2] regextester.com "Regex for Date". www.regextester.com [Online]. Available. "https://www.regextester.com/96222".[Accessed On: 27th July 2018]. */
    } elseif (!(preg_match('/^((19|20)\d{2})-((0|1)\d{1})-((0|1|2|3)\d{1})/', $_POST['departDate']))) {
        /* end of reference */
        $errs[] = "Invalid flight departure date.";
    } else {
        $departDate = $_POST['departDate'];
    }

    if (empty($_POST['returnDate'])) {
    $errs[]="Return date is required";
  /* [2] regextester.com "Regex for Date". www.regextester.com [Online]. Available. "https://www.regextester.com/96222".[Accessed On: 27th July 2018]. */
    } elseif (!(preg_match('/^((19|20)\d{2})-((0|1)\d{1})-((0|1|2|3)\d{1})/', $_POST['returnDate']))) {
        /* end of reference */
        $errs[] = "Invalid flight return date.";
    } else {
        $returnDate = $_POST['returnDate'];
    }

	if (empty($_POST['status'])) {
		$errs[]="Availability status is required";
	}else{
		$status=$_POST['status'];
	}


	if (count($errs)<1) {

          $con = new Database();
          $con = $con->connect();
                $exeQry = $con->query("INSERT INTO flights (name,source,destination,flightType,details,departDate,returnDate,status,price) VALUES('$name','$source','$destination','$flightType','$details','$departDate','$returnDate','$status','$price')");
                if ($exeQry==true){
                  $flightAdded=true;
                }        
	}
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Swoop Airlines - Admin</title>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<!-- Navbar Refernce: https://w3schools.com/bootstrap/bootstrap_navbar.asp -->  
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="../index.php">Swoop Airlines</a>
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

<h3 class="text-center">Enter Flight</h3>
<ul>
    <?php if (count($errs)>0){ foreach ($errs as $er) {
    ?>
    <li><?php echo $er; ?></li>
    <?php }} ?>
  </ul>
<form action="#" method="post">
	<div class="form-group">
    <input type="hidden" name="image" value="" id="imageURL">
    <label >Flight Name</label>
    <input type="text" class="form-control" name="name" value="<?php if(isset($name)){ echo $name; } ?>" placeholder="Flight Name" required>
  </div>
  <div class="form-group">
    <label >Type of Flight</label>
    <input type="text" class="form-control" name="flightType" value="<?php if(isset($flightType)){ echo $flightType; } ?>" placeholder="Flight Type" required>
  </div>
  <div class="form-group">
    <label >Source</label>
    <input type="text" class="form-control" name="source" value="<?php if(isset($source)){ echo $source; } ?>" placeholder="Source" required>
  </div>
  <div class="form-group">
    <label >Destination</label>
    <input type="text" class="form-control" name="destination" value="<?php if(isset($destination)){ echo $destination; } ?>" placeholder="Destination" required>
  </div>
  <div class="form-group">
    <label >details</label>
    <textarea name="details" required rows="6" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <label >Departure Date:</label>
    <input type="date" class="form-control" name="departDate" required>
  </div>
  <div class="form-group">
    <label >Return Date:</label>
    <input type="date" class="form-control" name="returnDate" required>
  </div>
  <div class="form-group">
    <label >Price</label>
    <input type="text" class="form-control" name="price" value="<?php if(isset($price)){ echo $price; } ?>" placeholder="Enter Amount" required>
  </div>

  <div class="form-group">
    <label >Available</label>
    <input type="text" class="form-control" name="status" value="<?php if(isset($status)){ echo $status; } ?>" placeholder="yes or no" required>
  </div>
  <input type="submit" value="Add Flight" name="btn-add-flight" class="btn btn-primary">
</form>
</div>
</div>
</div>


<!-- [5] w3schools.com "Bootstrap Modal". www.w3schools.com [Online]. Available. "https://www.w3schools.com/bootstrap/bootstrap_modal.asp".[Accessed On: 19th July 2018].-->
    <div id="modalSuccess" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <form action="../index.php" method="post">
                        <button type="submit" class="close">&times;</button>
                    </form>
                    <h4 class="modal-title">Flight successfully added</h4>
                </div>
                <div class="modal-body">
                    <p>This flight is now available for the customers.</p>
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


<?php if ($flightAdded==true){ $flightAdded=false; ?>
    $("#modalSuccess").modal('show');
    <?php } ?>
</script>
</body>
</html>