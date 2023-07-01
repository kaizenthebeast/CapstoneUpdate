<!-- admin settings sidebar tab

-->

<?php

include ("../userRegister/dbs.php");



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = $_POST['userEmail'];
    $password = $_POST['password'];


    $query = "SELECT * FROM registered_users WHERE userEmail = '$userEmail'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin['password'])) {


            header('Location: settings.php');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Settings Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            background-image: url('assets/ntcBg.jpg');            
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            width: 400px;
            padding: 40px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #2c3e50;
            font-size: 18px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #2980b9;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }

        .error {
            color: #c0392b;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .back-button {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-button a {
            color: #2980b9;
            text-decoration: none;
            font-size: 18px;
        }

        .back-button i {
            margin-right: 5px;
        }

        .card {
            background-color: #fff;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="back-button">
            <i class="fas fa-arrow-left"></i>
            <a href="index.php">Back</a>
        </div>
        <div class="card">
            <h1 class="card-title">Login Again</h1>

            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div>
                    <label for="username">UserEmail</label>
                    <input type="text" name="userEmail" value="<?php echo $userEmail; ?>">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" value="<?php echo $password; ?>">
                </div>
                <div>
                    <input type="submit" value="Login">
                </div>
            </form>

            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
