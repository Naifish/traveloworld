<?php
if (isset($_POST['id_token']) && !empty($_POST['id_token'])) {
  # code...
  session_start();
  $_SESSION['token']=$_POST['id_token'];
  $_SESSION['id']=$_POST['id'];
  $_SESSION['URS_AGNT']=md5($_SERVER['HTTP_USER_AGENT']);

  header("location: index.php");
}

?>

<!DOCTYPE html>
<html>
<?php 
require 'includes/header.php';
?>

<div class="container container-registration">
<div class="row">
	<div class="col-md-12">

  <h3 class="text-center">Login</h3>
  
  <form action="#" method="POST" id="myForm">
      <input type="hidden" name="id_token" id="id_token">
      <input type="hidden" name="id" id="id">
   </form>
  <div class="g-signin2" data-onsuccess="onSignIn" data-theme="dark"></div>
  

</div>
</div>
</div>
<?php 
include 'includes/footer.php';
?>
<!-- Reference: https://developers.google.com/identity/sign-in/web/backend-auth#create-an-account-or-session -->
<script>
      var id_token,id;
      function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        id= profile.getId(); 
        // The ID token you need to pass to your backend:
        id_token = googleUser.getAuthResponse().id_token;
        var id;
        var xhr = new XMLHttpRequest();
        var url = "https://atlantic-hotel.azurewebsites.net/public/index.php/signin/"+id_token;
        xhr.open('GET', url);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          
        };
        xhr.send('idtoken=' + id_token);
        $('#id_token').val(id_token);
        $('#id').val(id);
        $('#myForm').submit();

      };

      </script>

</body>
</html>