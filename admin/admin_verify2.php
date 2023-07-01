<!--

Admin Verifiy Page

-->

<?php
include ("../admin/index.php");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testing";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT *, CONCAT(fullName, ', ', userEmail) AS name FROM `registered_users` ORDER BY CONCAT(fullName, ', ', userEmail) ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>List of Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .img-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            object-position: center center;
            border-radius: 100%;
        }

        .card {
            margin-top: 20px;
        }

        .card-header {
            background-color: rgb(4, 4, 132);
            color: #fff;
        }

        .card-title {
            margin-bottom: 0;
        }

        .table {
            background-color: #fff;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .badge-verified {
            background-color: #28a745;
        }

        .badge-not-verified {
            background-color: #007bff;
        }

        .btn-verify {
            color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Status of Users</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-center"><?php echo $row['ID']; ?></td>
                                    <td><?php echo ucwords($row['fullName']); ?></td>
                                    <td>
                                        <p class="m-0 truncate-1"><?php echo $row['userEmail']; ?></p>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row['verify_status'] == 1): ?>
                                            <span class="badge badge-pill badge-verified">Verified</span>
                                        <?php else: ?>
                                            <span class="badge badge-pill badge-not-verified">Not Verified</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <a href="index.php" class="btn btn-primary">Back</a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $("body").on("click", ".verify_user", function() {
            var id = $(this).data("id");
            var email = $(this).data("email");
            console.log("Verify button clicked. ID: " + id + ", Email: " + email);

            if (!id) {
                alert("User ID is missing. Please check the data attributes of the button.");
                return;
            }

            $.ajax({
                url: 'verify_user2.php',
                method: 'POST',
                data: { 'id': id },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {
                        alert('User has been verified.');
                        location.reload();
                    } else {
                        alert('An error occurred while verifying the user.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                    alert('An error occurred while verifying the user.');
                }
            });
        });
    });
    </script>
</body>
</html>
