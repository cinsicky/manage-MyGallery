<?php
    // Create connection
    $servername = "localhost";
    $username = "mysql";
    $password = "";
    $db = "test";
    $conn = new mysqli($servername, $username, $password, $db);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $currentDir = getcwd().'/';
    $uploadDirectory = "images/";
    $errors = []; // Store all foreseen and unforseen errors here
    $fileExtensions = ['jpeg','jpg','png']; // Get all the file extensions

    // test if request is from submit
    if (isset($_POST['submit'])) {
        $action = $_POST['action'];
        if ($action === 'new'){
            // get all parameters 
            $name = $_POST['name'];
            $description = $_POST['description'];
            $fileName = $_FILES['myfile']['name'];
            $fileSize = $_FILES['myfile']['size'];
            $fileTmpName  = $_FILES['myfile']['tmp_name'];
            $fileType = $_FILES['myfile']['type'];
            $fileExtension = strtolower(end(explode('.',$fileName)));
            $uploadPath = $currentDir . $uploadDirectory . basename($fileName); 
            // test file extension
            if (! in_array($fileExtension,$fileExtensions)) {
                $errors = "This file extension is not allowed. Please upload a JPEG or PNG file";
                echo "<script type='text/javascript'>alert('$errors');</script>";
            }
            // test size
            if ($fileSize > 2000000) {
                $error = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
                echo "<script type='text/javascript'>alert('$error');</script>";
            }
            // test error
            if (empty($errors)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if ($didUpload) {
                    $img_url = $uploadDirectory . basename($fileName);
                    $insert_stmt = $conn->prepare("INSERT INTO item_info(name, description, img_url) VALUES(?, ?, ?)");
                    $insert_stmt->bind_param("sss", $name, $description, $img_url);
                    $insert_stmt->execute();
                    // echo "The file " . $uploadDirectory . basename($fileName) . " has been uploaded";
                    //sleep(2);
                    header("location:./management.php");
                } else {
                    echo "An error occurred somewhere. Try again or contact the admin";
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "These are the errors" . "\n";
                }
            }
            // update an item with specific id
        }else if($action === 'update' && !is_null($_POST['id'])) {
            // get parameters from post request
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $fileName = $_FILES['myfile']['name'];
            $fileSize = $_FILES['myfile']['size'];
            $fileTmpName  = $_FILES['myfile']['tmp_name'];
            $fileType = $_FILES['myfile']['type'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $fileName = $_FILES['myfile']['name'];
            $fileSize = $_FILES['myfile']['size'];
            $fileTmpName  = $_FILES['myfile']['tmp_name'];
            $fileType = $_FILES['myfile']['type'];
            // get file extension from filename
            $fileExtension = strtolower(end(explode('.',$fileName)));
            $uploadPath = $currentDir . $uploadDirectory . basename($fileName); 
            if (! in_array($fileExtension,$fileExtensions)) {
                $errors = "This file extension is not allowed. Please upload a JPEG or PNG file";
                echo "<script type='text/javascript'>alert('$errors');</script>";
            }
    
            if ($fileSize > 20000000) {
                $error = "This file is more than 20MB. Sorry, it has to be less than or equal to 2MB";
                echo "<script type='text/javascript'>alert('$error');</script>";
            }
    
            if (empty($errors)) {
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                if ($didUpload) {
                    $img_url = $uploadDirectory . basename($fileName);
                    $update_stmt = $conn->prepare("UPDATE item_info SET name=?, description=?, img_url=? WHERE _id=?");
                    $update_stmt->bind_param("sssi", $name, $description, $img_url, $id);
                    $update_stmt->execute();
                    header("location:./management.php");
                } else {
                    echo "An error occurred somewhere. Try again or contact the admin";
                }
            } else {
                foreach ($errors as $error) {
                    echo $error . "These are the errors" . "\n";
                }
            }
            // delete item 
        }else if($action === 'delete' && !is_null($_POST['id'])) {
            $id = $_POST['id'];
            $delete_stmt = $conn->prepare("DELETE FROM item_info WHERE _id=?");
            $delete_stmt->bind_param("i", $id);
            $delete_stmt->execute();
            header("location:./management.php");
        }
        
    }
    $conn->close();

?>