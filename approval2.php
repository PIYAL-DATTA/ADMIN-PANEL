<!DOCTYPE html>
<html lang="en">

<head>
    <title>Approval List</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <?php

    $textErr = $text = "";

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

    $sql = "SELECT * FROM pending_authorizedlist";
    $result = $conn->query($sql);
    ?>

    <div class="container mt-3">
    <div class="card mt-3 shadow-lg">
    <div class="card-body px-5">
        <h2>Final Approval Form</h2>
        <p>Approval list:</p>
        <!-- Wrap the table and form within <form> tags -->
        <form method="post">  
        <div class="card mt-2"><div class = "card-body shadow-lg"> 
            <div class="row fw-bold">   
                    <div class="col-2">
                        <div class="form-check">
                            <label class="fw-bold pb-2">Select</label>
                            <input type="checkbox" class="form-check-input" name="checkbox"></input>
                        </div>
                    </div>
                    <div class="col-1 d-flex justify-content-start">Name</div>
                    <div class="col-2 text-center">Department</div>
                    <div class="col-2 text-center">Email</div>
                    <div class="col-2 text-center">Contact</div>
                    <div class="col-2 text-center">Mac</div>
                    <div class="col-1 text-center">Level</div>
            </div></div></div>

    <?php
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {

                        $name = $row["name"];
                        $dept = $row["dept"];
                        $email = $row["email"];
                        $contact = $row["contact"];
                        $mac = $row["mac"];
                        $permission = $row["permission"];
                ?>  <!-- Printing Table -->
                        
                <div class="card mt-2"><div class = "card-body shadow-lg">
                    <div class="row text-center">   
                    <div class="col-1"><input type="checkbox" class="form-check form-check-input" name="selectedRows[]" value="<?php echo $row["id"]; ?>"></input></div>
                    <div class="col-2"><span> <?php echo $name; ?></span></div>
                    <div class="col-2"><span> <?php echo $dept; ?></span></div>
                    <div class="col-2"><span> <?php echo $email; ?></span></div>
                    <div class="col-2"><span> <?php echo $contact; ?></span></div>
                    <div class="col-2"><span> <?php echo $mac; ?></span></div>
                    <div class="col-1"><span> <?php echo $permission; ?></span></div>
                 </div></div></div>
                <?php
                    }
        
                } else {
                    echo "0 results";
                }

                $conn->close();
                ?>


            <div class="form-check">
                <label class="fw-bold pb-2">Select All </label>
                <input type="checkbox" class="form-check-input" name="checkbox"></input>
            </div>
            <!--------------------- Button ------------------->
            <div class="mt-4 mb-5">
                
                <input type="submit" class="btn btn-success" name="submit" value="Approve"></input>
                <input type="submit" class="btn btn-danger" name="delete" value="Reject"></input>
                <input type="submit" class="btn btn-secondary" name="reset" value="Reset"></input>
            </div>
        </form>
    </div>
    </div>    
    </div>

    <?php
    //----------------------------- Submit ----------------------------
    if (isset($_POST['submit'])) {
          
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

                    $sql = "SELECT id, name, email, contact, mac FROM pending_authorizedlist WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $selectedRowId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    // Remove the placeholder for "new_id" from the SQL query
                    $stmt = $conn->prepare("INSERT INTO approved ( id, name, email, contact, mac) VALUES ( ?, ?, ?, ?, ?)");

                    // Bind parameters
                    $stmt->bind_param("issss", $selectedRowId, $row["name"], $row["email"], $row["contact"], $row["mac"]);
                    $stmt->execute();

                    $sql = "DELETE FROM pending_authorizedlist WHERE id = ?";
                    //$conn->query($sql);
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $selectedRowId);
                    $stmt->execute();
                }

                $stmt->close();
                $conn->close();
            } else {
                echo "No rows selected.";
            } ?>
            
            <meta http-equiv="refresh" content="1">
<?php   }
//------------------------- Delete ---------------------------------
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
                    $sql = "DELETE FROM pending_authorizedlist WHERE id = ?";
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

                $sql = "DELETE FROM pending_authorizedlist";
                //$conn->query($sql);
                $result = $conn->query($sql);
            }
             else {
                echo "No rows selected.";
            } ?>

            <meta http-equiv="refresh" content="1">

<?php   }
//================== Select All ==========================

if (isset($_POST['checkbox'])) {
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
                
                $sql = "SELECT * FROM pending_authorizedlist";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {

                // Remove the placeholder for "new_id" from the SQL query
                $stmt = $conn->prepare("INSERT INTO approved (id, name, email, contact, mac) VALUES (?, ?, ?, ?, ?)");

                // Bind parameters
                $stmt->bind_param("issss", $row["id"], $row["name"], $row["email"], $row["contact"], $row["mac"]);
                $stmt->execute();
                    }
                }
                $sql = "DELETE FROM pending_authorizedlist";
                //$conn->query($sql);
                $result = $conn->query($sql);

                $stmt->close();
                $conn->close();
            
            
            ?>

            <meta http-equiv="refresh" content="1">
        <?php 
    }    
?>

</body>

</html>