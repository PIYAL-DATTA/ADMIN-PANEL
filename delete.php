<?php 
            if(isset($_GET["id"])) {
              $btn_id = $_GET["id"];

              $servername = "localhost";
              $username = "intern";
              $password = "Int3rn@cc";
              $dbname = "intern";

              // Create connection
              $conn = new mysqli($servername, $username, $password, $dbname);
              $sql = "DELETE FROM pendinglist WHERE id= $btn_id";
              $conn->query($sql);
            }
    header("location: /demo/pending.php");
    exit;
?>