<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$search="";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
	<div class="container-fluid">
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to your mail archive.</h1>
    </div>

 <div class="wrapper">
        <h2>Mail Archive</h2>
        

        <div class="row">
        <div class="mx-auto">
            
            <?php

            require_once "config.php";

            $sql = "SELECT id, email, sub, message FROM mail_archive";
            if($stmt = mysqli_prepare($link, $sql)){
            
            $result = $link->query($sql);
            
            if ($result->num_rows > 0) {
            echo "<table id=\"archive_tbl\" class=\"table-responsive\" style=\"width:100%\"><tr><th>ID</th><th>Receiver</th><th>Subject</th><th>Message</th></tr>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
            echo "<tr><td class=\"bg-primary\">".$row["id"].
                 "</td><td class=\"bg-success\">".$row["email"].
                 "</td><td class=\"bg-info\"> ".$row["sub"].
                 "</td><td class=\"bg-warning\">".$row["message"].
                 "</td></tr>";
            }
            echo "</table>";
            } else {
            echo "0 results";
            }
            }
            
        ?>
        </div>
        </div>

        <!--taking input to search for mail-->
        <div class="row">

        	<div class="col-md-12" style="margin:20px 20px 20px 0px">
        		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        		<label style="margin:20px">Enter email ID to search</label>
        		<input type="email" name="search" value="<?php $search;?>">
                <input type="submit" name="Search" class="btn btn-primary" value="search">
            </form>
             <?php

            require_once "config.php";

            $result="";
            $row="";

            if($_SERVER["REQUEST_METHOD"] == "POST"){
            $search = $_POST["search"];

            $sql = "SELECT * FROM mail_archive WHERE email like '%{$search}%'";


            if($stmt = mysqli_prepare($link, $sql)){
            
            $result = $link->query($sql);
            
            if ($result->num_rows > 0) {
            echo "<table id=\"archive_tbl\" class=\"table-responsive\" style=\"width:100%\"><tr><th>ID</th><th>Receiver</th><th>Subject</th><th>Message</th></tr>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
            echo "<tr><td class=\"bg-primary\">".$row["id"].
                 "</td><td class=\"bg-success\">".$row["email"].
                 "</td><td class=\"bg-info\"> ".$row["sub"].
                 "</td><td class=\"bg-warning\">".$row["message"].
                 "</td></tr>";
            }
            echo "</table>";
            } else {
            echo "0 results";
            }
            }
        }
            ?>
        </div>
        </div>

        <div class="row">
        <div class="col-md-12"  style="margin:10px; alignment:center">
             <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </div>
      
        </div>
  </div>
</div>
  </body>  
  </html> 