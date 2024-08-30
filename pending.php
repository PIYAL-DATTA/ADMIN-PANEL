<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php 
     
     $textErr = $text = "";
     $textb = "false";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {      
              //$text = test_input($_POST['text']);

              if (empty($_POST["text"])) {
                $textErr = "Permission is required";
                $textb = "false";
              } else {
                $text = test_input($_POST["text"]);
                $textb = "true";
              }

              if (isset($_POST['reset'])){
                $servername = "localhost";
                $username = "intern";
                $password = "Int3rn@cc";
                $dbname = "intern";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "DELETE FROM pendinglist";
                $conn->query($sql);
                $conn->close();
              }

              if (isset($_POST['submit'])){
                if($textb == "true"){
                $servername = "localhost";
                $username = "intern";
                $password = "Int3rn@cc";
                $dbname = "intern";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                $sql = "SELECT id, name, email, contact, mac FROM pendinglist";
                $result = $conn->query($sql);

                $sql = "DELETE FROM pendinglist";
                $conn->query($sql);
                $conn->close();

                if ($result->num_rows > 0) {
                  // output data of each row
                  while($row = $result->fetch_assoc()) {
                      $id=$row["id"];
                      $name=$row["name"];
                      $email=$row["email"];
                      $contact=$row["contact"];
                      $mac=$row["mac"];

                      $stmt = $conn->prepare("INSERT INTO pending_authorizedlist ( pre_id, name,	email,	contact,	mac, permission) VALUES ( ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("isssss", $id, $name, $email, $contact, $mac, $text);
                        $stmt->execute();
                        
                        echo "Successfully Done";
                        
                        $stmt->close();
                        $conn->close();
                  }
              }
            }
          }
        }
?>

<?php
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

$sql = "SELECT id, name, email, contact, mac FROM pendinglist";
$result = $conn->query($sql);
?>


<div class="container mt-3">
  <h2>Department Authorization Form</h2>
  <p>Pending for athorization list:</p>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Select</th>
        <th>ID</th>
        <th>Name</th>
        <th>Email ID</th>
        <th>Contact No</th>
        <th>MAC Address</th>
      </tr>
    </thead>
    <tbody>

<?php
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        /*$id=$row["id"];
        $name=$row["name"];
        $email=$row["email"];
        $contact=$row["contact"];
        $mac=$row["mac"]; */

        echo "

    
      <tr>
        <td>
          <a class='btn btn-secondary btn-sm' href='/demo/delete.php?id=$row[id]'>DELETE</a>
        </td>
        <td>$row[id]</td>
        <td>$row[name]</td>
        <td>$row[email]</td>
        <td>$row[contact]</td>
        <td>$row[mac]</td>
      </tr>
      ";

}
} else {
    echo "0 results";
}

$conn->close();
?>
  </tbody>
  </table>
  <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="comment" class="fw-bold">Permission Level:</label>
  <input type ="text" class="form-control" rows="1" placeholder="Enter Permission Level" name="text" value="<?php echo $text;?>">
    <span class="error">* <?php echo $textErr;?></span>
  <div class="mt-4 mb-5">
      <input type="submit" class="btn btn-secondary" name="reset" value="Reset"></input>
      <input type="submit" class="btn btn-secondary" name="submit" value="Submit"></input>
  </div>
</form>
</div>




</body>
</html>