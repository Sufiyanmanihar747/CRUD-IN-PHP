<?php
    $upload_dir = 'imageData/';
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "crud";
    $conn = mysqli_connect($server, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed" . mysqli_connect_error());
    }
    // echo "Connection succesfuly";

 // ADD RECORDS    
    if (isset($_POST['submit'])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $email = $_POST['Email'];
            $phone = $_POST['phone'];
            $add = $_POST['address'];
            if (isset($_FILES['image'])) {
                $image = $_FILES['image'];
                $image_name = $image['name'];
                $image_tmp = $image['tmp_name'];     //path of data base where image is store
                $image_destination = 'imageData/' . $image_name;
                
                if (move_uploaded_file($image_tmp, $image_destination)) { //move to $image_tmp
                    $imageData = file_get_contents($image_destination);
                    $base64Image = base64_encode($imageData);
                    $insert = "INSERT INTO user_record (name, email, phone, address, image, imgname) VALUES ('$name','$email','$phone','$add','$base64Image','$image_name')";
                    if($conn ->query($insert)){
                        echo"<div class='alert alert-success' role='alert'>
                        Record <b>inserted</b> Successfully!
                        </div>";
                    }    
                    else {
                        echo"<div class='alert alert-danger' role='alert'>
                        Record <b>Not</b> inserted!
                        </div>" .$insert . $conn ->error;
                    }
                    echo"<script>
                    setTimeout(function() {
                        window.location.href = 'index1.php';
                    }, 2000);
                    </script>";
                }
            }
        }
    }
?>

<!-- TO DELETE WHOLE RECORDS -->
<?php
    if (isset($_POST['deleteall'])){
        $deleteall = "DELETE FROM user_record";
        $setTo = "ALTER TABLE user_record AUTO_INCREMENT = 1";
        if ($conn ->query($deleteall) AND $conn ->query($setTo)) {
            echo"<div class='alert alert-success' role='alert'>
            <b>All Records Deleted</b> Successfully!
          </div>";
        }
        else{
            echo "<div class='alert alert-danger' role='alert'>
            Records <b>Not Deleted</b>!
          </div>".$deleteall . $conn ->error;
        }
         echo"<script>
            setTimeout(function() {
                window.location.href = 'index1.php';
            }, 2000);
        </script>";
    }
?>

<!-- TO DELETE SINGLE RECORD -->
<?php
 
    if(isset($_POST['id'])){
        $id = $_POST['id'];
        $delete = "DELETE FROM user_record WHERE id=$id ";
        
        if($conn ->query($delete)){
            echo"<div class='alert alert-success' role='alert'>".$id."th
            Record <b>Deleted</b> Successfully!
            </div>";  
        }
        else {
            echo"<div class='alert alert-danger' role='alert'>
            Record <b>Not Deleted</b>!
            </div>".$delete . $conn ->error;
        }
        echo"<script>
            setTimeout(function() {
                window.location.href = 'index1.php';
            }, 2000);
        </script>";
    }
?>  

<?php
    //FETCH  RECORD
    $fetch = "SELECT * FROM user_record";
    $result = $conn ->query($fetch);                                        
    $conn -> close();
?>

<!DOCTYPE html>
<title>index1</title>

<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style2.css">
</head>

<body>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>Manage <b>Records</b></h2>
                        </div>
                        <div class="col-sm-6">
                            <a href="#deleteallrecords" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Delete All</span>
                            </a>
                            <a href="#addModal" class="btn btn-success" data-toggle="modal"><i
                            class="material-icons">&#xE147;</i> <span>Add New</span></a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Srno</th>
                            <th>Name</th>
                            <th>Photo</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                                if($result ->num_rows > 0){
                                    while ($row = $result ->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th>". $row["id"]."</th>";
                                        echo "<td>". $row["name"]."</td>";
                                        echo "<td style='width:12vw;'><img src='data:image/jpeg;base64," . $row["image"] . "' alt='Image' style='width: 80%;'></td>";
                                        echo "<td>". $row["email"]."</td>";
                                        echo "<td>". $row["phone"]."</td>";
                                        echo "<td>". $row["address"]."</td>";
                                        echo "<td>";
                                        echo"<form method='post' action='update.php'>
                                        <a>
                                        <input type='hidden' name='updateid' value='". $row["id"]."'>
                                        <input type='submit' class='mb-1 btn btn-primary material-icons' data-toggle='tooltip' title='Edit' value='&#xE254;'>
                                        </a>
                                        </form>";
                                        echo"<form method='post'>
                                        <a>
                                        <input type='hidden' name='id' value='". $row["id"]."'>
                                        <input type='submit' class='btn btn-danger material-icons' data-toggle='tooltip' title='Delete' value='&#xE872;'>
                                        </a>
                                        </form>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } 
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- ADD USER -->
    <div id="addModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-header" actions="index1.php">
                        <h4 class="modal-title">Add</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="Email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="tel" pattern="[0-9]{10}" class="form-control" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" name="address" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" accept="image/*" class="form-control" name="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" name="submit" class="btn btn-success" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete All Record Modal HTML -->
    <div id="deleteallrecords" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete All Records</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete all Records?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" name="deleteall" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Particular Record Modal -->
    <div id="deleteRecordModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Records</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete these Records?</p>
                    <p class="text-warning"><small>This action cannot be undone.</small></p>
                </div>
                <form method='post'>
                    <div class='modal-footer'>
                        <input type='button' class='btn btn-default' data-dismiss='modal' value='Cancel'>
                        <input type='submit' name='delete' class='btn btn-danger' value='Delete'>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>