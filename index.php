<?php 
$success="";
$error="";
if (isset($_POST['sending_email_btn'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $emailSendMessage = $_POST['message'];
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $message = "<html>
  <head>
  	<title>Message from ". $name ."</title>
  </head>
  <body>
  	<p>".$emailSendMessage."</p>
  </body>
  </html>";
  if (mail('2020magics@gmail.com', $subject, $message, $headers)) {
   $success="Email Send Sucessfully";
  }else{
   $error="Failed to send email. Please try again later";
  }
}
?>
<html>
  <head>
    <title>Landscape Wallpapers App</title>
  </head>
  <body style="padding-top:20px;">
	  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
	<div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="well well-sm">
          <form class="form-horizontal" action="" method="post">
          <fieldset>
            <legend class="text-center">Contact us</legend>
			<?php
			  if($success != ""){
			?>
			  <p class="alert alert-success"><?php echo $success; ?></p>
			<?php
			  }
			?>
			<?php
			  if($error != ""){
			?>
			  <p class="alert alert-danger"><?php echo $error; ?></p>
			<?php
			  }
			?>
            <!-- Name input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="name">Name</label>
              <div class="col-md-9">
                <input id="name" name="name" type="text" placeholder="Your name" class="form-control">
              </div>
            </div>
    
            <!-- Email input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="email">Your E-mail</label>
              <div class="col-md-9">
                <input id="email" name="email" type="text" placeholder="Your email" class="form-control">
              </div>
            </div>
    
            <!-- Message body -->
            <div class="form-group">
              <label class="col-md-3 control-label" for="message">Your message</label>
              <div class="col-md-9">
                <textarea class="form-control" id="message" name="message" placeholder="Please enter your message here..." rows="5"></textarea>
              </div>
            </div>
    
            <!-- Form actions -->
            <div class="form-group">
              <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-lg" name="sending_email_btn">Submit</button>
              </div>
            </div>
          </fieldset>
          </form>
        </div>
      </div>
	</div>
</div>
  </body>
</html>
