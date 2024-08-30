<?php

$email = $_GET['user_email'];

$servername = "localhost";
$username = "intern";
$password = "Int3rn@cc";
$dbname = "intern";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);  
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    $sql2 = "SELECT * FROM login where email = $email ";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    if($row2["login"] == "Yes") {
      header("location: /demo/pendinglist.php?email='$email'");
      exit;
    }
    else{
        header("location: /demo/user.php");
        exit;
    }

    //$sql = "DELETE FROM login";
    //$result = $conn->query($sql);
    //header("location: /demo/user.php?user_email='$email'");
    //exit;
?>