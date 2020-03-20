<?php
  include("include/sql.php");
  include("include/title.php");
  include("sendemail.php");
  if(isset($_POST['sendemail'])){
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Phone = $_POST['Phone'];
    $Message = addslashes($_POST['Message']);
    $collectemail = "w2957360@gmail.com";

    $mail->setFrom('a2957360@gmail.com', 'sender');
    $mail->addAddress($Email, 'guest');     // Add a recipient
    try {
      //Content
      $mail->isHTML(true);                                  // Set email format to HTML
      // $mail->AddEmbeddedImage('static/img/icon.png','logo');
      $mail->Subject = 'LuLuread';
      $mail->Body    = "Thank you for contact us<br>".
      					"We will process it as soon as possiable";

      if($mail->send()){
        // echo "<script> location.href='passwordsuccess.html'; </script>";
      }
      } catch (Exception $e) {
          echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
      $mail->setFrom('a2957360@gmail.com', 'sender');
	  $mail->addAddress($collectemail, 'company');     // Add a recipient
	  try {
	      //Content
	      $mail->isHTML(true);                                  // Set email format to HTML
	      // $mail->AddEmbeddedImage('static/img/icon.png','logo');
	      $mail->Subject = 'LuLuread Contact Form';
	      $mail->Body    = "Guest Name:<br>".$Name."<br>".
		  					"Guest Email:<br>".$Email."<br>".
		  					"Guest Phone:<br>".$Phone."<br>".
		  					"Guest Message:<br>".$Message."<br>";

	      if($mail->send()){
	        // echo "<script> location.href='passwordsuccess.html'; </script>";
	      }
	      } catch (Exception $e) {
	          echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
	    }
  }

?>

  <body>
    <div class="container">
      <div class="row">

        <?php include("include/header.php");?>

        <div class="col-12 text-center content">
          <!-- recommend显示 -->
          <div class="componentup margin_5">
            <span class="componenttitle">Contact Us</span>
          </div>
          <div class="row margin_5">
            <div class="col-12">
              <span class="contactby">Contact By</span>
            </div>
            <div class="col-12">
              <span class="emailname">luluread@mail.com</span>
            </div>
          </div>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype='multipart/form-data'>
            <div class="row margin_5">
            <div class="col-12">
              <span class="contactby">Send Us Message</span>
            </div>
            <div class="col-12 text-left margin_d5">
              <span class="emailtitle">Name</span>
              <input class="inputtext" type="text" name="Name">
            </div>
            <div class="col-12 text-left margin_d5">
              <span class="emailtitle">Email</span>
              <input class="inputtext" type="text" name="Email">
            </div>
            <div class="col-12 text-left margin_d5">
              <span class="emailtitle">Phone</span>
              <input class="inputtext" type="text" name="Phone">
            </div>
            <div class="col-12 text-left margin_d5">
              <span class="emailtitle">Message</span>
              <textarea class="inputtextarea" name="Message"></textarea>
            </div>
            <div class="col-12 text-left margin_d5">
              <input class="emailbtn" type="submit" name="sendemail" value="Send">
            </div>
            </div>
          </form>

        </div>
<!--         <div class="col-12 text-center footer">
          底部
        </div> -->
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <?php
      include("include/js.php");
    ?>
    <script type="text/javascript">
    </script>
  </body>
</html>