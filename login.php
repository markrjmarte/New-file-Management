<!DOCTYPE html>
<html lang="en">

  <?php include('./header.php'); ?>
   <?php 
   session_start();
   if(isset($_SESSION['login_id']))
   header("location:index.php?page=dashboard");
   ?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>BEED EFile Management</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon1.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>
<style>
  
.avatar {
    height: 50%;
    border-radius: 50%;
    position: absolute;
    top: 0px;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 1s ease-in-out; /* Adjust transition duration as needed */
  }
.slsulogo {
    padding-top: 200px;
    left: 30%;
    width: 65%;
    margin: 30px 50px 0px;
    filter: drop-shadow(0px 0px 2px var(--blue));
    position: absolute;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 1s ease-in-out;
}
  #login-left{
    position: absolute;
    left: 0;
    width: 100%;
    height: -webkit-fill-available;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    display: flex;
    align-items: center;
    position: fixed;
}
#login-right .card{
		margin: auto
	}
.img .logo {
      width: 900px; 
    height: auto;
  }
.login-bg {
    background: rgb(251 248 248 / 73%);
    text-align: center;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 50px;
    border-radius: 20px;
    transform: translate(0%,0%);
}
.new-login-wrapper {
    margin-top: 40px;
    display: flex;
    flex-direction: column;
    color: var(--blue);
    box-sizing: border-box;
    border-radius: 20px;

}

.new-input-label {
    color: var(--black);
    margin-bottom: 4px;
    display: flex;
    justify-content: flex-start;
    font: normal normal medium 16px/19px Roboto;
    font-weight: 600;
}

.form-spacer {
    margin-top: 2rem;
}
.new-title-2 {
    color: #041E80;
    margin: 0;
    padding: 0px;
    font: normal normal bold 34px/43px "Nunito", sans-serif;
}

.new-title-3 {
    color: #222;
    margin: 0;
    padding: 0px;
    font: normal normal bold 27px/43px "Nunito", sans-serif;
}

h1, h2, h3, h4, h5, h6 {
    margin-top: .05rem;
    font-weight: bold;
}

.login-error{
    font-size: x-small;
}
</style>
<body>

<main>
  
  <div class="container">
      <div id="login-left" style="background-image: url('assets/img/background.jpg');">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
            <div class="login-bg">
                  <div class="new-login-wrapper">
                  <center><img src="assets/img/avatar.png" class="avatar"></center>
                  <center><img src="assets/img/avatar2.png" class="avatar"></center>
                          <form class="row g-3" id="login-form">
                            <div class="col-12">
                              <label for="username" class="new-input-label">Username</label>
                              <input type="text" autofocus="" name="username" class="form-control" id="username" required>
                            </div>
                            
                            <div class="col-12">
                              <label for="password" class="new-input-label">Password</label>
                              <div class="input-group">
                                <input type="password" name="password" class="form-control" id="password" required>
                                <span class="input-group-text">
                                  <i class="bi bi-eye" id="toggle-password"></i>
                                </span>
                              </div>
                            </div>
                            
                            <div class="col-12">
                              <div class="text-end mb-2">
                                <span id="login-error" class="text-danger login-error" style="display: none;">
                                  Username or password is incorrect
                                </span>
                              </div>
                              <button class="btn btn-primary w-100" type="submit">Login</button>
                            </div>
                          </form>
                    </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        
      </section>
    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>
<script>
  // Function to toggle password visibility
  function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("toggle-password");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      toggleIcon.classList.remove("bi-eye");
      toggleIcon.classList.add("bi-eye-slash");
    } else {
      passwordInput.type = "password";
      toggleIcon.classList.remove("bi-eye-slash");
      toggleIcon.classList.add("bi-eye");
    }
  }

  // Attach a click event to the toggle password button
  document.getElementById("toggle-password").addEventListener("click", togglePassword);
</script>
<script>
  $(document).ready(function () {
    $('#login-form').submit(function (e) {
      e.preventDefault();
      $('#login-form button[type="submit"]').attr('disabled', true).html('Logging in...');
  
      // Remove any existing error messages and reset styles
      $('#login-form .is-invalid').removeClass('is-invalid');
      $('.invalid-feedback-log').hide();
      $('#login-error').hide(); // Hide the error message
  
      $.ajax({
        url: 'ajax.php?action=login',
        method: 'POST',
        data: $(this).serialize(),
        error: function (err) {
          console.log(err);
          $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
        },
        success: function (resp) {
            if (resp == 1) {
                window.location.href = 'index.php?page=dashboard';
            } else {
                // Show invalid feedback for username and password fields
                $('#username').addClass('is-invalid');
                $('#password').addClass('is-invalid');
                $('.invalid-feedback-log').show();

                // Show the error message
                $('#login-error').show();

                $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
            }
        }
      });
    });
  });
</script>

<script>
  // JavaScript or jQuery to toggle avatar visibility
  document.addEventListener("DOMContentLoaded", function () {
    var avatars = document.querySelectorAll(".avatar");
    var index = 0;
    var intervalTime = 3000; // Time between avatar changes in milliseconds (adjust as needed)
    
    function toggleAvatar() {
      avatars[index].style.opacity = 0; // Hide the current avatar
      index = (index + 1) % avatars.length; // Move to the next avatar or back to the start if reached the end
      avatars[index].style.opacity = 1; // Show the next avatar
    }
    
    // Initial visibility setup
    avatars[0].style.opacity = 1; // Show the first avatar
    
    setInterval(toggleAvatar, intervalTime); // Start the interval to toggle avatars
  });
</script>

</html>