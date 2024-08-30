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
            <table class="table table-bordered text-center">
                <thead>
                    <tr class="info">
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Select</th>
                        
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Name</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Department</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Email ID</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Contact No</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">MAC Address</th>
                        <th style="background: linear-gradient(to right, rgb(192,192,192), rgb(255,255,255))">Permission</th>

                    </tr>
                </thead>

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
                                <td><span> <?php echo $permission; ?></span></td>
                            
                            </tr>
                        </tbody>
                <?php
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