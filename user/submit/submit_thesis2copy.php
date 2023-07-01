<?php


 $hostName= "localhost";
 $dbUser  = "root";
 $dbPassword = "";
 $dbName   = "testing";
 
 $conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
 if(!$conn){
   die("Database connection failed");
 }
 
$title = $_POST['title'];
$year = $_POST['year'];
$abstract = $_POST['abstract'];
$members = $_POST['members'];
$email = $_POST['email'];
$tags = $_POST['tags'];

$target_dir = "./uploads/";

$target_file = $target_dir . basename($_FILES["pdf_path"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if ($fileType != "pdf") {
    echo "Sorry, only PDF files are allowed.";
    $uploadOk = 0;
}

if ($_FILES["pdf_path"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["pdf_path"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["pdf_path"]["name"])) . " has been uploaded.";

        $sql = "INSERT INTO thesis (title, year, abstract, members, email, pdf_path, tags) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "sssssss", $title, $year, $abstract, $members, $email, $target_file, $tags); // Bind the tags value to the statement

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ");
            exit(); 
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

?>
