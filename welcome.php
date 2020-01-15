<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

 $email="";
 $message="";
 $subject="";
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
        <?php 
        if(isset($_POST['sendmail'])) {
            require 'PHPMailerAutoload.php';
            require 'credential.php';
            $mail = new PHPMailer;
            // $mail->SMTPDebug = 4;                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = EMAIL;                 // SMTP username
            $mail->Password = PASS;                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            $mail->setFrom(EMAIL, 'Tanzila Tahera');
            $mail->addAddress($_POST['email']);     // Add a recipient
            $mail->addReplyTo(EMAIL);
            // print_r($_FILES['file']); exit;
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $_POST['subject'];
            $mail->Body    = $_POST['message'];
            $mail->AltBody = $_POST['message'];
            if(!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent';
            }

            //storing mails in DB
            
            require_once "config.php";

            $sql = "INSERT INTO mail_archive (email, sub, message) VALUES (?, ?, ?)";

            if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_sub, $param_message);

            $param_email = $_POST['email'];
            $param_sub = $_POST['subject'];
            $param_message = $_POST['message'];
            if(mysqli_stmt_execute($stmt)){
                //if mail is stored correctly 
                echo "<b>Sent mail is stored in database";
            } else{
                echo "Mail could not be saved";
            }
            }
        }
     ?>
     

    <!-- form design for mail compose  -->  
    <div class="wrapper">
        <h2 align="center">Compose Mail</h2>
        <p> </p>
        <div class="row">
        <div class="col-md-11" style="margin:0px 0px 0px 80px">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Receiver email ID</label>
                <input type="email" id="email" name="email" placeholder="Please enter recipient mail address" class="form-control" value="<?php echo $email; ?>">
                
            </div>  
            <div class="form-group">
                <label>Subject</label>
                <textarea class="form-control" type="textarea" id="subject" name="subject" placeholder="Write subject here" value="<?php echo $subject; ?>" maxlength="50" rows="1"></textarea>
                
            </div>  
            <div class="form-group">
                <label>Message</label>
                <textarea class="form-control" type="textarea" id="message" name="message" placeholder="Your Message Here" value="<?php echo $message; ?>" maxlength="6000" rows="4"></textarea>
                
            </div>
            <div class="form-group">
                <input type="submit" name="sendmail" class="btn btn-primary" value="Send Mail">
            </div>
            
        </form>
    </div>
</div>
       

        <a href="archive.php" class="btn btn-danger">Mail Archive</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </div>
</body>
</html>