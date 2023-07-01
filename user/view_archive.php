<!-- ARCHIVE VIEW -->
<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'testing';

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!isset($_GET['id'])) {
    header('Location: view_archive.php');
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM thesis WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header('Location: view_archive.php');
    exit();
}

$row = mysqli_fetch_assoc($result);

$pdf_filename = basename($row['pdf_path']);
if (isset($pdf_filename) && !empty($pdf_filename)) { 
    $pdf_url = "../user/submit/uploads/" . $pdf_filename; 
} else {
    $pdf_url = '';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $row['title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
       body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        #pdf-container {
            position: relative;
            width: 60vw;
            height: 100vh;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
        }

        #pdf-viewer {
            width: 100%;
            height: 100%;
            overflow: auto;
        }

        #pdf-viewer canvas {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto;
        }

        #next-page-btn,
        #back-page-btn {
            position: absolute;
            bottom: 10px;
            padding: 10px 20px;
            font-size: 16px;
        }

        #next-page-btn {
            right: 10px;
        }

        #back-page-btn {
            left: 10px;
        }

        .white-card {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #000;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        .white-card legend {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .white-card p {
            margin: 0;
        }

        .white-card fieldset {
            border: none;
            padding: 0;
            margin-bottom: 20px;
        }

        .white-card .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .white-card .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .white-card .details {
            margin-top: 10px;
            padding-left: 10px;
            border-left: 2px solid #000;
        }

        .white-card .author {
            margin-bottom: 10px;
        }

        .white-card .abstract {
            margin-bottom: 10px;
        }

        .white-card .keywords {
            margin-bottom: 10px;
        }

        .white-card .pdf-link {
            margin-bottom: 10px;
        }

    </style>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    
</head>
<body>
    

<div class="white-card">
    <br>
    <fieldset>
        <legend class="text-navy"></legend>
        <div class="pl-4"><large><span style="font-size: 20px;"><?php echo $row['title']; ?></span></large></div>
    </fieldset>
    <fieldset>
        <legend class="text-navy">Year:</legend>
        <div class="pl-4"><large><?php echo $row['year']; ?></large></div>
    </fieldset>
    <fieldset>
        <legend class="text-navy">Abstract:</legend>
        <div class="pl-4"><large><p><?php echo $row['abstract']; ?></p></large></div>
    </fieldset>
    <fieldset>
        <legend class="text-navy">Members:</legend>
        <div class="pl-4"><large><p><?php echo $row['members']; ?></p></large></div>
    </fieldset>
    <fieldset>
    <legend class="text-navy">Full Paper:</legend>
    <div class="pl-4">
        <large>
            <form action="pages/request_paper.php?id=<?php echo $row['id']; ?>" method="post">
                <input type="email" name="requesting_user_email" placeholder="Enter your email" required>
                <input type="radio" name="email_type" value="student" required> Student Email
                <input type="radio" name="email_type" value="conference" required> Conference Email
                <button type="submit">Request Full Paper</button>
            </form>
        </large>
    </div>
</fieldset>

</div>

    
    
    <div id="pdf-container">
        <div id="pdf-viewer"></div>
        
        <button id="next-page-btn">Next Page</button>
        <button id="back-page-btn">Back Page</button>
    </div>

    
    
    <script>



        const pdfContainer = document.getElementById('pdf-container');
        const pdfViewer = document.getElementById('pdf-viewer');
        const nextPageButton = document.getElementById('next-page-btn');
        const backPageButton = document.getElementById('back-page-btn');

        let currentPage = 1;
        let totalPages = 0;
        let pdfInstance = null;

        // Load the PDF document
        const url = '<?php echo $pdf_url; ?>';
        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdfInstance = pdf;
            totalPages = pdf.numPages;

            // Render the initial page
            renderPage(currentPage);

            // Show the next page button if there are more pages
            if (totalPages > 1) {
                nextPageButton.style.display = 'block';
                nextPageButton.addEventListener('click', nextPage);
            }

            // Show the back page button if not on the first page
            // Show the back page button if not on the first page
            if (currentPage > 1) {
                backPageButton.style.display = 'block';
                backPageButton.addEventListener('click', backPage);
            } else {
                backPageButton.style.display = 'none';
            }

        });

        
        // Render the specified page
        // Define the maximum number of pages to display
const maxPagesToShow = 5;

// Render the specified page
function renderPage(pageNumber) {
    // Check if the current page exceeds the maximum pages to show
    if (pageNumber > maxPagesToShow) {
        // Hide the next page button if the current page exceeds the maximum
        nextPageButton.style.display = 'none';
        return;
    }

    pdfInstance.getPage(pageNumber).then(page => {
        const viewport = page.getViewport({ scale: 1 });
        const scale = pdfContainer.clientWidth / viewport.width;
        const scaledViewport = page.getViewport({ scale });
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        // Set canvas dimensions based on scaled viewport
        canvas.width = scaledViewport.width;
        canvas.height = scaledViewport.height;

        // Render the page on the canvas
        page.render({
            canvasContext: context,
            viewport: scaledViewport
        }).promise.then(() => {
            // Clear the previous page and append the new canvas
            pdfViewer.innerHTML = '';
            pdfViewer.appendChild(canvas);
        });
    });
}


        // Go to the next page
        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                renderPage(currentPage);

                // Show/hide the next/back buttons based on the current page
                if (currentPage === totalPages) {
                    nextPageButton.style.display = 'none';
                }
                if (currentPage > 1) {
                    backPageButton.style.display = 'block';
                }
            }
        }
        console.log('Attaching event listener to back button');
        backPageButton.addEventListener('click', backPage);

        // Go back to the previous page
        function backPage() {
            console.log('Back button clicked');

            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);

                // Show/hide the next/back buttons based on the current page
                if (currentPage === 1) {
                    backPageButton.style.display = 'none';
                }
                if (currentPage < totalPages) {
                    nextPageButton.style.display = 'block';
                }
            }
        }
    </script>
</body>
</html>
