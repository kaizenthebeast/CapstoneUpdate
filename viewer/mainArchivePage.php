<!-- main archive  page

-->

<!DOCTYPE html>
<html>
<head>
    <title>Archived List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="../user/assets/css/styles.css">
    <link rel="stylesheet" type="text/css" href="../user/assets/css/bootstrap.min.css">
    <style>
        .archived-list {
            padding: 20px;
        }

        .list-group-item {
            margin-bottom: 20px;    
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .navbar-brand img {
            height: 30px;
            margin-right: 10px;
        }

        .tags {
            margin-top: 10px;
        }

        .tag {
            display: inline-block;
            margin-right: 5px;
            background-color: #f0f0f0;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">
                <img src="../user/assets/images/bookblack.svg" alt="Archived List">
                Archived List
            </a>
        </nav>
        <div class="archived-list">
            <div class="form-group">
                <label for="search">Search:</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Enter keywords" id="search-input">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" id="search-button">Search</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="sort">Sort by:</label>
                <select name="sort" id="sort-select" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="title">Title</option>
                    <option value="year">Year</option>
                </select>
            </div>

            
            <div class="list-group" id="search-results">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "testing";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $results_per_page = 10;

                if (isset($_GET['page']) && $_GET['page'] === 'search' && isset($_POST['search'])) {
                    $search = $_POST['search'];
                    $sql = "SELECT * FROM thesis WHERE approved = 1 AND (title LIKE ? OR abstract LIKE ?)";
                    $stmt = $conn->prepare($sql);
                    $searchPattern = "%$search%";
                    $stmt->bind_param("ss", $searchPattern, $searchPattern);
                    if (!$stmt->execute()) {
                        die("Error executing search query: " . $stmt->error);
                    }
                    $result = $stmt->get_result();
                } else {
                    $sort = isset($_GET['sort']) ? $_GET['sort'] : ''; // Retrieve sorting option from URL parameter
                    $sortColumn = '';

                    if ($sort === 'title') {
                        $sortColumn = 'title ASC';
                    } elseif ($sort === 'year') {
                        $sortColumn = 'year ASC';
                    }

                    $sql = "SELECT * FROM thesis WHERE approved = 1";

                    if (!empty($sortColumn)) {
                        $sql .= " ORDER BY $sortColumn";
                    }

                    $result = $conn->query($sql);
                }

                $total_records = $result->num_rows;

                $total_pages = ceil($total_records / $results_per_page);

                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $start_limit = ($page - 1) * $results_per_page;

                if ($start_limit < 0) {
                    $start_limit = 0;
                }

                $sql .= " LIMIT ?, ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $start_limit, $results_per_page);
                if (!$stmt->execute()) {
                    die("Error executing query: " . $stmt->error);
                }
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        
                        echo '<a href="view_archive.php?id=' . $row['id'] . '" class="list-group-item list-group-item-action">';
                        echo '<div class="card">';
                        echo '<div class="row no-gutters">';
                        echo '<div class="col-md-3">';
                        echo '<img src="../user/assets/images/bookblack.svg" alt="' . $row['title'] . '" class="card-img">';
                        echo '</div>';
                        echo '<div class="col-md-9">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row['title'] . ' <small class="text-muted" style="display: block;">' . $row['year'] . '</small></h5>';
                        echo '<div class="card-text">';
                        echo '<p class="mb-1">' . $row['abstract'] . '</p>';
                        echo '<small class="text-muted">' . $row['members'] . '</small>';

                        // Display tags
                        $tags = explode(',', $row['tags']);
                        if (!empty($tags)) {
                            echo '<div class="tags">Tags: ';
                            $tagCount = count($tags);
                            foreach ($tags as $index => $tag) {
                                echo '<span class="tag">' . trim($tag) . '</span>';
                                if ($index < $tagCount - 1) {
                                    echo ', '; // Add comma except for the last tag
                                }
                            }
                            echo '</div>';
                        }

                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }

                    echo '<nav aria-label="Page navigation">';
                    echo '<ul class="pagination justify-content-center" id="pagination">';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item' . ($page == $i ? ' active' : '') . '"><a class="page-link" href="?page=' . $i . '&sort=' . $sort . '">' . $i . '</a></li>';
                    }
                    echo '</ul>';
                    echo '</nav>';

                } else {
                    echo "No results found";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#search-button").click(function() {
                var search = $("#search-input").val();
                jQuery.ajax({
                    type: "POST",
                    url: "search.php",
                    data: { search: search },
                    success: function(result) {
                        $("#search-results").html(result);
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                        $("#search-results").html("An error occurred. Please try again later.");
                    }
                });
            });

            $("#sort-select").change(function() {
                var selectedOption = $(this).val();
                var url = window.location.href;
                var separator = url.indexOf('?') !== -1 ? '&' : '?';
                var newUrl = url + separator + 'sort=' + selectedOption;
                window.location.href = newUrl;
            });
        });
    </script>
</body>
</html>
