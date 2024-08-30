<!DOCTYPE html>
<html lang="en">
<head>
  <title>Intern Registration Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!--  collapsible -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>

<?php
// define variables and set to empty values
$nameErr = $emailErr = $contactErr = $macErr = "";
$name = $email = $contact = $dept = $mac = "";
$nameb = $emailb = $contactb = $deptb = $macb = "false";
$collapsErr = "Fill up form";
$use = "false";

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
    $nameb = "false";
  } else {
    $name = test_input($_POST["name"]);
    $nameb = "true";
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
      $nameb = "false";
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
    $emailb = "false";
  } else {
    $email = test_input($_POST["email"]);
    $emailb = "true";
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
      $emailb = "false";
    }
  }
    
  if (empty($_POST["contact"])) {
    $contactErr = "Contact number is required";
    $contactb = "false";
  } else {
    $contact = test_input($_POST["contact"]);
    $contactb = "true";
    // check
    if (!preg_match('/^[0-9]{11}+$/',$contact)) {
      $contactErr = "Invalid Contact number";
      $contactb = "false";
    }
    elseif (substr($contact, 0, 2) != 01) {
      $contactErr = "Invalid Operator number";
      $contactb = "false";
    }
  }

  if (empty($_POST["mac"])) {
    $macErr = "Mac Address is required";
    $macb = "false";
  } else {
    $mac = test_input($_POST["mac"]);
    $macb = "true";
    // check 
    if (!preg_match('/^(?:(?:[0-9a-f]{2}[\:]{1}){5}|(?:[0-9a-f]{2}[-]{1}){5}|(?:[0-9a-f]{2}){5})[0-9a-f]{2}$/i', $mac)) {
      $macErr = "Invalid Mac Address";
      $macb = "false";
    }
  }

  $dept = $_POST["dept"];

  if (isset($_POST['reset'])){
    $nameErr = $emailErr = $contactErr = $macErr = "";
    $name = $email = $contact = $dept = $mac = "";
    $nameb = $emailb = $contactb = $macb = "false";
    $use = "false";
  }
//-------------------------- SUBMIT ------------------------
  if (isset($_POST['submit'])){
      if($nameb == "true" && $emailb == "true" &&  $contactb == "true" &&  $macb == "true"){

      $sql = "SELECT * FROM pendinglist";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $prev_email = $row["email"];
          $prev_contact = $row["contact"];
          
          if($prev_email == $email || $prev_contact == $contact) {
            $use = "true";
            if($prev_email == $email) {
              $emailErr = "Email already in use";
            }
            elseif($prev_contact == $contact) {
              $contactErr = "Contact already in use";
            }
          }
        }
      } 

      if($use == "false") {

      $stmt = $conn->prepare("INSERT INTO pendinglist ( name,	email,	contact,	mac) VALUES ( ?, ?, ?, ?)");
      $stmt->bind_param("ssss", $name, $email, $contact, $mac);
      $stmt->execute();

      $sql = "SELECT email FROM account where department = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $dept);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();

      $stmt = $conn->prepare("INSERT INTO department (dept,	email) VALUES ( ?, ?)");
      $stmt->bind_param("ss", $dept, $row["email"]);
      $stmt->execute();
      
      $stmt->close();

      $nameErr = $emailErr = $contactErr = $macErr = "";
      $name = $email = $contact = $dept = $mac = "";
      $nameb = $emailb = $contactb = $macb = "false";

      }
    }

  }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<div class="row">
<div class="col-lg-3">
</div>
<div class="col-lg-6">
  
<div class="card mt-3 shadow-lg" style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">
<div class="card-body px-5">
  <h2 class="d-flex justify-content-center">Intern Registration Form</h2>
  <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <!-----------------Name----------->
    <div class="mt-5">
    <div class="row">
      <div class="col-xxl-2">
      <label for="name" class="fw-bold">Name:</label>
      </div>
      <div class="col-xxl-10">
      <input type="text" class="form-control"  placeholder="Enter Full Name" name="name" value="<?php echo $name;?>">
      <span class="error">* <?php echo $nameErr;?></span>
      </div>
    </div>
    </div>
<!-----------------Email & Contact----------->
<div class="mt-3">    
<div class="row">
      <div class="col-xxl-2">
      <label for="email" class="fw-bold" >Email ID:</label>
      </div>
      <div class="col-xxl-4">
        <input type="text" class="form-control" placeholder="Enter email" name="email" value="<?php echo $email;?>">
        <span class="error">* <?php echo $emailErr;?></span>
      </div>
      <div class="col-xxl-2">
      <label for="contact" class="fw-bold">Contact:</label>
      </div>
      <div class="col-xxl-4">
        <input type="text" class="form-control" placeholder="Enter Contact number" name="contact" value="<?php echo $contact;?>">
        <span class="error">* <?php echo $contactErr;?></span>
      </div>
    </div>
    </div>

    <!-----------------Dept----------->
    <div class="mt-3">
    <div class="row">
    <div class="col-xxl-2">
      <label for="dept" class="fw-bold form-label">Dept:</label>
      </div>
      <div class="col-xxl-10">
        <select class="form-select" name="dept">
          <option>Web Development</option>
          <option>System Security</option>
        </select>
      </div>
    </div>
    </div>
<!-----------------Mac ID----------->
    <div class="mt-3">
    <div class="row">
      <div class="col-xxl-2">
      <label for="mac" class="fw-bold">MAC ID:</label>
      </div>
      <div class="col-xxl-10">
      <input type="text" class="form-control"  placeholder="Enter MAC ID" name="mac" value="<?php echo $mac;?>">
      <span class="error">* <?php echo $macErr;?></span>
      </div>
    </div>
    <!-----------------Button----------->
    <div class="mt-4">
        <input type="submit" class="btn btn-secondary" name="reset" value="Reset"></input> 
        <input type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#myModal" name="submit" value="Submit"></input> 
      </div>
  </form>
  </div>
</div>


</div>
<div class="col-lg-3">
</div>
</div>

<?php $conn->close(); ?>

</body>
</html>