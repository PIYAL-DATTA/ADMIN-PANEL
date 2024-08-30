<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<?php

if($_GET['user_email'] == ""){
    header("location: /demo/user.php");
    exit;
}

$user_email = $_GET['user_email'];
    $text = "";
    $textErr = " ";

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
    $sql2 = "SELECT * FROM login WHERE email = $user_email ";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    if($row2["login"] == "Yes") {
      

//================== selection =======================

$sql1 = "SELECT * FROM department where email = $user_email";
$result1 = $conn->query($sql1);
    ?>

    <div class="container mt-3">
    <div class="card mt-3 shadow-lg">
    <div class="card-body px-5">
        <h2>Department Authorization Form</h2>
        <p>Pending for authorization list:</p>
        <!-- Wrap the table and form within <form> tags -->
        <form method="post">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Select</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Name</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Department</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Email ID</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Contact No</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">MAC Address</th>
                    </tr>
                </thead>

<?php
if ($result1->num_rows > 0) {
    // output data of each row
    while ($row1 = $result1->fetch_assoc()) {

        $sql = "SELECT id, name, email, contact, mac FROM pendinglist where id = $row1[id] ";
        $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        
                        $name = $row["name"];
                        $dept = $row1["dept"];
                        $email = $row["email"];
                        $contact = $row["contact"];
                        $mac = $row["mac"];
                ?>  <!-- Printing Table -->
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="selectedRows[]" value="<?php echo $row["id"]; ?>"></input>
                                    </div>
                                </td>
                                
                                <td><span> <?php echo $name; ?></span></td>
                                <td><span> <?php echo $dept; ?></span></td>
                                <td><span> <?php echo $email; ?></span></td>
                                <td><span> <?php echo $contact; ?></span></td>
                                <td><span> <?php echo $mac; ?></span></td>
                            </tr>
                        </tbody>
                <?php
                    }
                } else {
                    echo "0 results";
                }

            }
        } else {
            echo "0 results";
        }
    $conn->close();
?>

            </table>
        
            <div class="form-check">
                <label class="fw-bold pb-2">Select All </label>
                <input type="checkbox" class="form-check-input" name="checkbox"></input>
            </div>
            <!-- Button and Text Area  -->
            <label for="comment" class="fw-bold pb-2">Permission Level:</label>
            <!-- Add name attribute to the textarea -->
            <input type ="text" class="form-control" placeholder="Enter Permission Level" name="text" value="<?php echo $text;?>"></input>
            
            <div class="mt-4 mb-5">
                <input type="submit" class="btn btn-secondary" name="reset" value="Reset"></input>
                <input type="submit" class="btn btn-success" name="submit" value="Submit"></input>
                <input type="submit" class="btn btn-danger" name="delete" value="Delete"></input>
                <input type="submit" class="btn btn-warning" name="logout" value="Logout"></input>
            </div>
        </form>

    </div>
    </div>
    </div>

    <?php 
    //---------------- SUBMIT Button ----------------------
    if (isset($_POST['submit'])) {
        if (empty($_POST["text"])) {
            $textErr = "Permission is required";
          } else {
            $text = $_POST["text"];
            $textErr = " ";
            if (!empty($_POST['selectedRows'])) {
                // foreach ($_POST['selectedRows'] as $selectedRowId) {
                //     echo $selectedRowId . "<br>";
                // }
                //     } else {
                // echo "No rows selected.";
                foreach ($_POST['selectedRows'] as $selectedRowId) {

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

                    $sql = "SELECT id, name, email, contact, mac FROM pendinglist WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $selectedRowId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    // Remove the placeholder for "new_id" from the SQL query
                    $stmt = $conn->prepare("INSERT INTO pending_authorizedlist (id, name, dept, email, contact, mac, permission) VALUES (?, ?, ?, ?, ?, ?, ?)");

                    // Bind parameters
                    $stmt->bind_param("issssss", $selectedRowId, $row["name"], $dept, $row["email"], $row["contact"], $row["mac"], $text);
                    $stmt->execute();

                    $sql = "DELETE FROM pendinglist WHERE id = ?";
                    //$conn->query($sql);
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $selectedRowId);
                    $stmt->execute();

                    $sql = "DELETE FROM department WHERE id = ?";
                    //$conn->query($sql);
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $selectedRowId);
                    $stmt->execute();

                }

                $stmt->close();
                $conn->close();
            } else {
                echo "No rows selected.";
            }
        }  ?>

        <meta http-equiv="refresh" content="1">
<?php    }
//---------------------------- DELETE button -----------------------------
    if (isset($_POST['delete'])) {  
            if (!empty($_POST['selectedRows'])) {
                // foreach ($_POST['selectedRows'] as $selectedRowId) {
                //     echo $selectedRowId . "<br>";
                // }
                //     } else {
                // echo "No rows selected.";
                foreach ($_POST['selectedRows'] as $selectedRowId) {

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

                    //$sql = "SELECT id, name, email, contact, mac FROM pendinglist WHERE id = ?";
                    $sql = "DELETE FROM pendinglist WHERE id = ?";
                    //$conn->query($sql);
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $selectedRowId);
                    $stmt->execute();

                    $sql = "DELETE FROM department WHERE id = ?";
                    //$conn->query($sql);
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $selectedRowId);
                    $stmt->execute();
                }


                $stmt->close();
                $conn->close();
            } 
            elseif (isset($_POST['checkbox'])) {

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

                $sql = "DELETE FROM pendinglist";
                //$conn->query($sql);
                $result = $conn->query($sql);

                $sql = "DELETE FROM department";
                //$conn->query($sql);
                $result = $conn->query($sql);
                $conn->close();
            }
            else {
                echo "No rows selected.";
            }
             ?>
            
            <meta http-equiv="refresh" content="1">
       <?php 
       }
//======================== LOGOUT =============================================
       if (isset($_POST['logout'])) {
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
        //$sql = "UPDATE login SET login='No' WHERE email = $user_email";
        $sql = "DELETE FROM login WHERE email = $user_email";
        $result = $conn->query($sql);
        $user_email = "";
        header("location: /demo/user.php");
        exit;
       }
    
    }
else{
    header("location: /demo/user.php");
    exit;
}

//======================= SELECT ALL =======================
if (isset($_POST['checkbox'])) {
    if (empty($_POST["text"])) {
        $textErr = "Permission is required";
      } else {
        $text = $_POST["text"];
        $textErr = " ";
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
                
                $sql = "SELECT * FROM pendinglist";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {

                // Remove the placeholder for "new_id" from the SQL query
                $stmt = $conn->prepare("INSERT INTO pending_authorizedlist (id, name, dept, email, contact, mac, permission) VALUES (?, ?, ?, ?, ?, ?, ?)");

                    // Bind parameters
                    $stmt->bind_param("issssss", $row["id"], $row["name"], $dept, $row["email"], $row["contact"], $row["mac"], $text);
                    $stmt->execute();
                    }
                }
                $sql = "DELETE FROM pendinglist";
                //$conn->query($sql);
                $result = $conn->query($sql);

                $sql = "DELETE FROM department";
                //$conn->query($sql);
                $result = $conn->query($sql);

                $stmt->close();
                $conn->close();
            
            }
            ?>

            <meta http-equiv="refresh" content="1">
        <?php 
    }    

?>
</body>

</html>
<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();

//echo "All session variables are now removed, and the session is destroyed."
?>