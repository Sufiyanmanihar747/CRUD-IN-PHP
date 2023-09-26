<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "crud";
    $conn = mysqli_connect($server, $username, $password, $dbname);
    if(!$conn){
        die("Connection failed" . mysqli_connect_error());
    }
    $select_data_query = '';
    if(isset( $_POST['updateid']))
    {
        $get_id = $_POST['updateid'];
        $select_data_of_user = "SELECT * FROM user_record WHERE id='$get_id'";
        $select_data_query = mysqli_query($conn, $select_data_of_user);
    }
?>

<?php
$updated = '';
$updat = '';
if (isset($_POST['update'])) {
    $get_id = $_POST['user_id'];
    $name = $_POST['user_name'];
    $email = $_POST['user_email'];
    $phone = $_POST['user_phone'];
    $add = $_POST['user_address'];

    if (isset($_FILES['new_user_image'])){
        $upload_dir = 'imageData/';
        $new_image_name = $_FILES['new_user_image']['name'];  //name of image
        $imageArray = $_FILES['new_user_image'];
        $image_tmp = $imageArray['tmp_name'];
        $imageName = $imageArray['name'];
        $upload_path = $upload_dir . $imageName;
    }
    
    if(move_uploaded_file($image_tmp, $upload_path)){ 
        $imageData = file_get_contents($upload_path);
        $base64Image = base64_encode($imageData);
        $update = "UPDATE user_record SET name ='$name', email ='$email', phone ='$phone', address ='$add', image ='$base64Image', imgname ='$imageName' WHERE id ='$get_id'";
        $updated = mysqli_query($conn, $update);
    }
    else {
        echo 'i am running';
        $update = "UPDATE user_record SET name ='$name', email ='$email', phone ='$phone', address ='$add' WHERE id ='$get_id'";
        $updated = mysqli_query($conn, $update);
    }  
}
?>
<!DOCTYPE html>
<title>Update</title>

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
    <?php
if ($updated){
    echo"<div class='alert alert-success' role='alert'>
    Records <b>Updated</b> Successfully!
    </div>";
    echo"<script>
    setTimeout(function() {
    window.location.href = 'index1.php';
    }, 500);
    </script>";
}
else{
    echo"
    <div class='modal-dialog'>
        <div class='modal-content'>
            <form method='post' enctype='multipart/form-data'>";
                        while($result1 = mysqli_fetch_assoc($select_data_query)){
                            echo"
                            <div class='modal-body'>
                            <div class='modal-header'>
                                <h4 class='modal-title'>Edit Record</h4>
                            </div>   
                            <div class = 'form-group'>
                                <input type='hidden' name='user_id' value=".$result1['id'].">
                                <label>Name</label>
                                <input type='text' class='form-control' name='user_name' value='$result1[name]' required>
                            </div>
                            <div class='form-group'>
                                <label>Email</label>
                                <input type='email' class='form-control' name='user_email' value=" .$result1['email']." required>
                            </div>
                            <div class='form-group'>
                                <label>Phone</label>
                                <input type='text' class='form-control' name='user_phone' value=" .$result1['phone']." required>
                            </div>
                            <div class='form-group'>
                                <label>Address</label>
                                <textarea class='form-control' name='user_address' value='' required>".$result1['address']."</textarea>
                            </div>
                            <div class='form-group'>
                                <label>Photo</label>
                                <div> ". $result1["imgname"] ."</div>
                                <input type='file' accept='image/*' class='form-control' name='new_user_image'>
                            </div>
                            <div class='modal-footer'>
                                <a href='index1.php'>
                                    <input type='button' class='btn btn-default' data-dismiss='modal' value='Cancel'>
                                </a>
                                <a>
                                    <input type='submit' name='update' class='btn btn-info' value='Save'>
                                </a>
                            </div>";
                        }
}
?>
    </div>
    </form>
    </div>
</body>

</html>