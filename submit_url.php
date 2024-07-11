<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit URL</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 border-0 p-5 rounded-4" style="box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;">
        <h2 class="mb-4">Submit URLs for Indexing</h2>
        <form action="submit_url.php" method="post">
            <div class="form-group">
                <label for="account">Select Account:</label>
                <select class="form-control" name="account" id="account" required>
                <option value="" disabled selected>Select Account</option>
                    <option value="indexing-service-account@indexing-api-425509.iam.gserviceaccount.com">indexing-service-account@indexing-api-425509.iam.gserviceaccount.com</option>
                    <option value="gi-api-service-account@airlineofficehubs.iam.gserviceaccount.com">gi-api-service-account@airlineofficehubs.iam.gserviceaccount.com</option>
                    <option value="airlines-terminals-json@airlines-terminal.iam.gserviceaccount.com">airlines-terminals-json@airlines-terminal.iam.gserviceaccount.com</option>
                    <option value="gi-api-service-account@airlineofficehubs2.iam.gserviceaccount.com">gi-api-service-account@airlineofficehubs2.iam.gserviceaccount.com</option>
                    <option value="indexing-project-1@adept-student-425604-k6.iam.gserviceaccount.com">indexing-project-1@adept-student-425604-k6.iam.gserviceaccount.com</option>
                    <option value="gi-api-service-account@airlineofficehubs3.iam.gserviceaccount.com">gi-api-service-account@airlineofficehubs3.iam.gserviceaccount.com</option>                  
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="form-group">
                <label for="urls">Enter URLs (one per line, up to 200):</label>
                <textarea class="form-control" name="urls" id="urls" rows="10" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
        <div class="mt-4">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['urls']) && !empty($_POST['urls']) && isset($_POST['account'])) {
                    $urls = $_POST['urls'];
                    $account = $_POST['account'];
                    $urlsArray = explode("\n", $urls);

                    foreach ($urlsArray as $url) {
                        $url = trim($url);
                        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                            $urlEscaped = escapeshellarg($url);
                            $accountEscaped = escapeshellarg($account);

                            // Call the Python script with the URL and account as arguments
                            $command = "python urls.py $urlEscaped $accountEscaped 2>&1";
                            echo "<div class='alert alert-info'>Executing command: $command</div>"; // Debug: Show the command
                            $output = shell_exec($command);

                            if ($output === null) {
                                echo "<div class='alert alert-danger'>Failed to execute Python script for URL: $url</div>";
                            } else {
                                // Display the output from the Python script
                                echo "<div class='alert alert-success'><pre>$output</pre></div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Invalid URL: $url</div>";
                        }
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please enter at least one URL and select an account.</div>";
                }
            }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
