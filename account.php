
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Account</title>
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
$emailErr = $passwordErr = $re_passwordErr = $nameErr = "";
$email = $password = $re_password = $name = "";
$emailb = $nameb = "false";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $emailb = "false";
      } else {
        $email = $_POST["email"];
        $emailb = "true";
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Invalid email format";
          $emailb = "false";
        }
      }

      if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $nameb = "false";
      } else {
        $name = $_POST["name"];
        $nameb = "true";
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
          $nameErr = "Only letters and white space allowed";
          $nameb = "false";
        }
      }

      if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
      } 
      elseif(strlen($_POST["password"]) < 6) {
        $passwordErr = "Atleast 6 character needed";
      }
      else {
        $password = $_POST["password"];
      }

      if (empty($_POST["re_password"])) {
        $re_passwordErr = "Re-type password";
      } else {
        $re_password = $_POST["re_password"];
      }

      $dept = $_POST["dept"];

    if (isset($_POST['create'])){
      if($nameb == "true" && $emailb == "true") {
        if($password == $re_password) {
        $servername = "localhost";
        $username = "intern";
        $password = "Int3rn@cc";
        $dbname = "intern";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        try {
          $stmt = $conn->prepare("INSERT INTO account ( email, password, department, depthead) VALUES ( ?, ?, ?, ?)");
          $stmt->bind_param("ssss", $email, $re_password, $dept, $name);
          $stmt->execute();
        }catch(Exception $e) {
          $emailErr = "Email already in USE";
        }
        ?>
        <!-- The Modal -->
        <div class="modal" id="myModal">
          <div class="modal-dialog">
            <div class="modal-content">
    
              <!-- Modal body -->
              <div class="modal-body">
                Successfully Done...
              </div>
    
            </div>
          </div>
        </div>
        <?php

        }
        else {
            echo "Password don't match";
        }
      }
      else {
        $emailb = $nameb = "false";
        $nameErr = "Name is required";
        $emailErr = "Email is required";
      }

    }

    if (isset($_POST['reset'])){
      $nameErr = $emailErr = $contactErr = $macErr = "";
      $name = $email = $contact = $dept = $mac = "";
      $emailb = $nameb = "false";
    }
}

?>

<div class="row">
<div class="col-lg-3">
</div>
<div class="col-lg-6">
  
<div class="card mt-3 shadow-lg" style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">
<div class="card-body px-5">
  <h2 class="d-flex justify-content-center">Create Account</h2>
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
  <!----------------- Email ----------->
    <div class="mt-3">
    <div class="row">
      <div class="col-xxl-2">
      <label for="email" class="fw-bold pb-2">User Email:</label>
      </div>
      <div class="col-xxl-10">
      <input type="text" class="form-control"  placeholder="Enter Email Address" name="email" value="<?php echo $email;?>">
      <span class="error">* <?php echo $emailErr;?></span>
      </div>
    </div>
    </div>
<!----------------- Department Name ----------->
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
    <!----------------- Pasword ----------->
    <div class="mt-3">
    <div class="row">
      <div class="col-xxl-2">
      <label for="password" class="fw-bold pb-2">Password:</label>
      </div>
      <div class="col-xxl-10">
      <input type="password" class="form-control"  placeholder="Enter Password" name="password">
      <span class="error">* <?php echo $passwordErr;?></span>
      </div>
    </div>
    <!----------------- Re-type Pasword ----------->
    <div class="mt-3">
    <div class="row">
      <div class="col-xxl-2">
      <label for="re_password" class="fw-bold pb-2">Re-type Password:</label>
      </div>
      <div class="col-xxl-10">
      <input type="password" class="form-control"  placeholder="Enter Password" name="re_password">
      <span class="error">* <?php echo $re_passwordErr;?></span>
      </div>
    </div>
    <!-----------------Button----------->
    <div class="mt-4">
        <input type="submit" class="btn btn-secondary" name="reset" value="Reset"></input> 
        <input type="submit" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#myModal" name="create" value="Create"></input>
      </div>
  </form>
  </div>
</div>


</div>
<div class="col-lg-3">
</div>
</div>
</body>