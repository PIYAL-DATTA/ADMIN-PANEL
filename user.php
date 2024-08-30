
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
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
$emailErr = $passwordErr = "";
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
      } else {
        $email = $_POST["email"];
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Invalid email format";
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

    if (isset($_POST['login'])){
        $servername = "localhost";
        $username = "intern";
        $pass = "Int3rn@cc";
        $dbname = "intern";

        // Create connection
        $conn = new mysqli($servername, $username, $pass, $dbname);

        $stmt = $conn->prepare("SELECT * FROM account WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        //$row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                if( $password == $row["password"]) {
                    $stmt = $conn->prepare("INSERT INTO login ( email, login) VALUES ( ?, 'Yes')");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    header("location: /demo/pendinglist.php?user_email='$email'");
                    exit;
                }
                //$sql = "UPDATE login SET login='No' WHERE email = $email ";
                //$result = $conn->query($sql);
            }
        }
      }

      if (isset($_POST['register'])){
        header("location: /demo/account.php");
        exit;
      }
}

?>

<div class="row">
<div class="col-lg-3">
</div>
<div class="col-lg-6">
  
<div class="card mt-3 shadow-lg" style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">
<div class="card-body px-5">
  <h2 class="d-flex justify-content-center">Account Login</h2>
  <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <!----------------- Email ----------->
    <div class="mt-5">
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
    <!-----------------Button----------->
    <div class="mt-4">
        <input type="submit" class="btn btn-secondary" name="login" value="Login"></input>
        <input type="submit" class="btn btn-secondary" name="register" value="Register"></input>
      </div>
  </form>
  </div>
</div>


</div>
<div class="col-lg-3">
</div>
</div>
</body>