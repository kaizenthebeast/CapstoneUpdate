<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'db_thesisarchive';

$conn = mysqli_connect($host, $username, $password, $dbname);

$id = $_GET['id'];

$userQuery = "SELECT email, title FROM thesis WHERE id = '$id'";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);
$userEmail = $user['email'];
$submissionTitle = $user['title'];

$requestingUserEmail = $_POST['requesting_user_email'];
$emailType = $_POST['email_type'];

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'digital.archivesgroup@gmail.com'; 
$mail->Password = 'ahhoglwszaxdulak'; 
$mail->setFrom('digital.archivesgroup@gmail.com', 'Digital Archive'); 

$mail->addAddress($userEmail);

$mail->isHTML(true);
$mail->Subject = 'Full Paper Request';
$mail->Body = 'Dear User, <br><br>
  The user with the email address "'.$requestingUserEmail.'" has requested the full paper for your submission "'.$submissionTitle.'".<br><br>
  Email Type: '.$emailType.'<br><br>
  Please verify the request and provide the PDF file if you accept the request.<br><br>
  Best regards,<br>
  Digital Archive Group';

if ($mail->send()) {
  
  echo '<html>
          <head>
            <title>Email Sent Successfully</title>
            <style>
              body {
                background-color: #f2f2f2;
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
              }
              .container {
                background-color: #fff;
                border: 1px solid #2ecc71;
                border-radius: 5px;
                padding: 20px;
                max-width: 400px;
                margin: 0 auto;
              }
              h1 {
                color: #2ecc71;
                font-size: 24px;
                margin-top: 0;
              }
              .go-back-btn {
                display: inline-block;
                background-color: #2ecc71;
                color: #fff;
                border: none;
                border-radius: 4px;
                padding: 10px 20px;
                margin-top: 20px;
                cursor: pointer;
              }
              .go-back-btn:hover {
                background-color: #27ae60;
              }
            </style>
          </head>
          <body>
            <div class="container">
              <h1>Email sent successfully.</h1>
              <button class="go-back-btn" onclick="goBack()">Go Back</button>
            </div>

            <script>
              function goBack() {
                window.history.back();
              }
            </script>
          </body>
        </html>';
} else {
  
  echo '<html>
          <head>
            <title>Error Sending Email</title>
            <style>
              body {
                background-color: #f2f2f2;
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
              }
              .container {
                background-color: #fff;
                border: 1px solid #e74c3c;
                border-radius: 5px;
                padding: 20px;
                max-width: 400px;
                margin: 0 auto;
              }
              h1 {
                color: #e74c3c;
                font-size: 24px;
                margin-top: 0;
              }
              p {
                color: #c0392b;
              }
              .go-back-btn {
                display: inline-block;
                background-color: #e74c3c;
                color: #fff;
                border: none;
                border-radius: 4px;
                padding: 10px 20px;
                margin-top: 20px;
                cursor: pointer;
              }
              .go-back-btn:hover {
                background-color: #c0392b;
              }
            </style>
          </head>
          <body>
            <div class="container">
              <h1>Error sending the email.</h1>
              <p>Error details: ' . $mail->ErrorInfo . '</p>
              <button class="go-back-btn" onclick="goBack()">Go Back</button>
            </div>

            <script>
              function goBack() {
                window.history.back();
              }
            </script>
          </body>
        </html>';
}

mysqli_close($conn);
?>
