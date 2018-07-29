<?php
if (isset($_GET['login'])){
    unset($_SESSION["email"]);
    unset($_SESSION["id"]);
    session_destroy();
    header('location:login.php');
}
?>


<head>
	<title>Swoop Airlines</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<!-- Navbar Refernce: https://w3schools.com/bootstrap/bootstrap_navbar.asp -->  
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="index.php">Swoop Airlines</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">Home</a></li>
    </ul>
    <?php if(!isset($_SESSION) || empty($_SESSION['id'])){ ?>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="registration.php">Sign Up</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
  <?php } else { 
      $userID= $_SESSION['id'];
  ?>
    <ul class="nav navbar-nav navbar-right">
        <li><a href="my-bookings.php?id=<?php echo $userID;?>">My Bookings</a></li>
        <li><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?login=false">Logout</a></li>
    </ul>
  <?php } ?>
    </div>
</nav>
<!-- End of Reference: Navbar -->