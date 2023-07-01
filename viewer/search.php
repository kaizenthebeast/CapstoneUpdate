<?php
$conn = mysqli_connect("localhost", "root", "", "testing");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT * FROM thesis WHERE approved = 1 AND (title LIKE '%$search%' OR members LIKE '%$search%' OR year LIKE '%$search%' OR tags LIKE '%$search%')";
} else {
    $sql = "SELECT * FROM thesis WHERE approved = 1";
}

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo '<a href="./?page=view_archive&id=' . $row['id'] . '" class="list-group-item list-group-item-action">';
        echo '<div class="card-body">';
        echo '<div class="row">';
        echo '<div class="col-md-3">';
        echo '<img src="../user/assets/images/bookBlack.svg" alt="' . $row['title'] . '" style="display:block; max-width:150px; height:auto;">';
        echo '</div>';
        echo '<div class="col-md-9">';
        echo '<h5 class="card-title">' . $row['title'] . ' <small class="text-muted" style="display: block;">' . $row['year'] . '</small></h5>';
        echo '<div class="card-text mb-3">';
        echo '<p class="mb-1">' . $row['abstract'] . '</p>';
        echo '<small class="text-muted">' . $row['members'] . '</small>';
        echo '<div class="tags">Tags: ' . $row['tags'] . '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</a>';
    }
} else {
    echo "No results found";
}

mysqli_close($conn);
?>
