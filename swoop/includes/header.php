<head>
	<title>Swoop Airlines</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="562698964895-f74sr0oq352l47ujth06mdtds2vk65es.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    
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
        <li><a href="google-login.php">Login</a></li>
    </ul>
  <?php } else { 
      //$userID= $_SESSION['id'];
  ?>
    <ul class="nav navbar-nav navbar-right">
        <li><a href="my-bookings.php">My Bookings</a></li>
        <li><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?login=false" onclick="signOut();"">Logout</a></li>
    </ul>
  <?php } ?>
    
    </div>

    <script>
  //Reference: https://developers.google.com/identity/sign-in/web/sign-in
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      <?php
    if (isset($_GET['login'])){
      unset($_SESSION["token"]);
      unset($_SESSION["id"]);
      session_destroy();
      header('location:index.php');
    }
?>
    });
  }
</script>
</nav>
<!-- End of Reference: Navbar -->